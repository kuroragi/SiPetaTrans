# Dokumentasi Skema Database SIPETA-TRANS

Sistem Informasi Pemetaan Aset Transportasi (SIPETA-TRANS) Dinas Perhubungan Kota Bukittinggi menggunakan database relasional dengan struktur tabel yang dikelompokkan berdasarkan modul fungsional sistem.

---

## 1. Modul Manajemen Pengguna & Hak Akses (RBAC)

Modul ini menangani autentikasi pengguna, sesi, pemulihan password, serta pengaturan hak akses berbasis peran (*Role-Based Access Control* / RBAC) menggunakan paket Laravel Spatie Permission.

### Tabel: `users`
Menyimpan informasi utama akun pengguna sistem.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `name` | varchar(255) | No | - | Nama lengkap pengguna |
| `email` | varchar(255) | No | - | Email unik untuk login |
| `email_verified_at`| timestamp | Yes | NULL | Waktu verifikasi email |
| `password` | varchar(255) | No | - | Hash password bcrypt |
| `remember_token` | varchar(100) | Yes | NULL | Token untuk fitur "remember me" |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### Tabel: `roles`
Menyimpan daftar peran (*role*) dalam sistem.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `name` | varchar(255) | No | - | Nama role (cth: `admin`, `petugas`) |
| `guard_name` | varchar(255) | No | - | Guard Laravel (biasanya `web`) |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### Tabel: `permissions`
Menyimpan daftar hak akses spesifik (*permission*).

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `name` | varchar(255) | No | - | Nama permission |
| `guard_name` | varchar(255) | No | - | Guard Laravel (biasanya `web`) |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### Tabel Hubungan RBAC:
- **`model_has_roles`**: Menghubungkan pengguna dengan perannya.
  - Field: `role_id` (foreign key ke `roles.id`), `model_type` (tipe model), `model_id` (ID pengguna).
- **`model_has_permissions`**: Menghubungkan pengguna langsung dengan hak akses khusus.
  - Field: `permission_id` (foreign key ke `permissions.id`), `model_type`, `model_id`.
- **`role_has_permissions`**: Menghubungkan peran (*role*) dengan hak akses (*permission*).
  - Field: `permission_id` (foreign key ke `permissions.id`), `role_id` (foreign key ke `roles.id`).

