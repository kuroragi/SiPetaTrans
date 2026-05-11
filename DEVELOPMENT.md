# SIPETA-TRANS Development Guide

## 🎯 Project Overview

SIPETA-TRANS adalah sistem informasi untuk pemetaan dan monitoring aset transportasi kota Bukittinggi. Aplikasi dibangun dengan Laravel 13 dan Tailwind CSS dengan fitur:

- Dashboard dengan visualisasi data aset
- Peta interaktif untuk sebaran aset
- Manajemen CRUD untuk aset
- Analisis kondisi aset dengan chart
- Database relasional untuk tracking aset

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                    Browser / Frontend                   │
│          (Blade Templates + Tailwind CSS)               │
└──────────────────┬──────────────────────────────────────┘
                   │
                   │ HTTP Request
                   ▼
┌─────────────────────────────────────────────────────────┐
│                 Laravel Routing                         │
│              (routes/web.php)                           │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────┐
│              Controllers                                │
│  - DashboardController (data aggregation)               │
│  - AssetController (CRUD operations)                    │
└──────────────────┬──────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────┐
│              Models & Database                          │
│  - Asset, AssetType, User, Notification                │
│  - SQLite (dev) / MySQL (prod)                          │
└─────────────────────────────────────────────────────────┘
```

## 📊 Database Schema

### Assets Table
```sql
CREATE TABLE assets (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) REQUIRED,
    asset_type_id BIGINT FOREIGN KEY,
    status ENUM('baik', 'perlu_perbaikan', 'rusak', 'dalam_pemeliharaan'),
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    location VARCHAR(255),
    last_maintenance DATE,
    description TEXT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Asset Types Table
```sql
CREATE TABLE asset_types (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) REQUIRED,
    icon VARCHAR(100),
    color VARCHAR(7),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## 🔄 Data Flow Example: Adding New Asset

1. **User fills form** → `/assets/create`
2. **Form submitted** → `AssetController@store`
3. **Validation** → Rules defined in store method
4. **Save to DB** → `Asset::create($validated)`
5. **Redirect** → Back to `/assets` with success message
6. **Display updated** → Database updated, view refreshed

## 🛠️ Development Workflow

### 1. Adding New Feature

**Scenario**: Tambah fitur "Export Aset ke PDF"

Steps:
```bash
# 1. Create controller method
php artisan make:controller ExportController

# 2. Create route
# Edit routes/web.php
Route::get('/assets/export', [ExportController::class, 'exportPdf'])->name('assets.export');

# 3. Install PDF library
composer require barryvdh/laravel-dompdf

# 4. Implement in controller
# app/Http/Controllers/ExportController.php

# 5. Create view for PDF
# resources/views/exports/assets.blade.php

# 6. Test
php artisan serve
# Visit http://127.0.0.1:8000/assets/export
```

### 2. Adding New Asset Type

```bash
# Edit database/seeders/AssetTypeSeeder.php
# Add new type in the $types array

# Run seeder
php artisan db:seed --class=AssetTypeSeeder

# Or fresh seed
php artisan migrate:fresh --seed
```

### 3. Modifying Database Schema

```bash
# Create new migration
php artisan make:migration add_column_to_assets_table

# Edit migration file
# database/migrations/YYYY_MM_DD_HHMMSS_add_column_to_assets_table.php

# Run migration
php artisan migrate
```

## 🧪 Testing

### Manual Testing Checklist

- [ ] Dashboard loads correctly
- [ ] Map displays asset markers
- [ ] Charts render with data
- [ ] Asset list shows all assets
- [ ] Can create new asset
- [ ] Can edit existing asset
- [ ] Can delete asset
- [ ] Can view asset details
- [ ] Status badges display correct colors
- [ ] Responsive on mobile/tablet

### Database Testing

```bash
php artisan tinker

# Query examples
>>> Asset::count()                                    # Total assets
>>> Asset::where('status', 'rusak')->count()         # Damaged assets
>>> Asset::with('type')->get()                       # Get with relationships
>>> AssetType::with('assets')->get()                 # Get with loaded assets
```

## 📈 Performance Tips

1. **Use Eager Loading**
   ```php
   // Good
   Asset::with('type')->get()
   
   // Bad (N+1 problem)
   $assets = Asset::all();
   foreach ($assets as $asset) {
       echo $asset->type->name;
   }
   ```

2. **Index Important Columns**
   ```php
   // In migration
   $table->index('status');
   $table->index('asset_type_id');
   ```

3. **Pagination for Large Lists**
   ```php
   // Good for large datasets
   $assets = Asset::paginate(15);
   
   // Bad for large datasets
   $assets = Asset::all();
   ```

## 🔐 Security Best Practices

1. **Input Validation**
   ```php
   $validated = $request->validate([
       'name' => 'required|string|max:255',
       'status' => 'required|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
   ]);
   ```

2. **Authorization (Future)**
   ```php
   // Define in AuthServiceProvider
   Gate::define('edit-asset', function (User $user, Asset $asset) {
       return $user->role === 'admin';
   });
   ```

3. **CSRF Protection**
   - All POST/PUT/DELETE routes require @csrf token
   - Already built-in with Blade forms

## 🐛 Debugging Tips

### Enable Debug Mode
```bash
# .env
APP_DEBUG=true   # Local development only!
```

### Use Laravel Tinker
```bash
php artisan tinker
>>> $asset = Asset::find(1)
>>> dd($asset)    # Dump and die
>>> DB::enableQueryLog()
>>> // Run queries
>>> dd(DB::getQueryLog())
```

### Check Logs
```bash
# Real-time log watching
tail -f storage/logs/laravel.log

# Or use Laravel Pail
php artisan pail
```

## 📦 Deployment Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Build frontend assets: `npm run build`
- [ ] Set correct file permissions
- [ ] Setup HTTPS/SSL
- [ ] Configure database backups
- [ ] Setup monitoring & logging

## 🤝 Contributing

1. Create feature branch: `git checkout -b feature/description`
2. Make changes and test
3. Commit: `git commit -m "Add description"`
4. Push: `git push origin feature/description`
5. Create Pull Request

## 📚 Useful Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Leaflet Map](https://leafletjs.com/)
- [Chart.js](https://www.chartjs.org/)

## 🆘 Common Issues & Solutions

### Issue: `Class not found`
```bash
# Solution: Regenerate autoloader
composer dump-autoload
```

### Issue: `CSRF token mismatch`
```bash
# Solution: Clear cookies or restart browser
# Ensure @csrf in forms
```

### Issue: Database locked
```bash
# Solution: Close all database connections
# Restart server: php artisan serve
```

## 📞 Contact

**Project Lead**: Development Team  
**Email**: dev@sipeta.com  
**Slack**: #sipeta-trans

---

**Last Updated**: April 2026  
**Version**: 1.0.0
