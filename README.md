<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Filament-4.x-FDAE4B?style=for-the-badge&logo=laravel&logoColor=white" alt="Filament">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
</p>

<h1 align="center">💰 Cash Flow Management System</h1>

<p align="center">
  <strong>Sistem manajemen arus kas terintegrasi untuk mengelola Job Order, Purchase Order, Invoice, Expenses, Salary, dan Production Tracking.</strong>
</p>

<p align="center">
  <a href="#-fitur">Fitur</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#-instalasi">Instalasi</a> •
  <a href="#-roles--permissions">Roles</a> •
  <a href="#-screenshots">Screenshots</a> •
  <a href="#-dokumentasi">Dokumentasi</a>
</p>

---

## ✨ Fitur

### 📊 Dashboard & Analytics
- **Stats Overview** - Ringkasan JO aktif, PO pending, Invoice, dan Expenses
- **Cash Flow Chart** - Grafik arus kas 6 bulan terakhir
- **Expense Breakdown** - Breakdown pengeluaran per kategori
- **Overdue Invoices** - Daftar invoice yang jatuh tempo
- **Role-based Widgets** - Widget tampil sesuai role pengguna

### 📋 Core Modules

| Module | Deskripsi |
|--------|-----------|
| **Job Orders** | Kelola order dari customer dengan tracking status |
| **Purchase Orders** | Manajemen pembelian material, consumable, tools |
| **Invoices** | Penagihan dan tracking pembayaran customer |
| **Expenses** | Catat semua pengeluaran operasional |
| **Employees** | Database karyawan (staff, daily, contract) |
| **Salaries** | Penggajian dengan approval workflow |
| **Man Powers** | Alokasi tenaga kerja per Job Order |
| **Production Progress** | Tracking progress produksi per stage |
| **Deliveries** | Manajemen pengiriman dan surat jalan |
| **Other Costs** | Biaya overhead dan administratif |

### 🔐 Security & Access Control
- **6 User Roles** - super_admin, hrd, marketing, purchasing, accounting, ppic
- **Granular Permissions** - Kontrol akses per resource dan action
- **Activity Logging** - Audit trail semua perubahan data
- **Policy-based Auth** - Laravel policies untuk authorization

### 📤 Export & Reports
- **Excel Export** - Export data ke format Excel (.xlsx)
- **Bulk Actions** - Bulk export untuk data terpilih
- **Filtered Export** - Export berdasarkan filter yang aktif

---

## 🛠 Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 12.x | PHP Framework |
| Filament | 4.x | Admin Panel |
| PHP | 8.2+ | Runtime |
| MySQL/PostgreSQL | 8.x | Database |
| Livewire | 3.x | Reactive Components |
| TailwindCSS | 3.x | Styling |
| Spatie Permission | 6.x | Role & Permission |
| Filament Shield | 3.x | Permission UI |
| pxlrbt Excel | 3.x | Excel Export |

---

## 🚀 Instalasi

### Requirements
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL 8.x / PostgreSQL

### Quick Start

```bash
# Clone repository
git clone https://github.com/prassaaa/cashflow.git
cd cashflow

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database di .env
# DB_DATABASE=cashflow
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations & seeders
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start server
php artisan serve
```

### Demo Data (Optional)

```bash
# Seed demo data untuk testing
php artisan db:seed --class=DemoDataSeeder
```

---

## 👥 Roles & Permissions

### Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@cashflow.test | password |
| HRD | hrd@cashflow.test | password |
| Marketing | marketing@cashflow.test | password |
| Purchasing | purchasing@cashflow.test | password |
| Accounting | accounting@cashflow.test | password |
| PPIC | ppic@cashflow.test | password |

### Role Responsibilities

```
┌──────────────────────────────────────────────────────────────────────────┐
│                         BUSINESS FLOW                                     │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  MARKETING ──▶ PURCHASING ──▶ PPIC ──▶ ACCOUNTING ──▶ HRD              │
│      │             │           │            │           │                │
│      ▼             ▼           ▼            ▼           ▼                │
│  Job Order    Purchase     Production   Expenses    Employee             │
│  Invoice      Order        Progress     Invoice     Salary               │
│  Delivery                  Man Power    Other Cost                       │
│                            Delivery                                      │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘
```

### Module Access Matrix

| Module | super_admin | accounting | marketing | ppic | hrd | purchasing |
|--------|:-----------:|:----------:|:---------:|:----:|:---:|:----------:|
| Job Orders | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ |
| Purchase Orders | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ |
| Expenses | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Invoices | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Employees | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Salaries | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| Man Powers | ✅ | ❌ | ❌ | ✅ | ✅ | ❌ |
| Production Progress | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| Deliveries | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| Other Costs | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## 📸 Screenshots

### Dashboard
```
┌─────────────────────────────────────────────────────────────┐
│  📊 Stats Overview                                          │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐    │
│  │JO: 5 │ │PO: 8 │ │Inv:12│ │Due: 3│ │Exp:5M│ │Sal:8M│    │
│  └──────┘ └──────┘ └──────┘ └──────┘ └──────┘ └──────┘    │
│                                                             │
│  📈 Cash Flow Chart (6 Months)    🍩 Expense Breakdown     │
│  ┌─────────────────────────┐      ┌─────────────────────┐  │
│  │ Income ████████████     │      │    PO: 40%          │  │
│  │ Expense ████████        │      │    Expense: 30%     │  │
│  │                         │      │    Salary: 25%      │  │
│  └─────────────────────────┘      └─────────────────────┘  │
│                                                             │
│  📋 Latest Job Orders           ⚠️ Overdue Invoices        │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ JO-001 │ PT ABC │ Project X │ Rp 50.000.000 │ ●     │   │
│  │ JO-002 │ PT XYZ │ Project Y │ Rp 75.000.000 │ ●     │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## 📖 Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [Testing Guide](docs/TESTING_GUIDE.md) | Panduan testing lengkap per role |
| [API Reference](#) | Dokumentasi API (coming soon) |

---

## 🔧 Artisan Commands

```bash
# Clear all caches
php artisan optimize:clear

# Generate permissions for new resource
php artisan shield:generate --resource=NewResource --panel=auth

# Generate all permissions
php artisan shield:generate --all --panel=auth

# Create super admin
php artisan shield:super-admin --panel=auth

# Run tests
php artisan test

# Check code style
./vendor/bin/pint --test
```

---

## 📁 Project Structure

```
cashflow/
├── app/
│   ├── Filament/
│   │   ├── Resources/          # Filament CRUD Resources
│   │   │   ├── JobOrders/
│   │   │   ├── PurchaseOrders/
│   │   │   ├── Invoices/
│   │   │   ├── Expenses/
│   │   │   ├── Employees/
│   │   │   ├── Salaries/
│   │   │   └── ...
│   │   └── Widgets/            # Dashboard Widgets
│   │       ├── StatsOverviewWidget.php
│   │       ├── CashFlowChartWidget.php
│   │       ├── ExpenseBreakdownChart.php
│   │       └── ...
│   ├── Models/                 # Eloquent Models
│   ├── Policies/               # Authorization Policies
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── DemoDataSeeder.php
├── docs/
│   └── TESTING_GUIDE.md
└── resources/
    └── views/
```

---

## 🤝 Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan:
- Buka [GitHub Issue](https://github.com/prassaaa/cashflow/issues)
- Email: support@example.com

---

<p align="center">
  Made with ❤️ using Laravel & Filament
</p>