### Tabel Pendukung Sesi & Keamanan:
- **`password_reset_tokens`**: Menyimpan token reset password (`email`, `token`, `created_at`).
- **`sessions`**: Menyimpan data sesi login pengguna (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`).

---

## 2. Modul Manajemen Aset & Jenis Aset (Inventory & Mapping)

Modul utama untuk mengelola kategori aset, sub-kategori, data inventaris aset fisik, serta posisi spasialnya (titik/garis/poligon).

### Tabel: `asset_types`
Menyimpan tipe/kategori utama aset (cth: Halte, Rambu Jalan, Parkir).

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `name` | varchar(255) | No | - | Nama tipe aset |
| `icon` | varchar(255) | Yes | NULL | Nama/path ikon untuk peta |
| `color` | varchar(255) | Yes | NULL | Kode warna visualisasi |
| `description` | text | Yes | NULL | Penjelasan tipe aset |
| `geometry` | enum('point','polygon','polyline') | No | 'point' | Tipe geometri visualisasi peta |
| `asset_category` | varchar(255) | No | 'general_asset'| Pengelompokan kategori aset |
| `is_active` | boolean | No | true | Status keaktifan tipe aset |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### Tabel: `asset_sub_types`
Menyimpan sub-kategori dari tipe aset utama.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `asset_type_id` | bigint | No | - | Foreign Key ke `asset_types.id` |
| `name` | varchar(255) | No | - | Nama sub-kategori aset |
| `color` | varchar(255) | Yes | NULL | Kode warna khusus |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### Tabel: `assets`
Menyimpan data detail setiap aset transportasi yang terinventarisasi.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `registration_number`| varchar(255) | Yes | NULL | Nomor registrasi/plat/kode aset |
| `name` | varchar(255) | No | - | Nama spesifik aset |
| `asset_type_id` | bigint (unsigned) | No | - | Foreign Key ke `asset_types.id` |
| `asset_sub_type_id` | bigint (unsigned) | Yes | NULL | Foreign Key ke `asset_sub_types.id` |
| `acquired_at` | date | Yes | NULL | Tanggal perolehan/pengadaan |
| `acquisition_value`| bigint | Yes | 0 | Nilai perolehan awal (Rupiah) |
| `acquisition_source`| varchar(255) | Yes | NULL | Sumber pendanaan aset |
| `current_value` | bigint | Yes | 0 | Nilai buku aset saat ini |
| `status` | enum('baik', 'perlu_perbaikan', 'rusak', 'dalam_pemeliharaan') | Yes | 'baik' | Kondisi fisik aset |
| `is_active` | boolean | No | true | Status operasional aset |
| `coordinates` | json | Yes | NULL | Data koordinat spasial (GeoJSON/array) |
| `latitude` | decimal(10,7) | Yes | NULL | Koordinat lintang (untuk tipe point) |
| `longitude` | decimal(10,7) | Yes | NULL | Koordinat bujur (untuk tipe point) |
| `location` | varchar(255) | Yes | NULL | Deskripsi lokasi (alamat/wilayah/kelurahan)|
| `quantity` | integer | No | 1 | Jumlah satuan barang |
| `last_maintenance` | date | Yes | NULL | Tanggal pemeliharaan terakhir |
| `last_maintenance_photo`| text | Yes | NULL | Path foto setelah pemeliharaan terakhir |
| `description` | text | Yes | NULL | Keterangan tambahan aset |
| `vehicle_type` | enum('R2', 'R4', 'R2/R4')| Yes | NULL | Tipe kendaraan (khusus aset parkir) |
| `r2` | integer | Yes | NULL | Kapasitas parkir roda 2 |
| `r4` | integer | Yes | NULL | Kapasitas parkir roda 4 |
| `tariff_type` | enum('flat', 'progresive')| Yes | NULL | Jenis tarif parkir |
| `manager` | varchar(255) | Yes | NULL | Nama pengelola/penanggung jawab |
| `area` | decimal(10,3) | Yes | 0.000 | Luas area aset (meter persegi / km) |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### Tabel: `sub_assets`
Menyimpan sub-item atau komponen pendukung dari suatu aset utama.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `asset_id` | bigint (unsigned) | No | - | Foreign Key ke `assets.id` |
| `photo_path` | varchar(255) | Yes | NULL | Path file foto sub-aset |
| `description` | text | Yes | NULL | Deskripsi kondisi/detail sub-aset |
| `status` | enum('baik', 'perlu_perbaikan', 'rusak') | No | 'baik' | Status kondisi sub-aset |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

---

## 3. Modul Monitoring & Riwayat Kondisi Aset

Modul ini mencatat riwayat pemantauan berkala terhadap kondisi fisik aset serta depresiasi (penurunan nilai) aset dari waktu ke waktu.

### Tabel: `asset_monitorings` (sebelumnya `asset_photos`)
Menyimpan riwayat pengecekan visual dan pembaruan kondisi aset.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `asset_id` | bigint (unsigned) | No | - | Foreign Key ke `assets.id` |
| `photo_path` | varchar(255) | No | - | Path file foto pemantauan |
| `condition` | enum('baik', 'perlu_perbaikan', 'rusak', 'dalam_pemeliharaan') | Yes | NULL | Kondisi yang tercatat saat dipantau |
| `notes` | text | Yes | NULL | Catatan pemantauan/monitoring |
| `photo_date` | datetime | No | now() | Tanggal pengambilan foto/pengecekan |
| `captured_by` | varchar(255) | Yes | NULL | Petugas yang melakukan pemantauan |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### Tabel: `asset_depreciations`
Menyimpan catatan riwayat penyusutan/penurunan nilai ekonomis aset.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `asset_id` | bigint (unsigned) | No | - | Foreign Key ke `assets.id` |
| `depreciation_date`| date | No | - | Tanggal pencatatan depresiasi |
| `value` | bigint | No | - | Nilai susut atau nilai baru setelah susut|
| `notes` | text | Yes | NULL | Catatan perhitungan depresiasi |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

### View Database: `asset_reports_view`
View untuk mempermudah query pembuatan laporan aset secara cepat dan terintegrasi.
*   **Query Struktur**: menggabungkan tabel `assets` dengan `asset_types` dan `asset_sub_types`.

---

## 4. Modul Pemeliharaan Aset (Maintenance)

Modul ini menangani pencatatan jadwal, pengerjaan, dan biaya pemeliharaan/perbaikan aset transportasi.

### Tabel: `asset_maintenances`
Menyimpan riwayat dan data aktif pengerjaan pemeliharaan aset.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `asset_id` | bigint (unsigned) | No | - | Foreign Key ke `assets.id` |
| `maintenance_type` | enum('rutin', 'perbaikan')| No | - | Tipe tindakan pemeliharaan |
| `status` | enum('sedang_berjalan', 'selesai')| No | - | Status pengerjaan pemeliharaan |
| `start_date` | date | No | - | Tanggal mulai pemeliharaan |
| `end_date` | date | Yes | NULL | Tanggal selesai pemeliharaan |
| `cost` | decimal(15,2) | Yes | NULL | Biaya yang dikeluarkan (Rupiah) |
| `description` | text | Yes | NULL | Deskripsi rincian pemeliharaan |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

---

## 5. Modul Pelaporan Kerusakan (Damage Reports)

Modul yang digunakan oleh masyarakat maupun petugas untuk melaporkan kerusakan fisik pada sarana/prasarana transportasi.

### Tabel: `damage_reports`
Menyimpan pengaduan kerusakan aset dari publik/petugas.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `nama_pelapor` | varchar(255) | No | - | Nama orang yang melaporkan |
| `kontak` | varchar(255) | No | - | No. HP / Kontak pelapor |
| `lokasi` | text | No | - | Deskripsi lokasi kerusakan |
| `asset_id` | bigint (unsigned) | Yes | NULL | Foreign Key ke `assets.id` (opsional) |
| `foto` | varchar(255) | No | - | Path foto bukti kerusakan awal |
| `keterangan` | text | No | - | Rincian detail kerusakan |
| `status` | enum('baru', 'ditindak_lanjuti', 'selesai') | No | 'baru' | Status penanganan laporan |
| `seen` | boolean | Yes | false | Status apakah laporan sudah dilihat admin |
| `forwarded_at` | dateTime | Yes | NULL | Tanggal laporan diteruskan ke petugas |
| `foto_selesai` | varchar(255) | Yes | NULL | Path foto bukti setelah diperbaiki |
| `tanggal_selesai` | dateTime | Yes | NULL | Tanggal pengerjaan laporan selesai |
| `created_at` | timestamp | Yes | NULL | Tanggal/waktu aduan dikirim |
| `updated_at` | timestamp | Yes | NULL | Tanggal/waktu perubahan status aduan |

---

## 6. Modul Pengusulan Aset Baru (Proposals)

Modul ini memfasilitasi pengusulan pembangunan/pengadaan fasilitas transportasi baru dari instansi atau masyarakat.

### Tabel: `proposals`
Menyimpan berkas usulan pengadaan sarana/prasarana baru.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `pengusul` | varchar(255) | No | - | Nama individu/lembaga pengusul |
| `email_pengusul` | varchar(255) | No | - | Email kontak pengusul |
| `tanggal` | date | No | - | Tanggal pengusulan |
| `jenis_permintaan` | varchar(255) | No | - | Jenis sarana yang diusulkan (cth: Halte)|
| `jumlah` | integer | No | - | Jumlah unit yang diusulkan |
| `lokasi` | text | No | - | Alamat/lokasi target pengadaan |
| `coordinates` | json | Yes | NULL | Koordinat usulan lokasi baru (spasial)|
| `perkiraan_anggaran`| bigint | Yes | NULL | Estimasi anggaran biaya |
| `foto` | varchar(255) | No | - | Path foto lokasi usulan saat ini |
| `arsip_surat` | varchar(255) | No | - | Path file dokumen/surat resmi usulan |
| `status` | enum('pending', 'ditindak lanjuti', 'ditolak', 'selesai')| No | 'pending' | Status evaluasi usulan |
| `kelayakan` | enum('layak', 'tidak layak')| Yes | NULL | Status kelayakan hasil survei |
| `tindak_lanjut` | text | Yes | NULL | Rencana tindakan berikutnya |
| `keterangan_admin` | text | Yes | NULL | Catatan peninjauan dari administrator |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

---

## 7. Modul Trayek & Rute (Trayeks)

Modul yang mengelola pembagian rute operasional angkutan umum atau transportasi kota (Trayek).

### Tabel: `trayeks`
Menyimpan rute perjalanan operasional kendaraan umum.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `code` | varchar(255) | No | - | Kode unik trayek (cth: `T01`) |
| `name` | varchar(255) | No | - | Nama trayek/jurusan perjalanan |
| `distance` | decimal(8,2) | Yes | NULL | Jarak tempuh rute (km) |
| `coordinate` | longtext | Yes | NULL | Data koordinat path rute (GeoJSON/array)|
| `classification` | varchar(255) | Yes | NULL | Klasifikasi jenis trayek |
| `color` | varchar(255) | Yes | NULL | Warna garis rute di peta |
| `route_type` | varchar(255) | No | 'loop' | Jenis trayek (`loop` atau `one_way`) |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |

---

## 8. Modul Notifikasi & System Logs

Modul pendukung untuk mengirimkan peringatan sistem, info status, serta antrean sistem.

### Tabel: `notifications`
Menyimpan notifikasi pengguna di dalam aplikasi.

| Nama Field | Tipe Data | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- |
| `id` | bigint (unsigned) | No | - | Primary Key |
| `user_id` | bigint (unsigned) | No | - | Foreign Key ke `users.id` |
| `title` | varchar(255) | No | - | Judul notifikasi |
| `message` | text | No | - | Pesan/isi detail notifikasi |
| `type` | enum('info', 'warning', 'error', 'success')| No | - | Jenis tingkat urgensi notifikasi |
| `read_at` | timestamp | Yes | NULL | Waktu pengguna membaca notifikasi |
| `created_at` | timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | timestamp | Yes | NULL | Waktu data diubah |
