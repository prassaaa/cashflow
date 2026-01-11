<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Filament Shield - Role & Permission Management

Aplikasi ini menggunakan [Filament Shield](https://filamentphp.com/plugins/bezhansalleh-shield) untuk manajemen hak akses, role, dan permissions.

### 📚 Dokumentasi

**[📖 Documentation Index](DOCUMENTATION_INDEX.md)** - Navigasi lengkap semua dokumentasi

**Quick Links**:
- **[Quick Start Guide](QUICK_START.md)** - Panduan cepat untuk memulai
- **[Panduan Lengkap](FILAMENT_SHIELD_GUIDE.md)** - Dokumentasi lengkap Filament Shield
- **[Contoh Penggunaan](PERMISSION_EXAMPLES.md)** - Contoh code untuk menggunakan permissions
- **[FAQ](FAQ.md)** - Pertanyaan umum & troubleshooting
- **[Architecture](ARCHITECTURE.md)** - Diagram & arsitektur sistem

### Fitur yang Tersedia:

- **User Management**: CRUD lengkap untuk user dengan assignment role
- **Role Management**: Kelola role dan permissions melalui Filament panel
- **Permission Management**: Kontrol akses granular untuk Resources, Pages, dan Widgets
- **Policy-based Authorization**: Otomatis generate policies untuk setiap resource

### Login Super Admin:

- Email: `superadmin@example.com`
- Password: `password`

### Akses Panel:

Buka browser dan akses: `http://localhost/auth/login`

### Roles yang Tersedia:

| Role | Deskripsi | Permissions |
|------|-----------|-------------|
| `super_admin` | Super Administrator | Semua akses (bypass all checks) |
| `admin` | Administrator | Kelola users (CRUD lengkap) |
| `staff` | Staff | Hanya lihat users (read-only) |

### Quick Commands:

```bash
# Generate permissions untuk resource baru
php artisan shield:generate --resource=NamaResource --panel=auth

# Generate permissions untuk semua resources
php artisan shield:generate --all --panel=auth

# Buat super admin baru
php artisan shield:super-admin --panel=auth

# Seed roles & permissions
php artisan db:seed --class=RolePermissionSeeder
```

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
