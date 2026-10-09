<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi (Validation Language Lines)
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan standar kesalahan yang digunakan oleh
    | pustaka validator. Beberapa aturan memiliki beberapa versi seperti aturan
    | ukuran (size). Anda bebas memodifikasi pesan-pesan ini agar lebih ramah
    | bagi pengguna aplikasi.
    |
    */

    'accepted'             => ':attribute harus diterima dan disetujui.',
    'accepted_if'          => ':attribute harus disetujui ketika :other adalah :value.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa daftar (array).',
    'ascii'                => ':attribute hanya boleh berisi karakter alfanumerik byte tunggal dan simbol.',
    'before'               => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'array'   => ':attribute harus memiliki antara :min sampai :max item.',
        'file'    => 'Ukuran berkas :attribute harus antara :min sampai :max kilobita.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string'  => ':attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'boolean'              => ':attribute harus bernilai benar atau salah.',
    'can'                  => ':attribute berisi nilai yang tidak diizinkan.',
    'confirmed'            => 'Konfirmasi :attribute tidak sesuai.',
    'contains'             => ':attribute tidak memiliki nilai yang dibutuhkan.',
    'current_password'     => 'Kata sandi saat ini tidak cocok.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'decimal'              => ':attribute harus memiliki :decimal tempat desimal.',
    'declined'             => ':attribute harus ditolak.',
    'declined_if'          => ':attribute harus ditolak ketika :other adalah :value.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits angka.',
    'digits_between'       => ':attribute harus terdiri dari :min sampai :max angka.',
    'dimensions'           => 'Dimensi gambar untuk :attribute tidak sesuai.',
    'distinct'             => ':attribute memiliki nilai duplikat.',
    'doesnt_end_with'      => ':attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with'    => ':attribute tidak boleh diawali dengan salah satu dari: :values.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'ends_with'            => ':attribute harus diakhiri dengan salah satu dari: :values.',
    'enum'                 => ':attribute yang dipilih tidak valid.',
    'exists'               => ':attribute yang dipilih tidak valid atau tidak ditemukan.',
    'extensions'           => ':attribute harus memiliki salah satu ekstensi: :values.',
    'file'                 => ':attribute harus berupa berkas file.',
    'filled'               => ':attribute wajib diisi.',
    'gt'                   => [
        'array'   => ':attribute harus memiliki lebih dari :value item.',
        'file'    => 'Ukuran :attribute harus lebih besar dari :value kilobita.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string'  => ':attribute harus lebih panjang dari :value karakter.',
    ],
    'gte'                  => [
        'array'   => ':attribute harus memiliki minimal :value item.',
        'file'    => 'Ukuran :attribute minimal :value kilobita.',
        'numeric' => ':attribute harus lebih besar atau sama dengan :value.',
        'string'  => ':attribute minimal terdiri dari :value karakter.',
    ],
    'hex_color'            => ':attribute harus berupa kode warna heksadesimal yang valid.',
    'image'                => ':attribute harus berupa berkas gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => ':attribute tidak ada dalam :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa teks format JSON yang valid.',
    'list'                 => ':attribute harus berupa daftar (list).',
    'lowercase'            => ':attribute harus berupa huruf kecil.',
    'lt'                   => [
        'array'   => ':attribute harus memiliki kurang dari :value item.',
        'file'    => 'Ukuran :attribute harus kurang dari :value kilobita.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string'  => ':attribute harus kurang dari :value karakter.',
    ],
    'lte'                  => [
        'array'   => ':attribute tidak boleh lebih dari :value item.',
        'file'    => 'Ukuran :attribute tidak boleh lebih dari :value kilobita.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string'  => ':attribute tidak boleh lebih dari :value karakter.',
    ],
    'mac_address'          => ':attribute harus berupa alamat MAC yang valid.',
    'max'                  => [
        'array'   => ':attribute maksimal terdiri dari :max item.',
        'file'    => 'Ukuran :attribute maksimal :max kilobita.',
        'numeric' => ':attribute maksimal bernilai :max.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits'           => ':attribute maksimal terdiri dari :max digit angka.',
    'mimes'                => ':attribute harus berupa berkas dengan format: :values.',
    'mimetypes'            => ':attribute harus berupa berkas dengan tipe: :values.',
    'min'                  => [
        'array'   => ':attribute minimal harus memiliki :min item.',
        'file'    => 'Ukuran :attribute minimal :min kilobita.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string'  => ':attribute minimal terdiri dari :min karakter.',
    ],
    'min_digits'           => ':attribute minimal terdiri dari :min digit angka.',
    'missing'              => ':attribute tidak boleh disertakan.',
    'missing_if'           => ':attribute tidak boleh disertakan saat :other bernilai :value.',
    'missing_unless'       => ':attribute tidak boleh disertakan kecuali :other bernilai :value.',
    'missing_with'         => ':attribute tidak boleh disertakan saat terdapat :values.',
    'missing_with_all'     => ':attribute tidak boleh disertakan saat terdapat :values.',
    'multiple_of'          => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':attribute harus mengandung minimal satu huruf.',
        'mixed'         => ':attribute harus mengandung huruf besar dan huruf kecil.',
        'numbers'       => ':attribute harus mengandung minimal satu angka.',
        'symbols'       => ':attribute harus mengandung minimal satu simbol khusus.',
        'uncompromised' => ':attribute yang dimasukkan pernah terindikasi kebocoran data. Silakan pilih kata sandi lain.',
    ],
    'present'              => ':attribute wajib ada.',
    'present_if'           => ':attribute wajib ada saat :other bernilai :value.',
    'present_unless'       => ':attribute wajib ada kecuali :other bernilai :value.',
    'present_with'         => ':attribute wajib ada saat terdapat :values.',
    'present_with_all'     => ':attribute wajib ada saat terdapat :values.',
    'prohibited'           => ':attribute dilarang diisi.',
    'prohibited_if'        => ':attribute dilarang diisi saat :other bernilai :value.',
    'prohibited_unless'    => ':attribute dilarang diisi kecuali :other terdapat dalam :values.',
    'prohibits'            => ':attribute melarang :other untuk disertakan.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_array_keys'  => ':attribute harus memuat entri untuk: :values.',
    'required_if'          => ':attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => ':attribute wajib diisi jika :other disetujui.',
    'required_if_declined' => ':attribute wajib diisi jika :other ditolak.',
    'required_unless'      => ':attribute wajib diisi kecuali :other terdapat dalam :values.',
    'required_with'        => ':attribute wajib diisi jika terdapat :values.',
    'required_with_all'    => ':attribute wajib diisi jika terdapat :values.',
    'required_without'     => ':attribute wajib diisi jika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi jika satupun dari :values tidak ada.',
    'same'                 => ':attribute harus sama dengan :other.',
    'size'                 => [
        'array'   => ':attribute harus memuat :size item.',
        'file'    => 'Ukuran :attribute harus berukuran :size kilobita.',
        'numeric' => ':attribute harus bernilai :size.',
        'string'  => ':attribute harus berukuran :size karakter.',
    ],
    'starts_with'          => ':attribute harus diawali dengan salah satu dari: :values.',
    'string'               => ':attribute harus berupa teks.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah digunakan. Silakan gunakan yang lain.',
    'uploaded'             => ':attribute gagal diunggah.',
    'uppercase'            => ':attribute harus berupa huruf besar.',
    'url'                  => ':attribute harus berupa URL yang valid.',
    'ulid'                 => ':attribute harus berupa format ULID yang valid.',
    'uuid'                 => ':attribute harus berupa format UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Kustomisasi Pesan Validasi
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi kustom untuk atribut dengan
    | konvensi penamaan "attribute.rule" untuk menentukan baris pesannya.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nama Atribut Kustom (Bahasa Indonesia Ramah Pengguna)
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar nama placeholder atribut
    | dengan sesuatu yang lebih ramah pembaca seperti "Alamat Email" daripada
    | "email" atau "Nama Menu" daripada "nama_menu".
    |
    */

    'attributes' => [
        'nama'                     => 'Nama lengkap',
        'nama_menu'                => 'Nama menu hidangan',
        'nama_kategori'            => 'Nama kategori',
        'nama_pelanggan'           => 'Nama pemesan',
        'email'                    => 'Alamat email',
        'password'                 => 'Kata sandi',
        'password_confirmation'    => 'Konfirmasi kata sandi',
        'current_password'         => 'Kata sandi saat ini',
        'role'                     => 'Hak akses (role)',
        'no_hp'                    => 'Nomor HP / WhatsApp',
        'no_hp_pelanggan'          => 'Nomor HP pemesan',
        'jenis_kelamin'            => 'Jenis kelamin',
        'tanggal_lahir'            => 'Tanggal lahir',
        'alamat'                   => 'Alamat lengkap',
        'patokan_alamat'           => 'Patokan alamat',
        'foto'                     => 'Foto profil',
        'gambar'                   => 'Foto hidangan',
        'harga'                    => 'Harga hidangan',
        'stok'                     => 'Jumlah stok',
        'deskripsi'                => 'Deskripsi singkat',
        'is_active'                => 'Status keaktifan',
        'slug'                     => 'Slug tautan',
        'judul'                    => 'Judul banner',
        'sub_judul'                => 'Sub judul banner',
        'menu_id'                  => 'Pilihan menu',
        'kategori_id'              => 'Kategori menu',
        'pengguna_id'              => 'Data pelanggan',
        'karyawan_id'              => 'Petugas kasir',
        'kurir_id'                 => 'Petugas kurir',
        'tipe_pesanan'             => 'Tipe pesanan',
        'metode_pembayaran'        => 'Metode pembayaran',
        'status_pembayaran'        => 'Status pembayaran',
        'status_pesanan'           => 'Status pesanan',
        'catatan'                  => 'Catatan pesanan',
        'nomor_meja'               => 'Nomor meja',
        'opsi_dinein'              => 'Opsi makan di tempat',
        'jam_kedatangan'           => 'Jam perkiraan kedatangan',
        'jumlah_orang'             => 'Jumlah orang / porsi',
        'items'                    => 'Daftar pesanan menu',
        'items.*.menu_id'          => 'Menu pesanan',
        'items.*.qty'              => 'Jumlah porsi menu',
        'bukti_pembayaran'         => 'Bukti pembayaran',
        'bukti_pengantaran'        => 'Bukti foto pengantaran',
        'bukti_pengantaran_file'   => 'Berkas bukti pengantaran',
        'bukti_pengantaran_kamera' => 'Foto bukti pengantaran',
        'diterima_oleh'            => 'Nama penerima',
        'dari'                     => 'Tanggal awal',
        'sampai'                   => 'Tanggal akhir',
    ],

];

