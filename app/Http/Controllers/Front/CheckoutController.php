<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('front.checkout.index');
    }

    public function proses(Request $request, MidtransService $midtrans)
    {
        $rules = [
            'tipe_pesanan' => ['required', 'in:Makan di Tempat,Delivery'],
            'metode_pembayaran' => ['required', 'in:Kasir,QRIS,COD'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'integer', 'distinct', 'exists:menu,id'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required_if:tipe_pesanan,Delivery', 'nullable', 'string', 'max:1000'],
            'patokan_alamat' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'opsi_dinein' => ['required_if:tipe_pesanan,Makan di Tempat', 'in:sekarang,reservasi'],
            'nomor_meja' => ['nullable', 'string', 'max:50'],
            'jam_kedatangan' => ['nullable', 'date_format:H:i'],
            'jumlah_orang' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];

        if (! Auth::check()) {
            $rules['nama'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules, [
            'tipe_pesanan.required' => 'Silakan pilih tipe layanan pesanan (Makan di Tempat atau Delivery).',
            'tipe_pesanan.in' => 'Pilihan tipe pesanan tidak valid.',
            'metode_pembayaran.required' => 'Silakan pilih metode pembayaran.',
            'metode_pembayaran.in' => 'Metode pembayaran yang dipilih tidak valid.',
            'items.required' => 'Keranjang belanja Anda masih kosong. Silakan pilih menu hidangan terlebih dahulu.',
            'items.array' => 'Format daftar pesanan tidak valid.',
            'items.min' => 'Keranjang belanja Anda masih kosong. Silakan pilih menu hidangan terlebih dahulu.',
            'items.*.menu_id.required' => 'Menu hidangan wajib dipilih.',
            'items.*.menu_id.exists' => 'Menu yang dipilih tidak ditemukan dalam sistem.',
            'items.*.qty.required' => 'Jumlah porsi menu wajib diisi.',
            'items.*.qty.integer' => 'Jumlah porsi menu harus berupa bilangan bulat.',
            'items.*.qty.min' => 'Jumlah porsi menu minimal 1.',
            'items.*.qty.max' => 'Jumlah porsi menu maksimal 100.',
            'nama.required' => 'Nama lengkap pemesan wajib diisi.',
            'nama.max' => 'Nama lengkap pemesan maksimal 255 karakter.',
            'no_hp.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'no_hp.max' => 'Nomor WhatsApp / HP maksimal 20 karakter.',
            'alamat.required_if' => 'Alamat pengantaran wajib diisi jika pesanan bertipe Delivery.',
            'alamat.max' => 'Alamat pengantaran maksimal 1000 karakter.',
            'patokan_alamat.max' => 'Patokan alamat maksimal 255 karakter.',
            'catatan.max' => 'Catatan pesanan maksimal 1000 karakter.',
            'opsi_dinein.required_if' => 'Silakan pilih opsi kedatangan makan di tempat (Langsung Datang atau Reservasi).',
            'opsi_dinein.in' => 'Pilihan opsi makan di tempat tidak valid.',
            'nomor_meja.max' => 'Nomor meja maksimal 50 karakter.',
            'jam_kedatangan.date_format' => 'Format jam kedatangan tidak valid (contoh: 18:30).',
            'jumlah_orang.integer' => 'Jumlah orang harus berupa bilangan bulat.',
            'jumlah_orang.min' => 'Jumlah orang minimal 1 orang.',
            'jumlah_orang.max' => 'Jumlah orang maksimal 100 orang.',
        ]);

        if ($validated['tipe_pesanan'] === 'Delivery' && $validated['metode_pembayaran'] === 'Kasir') {
            throw ValidationException::withMessages(['metode_pembayaran' => 'Pesanan delivery tidak dapat dibayar di kasir.']);
        }

        if ($validated['tipe_pesanan'] === 'Makan di Tempat' && $validated['metode_pembayaran'] === 'COD') {
            throw ValidationException::withMessages(['metode_pembayaran' => 'COD hanya tersedia untuk pesanan delivery.']);
        }

        if ($validated['tipe_pesanan'] === 'Delivery' && $validated['metode_pembayaran'] === 'COD' && ! Auth::check()) {
            abort(403, 'Login diperlukan untuk pembayaran COD.');
        }

        $enumTipePesanan = $validated['tipe_pesanan'] === 'Delivery' ? 'Delivery' : 'Dine-In';
        $enumMetodePembayaran = $validated['metode_pembayaran'] === 'Kasir' ? 'Tunai' : $validated['metode_pembayaran'];

        $infoDineIn = '';
        if ($validated['tipe_pesanan'] === 'Makan di Tempat') {
            if ($request->input('opsi_dinein') === 'sekarang') {
                $infoDineIn = '[LOKASI] Nomor Meja: '.($request->input('nomor_meja') ?: 'Belum diisi')."\n";
            } else {
                $infoDineIn = '[RESERVASI] Jam: '.$request->input('jam_kedatangan').' | '.$request->input('jumlah_orang')." Orang\n";
            }
        }

        $catatanAsli = $request->filled('catatan') ? "Catatan Dapur: {$validated['catatan']}" : '';
        $finalCatatan = $infoDineIn.$catatanAsli;
        $kodeTransaksi = 'TRX-'.date('Ymd').'-'.strtoupper(Str::random(5));
        $namaPelanggan = Auth::user()?->nama ?? ($validated['nama'] ?? null);

        $transaksi = DB::transaction(function () use ($validated, $enumTipePesanan, $enumMetodePembayaran, $kodeTransaksi, $finalCatatan, $namaPelanggan) {
            $menuIds = collect($validated['items'])->pluck('menu_id')->sort()->values();
            $menus = Menu::whereIn('id', $menuIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $totalBayar = 0;
            $ongkosKirim = $enumTipePesanan === 'Delivery' ? 15000 : 0;
            $details = [];

            foreach ($validated['items'] as $item) {
                $menu = $menus->get($item['menu_id']);
                if (! $menu || $menu->stok < $item['qty']) {
                    $namaMenu = $menu ? $menu->nama_menu : 'Menu';
                    $sisaStok = $menu ? $menu->stok : 0;
                    throw ValidationException::withMessages([
                        'items' => "Stok hidangan '{$namaMenu}' tidak mencukupi (tersisa {$sisaStok} porsi). Silakan sesuaikan jumlah pesanan Anda.",
                    ]);
                }

                $subtotal = $menu->harga * $item['qty'];
                $totalBayar += $subtotal;
                $details[] = [$menu->id, $menu->nama_menu, $item['qty'], $menu->harga, $subtotal];
                $menu->decrement('stok', $item['qty']);
            }

            $totalBayar += $ongkosKirim;

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'pengguna_id' => Auth::id(),
                'karyawan_id' => null,
                'nama_pelanggan' => $namaPelanggan,
                'no_hp_pelanggan' => $validated['no_hp'],
                'tipe_pesanan' => $enumTipePesanan,
                'alamat' => $enumTipePesanan === 'Delivery' ? $validated['alamat'] : null,
                'patokan_alamat' => $enumTipePesanan === 'Delivery' ? ($validated['patokan_alamat'] ?? null) : null,
                'metode_pembayaran' => $enumMetodePembayaran,
                'status_pembayaran' => 'Belum Lunas',
                'status_pesanan' => 'Menunggu ACC',
                'subtotal_bayar' => $totalBayar - $ongkosKirim,
                'ongkos_kirim' => $ongkosKirim,
                'total_bayar' => $totalBayar,
                'catatan' => trim($finalCatatan),
            ]);

            foreach ($details as [$menuId, $namaMenu, $qty, $harga, $subtotal]) {
                $transaksi->detail()->create([
                    'menu_id' => $menuId,
                    'nama_menu' => $namaMenu,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                ]);
            }

            return $transaksi;
        });

        session()->put('checkout_transaction_code', $transaksi->kode_transaksi);

        if ($enumMetodePembayaran == 'QRIS') {
            $midtrans->createSnapToken($transaksi);

            return redirect()->to(URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
        }

        return redirect()->to(URL::temporarySignedRoute('checkout.sukses', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
    }

    public function qris(string $kode_transaksi, MidtransService $midtrans)
    {
        $transaksi = $this->accessibleTransaction($kode_transaksi);
        $transaksi->batalkanJikaKedaluwarsa();
        $transaksi->load('detail.menu');

        if ($transaksi->metode_pembayaran === 'QRIS' && ! in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true)) {
            if (! $transaksi->snap_token || str_starts_with($transaksi->snap_token, 'SIM-')) {
                $midtrans->createSnapToken($transaksi);
                $transaksi->refresh();
            }
            if ($transaksi->status_pembayaran !== 'Lunas') {
                $midtrans->syncTransactionStatus($transaksi);
                $transaksi->refresh();
            }
        }

        $snapToken = $transaksi->snap_token;
        $midtransConfigured = $midtrans->isConfigured();
        $midtransClientKey = $midtrans->getClientKey();
        $midtransSnapJsUrl = $midtrans->getSnapJsUrl();

        return view('front.checkout.qris', compact(
            'transaksi',
            'snapToken',
            'midtransConfigured',
            'midtransClientKey',
            'midtransSnapJsUrl'
        ));
    }

    public function checkPaymentStatus(Request $request, string $kode_transaksi, MidtransService $midtrans)
    {
        $transaksi = $this->accessibleTransaction($kode_transaksi);
        $transaksi->batalkanJikaKedaluwarsa();

        if ($transaksi->metode_pembayaran === 'QRIS' && ! in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true)) {
            $orderIdHint = $request->query('order_id');
            $transactionIdHint = $request->query('transaction_id');

            $callbackData = $request->all();
            if ($request->filled('va_number') && $request->filled('bank')) {
                $callbackData['va_numbers'] = [
                    [
                        'bank' => $request->query('bank'),
                        'va_number' => $request->query('va_number'),
                    ],
                ];
            }
            if ($request->filled('channel')) {
                $requestedChannel = strtolower((string) $request->query('channel'));
                $callbackData['channel'] = $requestedChannel;
                if (in_array($requestedChannel, ['dana', 'gopay', 'ovo', 'shopeepay', 'qris', 'bank_transfer', 'bca_va', 'mandiri_va', 'bri_va', 'bni_va'], true)) {
                    if (! $transaksi->payment_type || in_array($transaksi->payment_type, ['qris', 'transfer', 'bank_transfer'], true)) {
                        $transaksi->update(['payment_type' => $requestedChannel]);
                    }
                }
            }
            if ($request->filled('issuer')) {
                $callbackData['issuer'] = $request->query('issuer');
            }
            if ($request->filled('acquirer')) {
                $callbackData['acquirer'] = $request->query('acquirer');
            }

            if (! empty($callbackData['transaction_status']) || ! empty($callbackData['payment_type']) || ! empty($callbackData['va_numbers']) || ! empty($callbackData['bill_key']) || ! empty($callbackData['channel'])) {
                $midtrans->applyGatewayStatus($transaksi, $callbackData);
            }

            if ($transaksi->status_pembayaran !== 'Lunas' || $transaksi->payment_type === 'qris' || ! $transaksi->payment_type) {
                $midtrans->syncTransactionStatus($transaksi, is_string($orderIdHint) ? $orderIdHint : null, is_string($transactionIdHint) ? $transactionIdHint : null);
            }
            $transaksi->refresh();
        }

        return response()->json([
            'kode_transaksi' => $transaksi->kode_transaksi,
            'status_pembayaran' => $transaksi->status_pembayaran,
            'status_pesanan' => $transaksi->status_pesanan,
            'is_lunas' => $transaksi->status_pembayaran === 'Lunas',
            'is_batal' => in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true) || $transaksi->status_pembayaran === 'Batal',
            'payment_channel' => $transaksi->payment_channel_label,
            'payment_reference' => $transaksi->payment_reference,
            'dibayar_pada' => $transaksi->dibayar_pada ? $transaksi->dibayar_pada->format('d M Y, H:i').' WIB' : null,
            'sukses_url' => URL::temporarySignedRoute('checkout.sukses', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]),
        ]);
    }

    public function midtransFinish(Request $request, MidtransService $midtrans)
    {
        $orderId = (string) ($request->query('order_id') ?? $request->query('transaction_id') ?? '');
        $transactionId = (string) $request->query('transaction_id', '');
        $transactionStatus = strtolower((string) $request->query('transaction_status', ''));
        $statusCode = (string) $request->query('status_code', '');

        if ($orderId === '') {
            $lastKode = session('checkout_transaction_code');
            if ($lastKode) {
                return redirect()->to(URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $lastKode]));
            }

            return redirect()->route('home');
        }

        $baseKode = preg_replace('/^(TRX-\d{8}-[A-Z0-9]{5}).*$/', '$1', $orderId);
        $transaksi = Transaksi::where('kode_transaksi', $orderId)
            ->orWhere('kode_transaksi', $baseKode)
            ->first();

        if (! $transaksi) {
            return redirect()->route('home')->with('error', 'Pesanan '.$orderId.' tidak ditemukan.');
        }

        session()->put('checkout_transaction_code', $transaksi->kode_transaksi);

        if (! in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true)) {
            if (in_array($transactionStatus, ['settlement', 'capture'], true) || $statusCode === '200') {
                $midtrans->applyGatewayStatus($transaksi, $request->all());
            }

            if ($transaksi->status_pembayaran !== 'Lunas' || $transaksi->payment_type === 'qris' || ! $transaksi->payment_type) {
                $midtrans->syncTransactionStatus($transaksi, $orderId, $transactionId ?: null);
            }
            $transaksi->refresh();
        }

        if ($transaksi->status_pembayaran === 'Lunas') {
            return redirect()->to(URL::temporarySignedRoute('checkout.sukses', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
        }

        return redirect()->to(URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
    }

    public function midtransCallback(Request $request, MidtransService $midtrans)
    {
        $result = $midtrans->handleWebhookNotification($request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function sukses(string $kode_transaksi, MidtransService $midtrans)
    {
        $transaksi = $this->accessibleTransaction($kode_transaksi);
        $transaksi->load('detail.menu');

        if ($transaksi->metode_pembayaran === 'QRIS' && ! in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true)) {
            if ($transaksi->status_pembayaran !== 'Lunas' || $transaksi->payment_type === 'qris' || ! $transaksi->payment_type) {
                $midtrans->syncTransactionStatus($transaksi);
                $transaksi->refresh();
            }
        }

        return view('front.checkout.success', compact('transaksi'));
    }

    public function pesanan(MidtransService $midtrans)
    {
        Transaksi::batalkanSemuaQrisKedaluwarsa();

        $transaksi = Transaksi::with('detail')
            ->where('pengguna_id', Auth::id())
            ->latest()
            ->paginate(6);

        if ($midtrans->isConfigured()) {
            foreach ($transaksi as $trx) {
                if ($trx->metode_pembayaran === 'QRIS' && ($trx->status_pembayaran === 'Belum Lunas' || $trx->payment_type === 'qris' || ! $trx->payment_type) && ! in_array($trx->status_pesanan, ['Dibatalkan', 'Batal'], true)) {
                    $midtrans->syncTransactionStatus($trx);
                    $trx->refresh();
                }
            }
        }

        return view('front.pesanan', compact('transaksi'));
    }

    public function detailPesanan(string $kode_transaksi, MidtransService $midtrans)
    {
        $transaksi = Transaksi::with(['pelanggan', 'detail.menu', 'kurir'])
            ->where('pengguna_id', Auth::id())
            ->where('kode_transaksi', $kode_transaksi)
            ->firstOrFail();

        $transaksi->batalkanJikaKedaluwarsa();

        if ($transaksi->metode_pembayaran === 'QRIS' && ! in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true)) {
            if ($transaksi->status_pembayaran !== 'Lunas' || $transaksi->payment_type === 'qris' || ! $transaksi->payment_type) {
                $midtrans->syncTransactionStatus($transaksi);
                $transaksi->refresh();
            }
        }

        return view('front.pesanan-detail', compact('transaksi'));
    }

    public function batalkanPesanan(string $kode_transaksi)
    {
        $transaksi = $this->accessibleTransaction($kode_transaksi);

        if (! in_array($transaksi->status_pesanan, ['Menunggu ACC'], true) || $transaksi->status_pembayaran === 'Lunas') {
            return back()->with('error', 'Pesanan yang sedang diproses atau sudah lunas tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($transaksi) {
            $transaksi->update([
                'status_pesanan' => 'Dibatalkan',
                'status_pembayaran' => 'Batal',
            ]);
            $transaksi->restoreStockIfNeeded();
        });

        return back()->with('success', 'Pesanan '.$transaksi->kode_transaksi.' berhasil dibatalkan.');
    }

    public function struk($kode_transaksi)
    {
        $transaksi = $this->accessibleTransaction($kode_transaksi);
        $transaksi->load(['pelanggan', 'karyawan', 'detail.menu']);

        return view('front.struk', compact('transaksi'));
    }

    public function uploadBukti(Request $request, $kode_transaksi)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'bukti_pembayaran.required' => 'Silakan pilih foto bukti transfer/QRIS untuk diunggah.',
            'bukti_pembayaran.image' => 'Berkas bukti pembayaran harus berupa file gambar.',
            'bukti_pembayaran.mimes' => 'Format foto bukti pembayaran harus berupa JPEG, PNG, atau JPG.',
            'bukti_pembayaran.max' => 'Ukuran file foto bukti pembayaran maksimal 5 MB.',
        ]);

        $transaksi = $this->accessibleTransaction($kode_transaksi);

        if ($transaksi->batalkanJikaKedaluwarsa() || in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true) || $transaksi->status_pembayaran === 'Batal') {
            return back()->with('error', 'Batas waktu pembayaran QRIS (1 jam) telah berakhir dan pesanan telah dibatalkan.');
        }

        abort_if($transaksi->metode_pembayaran !== 'QRIS' || $transaksi->status_pembayaran !== 'Belum Lunas', 422, 'Bukti pembayaran hanya dapat diunggah satu kali untuk transaksi yang belum dibayar.');

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_bayar', 'local');

            $transaksi->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'Menunggu Konfirmasi',
            ]);
        }

        return redirect()->to(URL::temporarySignedRoute('checkout.sukses', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
    }

    private function accessibleTransaction(string $kode_transaksi): Transaksi
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();
        $user = Auth::user();
        $isStaff = $user && in_array($user->role, ['admin', 'karyawan', 'kurir'], true);
        $isOwner = $user && (int) $transaksi->pengguna_id === (int) $user->id;
        $isGuestCheckout = session('checkout_transaction_code') === $transaksi->kode_transaksi;

        abort_unless($isStaff || $isOwner || $isGuestCheckout, 404);

        return $transaksi;
    }
}

