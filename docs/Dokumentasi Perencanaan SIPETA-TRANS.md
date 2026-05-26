# Dokumentasi Perencanaan Aplikasi SIPETA-TRANS

**Sistem Informasi Pemetaan Aset Transportasi**
Dinas Perhubungan Kota Bukittinggi
Versi 1.0

---

## BAB I
## Pendahuluan

### 1. Latar Belakang

Dinas Perhubungan Kota Bukittinggi sebagai perangkat daerah yang bertanggung jawab terhadap pengelolaan sarana dan prasarana transportasi menghadapi tantangan serius dalam pengelolaan aset transportasi yang tersebar di berbagai wilayah kota. Selama ini, proses inventarisasi, monitoring, dan pelaporan aset masih banyak dilakukan secara manual dan tidak terintegrasi, sehingga menyebabkan berbagai kendala seperti data aset yang tersebar di berbagai dokumen, ketidakakuratan informasi kondisi aset, keterlambatan pelaporan kerusakan, serta sulitnya menghasilkan laporan yang cepat dan akurat.

Kondisi tersebut berdampak pada lemahnya pengelolaan Barang Milik Daerah (BMD) dari sisi inventarisasi, monitoring kondisi, pemeliharaan, maupun pengambilan keputusan oleh pimpinan. Oleh karena itu, diperlukan sebuah sistem informasi berbasis web yang mampu memfasilitasi seluruh proses pengelolaan aset transportasi secara terintegrasi, efisien, transparan, dan mendukung pemetaan digital lokasi aset.

Sebagai bagian dari rencana aksi perubahan kinerja organisasi, Dinas Perhubungan Kota Bukittinggi merencanakan pembangunan **Sistem Informasi Pemetaan Aset Transportasi (SIPETA-TRANS)**. Sistem ini dikembangkan menggunakan framework Laravel berbasis web dengan fitur pemetaan digital, monitoring kondisi aset, pelaporan kerusakan, pengajuan usulan aset baru, serta penyajian laporan secara cepat dan akurat. Dengan adanya SIPETA-TRANS, diharapkan pengelolaan aset transportasi dapat dilakukan secara lebih efektif, efisien, akurat, dan mendukung tata kelola Barang Milik Daerah yang baik.

### 2. Tujuan Proyek

Tujuan utama dari proyek ini adalah membangun sistem informasi pemetaan aset transportasi berbasis web yang diberi nama SIPETA-TRANS. Sistem ini bertujuan untuk:

- Meningkatkan efektivitas pengelolaan aset transportasi Dinas Perhubungan
- Menyediakan data aset yang terintegrasi dan terpusat dalam satu platform digital
- Menyediakan sistem pemetaan lokasi aset berbasis digital menggunakan koordinat GPS
- Mempermudah monitoring kondisi aset transportasi secara real-time
- Mempermudah proses pelaporan kerusakan aset oleh masyarakat maupun petugas
- Mempermudah pengajuan usulan penambahan aset transportasi
- Menyediakan laporan aset secara cepat, akurat, dan dapat diekspor
- Mendukung pengambilan keputusan pimpinan berdasarkan data yang valid

### 3. Lingkup Proyek

Aplikasi SIPETA-TRANS mencakup beberapa fitur utama yang dapat diakses oleh pengguna yang telah memiliki hak akses sesuai peran masing-masing, serta fitur publik yang dapat diakses oleh masyarakat. Lingkup proyek meliputi:

- Sistem login dan manajemen hak akses pengguna berdasarkan peran
- Dashboard monitoring dengan statistik dan peta sebaran aset
- Pendataan dan pemetaan lokasi aset transportasi
- Monitoring dan pembaruan kondisi aset
- Manajemen pemeliharaan aset
- Pelaporan kerusakan aset oleh masyarakat dan petugas
- Pengajuan usulan penambahan fasilitas transportasi
- Penyusunan dan ekspor laporan aset

### 4. Target Pengguna

Target pengguna dari aplikasi SIPETA-TRANS meliputi:

- **Administrator Sistem** — mengelola sistem secara keseluruhan, termasuk pengguna, hak akses, dan data master
- **Petugas Lapangan** — melakukan pendataan aset, pembaruan kondisi aset, dan input pemeliharaan
- **Kepala Bidang** — melakukan pengawasan, verifikasi data aset, dan evaluasi laporan kerusakan
- **Pimpinan Dinas** — memantau dashboard monitoring, mengevaluasi laporan, dan mengambil keputusan
- **Masyarakat** — melaporkan kerusakan aset transportasi dan mengusulkan penambahan fasilitas

