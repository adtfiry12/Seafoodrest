@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6"
     x-data="{
         completeModalOpen: false,
         actionUrl: '',
         kodeTrx: '',
         penerimaNama: '',
         isCod: false,
         totalTagihan: '',
         cameraOpen: false,
         cameraRequesting: false,
         cameraError: '',
         cameraErrorType: '',
         cameraStream: null,
         facingMode: 'environment',
         hasMultipleCameras: false,
         photoPreviewUrl: null,
         photoFileName: '',
         photoFileSize: '',
         photoSource: '',
         isProcessingPhoto: false,

         isMobile() {
             return /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
                    (navigator.maxTouchPoints && navigator.maxTouchPoints > 2 && /Macintosh/i.test(navigator.userAgent));
         },

         init() {
             window.addEventListener('beforeunload', () => this.stopCamera());
             document.addEventListener('pjax:before-send', () => this.stopCamera());
         },

         openCompleteModal(url, kode, nama, isCod, tagihan) {
             this.actionUrl = url;
             this.kodeTrx = kode;
             this.penerimaNama = nama;
             this.isCod = isCod;
             this.totalTagihan = tagihan;
             this.resetPhoto();
             this.cameraError = '';
             this.cameraErrorType = '';
             this.completeModalOpen = true;
         },

         closeCompleteModal() {
             this.stopCamera();
             this.completeModalOpen = false;
         },

         handleCameraClick() {
             if (this.isMobile()) {
                 // Di HP: langsung gunakan kamera bawaan HP agar instan & tanpa masalah izin WebRTC
                 this.triggerNativeCamera();
             } else {
                 // Di Laptop/PC (Linux / Windows): langsung minta izin kamera browser (tiru webcammictest.com)
                 this.startCamera();
             }
         },

         async startCamera() {
             this.cameraError = '';
             this.cameraErrorType = '';
             this.cameraRequesting = true;

             if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                 this.cameraRequesting = false;
                 this.cameraErrorType = 'unsupported';
                 this.cameraError = 'Browser tidak mendukung akses live kamera pada protokol ini. Gunakan kamera HP atau pilih file.';
                 this.triggerNativeCamera();
                 return;
             }

             try {
                 this.stopCameraStreamOnly();

                 try {
                     const devices = await navigator.mediaDevices.enumerateDevices();
                     const videoInputs = devices.filter(d => d.kind === 'videoinput');
                     this.hasMultipleCameras = videoInputs.length > 1;
                 } catch (e) {
                     this.hasMultipleCameras = false;
                 }

                 // Tiru webcammictest.com:
                 // Panggil getUserMedia langsung dengan fallback universal yang kompatibel di Linux (V4L2), Windows, Cloudflare, dan Localhost
                 let stream = null;
                 try {
                     const constraints = this.isMobile()
                         ? { video: { facingMode: { ideal: this.facingMode }, width: { ideal: 1280 }, height: { ideal: 720 } }, audio: false }
                         : { video: { width: { ideal: 1280 }, height: { ideal: 720 } }, audio: false };

                     stream = await navigator.mediaDevices.getUserMedia(constraints);
                 } catch (constraintErr) {
                     console.warn('Constraint ideal gagal, fallback ke video standar:', constraintErr);
                     stream = await navigator.mediaDevices.getUserMedia({
                         video: true,
                         audio: false
                     });
                 }

                 this.cameraStream = stream;
                 this.cameraOpen = true;
                 this.cameraRequesting = false;

                 await this.$nextTick();
                 const videoEl = this.$refs.cameraPreview;
                 if (videoEl) {
                     videoEl.srcObject = stream;
                     try {
                         await videoEl.play();
                     } catch (playErr) {
                         console.log('Video playback notice:', playErr);
                     }
                 }
             } catch (err) {
                 console.error('Camera request error:', err);
                 this.cameraRequesting = false;
                 this.cameraOpen = false;

                 // Penanganan error persis webcammictest.com
                 if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                     this.cameraErrorType = 'permission_denied';
                     this.cameraError = 'Izin kamera diblokir atau belum diberikan oleh browser.';
                 } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                     this.cameraErrorType = 'not_readable';
                     this.cameraError = 'Kamera sedang digunakan oleh aplikasi lain (seperti Zoom, Cheese, OBS, atau browser lain).';
                 } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                     this.cameraErrorType = 'not_found';
                     this.cameraError = 'Perangkat kamera tidak ditemukan pada laptop/komputer ini.';
                 } else {
                     this.cameraErrorType = 'general';
                     this.cameraError = 'Gagal mengakses kamera (' + (err.message || err.name) + ').';
                 }
             }
         },

         async toggleFacingMode() {
             this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
             await this.startCamera();
         },

         stopCameraStreamOnly() {
             if (this.cameraStream) {
                 this.cameraStream.getTracks().forEach(t => t.stop());
                 this.cameraStream = null;
             }
             if (this.$refs.cameraPreview) {
                 this.$refs.cameraPreview.srcObject = null;
             }
         },

         stopCamera() {
             this.stopCameraStreamOnly();
             this.cameraOpen = false;
             this.cameraRequesting = false;
         },

         capturePhoto() {
             const video = this.$refs.cameraPreview;
             const canvas = this.$refs.cameraCanvas;
             if (!video || !canvas || !video.videoWidth) return;

             canvas.width = video.videoWidth;
             canvas.height = video.videoHeight;
             const ctx = canvas.getContext('2d');
             ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

             canvas.toBlob((blob) => {
                 if (!blob) return;
                 const fileName = `pod-${this.kodeTrx || 'trx'}-${Date.now()}.jpg`;
                 const file = new File([blob], fileName, { type: 'image/jpeg' });

                 const dataTransfer = new DataTransfer();
                 dataTransfer.items.add(file);
                 if (this.$refs.cameraInput) {
                     this.$refs.cameraInput.files = dataTransfer.files;
                 }

                 if (this.photoPreviewUrl) URL.revokeObjectURL(this.photoPreviewUrl);
                 this.photoPreviewUrl = URL.createObjectURL(blob);
                 this.photoFileName = fileName;
                 this.photoFileSize = (blob.size / 1024).toFixed(0) + ' KB';
                 this.photoSource = 'live_camera';

                 if (this.$refs.fileUploadInput) this.$refs.fileUploadInput.value = '';
                 if (this.$refs.nativeCameraInput) this.$refs.nativeCameraInput.value = '';

                 this.stopCamera();
             }, 'image/jpeg', 0.88);
         },

         triggerNativeCamera() {
             if (this.$refs.nativeCameraInput) {
                 this.$refs.nativeCameraInput.click();
             }
         },

         async handleFileInput(event, source) {
             const file = event.target.files && event.target.files[0];
             if (!file) return;

             this.isProcessingPhoto = true;

             try {
                 const processedFile = await this.optimizeImageIfNeeded(file);

                 const dataTransfer = new DataTransfer();
                 dataTransfer.items.add(processedFile);
                 if (this.$refs.cameraInput) {
                     this.$refs.cameraInput.files = dataTransfer.files;
                 }

                 if (this.photoPreviewUrl) URL.revokeObjectURL(this.photoPreviewUrl);
                 this.photoPreviewUrl = URL.createObjectURL(processedFile);
                 this.photoFileName = processedFile.name;
                 this.photoFileSize = (processedFile.size / 1024).toFixed(0) + ' KB';
                 this.photoSource = source;
                 this.cameraError = '';
                 this.cameraErrorType = '';
                 this.stopCamera();
             } catch (e) {
                 console.error('Gagal memproses foto:', e);
             } finally {
                 this.isProcessingPhoto = false;
             }
         },

         optimizeImageIfNeeded(file) {
             return new Promise((resolve) => {
                 if (file.size <= 2 * 1024 * 1024 && file.type === 'image/jpeg') {
                     return resolve(file);
                 }

                 const img = new Image();
                 const reader = new FileReader();
                 reader.onload = (e) => {
                     img.onload = () => {
                         const canvas = document.createElement('canvas');
                         const maxDim = 1920;
                         let width = img.width;
                         let height = img.height;

                         if (width > maxDim || height > maxDim) {
                             if (width > height) {
                                 height = Math.round((height * maxDim) / width);
                                 width = maxDim;
                             } else {
                                 width = Math.round((width * maxDim) / height);
                                 height = maxDim;
                             }
                         }

                         canvas.width = width;
                         canvas.height = height;
                         const ctx = canvas.getContext('2d');
                         ctx.drawImage(img, 0, 0, width, height);

                         canvas.toBlob((blob) => {
                             if (!blob) return resolve(file);
                             const cleanName = (file.name || 'foto-bukti').replace(/\.[^/.]+$/, '') + '.jpg';
                             const optimizedFile = new File([blob], cleanName, { type: 'image/jpeg' });
                             resolve(optimizedFile);
                         }, 'image/jpeg', 0.85);
                     };
                     img.onerror = () => resolve(file);
                     img.src = e.target.result;
                 };
                 reader.onerror = () => resolve(file);
                 reader.readAsDataURL(file);
             });
         },

         resetPhoto() {
             if (this.photoPreviewUrl) {
                 URL.revokeObjectURL(this.photoPreviewUrl);
             }
             this.photoPreviewUrl = null;
             this.photoFileName = '';
             this.photoFileSize = '';
             this.photoSource = '';
             if (this.$refs.cameraInput) this.$refs.cameraInput.value = '';
             if (this.$refs.nativeCameraInput) this.$refs.nativeCameraInput.value = '';
             if (this.$refs.fileUploadInput) this.$refs.fileUploadInput.value = '';
         }
     }">

    <!-- Header Pos Kurir -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shrink-0 border border-blue-100">
                <i class="fa-solid fa-motorcycle text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Pengantaran (Delivery)</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola pesanan siap antar, rute pelanggan, hingga konfirmasi serah terima.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('karyawan.delivery') }}" method="GET" class="w-full sm:w-64 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / pelanggan / alamat..." class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-xs">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition" aria-label="Cari">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <button onclick="window.location.reload();" class="group w-full sm:w-auto px-4 py-2.5 bg-white border border-slate-200/90 hover:border-gold-300 hover:bg-gold-50/80 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-gold-900 transition-all duration-200 shadow-2xs flex items-center justify-center gap-2 active:scale-95">
                <i class="fa-solid fa-rotate-right text-xs text-gold-600 group-hover:rotate-180 transition-transform duration-500"></i> <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('karyawan.delivery', array_filter(['search' => request('search')])) }}"
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition whitespace-nowrap {{ !request('status') ? 'bg-charcoal-900 text-gold-400 shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 hover:text-charcoal-950 border border-slate-200 hover:border-slate-300 shadow-2xs' }}">
            Semua ({{ $countSiap + $countDiantar }})
        </a>
        <a href="{{ route('karyawan.delivery', array_filter(['status' => 'Siap Diantar', 'search' => request('search')])) }}"
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition whitespace-nowrap {{ request('status') === 'Siap Diantar' ? 'bg-charcoal-900 text-gold-400 shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 hover:text-charcoal-950 border border-slate-200 hover:border-slate-300 shadow-2xs' }}">
            Siap Diantar ({{ $countSiap }})
        </a>
        <a href="{{ route('karyawan.delivery', array_filter(['status' => 'Diantar', 'search' => request('search')])) }}"
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition whitespace-nowrap {{ request('status') === 'Diantar' ? 'bg-charcoal-900 text-gold-400 shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 hover:text-charcoal-950 border border-slate-200 hover:border-slate-300 shadow-2xs' }}">
            Sedang Jalan ({{ $countDiantar }})
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Kanban Grid Kurir -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($transaksi as $trx)
            @php
                $isSiap = $trx->status_pesanan === 'Siap Diantar';
                $isCod = $trx->metode_pembayaran === 'COD' && $trx->status_pembayaran !== 'Lunas';
                $phoneClean = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $trx->no_hp_pelanggan));
            @endphp
            <div class="bg-white border-2 border-slate-200 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between transition hover:shadow-md relative">

                <!-- Indicator Bar Atas -->
                <div class="absolute top-0 left-0 w-full h-1.5 {{ $isSiap ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }}"></div>

                <div>
                    <!-- Header Card -->
                    <div class="p-4 border-b border-slate-100 flex justify-between items-start bg-slate-50/70">
                        <div>
                            <h3 class="font-mono font-bold text-charcoal-900 text-base sm:text-lg leading-tight">{{ $trx->kode_transaksi }}</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i> Pesanan masuk: {{ $trx->created_at->format('H:i') }} WIB</p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            @if($isSiap)
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                                    <i class="fa-solid fa-box mr-1"></i> Siap Diantar
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-lg uppercase tracking-wider animate-pulse">
                                    <i class="fa-solid fa-motorcycle mr-1"></i> Sedang Jalan
                                </span>
                            @endif

                            @if($trx->status_pembayaran == 'Lunas')
                                <span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold rounded uppercase tracking-wider">
                                    <i class="fa-solid fa-check mr-1"></i> Lunas
                                </span>
                            @else
                                <span class="inline-flex px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-bold rounded uppercase tracking-wider">
                                    <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Tagih (COD)
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-4 sm:p-5 space-y-3.5">
                        <!-- Penerima & Link Detail -->
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Penerima</p>
                                <p class="font-bold text-charcoal-900 text-sm truncate">{{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}</p>
                                <p class="text-xs text-slate-500 font-mono mt-0.5"><i class="fa-solid fa-phone text-[10px] text-slate-400 mr-1"></i> {{ $trx->no_hp_pelanggan }}</p>
                            </div>
                            <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="group inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-xl bg-slate-50 hover:bg-charcoal-950 text-slate-500 hover:text-gold-400 border border-slate-200/80 hover:border-charcoal-950 transition-all duration-200 shadow-2xs active:scale-95">
                                <i class="fa-solid fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                            </a>
                        </div>

                        <!-- Alamat & Patokan Lengkap -->
                        <div class="rounded-xl bg-blue-50/70 border border-blue-100 p-3 text-xs">
                            <p class="font-bold text-blue-700 uppercase tracking-wider text-[10px] flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-blue-600"></i> Alamat Pengantaran:
                            </p>
                            <p class="mt-1 font-medium text-charcoal-900 leading-relaxed">{{ $trx->alamat ?: 'Alamat tidak dicantumkan' }}</p>

                            @if($trx->patokan_alamat)
                                <div class="mt-2 pt-2 border-t border-blue-100/80 text-[11px] text-blue-900 flex items-start gap-1.5">
                                    <i class="fa-solid fa-map-pin text-gold-600 mt-0.5 shrink-0"></i>
                                    <span><strong>Patokan:</strong> {{ $trx->patokan_alamat }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Operasional Kurir (Maps & WA) -->
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($trx->alamat) }}"
                               target="_blank"
                               rel="noopener"
                               class="group inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-blue-50/90 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-bold transition-all duration-200 shadow-2xs border border-blue-200/80 hover:border-blue-600 active:scale-95">
                                <i class="fa-solid fa-location-arrow text-xs text-blue-600 group-hover:text-white transition-colors duration-200"></i>
                                <span>Rute Maps</span>
                            </a>

                            <a href="https://wa.me/{{ $phoneClean }}?text={{ urlencode('Halo kak, saya kurir Niskala Samudra ingin mengantarkan pesanan ' . $trx->kode_transaksi . ' ke alamat kakak. Apakah kakak berada di lokasi?') }}"
                               target="_blank"
                               rel="noopener"
                               class="group inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-50/90 hover:bg-emerald-600 text-emerald-700 hover:text-white text-xs font-bold transition-all duration-200 shadow-2xs border border-emerald-200/80 hover:border-emerald-600 active:scale-95">
                                <i class="fa-brands fa-whatsapp text-sm text-emerald-600 group-hover:text-white transition-colors duration-200"></i>
                                <span>Chat WA</span>
                            </a>
                        </div>

                        <!-- Status Kurir (Jika sudah berjalan) -->
                        @if(!$isSiap)
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <span class="text-slate-400 block font-semibold uppercase text-[9px] tracking-wider">Kurir Bertugas</span>
                                <div class="flex items-center justify-between mt-1">
                                    <strong class="text-charcoal-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-tag text-gold-600 text-xs"></i>
                                        {{ $trx->kurir->nama ?? 'Karyawan' }}
                                    </strong>
                                    <span class="text-slate-500 font-mono text-[11px]">
                                        <i class="fa-regular fa-clock mr-0.5"></i> Berangkat {{ $trx->waktu_berangkat?->format('H:i') ?? '-' }} WIB
                                        @if($trx->waktu_berangkat)
                                            <span class="text-amber-600 font-sans font-bold text-[10px] block text-right">({{ $trx->waktu_berangkat->diffForHumans(null, true) }} jalan)</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Ringkasan Menu & Total -->
                        <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-100">
                            <span class="text-slate-500 font-medium">{{ $trx->detail->sum('qty') }} Menu Hidangan</span>
                            <span class="font-bold text-gold-600 text-sm">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Card (Aksi Status) -->
                <div class="p-4 bg-slate-50/80 border-t border-slate-100">
                    @if($isSiap)
                        <!-- Form Mulai Antar (Menugaskan Kurir) -->
                        <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST"
                              @submit.prevent="openActionModal($event, 'Ambil Pesanan & Mulai Antar', 'Anda akan tercatat sebagai kurir untuk pesanan {{ $trx->kode_transaksi }}. Pesanan sudah siap dibawa?')">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="Diantar">

                            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-2 active:scale-95">
                                <i class="fa-solid fa-motorcycle text-sm"></i>
                                <span>Ambil & Mulai Antar</span>
                            </button>
                        </form>
                    @else
                        <!-- Tombol Selesaikan Pengantaran (Buka Modal POD) -->
                        @if($isCod)
                            <div class="flex items-center justify-between mb-2.5 px-3 py-1.5 bg-rose-50 border border-rose-200 rounded-xl">
                                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Wajib Tagih COD:</span>
                                <span class="font-bold text-rose-700 text-sm">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <button type="button"
                                @click="openCompleteModal(
                                    '{{ route('karyawan.update_status', $trx->kode_transaksi) }}',
                                    '{{ $trx->kode_transaksi }}',
                                    '{{ addslashes($trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Pelanggan') }}',
                                    {{ $isCod ? 'true' : 'false' }},
                                    '{{ number_format($trx->total_bayar, 0, ',', '.') }}'
                                )"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-2 active:scale-95">
                            <i class="fa-solid fa-flag-checkered text-sm"></i>
                            <span>Selesaikan Pengantaran</span>
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl shadow-xs border border-slate-100">
                <div class="w-20 h-20 mx-auto rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-3xl mb-4">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
                <h3 class="text-xl font-serif font-bold text-charcoal-900 mb-1.5">Tidak Ada Antrean Delivery</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto">
                    @if(request('status'))
                        Tidak ada pesanan delivery dengan status "{{ request('status') }}".
                    @else
                        Semua hidangan pesan antar telah diserahkan kepada pelanggan.
                    @endif
                </p>
                @if(request('status') || request('search'))
                    <a href="{{ route('karyawan.delivery') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-charcoal-900 text-white text-xs font-bold rounded-xl hover:bg-gold-600 transition">
                        Reset Filter
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($transaksi->hasPages())
        <div class="mt-8 px-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-xs flex justify-center">
            {{ $transaksi->links() }}
        </div>
    @endif

    <!-- Modal Konfirmasi Penyelesaian Pengantaran (Proof of Delivery) -->
    <div x-show="completeModalOpen" x-cloak
         class="fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs overflow-y-auto"
         role="dialog" aria-modal="true" aria-labelledby="complete-modal-title">

        <div x-show="completeModalOpen"
             x-transition
             @click.away="closeCompleteModal()"
             class="relative w-full max-w-lg my-auto bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200 flex flex-col max-h-[92dvh] sm:max-h-[88vh] overflow-hidden">

            <!-- Sticky Modal Header -->
            <div class="shrink-0 flex items-center justify-between border-b border-slate-100 px-5 py-4 bg-charcoal-950 text-white">
                <div>
                    <span class="text-gold-400 font-bold uppercase tracking-wider text-[10px]">Konfirmasi Serah Terima</span>
                    <h2 id="complete-modal-title" class="text-base sm:text-lg font-serif font-bold text-white mt-0.5">Selesaikan Pengantaran</h2>
                    <p class="text-[11px] text-slate-300 font-mono" x-text="kodeTrx"></p>
                </div>
                <button type="button" @click="closeCompleteModal()" class="text-slate-400 hover:text-white transition p-1.5 rounded-lg hover:bg-white/10" aria-label="Tutup modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Form with scrollable body and sticky footer -->
            <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf
                <input type="hidden" name="status_pesanan" value="Selesai">
                <template x-if="isCod">
                    <input type="hidden" name="status_pembayaran" value="Lunas">
                </template>

                <!-- Scrollable Form Body -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 overscroll-contain custom-scrollbar">
                    <!-- COD Alert Box -->
                    <div x-show="isCod" class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-amber-800">
                            <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                            <span>Tagihan Tunai (COD) Wajib Diterima:</span>
                        </div>
                        <p class="text-base font-bold text-charcoal-950">Rp <span x-text="totalTagihan"></span></p>
                        <p class="text-[11px] text-amber-700">Pastikan uang tunai dari pelanggan sudah Anda terima penuh sebelum menyelesaikan pesanan.</p>
                    </div>

                    <!-- Input Nama Penerima -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal-900 uppercase tracking-wider mb-1.5">
                            Nama Penerima di Lokasi
                        </label>
                        <input type="text"
                               name="diterima_oleh"
                               :placeholder="'Contoh: ' + (penerimaNama || 'Pelanggan') + ' Sendiri / Istri / Satpam'"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-charcoal-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition">
                        <span class="text-[11px] text-slate-400 mt-1 block">Tulis siapa yang menerima makanan secara langsung di alamat tujuan (opsional).</span>
                    </div>

                    <!-- Input Foto Bukti Pengantaran (POD) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-charcoal-900 uppercase tracking-wider">
                                Foto Bukti Serah Terima (Opsional)
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">POD (Proof of Delivery)</span>
                        </div>

                        <!-- Opsi Ambil Foto Utama (jika belum ada foto & kamera belum terbuka) -->
                        <div x-show="!photoPreviewUrl && !cameraOpen" class="space-y-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <!-- Tombol Kamera Utama -->
                                <button type="button"
                                        @click="handleCameraClick()"
                                        :disabled="cameraRequesting"
                                        class="flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50/90 hover:bg-blue-100 px-4 py-3 text-xs font-bold text-blue-700 disabled:cursor-wait disabled:opacity-60 cursor-pointer transition shadow-2xs">
                                    <template x-if="cameraRequesting">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </template>
                                    <template x-if="!cameraRequesting">
                                        <i class="fa-solid fa-camera text-sm"></i>
                                    </template>
                                    <span x-text="cameraRequesting ? 'Meminta Izin...' : (isMobile() ? 'Ambil Foto Kamera' : 'Buka Kamera Web')"></span>
                                </button>

                                <!-- Tombol Pilih File / Galeri -->
                                <label class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 px-4 py-3 text-xs font-bold text-slate-700 cursor-pointer transition shadow-2xs">
                                    <i class="fa-solid fa-images text-slate-500 text-sm"></i>
                                    <span>Pilih dari Galeri</span>
                                    <input type="file"
                                           x-ref="fileUploadInput"
                                           name="bukti_pengantaran_file"
                                           accept="image/*"
                                           @change="handleFileInput($event, 'file_upload')"
                                           class="sr-only">
                                </label>
                            </div>

                            <!-- Opsi Alternatif Mode (Kamera Browser vs Native) -->
                            <div class="flex items-center justify-center gap-3 text-[11px] text-slate-400">
                                <template x-if="isMobile()">
                                    <button type="button" @click="startCamera()" class="hover:text-blue-600 underline underline-offset-2">
                                        Gunakan Live Kamera Web (Browser)
                                    </button>
                                </template>
                                <template x-if="!isMobile()">
                                    <button type="button" @click="triggerNativeCamera()" class="hover:text-blue-600 underline underline-offset-2">
                                        Buka Kamera HP Bawaan / File
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Banner Error / Panduan Izin (Hanya muncul jika kamera gagal/ditolak - tiru webcammictest.com) -->
                        <div x-show="cameraError && !cameraOpen" x-cloak class="mt-2.5 p-3.5 rounded-2xl border border-rose-200 bg-rose-50/95 text-rose-950 text-xs space-y-2.5">
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-rose-200 text-rose-800 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-camera-slash text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-rose-950 text-sm">Akses Kamera Browser Dibatasi</h4>
                                    <p class="text-rose-800 text-xs mt-0.5" x-text="cameraError"></p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 pt-0.5">
                                <button type="button"
                                        @click="startCamera()"
                                        class="flex-1 py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 transition shadow-xs active:scale-95">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    <span>Coba Minta Izin Lagi</span>
                                </button>
                                <button type="button"
                                        @click="triggerNativeCamera()"
                                        class="py-2 px-3 bg-white border border-rose-300 hover:bg-rose-100 text-rose-800 font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 transition">
                                    <i class="fa-solid fa-camera"></i>
                                    <span>Buka Kamera HP Bawaan / File</span>
                                </button>
                            </div>

                            <!-- Petunjuk Izin Browser (seperti webcammictest.com) -->
                            <template x-if="cameraErrorType === 'permission_denied'">
                                <div class="p-2.5 bg-white rounded-xl border border-rose-200/80 text-slate-700 text-[11px] space-y-1.5">
                                    <p class="font-bold text-slate-900">Cara mengizinkan kamera di browser:</p>
                                    <ul class="list-disc list-inside space-y-1 text-slate-600 pl-1">
                                        <li><strong>Brave / Chrome:</strong> Klik ikon slider <strong>⊶</strong> atau <strong>gembok 🔒</strong> di samping kiri alamat URL &rarr; ubah <strong>Kamera</strong> menjadi <strong>Izinkan (Allow)</strong>.</li>
                                        <li><strong>Firefox:</strong> Klik ikon izin di samping kiri URL &rarr; hapus status blokir kamera.</li>
                                        <li><strong>Linux OS:</strong> Pastikan kamera webcam tidak sedang digunakan oleh aplikasi lain (seperti Zoom, Cheese, OBS).</li>
                                    </ul>
                                </div>
                            </template>

                            <template x-if="cameraErrorType === 'not_readable'">
                                <div class="p-2.5 bg-white rounded-xl border border-rose-200/80 text-slate-700 text-[11px]">
                                    <p>Tutup aplikasi video call atau perekam lain (Zoom, OBS, Cheese) yang mungkin sedang mengunci perangkat kamera webcam Anda di Linux.</p>
                                </div>
                            </template>
                        </div>

                        <!-- Live Camera Viewfinder (Tampilan Kamera Langsung) -->
                        <div x-show="cameraOpen" x-cloak class="mt-3 overflow-hidden rounded-2xl border border-slate-700 bg-slate-950 shadow-xl">
                            <!-- Viewfinder Header -->
                            <div class="flex items-center justify-between px-3 py-2 bg-slate-900 border-b border-slate-800 text-white text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                    <span class="font-bold text-slate-200">Kamera Aktif</span>
                                    <span class="text-[10px] text-slate-400 font-mono" x-text="facingMode === 'environment' ? '(Kamera Belakang)' : '(Kamera Depan)'"></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <template x-if="hasMultipleCameras">
                                        <button type="button"
                                                @click="toggleFacingMode()"
                                                class="px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 text-[11px] font-semibold text-slate-200 flex items-center gap-1 transition"
                                                title="Ganti Kamera Depan/Belakang">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                            <span class="hidden sm:inline">Ganti</span>
                                        </button>
                                    </template>
                                    <button type="button"
                                            @click="stopCamera()"
                                            class="p-1 rounded text-slate-400 hover:text-white transition"
                                            title="Tutup Kamera">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Video Stream & Frame Overlay -->
                            <div class="relative bg-black flex items-center justify-center aspect-[4/3] sm:aspect-video w-full overflow-hidden">
                                <video x-ref="cameraPreview" autoplay playsinline muted class="w-full h-full object-cover"></video>
                                <canvas x-ref="cameraCanvas" class="hidden"></canvas>

                                <!-- Viewfinder Guide Overlay -->
                                <div class="absolute inset-4 pointer-events-none border-2 border-white/40 rounded-xl flex items-end justify-center pb-2.5">
                                    <span class="bg-black/60 backdrop-blur-xs text-white text-[10px] font-medium px-2.5 py-1 rounded-full border border-white/20">
                                        Arahkan ke paket / penerima pesanan
                                    </span>
                                </div>
                            </div>

                            <!-- Viewfinder Action Bar -->
                            <div class="p-3 bg-slate-900 border-t border-slate-800 flex items-center justify-between gap-3">
                                <button type="button"
                                        @click="stopCamera()"
                                        class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                                    Batal
                                </button>

                                <button type="button"
                                        @click="capturePhoto()"
                                        class="flex-1 py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold flex items-center justify-center gap-2 transition shadow-md active:scale-95">
                                    <i class="fa-solid fa-camera text-sm"></i>
                                    <span>Jepret Foto</span>
                                </button>

                                <template x-if="hasMultipleCameras">
                                    <button type="button"
                                            @click="toggleFacingMode()"
                                            class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs transition"
                                            title="Putar Kamera">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Preview Foto yang Sudah Diambil / Diunggah -->
                        <div x-show="photoPreviewUrl" x-cloak class="mt-2.5 p-3 rounded-2xl border border-emerald-200 bg-emerald-50/60">
                            <div class="flex items-center gap-3">
                                <div class="relative w-16 h-16 rounded-xl overflow-hidden border border-emerald-300 bg-slate-900 shrink-0 shadow-2xs">
                                    <img :src="photoPreviewUrl" alt="Bukti Serah Terima" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1 text-emerald-700 font-bold text-xs">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Foto Bukti Siap Dikirim</span>
                                    </div>
                                    <p class="text-[11px] font-mono text-slate-600 truncate mt-0.5" x-text="photoFileName"></p>
                                    <span class="inline-block text-[10px] font-semibold text-slate-400" x-text="photoFileSize"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-1.5 shrink-0">
                                    <button type="button"
                                            @click="handleCameraClick()"
                                            class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[11px] font-bold text-slate-700 transition"
                                            title="Foto Ulang">
                                        <i class="fa-solid fa-rotate-right mr-1"></i> Ulang
                                    </button>
                                    <button type="button"
                                            @click="resetPhoto()"
                                            class="px-2.5 py-1.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-[11px] font-bold text-rose-600 transition"
                                            title="Hapus Foto">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Input File Tersembunyi untuk Native Camera dan Form Submission -->
                        <input type="file"
                               x-ref="nativeCameraInput"
                               accept="image/*"
                               capture="environment"
                               @change="handleFileInput($event, 'native_camera')"
                               class="sr-only">

                        <input type="file"
                               x-ref="cameraInput"
                               name="bukti_pengantaran_kamera"
                               accept="image/*"
                               class="sr-only">

                        <span class="text-[11px] text-slate-400 mt-1 block">
                            Foto bukti serah terima hidangan di lokasi pelanggan sebagai bukti pengantaran selesai.
                        </span>
                    </div>
                </div>

                <!-- Sticky Modal Footer (Selalu kelihatan dan bisa di-klik di HP) -->
                <div class="shrink-0 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 p-3 sm:px-6 sm:py-4 bg-slate-50 border-t border-slate-100">
                    <button type="button" @click="closeCompleteModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-100 transition text-center">
                        Batal
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold transition shadow-xs flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Konfirmasi Selesai</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Auto-refresh setiap 30 detik untuk memantau pesanan baru siap antar secara aman
    if (typeof window.scheduleKaryawanAutoRefresh === 'function') {
        window.scheduleKaryawanAutoRefresh(30);
    }
</script>
@endsection
