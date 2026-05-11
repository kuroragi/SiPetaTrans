# SIPETA-TRANS - Sistem Informasi Pemetaan Aset Transportasi

Aplikasi dashboard untuk manajemen dan monitoring aset transportasi kota Bukittinggi dengan fitur pemetaan lokasi dan analisis kondisi aset.

## 🎯 Fitur Utama

- **Dashboard Interaktif** - Visualisasi data aset dengan KPI metrics
- **Peta Sebaran Aset** - Tampilan geografis aset menggunakan Leaflet Map
- **Analisis Kondisi** - Grafik kondisi aset (Baik, Perlu Perbaikan, Rusak, Dalam Pemeliharaan)
- **Manajemen Aset** - CRUD operations untuk data aset
- **Multi Jenis Aset** - Support untuk berbagai tipe aset transportasi
- **Responsive Design** - Interface yang responsif dengan Tailwind CSS

## 🛠️ Tech Stack

- **Backend**: Laravel 13 (PHP 8.3+)
- **Database**: SQLite (development) / MySQL (production)
- **Frontend**: Blade Templates + Tailwind CSS v4
- **Visualisasi Data**: Chart.js + Leaflet Map
- **Package Manager**: Composer + npm

## 📋 Prerequisites

- PHP >= 8.3
- Node.js >= 18
- npm atau yarn
- Composer

## 🚀 Quick Start

### 1. Setup Project
```bash
cd /Users/rahmadsyahmulya/Aplikasi/Sipeta-Trans

# Install dependencies
composer install
npm install

# Build CSS assets
npm run build
```

### 2. Jalankan Server
```bash
php artisan serve
```

**Akses aplikasi di**: http://127.0.0.1:8000

### 3. Login
- **Email**: admin@sipeta.com
- **Password**: Gunakan default atau buat user baru

## 📊 Database Structure

### Main Tables
- **users** - Data pengguna sistem
- **asset_types** - Jenis-jenis aset (Rambu, Halte, APILL, Marka, PAJ)
- **assets** - Data aset dengan lokasi GPS dan status
- **notifications** - Notifikasi sistem

### Asset Status
- `baik` - Kondisi baik
- `perlu_perbaikan` - Memerlukan perbaikan
- `rusak` - Rusak berat  
- `dalam_pemeliharaan` - Sedang dalam pemeliharaan

## 📁 Project Structure

```
sipeta-trans/
├── app/Models/              # Database models
├── app/Http/Controllers/    # Application controllers
├── database/
│   ├── migrations/          # Database schemas
│   └── seeders/             # Sample data
├── resources/
│   ├── css/app.css          # Tailwind CSS
│   └── views/               # Blade templates
├── routes/web.php           # Application routes
├── tailwind.config.js       # Tailwind configuration
└── package.json             # Frontend dependencies
```

## 🔧 Available Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/` | GET | Redirect ke dashboard |
| `/dashboard` | GET | Dashboard utama |
| `/assets` | GET | Daftar aset |
| `/assets/create` | GET | Tambah aset baru |
| `/assets/{id}` | GET | Detail aset |
| `/assets/{id}/edit` | GET | Edit aset |
| `/assets/{id}` | DELETE | Hapus aset |

## 🎨 Dashboard Features

- 📊 5 Metric Cards (Total, Baik, Perlu Perbaikan, Rusak, Dalam Pemeliharaan)
- 🗺️ Interactive Map dengan asset markers
- 📈 Pie Chart untuk distribusi kondisi
- 📉 Bar Chart untuk kondisi per jenis aset
- 🔴 List aset rusak/perlu perbaikan
- 📱 Fully responsive design

## 💻 Development Commands

```bash
# Development server
php artisan serve

# Build production assets
npm run build

# Watch mode (development)
npm run dev

# Database commands
php artisan migrate                 # Run migrations
php artisan db:seed                # Seed data
php artisan migrate:fresh --seed   # Reset + seed

# Cache clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🗺️ Jenis Aset

1. **Rambu Lalu Lintas** - Rambu keselamatan jalan
2. **Halte** - Perhentian kendaraan umum
3. **APILL** - Alat Pemberi Isyarat Lalu Lintas
4. **Marka Jalan** - Garis penanda jalan
5. **PAJ** - Perangkat Alat Jalan (CCTV, sensor)

## 🎨 Customize Colors

Edit `tailwind.config.js`:
```javascript
theme: {
  extend: {
    colors: {
      primary: '#1e40af',
      success: '#22c55e',
      warning: '#f59e0b',
      danger: '#ef4444',
    }
  }
}
```

Then rebuild: `npm run build`

## 🚢 Production Deployment

### Setup MySQL
```bash
# Create database
mysql -u root -e "CREATE DATABASE sipeta_trans CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Update .env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sipeta_trans
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations
php artisan migrate --force
php artisan db:seed --force
```

### Build for Production
```bash
composer install --no-dev
npm run build
chmod -R 775 storage bootstrap/cache
```

## 🐛 Troubleshooting

### Database Issues
```bash
# Reset database
php artisan migrate:fresh --seed

# Check connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### CSS Not Loading
```bash
# Rebuild assets
npm run build

# Clear cache
php artisan cache:clear
php artisan view:clear
```

### Port Already in Use
```bash
php artisan serve --port=8001
```

## 📞 Support

Untuk pertanyaan atau bug report, hubungi tim development.

---

**Status**: Production Ready ✅  
**Version**: 1.0.0  
**Last Updated**: April 2026

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