### 5. Sumber Daya yang Dibutuhkan

Untuk merealisasikan proyek ini, dibutuhkan beberapa sumber daya penting:

**Sumber Daya Manusia:**

| No | Jabatan | Tugas |
|----|---------|-------|
| 1 | Project Manager | Mengelola pelaksanaan proyek |
| 2 | System Analyst | Menganalisis kebutuhan sistem |
| 3 | Programmer/Developer | Mengembangkan aplikasi |
| 4 | UI/UX Designer | Mendesain tampilan aplikasi |
| 5 | Database Administrator | Mengelola database |
| 6 | Tester/Quality Assurance | Melakukan pengujian sistem |
| 7 | Administrator Sistem | Mengelola operasional aplikasi |

**Perangkat Keras:**
- Server aplikasi dan database
- Komputer/laptop untuk operator dan petugas
- Smartphone untuk petugas lapangan
- Jaringan internet
- Perangkat GPS atau lokasi berbasis mobile

**Perangkat Lunak:**
- Sistem operasi server Linux
- Web server Apache/Nginx
- Framework Laravel (PHP)
- Database MySQL
- API peta digital (LeafletJS + OpenStreetMap)
- Browser web modern

### 6. Estimasi Waktu Pengerjaan

Proyek ini mengikuti siklus hidup pengembangan sistem (SDLC) dengan estimasi waktu sebagai berikut:

- **Analisis kebutuhan sistem**: 2 minggu — mengundang pemangku kepentingan terkait untuk mengumpulkan dan menganalisa kebutuhan sistem secara menyeluruh
- **Perancangan sistem dan database**: 2 minggu — membuat rencana arsitektur sistem, flowchart, dan desain database
- **Desain antarmuka aplikasi**: 2 minggu — pembuatan wireframe dan desain tampilan front end
- **Pengembangan backend sistem**: 4 minggu — pengembangan logika bisnis, API, dan integrasi database
- **Pengembangan frontend sistem**: 3 minggu — pembuatan kode tampilan dan pengujian fungsionalitas
- **Integrasi peta digital**: 2 minggu — integrasi LeafletJS dan penentuan titik koordinat aset
- **Pengujian sistem**: 2 minggu — pengujian fungsionalitas, keamanan, dan performa sistem
- **Perbaikan dan penyempurnaan**: 1 minggu — revisi berdasarkan hasil pengujian
- **Implementasi dan pelatihan**: 1 minggu — penerapan sistem dan pelatihan pengguna

**Total estimasi waktu: ± 4 bulan**

### 7. Risiko dan Mitigasi

Berikut beberapa risiko potensial beserta strategi mitigasinya:

- **Data aset tidak lengkap**: Melakukan validasi data dan survei lapangan sebelum input
- **Kesalahan input data**: Menambahkan validasi input yang ketat pada sisi sistem
- **Keterbatasan jaringan internet di lapangan**: Menyediakan jaringan cadangan dan mengoptimalkan performa sistem
- **Kurangnya kemampuan pengguna dalam mengoperasikan sistem**: Menyediakan pelatihan pengguna dan panduan dalam bentuk digital
- **Kerusakan server**: Menerapkan backup otomatis dan monitoring server secara berkala
- **Ketidaksesuaian koordinat lokasi aset**: Menggunakan validasi koordinat GPS dan verifikasi lapangan
- **Perubahan kebutuhan sistem di tengah pengembangan**: Melakukan analisis kebutuhan secara detail di awal dan komunikasi rutin dengan pemangku kepentingan
- **Risiko keamanan dan kebocoran data**: Menerapkan autentikasi, enkripsi password, dan hak akses berbasis peran

---

## BAB II
## Analisa Kebutuhan Sistem

### 1. Pendahuluan

Dokumen ini disusun untuk mendeskripsikan kebutuhan sistem aplikasi SIPETA-TRANS secara terperinci. Analisis kebutuhan ini menjadi dasar dalam proses desain, pengembangan, dan pengujian sistem sehingga sistem yang dibangun sesuai dengan harapan pengguna akhir dan pemangku kepentingan terkait di lingkungan Dinas Perhubungan Kota Bukittinggi.

