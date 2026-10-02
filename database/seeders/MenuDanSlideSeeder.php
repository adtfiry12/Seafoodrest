<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Slide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MenuDanSlideSeeder extends Seeder
{
    public function run(): void
    {
        $menuDir = storage_path('app/public/menu');
        $slideDir = storage_path('app/public/slide_images');

        if (! File::isDirectory($menuDir)) {
            File::makeDirectory($menuDir, 0755, true);
        }
        if (! File::isDirectory($slideDir)) {
            File::makeDirectory($slideDir, 0755, true);
        }

        // 1. Sinkronisasi Kategori Menu
        $categoriesData = [
            'Kepiting & Lobster' => 'kepiting-lobster',
            'Ikan Laut' => 'ikan-laut',
            'Udang & Cumi' => 'udang-cumi',
            'Kerang & Spesial' => 'kerang-spesial',
            'Sup & Sayuran' => 'sup-sayuran',
            'Minuman Segar' => 'minuman-segar',
        ];

        $kategoriMap = [];
        foreach ($categoriesData as $name => $slug) {
            $cat = Kategori::updateOrCreate(
                ['slug' => $slug],
                ['nama_kategori' => $name]
            );
            $kategoriMap[$name] = $cat->id;
        }

        // 2. Daftar 30 Menu Seafood Otentik & Lengkap
        $menusData = [
            // --- KEPITING & LOBSTER ---
            [
                'kategori' => 'Kepiting & Lobster',
                'nama' => 'Kepiting Saus Padang Spesial',
                'harga' => 145000,
                'stok' => 25,
                'deskripsi' => 'Kepiting bakau segar berdaging tebal dimasak dengan racikan saus Padang pedas manis gurih beraroma daun jeruk dan rempah Nusantara.',
                'img_url' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=800&q=80',
                'filename' => 'kepiting_saus_padang.jpg',
            ],
            [
                'kategori' => 'Kepiting & Lobster',
                'nama' => 'Kepiting Saus Lada Hitam',
                'harga' => 145000,
                'stok' => 20,
                'deskripsi' => 'Kepiting jantan pilihan ditumis bumbu lada hitam khas Lampung dengan sensasi pedas hangat yang mantap meresap ke dalam daging.',
                'img_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&q=80',
                'filename' => 'kepiting_lada_hitam.jpg',
            ],
            [
                'kategori' => 'Kepiting & Lobster',
                'nama' => 'Kepiting Bakar Asap Aroma Pandan',
                'harga' => 150000,
                'stok' => 18,
                'deskripsi' => 'Kepiting dibakar perlahan dalam balutan daun pisang dan rempah asap harum wangi, meresap sempurna hingga ke dalam capit.',
                'img_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=800&q=80',
                'filename' => 'kepiting_bakar_asap.jpg',
            ],
            [
                'kategori' => 'Kepiting & Lobster',
                'nama' => 'Lobster Mutiara Saus Mentega Bawang',
                'harga' => 185000,
                'stok' => 15,
                'deskripsi' => 'Lobster mutiara segar berukuran besar dimasak tumis mentega gurih harum bawang putih dan perasan jeruk limau segar.',
                'img_url' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?w=800&q=80',
                'filename' => 'lobster_saus_mentega.jpg',
            ],
            [
                'kategori' => 'Kepiting & Lobster',
                'nama' => 'Lobster Bakar Keju Mozzarella',
                'harga' => 195000,
                'stok' => 12,
                'deskripsi' => 'Daging lobster kenyal gurih dipanggang dengan saus keju istimewa dan lelehan mozzarella keemasan yang menggoda.',
                'img_url' => 'https://images.unsplash.com/photo-1553659971-f01207815844?w=800&q=80',
                'filename' => 'lobster_bakar_keju.jpg',
            ],

            // --- IKAN LAUT ---
            [
                'kategori' => 'Ikan Laut',
                'nama' => 'Ikan Gurame Bakar Madu Pedas',
                'harga' => 78000,
                'stok' => 30,
                'deskripsi' => 'Ikan gurame bakar olesan madu hutan alami dan sambal rica yang manis, gurih, dan berkarakter harum bakaran arang batok.',
                'img_url' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=800&q=80',
                'filename' => 'gurame_bakar_madu.jpg',
            ],
            [
                'kategori' => 'Ikan Laut',
                'nama' => 'Ikan Kakap Merah Saus Asam Manis',
                'harga' => 88000,
                'stok' => 25,
                'deskripsi' => 'Ikan kakap merah goreng renyah disiram saus asam manis nanas segar, paprika, dan irisan bawang bombay renyah.',
                'img_url' => 'https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=800&q=80',
                'filename' => 'kakap_asam_manis.jpg',
            ],
            [
                'kategori' => 'Ikan Laut',
                'nama' => 'Ikan Kerapu Kukus Saus Tiram Jahe',
                'harga' => 95000,
                'stok' => 20,
                'deskripsi' => 'Daging ikan kerapu lembut dikukus gaya oriental dengan irisan jahe muda, daun ketumbar, dan siraman minyak wijen gurih.',
                'img_url' => 'https://images.unsplash.com/photo-1574484284002-952d92456975?w=800&q=80',
                'filename' => 'kerapu_kukus_jahe.jpg',
            ],
            [
                'kategori' => 'Ikan Laut',
                'nama' => 'Ikan Baronang Bakar Bumbu Jimbaran',
                'harga' => 82000,
                'stok' => 25,
                'deskripsi' => 'Ikan baronang bakar resep khas Jimbaran Bali kaya rempah kunyit dan terasi bakar, disajikan bersama sambal matah.',
                'img_url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80',
                'filename' => 'baronang_bakar_jimbaran.jpg',
            ],
            [
                'kategori' => 'Ikan Laut',
                'nama' => 'Ikan Bawal Bintang Goreng Kering Sambal Terasi',
                'harga' => 75000,
                'stok' => 35,
                'deskripsi' => 'Ikan bawal bintang segar digoreng renyah garing hingga ke sirip, disajikan dengan lalapan segar dan sambal terasi ulek dadak.',
                'img_url' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?w=800&q=80',
                'filename' => 'bawal_goreng_kering.jpg',
            ],

            // --- UDANG & CUMI ---
            [
                'kategori' => 'Udang & Cumi',
                'nama' => 'Udang Windu Bakar Madu Jimbaran',
                'harga' => 72000,
                'stok' => 40,
                'deskripsi' => 'Udang windu ukuran jumbo dibelah punggung, diolesi bumbu bakar rempah madu manis gurih khas pesisir pantai.',
                'img_url' => 'https://images.unsplash.com/photo-1559737558-245cb34399e2?w=800&q=80',
                'filename' => 'udang_windu_bakar.jpg',
            ],
            [
                'kategori' => 'Udang & Cumi',
                'nama' => 'Udang Goreng Saus Telur Asin',
                'harga' => 68000,
                'stok' => 35,
                'deskripsi' => 'Udang goreng tepung renyah berbalut saus telur asin lembut gurih creamy beraroma daun kari dan cabai rawit.',
                'img_url' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=800&q=80',
                'filename' => 'udang_telur_asin.jpg',
            ],
            [
                'kategori' => 'Udang & Cumi',
                'nama' => 'Udang Asam Manis Nanas Segar',
                'harga' => 65000,
                'stok' => 40,
                'deskripsi' => 'Udang segar kupas dimasak saus asam manis kental dengan potongan buah nanas manis dan taburan daun bawang.',
                'img_url' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=800&q=80',
                'filename' => 'udang_asam_manis.jpg',
            ],
            [
                'kategori' => 'Udang & Cumi',
                'nama' => 'Cumi Goreng Tepung Calamari Crispy',
                'harga' => 55000,
                'stok' => 45,
                'deskripsi' => 'Cincin cumi segar berbalut tepung bumbu krispi keemasan, disajikan dengan saus tar-tar mayones lembut.',
                'img_url' => 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?w=800&q=80',
                'filename' => 'cumi_calamari_crispy.jpg',
            ],
            [
                'kategori' => 'Udang & Cumi',
                'nama' => 'Cumi Bakar Saus Madu Karamel',
                'harga' => 58000,
                'stok' => 35,
                'deskripsi' => 'Cumi utuh bergaris dipanggang dengan olesan kecap madu gurih, menghasilkan aroma karamel panggang yang harum menggugah selera.',
                'img_url' => 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=800&q=80',
                'filename' => 'cumi_bakar_madu.jpg',
            ],
            [
                'kategori' => 'Udang & Cumi',
                'nama' => 'Cumi Masak Hitam Gurih Pedas',
                'harga' => 58000,
                'stok' => 30,
                'deskripsi' => 'Olahan cumi tradisional dimasak langsung dengan tinta alaminya yang pekat, gurih manis, dan beraroma cabai rawit melimpah.',
                'img_url' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=800&q=80',
                'filename' => 'cumi_masak_hitam.jpg',
            ],

            // --- KERANG & SPESIAL ---
            [
                'kategori' => 'Kerang & Spesial',
                'nama' => 'Kerang Dara Rebus Saus Nanas Pedas',
                'harga' => 38000,
                'stok' => 50,
                'deskripsi' => 'Kerang dara segar direbus bumbu jahe serai wangi, disantap bersama cocolan saus sambal nanas asam pedas legendaris.',
                'img_url' => 'https://images.unsplash.com/photo-1608897013039-887f21d8c804?w=800&q=80',
                'filename' => 'kerang_dara_rebus.jpg',
            ],
            [
                'kategori' => 'Kerang & Spesial',
                'nama' => 'Kerang Hijau Saus Padang Melimpah',
                'harga' => 42000,
                'stok' => 45,
                'deskripsi' => 'Kerang hijau pilihan dimasak tumis dengan saus Padang kental bertabur irisan cabai merah dan daun bawang segar.',
                'img_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800&q=80',
                'filename' => 'kerang_hijau_padang.jpg',
            ],
            [
                'kategori' => 'Kerang & Spesial',
                'nama' => 'Kerang Bambu Tumis Bawang Cabai Garam',
                'harga' => 48000,
                'stok' => 30,
                'deskripsi' => 'Kerang bambu kenyal manis ditumis kering dengan cabai merah, bawang putih cincang renyah, dan garam gurih.',
                'img_url' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&q=80',
                'filename' => 'kerang_bambu_cabai_garam.jpg',
            ],
            [
                'kategori' => 'Kerang & Spesial',
                'nama' => 'Platter Seafood Tumpah Niskala (Porsi 3-4 Orang)',
                'harga' => 245000,
                'stok' => 15,
                'deskripsi' => 'Pesta hidangan laut komplit berisi kepiting bakau, udang windu, cumi, kerang dara, kerang hijau, dan jagung manis saus Padang melimpah.',
                'img_url' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=800&q=80',
                'filename' => 'platter_seafood_tumpah.jpg',
            ],
            [
                'kategori' => 'Kerang & Spesial',
                'nama' => 'Otak-Otak Ikan Tenggiri Bakar Asli (Isi 5 Pcs)',
                'harga' => 32000,
                'stok' => 40,
                'deskripsi' => 'Otak-otak panggang daun pisang dari daging ikan tenggiri asli Jepara yang kenyal harum, disajikan dengan saus kacang gurih pedas.',
                'img_url' => 'https://images.unsplash.com/photo-1541544741938-0af808871cc0?w=800&q=80',
                'filename' => 'otak_otak_tenggiri.jpg',
            ],

            // --- SUP & SAYURAN ---
            [
                'kategori' => 'Sup & Sayuran',
                'nama' => 'Sup Tom Yum Seafood Kuah Merah Segar',
                'harga' => 58000,
                'stok' => 35,
                'deskripsi' => 'Sup kuah merah kaya rempah serai, lengkuas, dan daun jeruk berisi aneka udang, cumi, jamur, serta perasan jeruk nipis segar.',
                'img_url' => 'https://images.unsplash.com/photo-1548943487-a2e4e43b4853?w=800&q=80',
                'filename' => 'tom_yum_seafood.jpg',
            ],
            [
                'kategori' => 'Sup & Sayuran',
                'nama' => 'Sup Ikan Gurame Kuah Bening Kemangi',
                'harga' => 65000,
                'stok' => 25,
                'deskripsi' => 'Sup ikan gurame segar kuah bening rempah kemangi, tomat hijau, dan belimbing wuluh yang menyegarkan tanpa aroma amis.',
                'img_url' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=800&q=80',
                'filename' => 'sup_gurame_kemangi.jpg',
            ],
            [
                'kategori' => 'Sup & Sayuran',
                'nama' => 'Cah Kangkung Terasi Balacan Hotplate',
                'harga' => 25000,
                'stok' => 50,
                'deskripsi' => 'Kangkung segar ditumis di atas wajan membara dengan pasta terasi bakar, udang rebon, dan irisan cabai rawit pedas.',
                'img_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&q=80',
                'filename' => 'cah_kangkung_terasi.jpg',
            ],
            [
                'kategori' => 'Sup & Sayuran',
                'nama' => 'Toge Ikan Asin Jambal Roti Crispy',
                'harga' => 28000,
                'stok' => 40,
                'deskripsi' => 'Tauge montok segar ditumis renyah cepat bersama potongan ikan asin jambal roti gurih dan daun bawang.',
                'img_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80',
                'filename' => 'toge_ikan_asin.jpg',
            ],
            [
                'kategori' => 'Sup & Sayuran',
                'nama' => 'Capcay Seafood Spesial Kuah Kental',
                'harga' => 45000,
                'stok' => 35,
                'deskripsi' => 'Kombinasi aneka sayuran segar wortel, brokoli, kembang kol, udang, dan bakso ikan dalam balutan kuah gurih hangat.',
                'img_url' => 'https://images.unsplash.com/photo-1569058242253-92a9c755a0ec?w=800&q=80',
                'filename' => 'capcay_seafood.jpg',
            ],

            // --- MINUMAN SEGAR ---
            [
                'kategori' => 'Minuman Segar',
                'nama' => 'Kelapa Muda Batok Murni Segar',
                'harga' => 22000,
                'stok' => 60,
                'deskripsi' => 'Air dan daging kelapa muda segar utuh langsung dari batok kelapa pilihan, manis alami pelepas dahaga sempurna saat bersantap seafood.',
                'img_url' => 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?w=800&q=80',
                'filename' => 'kelapa_muda_murni.jpg',
            ],
            [
                'kategori' => 'Minuman Segar',
                'nama' => 'Es Jeruk Nipis Madu Selasih',
                'harga' => 18000,
                'stok' => 60,
                'deskripsi' => 'Perasan jeruk nipis segar dipadu madu murni dan biji selasih dingin yang kaya kesegaran untuk menetralisir rasa gurih seafood.',
                'img_url' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=800&q=80',
                'filename' => 'es_jeruk_madu_selasih.jpg',
            ],
            [
                'kategori' => 'Minuman Segar',
                'nama' => 'Es Teh Manis Melati Jumbo',
                'harga' => 10000,
                'stok' => 100,
                'deskripsi' => 'Seduhan teh melati wangi aroma Nusantara dalam gelas ukuran jumbo dengan gula tebu asli dan es batu melimpah.',
                'img_url' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=800&q=80',
                'filename' => 'es_teh_manis_jumbo.jpg',
            ],
            [
                'kategori' => 'Minuman Segar',
                'nama' => 'Es Teler Niskala Durian & Alpukat',
                'harga' => 32000,
                'stok' => 40,
                'deskripsi' => 'Es teler mewah berisi serutan kelapa muda, nangka manis, alpukat mentega, dan topping buah durian asli berkuah santan kental manis gurih.',
                'img_url' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=800&q=80',
                'filename' => 'es_teler_durian.jpg',
            ],
        ];

        $fallbackImage = public_path('image/seafood.webp');
        $createdMenus = [];

        foreach ($menusData as $i => $item) {
            $imagePath = 'menu/' . $item['filename'];
            $fullTarget = storage_path('app/public/' . $imagePath);

            // Unduh foto jika belum ada di storage
            if (! File::exists($fullTarget)) {
                $downloaded = false;
                try {
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 8,
                            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        ],
                    ]);
                    $content = @file_get_contents($item['img_url'], false, $context);
                    if ($content && strlen($content) > 1000) {
                        File::put($fullTarget, $content);
                        $downloaded = true;
                    }
                } catch (\Throwable $e) {
                    $downloaded = false;
                }

                if (! $downloaded) {
                    // Fallback aman dari gambar seafood lokal
                    if (File::exists($fallbackImage)) {
                        File::copy($fallbackImage, $fullTarget);
                    }
                }
            }

            $catId = $kategoriMap[$item['kategori']] ?? 1;
            $slug = Str::slug($item['nama']);

            // Update record jika sudah ada (misal menu 1-4) atau buat baru
            $menu = Menu::updateOrCreate(
                ['slug' => $slug],
                [
                    'kategori_id' => $catId,
                    'nama_menu' => $item['nama'],
                    'deskripsi' => $item['deskripsi'],
                    'gambar' => $imagePath,
                    'harga' => $item['harga'],
                    'stok' => $item['stok'],
                    'is_active' => true,
                ]
            );

            $createdMenus[$item['nama']] = $menu;
        }

        // 3. Empat Slide Banner Promo Eksklusif
        $slidesData = [
            [
                'judul' => 'FESTIVAL KEPITING SAUS NUSANTARA',
                'sub_judul' => 'Menu Signature Pilihan Chef',
                'deskripsi' => 'Nikmati kelezatan kepiting bakau segar berbalut aneka racikan saus Padang, lada hitam, dan bakar asap wangi rempah asli Jepara.',
                'menu_target' => 'Kepiting Saus Padang Spesial',
                'img_url' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=1600&q=80',
                'filename' => 'slide_kepiting_nusantara.jpg',
            ],
            [
                'judul' => 'TANGKAPAN LAUT SEGAR SETIAP HARI',
                'sub_judul' => 'Jaminan Kualitas Mutu Terbaik',
                'deskripsi' => 'Setiap hidangan diolah dari hasil tangkapan nelayan lokal pagi hari untuk memastikan kesegaran, rasa manis alami, dan gizi terbaik.',
                'menu_target' => 'Ikan Gurame Bakar Madu Pedas',
                'img_url' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=1600&q=80',
                'filename' => 'slide_ikan_bakar.jpg',
            ],
            [
                'judul' => 'PAKET SEAFOOD TUMPAH KELUARGA',
                'sub_judul' => 'Sensasi Santap Bersama Paling Hemat',
                'deskripsi' => 'Pesta hidangan laut komplit berisi kepiting, udang windu jumbo, cumi krispi, dan aneka kerang saus melimpah untuk santap 3-4 orang.',
                'menu_target' => 'Platter Seafood Tumpah Niskala (Porsi 3-4 Orang)',
                'img_url' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=1600&q=80',
                'filename' => 'slide_seafood_tumpah.jpg',
            ],
            [
                'judul' => 'PENGALAMAN KULINER PESISIR JEPARA',
                'sub_judul' => 'Suasana Hangat Tepi Pantai Kartini',
                'deskripsi' => 'Suasana bersantap nyaman di tepi Pantai Kartini dengan hidangan istimewa, panorama laut senja, dan pelayanan ramah berstandar tinggi.',
                'menu_target' => 'Lobster Mutiara Saus Mentega Bawang',
                'img_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=1600&q=80',
                'filename' => 'slide_kuliner_pesisir.jpg',
            ],
        ];

        // Hapus slide lama sesuai permintaan user agar diganti bersih 4 slide baru
        Slide::truncate();

        foreach ($slidesData as $s) {
            $imagePath = 'slide_images/' . $s['filename'];
            $fullTarget = storage_path('app/public/' . $imagePath);

            if (! File::exists($fullTarget)) {
                $downloaded = false;
                try {
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 8,
                            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        ],
                    ]);
                    $content = @file_get_contents($s['img_url'], false, $context);
                    if ($content && strlen($content) > 1000) {
                        File::put($fullTarget, $content);
                        $downloaded = true;
                    }
                } catch (\Throwable $e) {
                    $downloaded = false;
                }

                if (! $downloaded && File::exists($fallbackImage)) {
                    File::copy($fallbackImage, $fullTarget);
                }
            }

            $linkedMenu = $createdMenus[$s['menu_target']] ?? null;

            Slide::create([
                'menu_id' => $linkedMenu?->id,
                'judul' => $s['judul'],
                'sub_judul' => $s['sub_judul'],
                'deskripsi' => $s['deskripsi'],
                'gambar' => $imagePath,
                'is_active' => true,
            ]);
        }
    }
}