### 2. Kebutuhan Fungsional

Kebutuhan fungsional berikut mendefinisikan fitur-fitur yang wajib tersedia dalam sistem:

**Login dan Manajemen Pengguna:**
- Sistem login multi-role (Administrator, Petugas, Kepala Bidang, Pimpinan, Masyarakat)
- Logout pengguna
- Pengaturan hak akses pengguna berdasarkan peran
- Reset password

**Dashboard:**
- Kartu statistik jumlah aset berdasarkan jenis dan kondisi
- Grafik kondisi aset (baik, perlu perbaikan, rusak, dalam pemeliharaan)
- Grafik laporan kerusakan
- Peta sebaran aset berbasis LeafletJS
- Informasi aktivitas terbaru

**Peta Aset:**
- Tampilan lokasi aset pada peta digital interaktif
- Detail informasi aset saat marker dipilih
- Filter berdasarkan jenis aset dan kondisi aset
- Pencarian aset berdasarkan nama atau lokasi

**Data Aset:**
- Tambah, edit, hapus, dan lihat detail data aset
- Upload foto aset
- Penentuan titik lokasi aset pada peta
- Pencarian dan filter data aset
- Ekspor data aset

**Monitoring Kondisi Aset:**
- Pembaruan kondisi aset (baik, perlu perbaikan, rusak, dalam pemeliharaan)
- Riwayat perubahan kondisi dan unggah foto kondisi terkini
- Monitoring aset bermasalah
- Statistik kondisi aset per jenis

**Pemeliharaan Aset:**
- Input jadwal dan hasil pemeliharaan
- Riwayat pemeliharaan per aset
- Monitoring biaya pemeliharaan

**Pelaporan Kerusakan:**
- Input laporan kerusakan oleh masyarakat maupun petugas
- Upload foto kerusakan
- Tracking status laporan (baru, dalam proses, selesai)
- Verifikasi dan tindak lanjut laporan oleh admin

**Usulan Penambahan Aset:**
- Pengajuan usulan penambahan fasilitas transportasi
- Verifikasi usulan oleh admin
- Monitoring status usulan

**Laporan:**
- Cetak laporan dalam format PDF
- Ekspor data dalam format Excel
- Filter laporan berdasarkan periode, jenis, dan kondisi aset

### 3. Kebutuhan Non-Fungsional

Berikut adalah kebutuhan non-fungsional dari sistem:

- **Keamanan**: Sistem login dan autentikasi, hak akses berbasis peran, password terenkripsi, validasi input, proteksi upload file, dan audit log aktivitas pengguna
- **Performa**: Waktu respons sistem yang cepat, mampu mendukung penggunaan multi-user secara bersamaan
- **Aksesibilitas**: Dapat diakses melalui perangkat desktop, laptop, dan smartphone dengan antarmuka yang responsif
- **Ketersediaan**: Sistem tersedia 24 jam dengan backup database berkala
- **Kemudahan Penggunaan**: Tampilan yang ramah pengguna (user-friendly), navigasi yang mudah dipahami, dan panduan penggunaan yang jelas
- **Skalabilitas**: Arsitektur sistem yang dapat dikembangkan untuk menambah fitur di masa mendatang

### 4. Use Case Umum Sistem

Beberapa use case utama dalam sistem SIPETA-TRANS meliputi:

- Pengguna melakukan login sesuai peran masing-masing
- Petugas menginput dan memperbarui data aset beserta titik lokasinya
- Petugas memperbarui kondisi aset dan mengupload foto kondisi terkini
- Admin mengelola data master jenis aset, pengguna, dan pemeliharaan
- Masyarakat melaporkan kerusakan aset dan mengajukan usulan penambahan
- Kepala bidang melakukan verifikasi laporan dan memantau kondisi aset
- Pimpinan melihat dashboard monitoring dan laporan aset secara keseluruhan
- Sistem menyajikan peta sebaran aset secara real-time

### 5. Spesifikasi Antarmuka Pengguna

Tampilan antarmuka akan mengikuti standar UI/UX modern dengan navigasi yang sederhana namun menyajikan informasi yang lengkap. Sistem menggunakan TailwindCSS sebagai kerangka desain antarmuka dengan pendekatan responsif untuk memastikan tampilan yang optimal di berbagai ukuran layar. Dashboard utama akan menampilkan informasi statistik terstruktur dalam bentuk kartu dan grafik, sementara halaman data akan menggunakan tabel dengan fitur pencarian dan filter. Peta aset menggunakan LeafletJS yang terintegrasi dengan OpenStreetMap untuk tampilan peta digital yang ringan dan interaktif.

---

## BAB III
## Proses Bisnis Sistem

### 1. Deskripsi Umum Proses Bisnis

Proses bisnis aplikasi SIPETA-TRANS mencakup interaksi antara petugas lapangan, administrator, pimpinan, dan masyarakat dalam rangka pengelolaan aset transportasi Dinas Perhubungan Kota Bukittinggi. Proses dimulai dari pendataan aset, dilanjutkan dengan monitoring kondisi, pemeliharaan, pelaporan kerusakan, hingga penyusunan laporan sebagai bahan evaluasi dan pengambilan keputusan.

### 2. Tahapan Proses Bisnis

**1. Proses Pendataan Aset**
1. Petugas login ke aplikasi menggunakan akun yang terdaftar
2. Petugas membuka menu Data Aset dan memilih tambah aset baru
3. Petugas mengisi data aset meliputi nama, jenis, lokasi, kondisi, dan keterangan
4. Petugas menentukan titik koordinat lokasi aset pada peta digital
5. Petugas mengupload foto aset
6. Sistem menyimpan data aset ke database
7. Aset tampil pada peta digital dan daftar data aset

**2. Proses Monitoring Kondisi**
1. Petugas melakukan pengecekan kondisi aset di lapangan
2. Petugas membuka menu Monitoring Kondisi dan memilih aset yang diperiksa
3. Petugas memperbarui status kondisi aset (baik, perlu perbaikan, rusak, dalam pemeliharaan)
4. Petugas mengupload foto kondisi terkini sebagai dokumentasi
5. Sistem menyimpan riwayat perubahan kondisi
6. Dashboard diperbarui secara otomatis menampilkan statistik kondisi terkini

**3. Proses Pelaporan Kerusakan**
1. Masyarakat atau petugas membuka form pelaporan kerusakan
2. Pelapor mengisi data laporan meliputi nama, kontak, lokasi, dan keterangan kerusakan
3. Pelapor mengupload foto kerusakan sebagai bukti
4. Sistem menyimpan data laporan dan memberikan nomor laporan
5. Admin melakukan verifikasi laporan
6. Petugas melakukan tindak lanjut perbaikan di lapangan
7. Status laporan diperbarui sesuai progres penanganan

**4. Proses Usulan Penambahan Aset**
1. Pemohon membuka form usulan penambahan melalui aplikasi
2. Pemohon mengisi data usulan meliputi nama, kontak, lokasi, jenis usulan, dan keterangan
3. Pemohon mengupload foto lokasi yang diusulkan
4. Sistem menyimpan usulan dan memberikan nomor pengajuan
5. Admin melakukan verifikasi usulan
6. Pimpinan atau kepala bidang melakukan evaluasi dan keputusan
7. Status usulan diperbarui dan pemohon mendapat notifikasi

**5. Proses Pemeliharaan Aset**
1. Admin atau petugas membuat jadwal pemeliharaan aset
2. Petugas melaksanakan kegiatan pemeliharaan di lapangan
3. Petugas menginput hasil pemeliharaan ke dalam sistem
4. Sistem menyimpan riwayat dan biaya pemeliharaan
5. Kondisi aset diperbarui setelah pemeliharaan selesai

**6. Proses Pelaporan**
1. Pengguna memilih jenis laporan yang dibutuhkan
2. Pengguna menentukan periode dan parameter filter laporan
3. Sistem menghasilkan laporan sesuai filter yang dipilih
4. Laporan dapat dicetak dalam format PDF atau diekspor ke Excel

### 3. Aktor yang Terlibat

| No | Aktor | Peran |
|----|-------|-------|
| 1 | Administrator | Mengelola sistem, pengguna, data master, dan monitoring aktivitas aplikasi |
| 2 | Petugas Lapangan | Melakukan pendataan aset, monitoring kondisi, dan input pemeliharaan |
| 3 | Kepala Bidang | Memverifikasi data aset, memantau kondisi, dan menyetujui tindak lanjut |
| 4 | Pimpinan Dinas | Memantau dashboard, mengevaluasi laporan, dan mengambil keputusan |
| 5 | Masyarakat | Melaporkan kerusakan aset dan mengusulkan penambahan fasilitas transportasi |
| 6 | Sistem | Mengelola data, menyimpan riwayat, dan menyajikan informasi secara otomatis |

**Rincian Peran Aktor:**

**Administrator**
- Mengelola pengguna dan hak akses
- Mengelola data master jenis aset
- Memverifikasi laporan kerusakan dan usulan penambahan
- Monitoring aktivitas sistem
- Melakukan backup data

**Petugas Lapangan**
- Menginput data aset baru ke dalam sistem
- Memperbarui kondisi aset berdasarkan hasil pengecekan lapangan
- Menginput data dan hasil pemeliharaan
- Melakukan verifikasi lokasi aset

**Kepala Bidang**
- Memverifikasi data aset yang diinput petugas
- Memantau kondisi dan status aset secara keseluruhan
- Mengevaluasi laporan kerusakan
- Menyetujui tindak lanjut pemeliharaan

**Pimpinan Dinas**
- Melihat dashboard monitoring dan statistik aset
- Mengevaluasi laporan aset secara berkala
- Mengambil keputusan terkait pengelolaan dan pengadaan aset

**Masyarakat**
- Melaporkan kerusakan aset transportasi
- Mengusulkan penambahan fasilitas atau aset transportasi baru

### 4. Dokumen yang Terlibat

| No | Nama Dokumen | Fungsi |
|----|--------------|--------|
| 1 | Data Inventaris Aset | Data utama aset transportasi yang tersimpan dalam sistem |
| 2 | Form Input Aset Baru | Pendataan aset baru beserta titik lokasi dan foto |
| 3 | Laporan Kondisi Aset | Monitoring kondisi dan riwayat perubahan status aset |
| 4 | Laporan Pemeliharaan | Riwayat kegiatan dan biaya pemeliharaan aset |
| 5 | Form Pelaporan Kerusakan | Pelaporan kerusakan aset oleh masyarakat atau petugas |
| 6 | Form Usulan Penambahan | Pengajuan penambahan aset atau fasilitas transportasi |
| 7 | Laporan Data Aset | Pelaporan aset keseluruhan untuk evaluasi |
| 8 | Dokumentasi Foto Aset | Bukti visual kondisi aset di lapangan |
| 9 | Laporan Rekapitulasi | Rekapitulasi data aset untuk pengambilan keputusan |

---

## BAB IV
## Desain Sistem

### 1. Gambaran Umum Desain Sistem

Sistem Informasi Pemetaan Aset Transportasi (SIPETA-TRANS) dirancang sebagai aplikasi berbasis web yang mengintegrasikan pengelolaan data aset, pemetaan lokasi, monitoring kondisi, pemeliharaan, pelaporan, dan penyusunan laporan dalam satu platform digital. Desain sistem mengacu pada konsep yang mudah digunakan, responsif pada berbagai perangkat, aman dengan pengaturan hak akses berbasis peran, dan mendukung pengelolaan data secara real-time.

### 2. Arsitektur Sistem

SIPETA-TRANS menggunakan arsitektur client-server berbasis web dengan komponen utama sebagai berikut:

**Client/User**
Pengguna mengakses aplikasi melalui browser modern pada perangkat komputer desktop, laptop, smartphone, maupun tablet.

**Web Application Server**
Server aplikasi menggunakan framework Laravel (PHP) yang bertugas menjalankan logika bisnis, memproses permintaan data, mengelola autentikasi pengguna, dan menyediakan antarmuka web.

**Map Service**
Layanan peta menggunakan LeafletJS yang terintegrasi dengan OpenStreetMap untuk menampilkan lokasi aset secara geografis, marker posisi aset, dan penentuan koordinat aset baru.

### 3. Desain Hak Akses Pengguna

Sistem menggunakan hak akses berbasis peran (role-based access control) dengan rincian sebagai berikut:

| No | Jenis Pengguna | Hak Akses |
|----|---------------|-----------|
| 1 | Administrator | Akses penuh seluruh modul sistem |
| 2 | Petugas | Pendataan aset, monitoring kondisi, dan input pemeliharaan |
| 3 | Kepala Bidang | Verifikasi data, pemantauan, dan persetujuan tindak lanjut |
| 4 | Pimpinan | Melihat dashboard, statistik, dan laporan |
| 5 | Masyarakat | Pelaporan kerusakan dan pengajuan usulan penambahan aset |

### 4. Desain Struktur Menu

**Dashboard**
- Kartu statistik jumlah aset berdasarkan jenis dan kondisi
- Grafik kondisi aset dan laporan kerusakan
- Peta sebaran aset interaktif
- Informasi aktivitas terbaru

**Peta Aset**
- Peta digital interaktif dengan marker aset
- Popup detail informasi aset saat marker dipilih
- Filter berdasarkan jenis dan kondisi aset
- Pencarian aset pada peta

**Data Aset**
- Daftar aset dalam bentuk tabel
- Tambah, edit, dan hapus data aset
- Detail informasi lengkap per aset beserta foto
- Ekspor data aset

**Jenis Aset**
- Manajemen kategori/jenis aset transportasi
- Tambah, edit, dan hapus jenis aset

**Monitoring Kondisi**
- Daftar aset dengan status kondisi terkini
- Pembaruan kondisi aset dan upload foto
- Riwayat perubahan kondisi per aset

**Pemeliharaan**
- Input dan jadwal pemeliharaan aset
- Riwayat kegiatan pemeliharaan per aset
- Monitoring biaya pemeliharaan

**Pelaporan Kerusakan**
- Daftar laporan kerusakan masuk
- Verifikasi dan tindak lanjut laporan
- Tracking status laporan

**Usulan Penambahan**
- Daftar usulan penambahan aset masuk
- Verifikasi dan evaluasi usulan
- Monitoring status usulan

**Laporan**
- Cetak laporan dalam format PDF
- Ekspor data ke Excel
- Filter laporan berdasarkan periode dan kategori

### 5. Desain Database Sistem

**Tabel users**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| name | varchar | Nama pengguna |
| email | varchar | Email pengguna |
| password | varchar | Password terenkripsi |
| role | varchar | Hak akses: admin, petugas, kepala_bidang, pimpinan, masyarakat |
| created_at | timestamp | Tanggal dibuat |

**Tabel asset_types**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| name | varchar | Nama jenis aset |
| icon | varchar | Ikon representasi jenis aset |
| description | text | Keterangan jenis aset |
| created_at | timestamp | Tanggal dibuat |

**Tabel assets**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| name | varchar | Nama aset |
| asset_type_id | bigint | Relasi ke tabel asset_types |
| status | enum | Kondisi: baik, perlu_perbaikan, rusak, dalam_pemeliharaan |
| latitude | decimal | Koordinat latitude |
| longitude | decimal | Koordinat longitude |
| location | varchar | Deskripsi lokasi/kelurahan |
| last_maintenance | date | Tanggal pemeliharaan terakhir |
| description | text | Keterangan aset |
| quantity | integer | Jumlah aset |
| created_at | timestamp | Tanggal dibuat |

**Tabel asset_photos**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| asset_id | bigint | Relasi ke tabel assets |
| photo_path | varchar | Path file foto |
| photo_date | date | Tanggal pengambilan foto |
| notes | text | Catatan kondisi pada foto |
| created_at | timestamp | Tanggal dibuat |

**Tabel maintenance**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| asset_id | bigint | Relasi ke tabel assets |
| tanggal | date | Tanggal pemeliharaan |
| kegiatan | text | Kegiatan pemeliharaan yang dilakukan |
| biaya | decimal | Biaya pemeliharaan |
| hasil | text | Hasil pemeliharaan |
| petugas_id | bigint | Relasi ke tabel users (petugas) |
| created_at | timestamp | Tanggal dibuat |

**Tabel damage_reports**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nama_pelapor | varchar | Nama pelapor |
| kontak | varchar | Nomor kontak pelapor |
| lokasi | text | Lokasi kerusakan |
| asset_id | bigint | Relasi ke aset terkait (opsional) |
| foto | varchar | File foto kerusakan |
| keterangan | text | Keterangan kerusakan |
| status | enum | Status: baru, dalam_proses, selesai |
| created_at | timestamp | Tanggal laporan masuk |

**Tabel proposals**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| nama_pemohon | varchar | Nama pemohon |
| kontak | varchar | Nomor kontak pemohon |
| lokasi | text | Lokasi yang diusulkan |
| jenis_usulan | varchar | Jenis fasilitas yang diusulkan |
| keterangan | text | Keterangan usulan |
| foto | varchar | File foto lokasi |
| status | enum | Status: baru, dalam_proses, disetujui, ditolak |
| created_at | timestamp | Tanggal usulan masuk |

**Tabel notifications**

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| user_id | bigint | Relasi ke tabel users |
| title | varchar | Judul notifikasi |
| message | text | Isi notifikasi |
| is_read | boolean | Status notifikasi sudah dibaca |
| created_at | timestamp | Tanggal notifikasi dibuat |

### 6. Desain Alur Sistem

**Alur Input Aset Baru:**
1. Petugas login ke sistem menggunakan kredensial yang valid
2. Petugas membuka menu Data Aset dan memilih tambah aset baru
3. Petugas mengisi formulir data aset secara lengkap
4. Petugas menentukan titik lokasi aset pada peta interaktif
5. Petugas mengupload foto aset
6. Sistem memvalidasi data dan menyimpan ke database
7. Aset baru tampil pada peta digital dan daftar data aset

**Alur Monitoring dan Pembaruan Kondisi:**
1. Petugas membuka menu Monitoring Kondisi
2. Petugas memilih aset yang akan diperbarui kondisinya
3. Petugas memilih status kondisi terkini
4. Petugas mengupload foto kondisi aset terbaru
5. Sistem menyimpan perubahan kondisi dan mencatat riwayat
6. Dashboard diperbarui secara otomatis

**Alur Pelaporan Kerusakan:**
1. Pelapor mengakses form pelaporan kerusakan
2. Pelapor mengisi data laporan dan mengupload foto kerusakan
3. Sistem menyimpan laporan dan memberikan nomor referensi
4. Admin menerima notifikasi dan melakukan verifikasi laporan
5. Petugas melakukan tindak lanjut di lapangan
6. Status laporan diperbarui sesuai progres penanganan

### 7. Desain Antarmuka Sistem

**Halaman Login:**
- Form input email dan password
- Tombol login dengan validasi
- Tautan lupa password

**Halaman Dashboard:**
- Kartu statistik jumlah aset berdasarkan kondisi
- Grafik kondisi aset dalam bentuk diagram
- Peta sebaran aset interaktif
- Tabel aktivitas terbaru sistem

**Halaman Peta Aset:**
- Peta digital full-width dengan marker aset
- Popup detail aset saat marker diklik
- Panel filter berdasarkan jenis dan kondisi aset
- Kolom pencarian aset

**Halaman Data Aset:**
- Tabel data aset dengan kolom utama
- Tombol tambah, edit, hapus, dan detail
- Fitur pencarian dan filter
- Tombol ekspor data

**Halaman Monitoring Kondisi:**
- Daftar aset dengan status kondisi dan foto terbaru
- Form pembaruan kondisi dan upload foto
- Timeline riwayat perubahan kondisi per aset

**Halaman Pelaporan Kerusakan:**
- Form laporan kerusakan yang dapat diakses publik
- Daftar laporan masuk dengan status penanganan
- Fitur verifikasi dan tindak lanjut untuk admin

### 8. Teknologi yang Digunakan

**Backend:**
- PHP 8.x
- Laravel Framework (MVC Architecture)
- Laravel Eloquent ORM
- RESTful routing

**Frontend:**
- HTML5 & CSS3
- JavaScript (Vanilla JS)
- TailwindCSS
- Alpine.js (interaksi ringan)

**Peta Digital:**
- LeafletJS
- OpenStreetMap tile layer

**Database:**
- MySQL

**Server dan Hosting:**
- Linux Server
- Nginx / Apache Web Server
- Cloud Hosting atau VPS

### 9. Keamanan Sistem

Keamanan sistem yang diterapkan dalam SIPETA-TRANS meliputi:

- Autentikasi login pengguna dengan validasi kredensial
- Password disimpan dalam bentuk hash menggunakan bcrypt
- Hak akses berbasis peran yang membatasi fungsi sesuai level pengguna
- Validasi dan sanitasi seluruh input data untuk mencegah injeksi
- Proteksi upload file dengan validasi tipe dan ukuran file
- Pencatatan log aktivitas pengguna untuk keperluan audit
- Proteksi CSRF (Cross-Site Request Forgery) menggunakan token bawaan Laravel
- Backup database secara berkala untuk menjamin ketersediaan data
