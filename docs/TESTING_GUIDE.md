# Cash Flow Management System - Testing Guide

## 📋 Daftar Isi
1. [Test Users](#test-users)
2. [Role & Permission Overview](#role--permission-overview)
3. [Business Flow](#business-flow)
4. [Testing Scenarios per Role](#testing-scenarios-per-role)
5. [Complete Testing Flow](#complete-testing-flow)

---

## 🔐 Test Users

| Email | Role | Password |
|-------|------|----------|
| admin@cashflow.test | super_admin | password |
| hrd@cashflow.test | hrd | password |
| marketing@cashflow.test | marketing | password |
| purchasing@cashflow.test | purchasing | password |
| accounting@cashflow.test | accounting | password |
| ppic@cashflow.test | ppic | password |

**Login URL:** `http://localhost:8000/auth/login`

---

## 👥 Role & Permission Overview

### Dashboard Widgets per Role

| Widget | super_admin | accounting | marketing | ppic | hrd | purchasing |
|--------|:-----------:|:----------:|:---------:|:----:|:---:|:----------:|
| Stats Overview | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Salary Stats | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| PO Stats | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Latest Job Orders | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ |
| Latest PO | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Employee List | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Overdue Invoices | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Cash Flow Chart | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Expense Breakdown | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |

### Menu Access per Role

| Menu | super_admin | accounting | marketing | ppic | hrd | purchasing |
|------|:-----------:|:----------:|:---------:|:----:|:---:|:----------:|
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
| Rekap HRD | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Users | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Roles | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## 🔄 Business Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CASH FLOW BUSINESS PROCESS                         │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│ MARKETING│───▶│PURCHASING│───▶│   PPIC   │───▶│ACCOUNTING│───▶│   HRD    │
└──────────┘    └──────────┘    └──────────┘    └──────────┘    └──────────┘
     │               │               │               │               │
     ▼               ▼               ▼               ▼               ▼
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│Create JO │    │Create PO │    │Production│    │ Expenses │    │ Employee │
│          │    │          │    │ Progress │    │ Invoices │    │ Salaries │
│          │    │          │    │ ManPower │    │ OtherCost│    │          │
│          │    │          │    │ Delivery │    │          │    │          │
└──────────┘    └──────────┘    └──────────┘    └──────────┘    └──────────┘

FLOW LENGKAP:
─────────────
1. Marketing membuat Job Order (JO) dari customer
2. Purchasing membuat Purchase Order (PO) untuk material
3. PPIC mengatur produksi, manpower, dan delivery
4. Accounting mengelola invoice dan expenses
5. HRD mengelola karyawan dan gaji
```

---

## 🧪 Testing Scenarios per Role

### 1. SUPER_ADMIN (Full Access)
**Login:** admin@cashflow.test

#### Dashboard Test
- [ ] Lihat semua widgets
- [ ] Verifikasi Stats Overview menampilkan data
- [ ] Verifikasi Cash Flow Chart dan Expense Breakdown

#### CRUD Test
- [ ] Buat User baru dengan role tertentu
- [ ] Edit permission role
- [ ] Akses semua menu dan verifikasi CRUD berfungsi

---

### 2. MARKETING
**Login:** marketing@cashflow.test

#### Dashboard Test
- [ ] Verifikasi hanya melihat: Latest Job Orders, Overdue Invoices
- [ ] Tidak melihat: Stats Overview, Cash Flow Chart, Expense Breakdown

#### Job Order Management
1. **Create Job Order**
   - [ ] Klik menu "Job Orders"
   - [ ] Klik tombol "Buat Job Order"
   - [ ] Isi data:
     - No. JO: JO-2026-TEST
     - Nama Customer: PT Test Customer
     - Nama Project: Project Testing
     - Nilai: 50000000
     - Tanggal Order: Hari ini
     - Due Date: 1 bulan ke depan
     - Status: pending
   - [ ] Simpan dan verifikasi

2. **Update JO Status**
   - [ ] Edit JO yang dibuat
   - [ ] Ubah status ke `in_progress`
   - [ ] Simpan

3. **Export Data**
   - [ ] Klik tombol Export di header table
   - [ ] Download file Excel

#### Invoice Management
1. **View Invoices**
   - [ ] Lihat daftar invoice yang ada
   - [ ] Filter berdasarkan status

2. **Create Invoice** (jika ada permission)
   - [ ] Buat invoice baru untuk JO yang ada
   - [ ] Isi nomor invoice, amount, due date

#### Delivery Tracking
- [ ] Lihat status pengiriman
- [ ] Filter berdasarkan JO

---

### 3. PURCHASING
**Login:** purchasing@cashflow.test

#### Dashboard Test
- [ ] Verifikasi melihat: PO Stats, Latest Purchase Orders
- [ ] Tidak melihat: Stats Overview, Cash Flow, Job Orders, Employee

#### Purchase Order Management
1. **Create PO**
   - [ ] Klik menu "Purchase Orders"
   - [ ] Klik "Buat Purchase Order"
   - [ ] Isi data:
     - No. PO: PO-2026-TEST
     - Job Order: Pilih JO yang ada
     - Nama Supplier: PT Supplier Test
     - Kategori: jo_related
     - Nilai: 10000000
     - Status: pending
   - [ ] Simpan

2. **Update PO Status**
   - [ ] Edit PO yang dibuat
   - [ ] Ubah status: pending → approved → received
   - [ ] Verifikasi setiap perubahan

3. **Filter & Search**
   - [ ] Filter PO berdasarkan status
   - [ ] Search berdasarkan supplier
   - [ ] Filter berdasarkan kategori

4. **Export**
   - [ ] Export data PO ke Excel

---

### 4. PPIC (Production Planning & Inventory Control)
**Login:** ppic@cashflow.test

#### Dashboard Test
- [ ] Verifikasi melihat: Latest Job Orders
- [ ] Tidak melihat: Stats Overview, Cash Flow, PO Stats

#### Production Progress
1. **Create Progress**
   - [ ] Klik menu "Production Progress"
   - [ ] Buat progress baru:
     - Job Order: Pilih JO yang ada
     - Stage: planning (awal produksi)
     - Status Achievement %: 0
     - Material: (isi material)
     - Proses: planning
     - Packing: (isi metode/jenis packing)
     - Problem: (jika ada)
     - Solusi: (jika ada)
   - [ ] Simpan

2. **Update Progress**
   - [ ] Edit progress yang dibuat
   - [ ] Update stage secara berurutan:
     - planning → material_prep → production → quality_check → finishing → packing → completed
   - [ ] Update progress %: 10 → 30 → 60 → 75 → 90 → 100
   - [ ] Pastikan stage = completed saat progress 100%

#### Man Power Management
1. **Assign Man Power**
   - [ ] Klik menu "Man Powers"
   - [ ] Assign karyawan ke JO:
     - Job Order: Pilih JO
     - Employee: Pilih karyawan
     - Tanggal: Hari ini
     - Jam kerja: 8
   - [ ] Simpan

2. **View Man Power Report**
   - [ ] Filter berdasarkan tanggal
   - [ ] Filter berdasarkan JO
   - [ ] Export data

#### Delivery Management
1. **Create Delivery**
   - [ ] Klik menu "Deliveries"
   - [ ] Buat delivery baru:
     - Job Order: Pilih JO (status harus completed)
     - No. Surat Jalan: SJ-2026-TEST
     - Tanggal Delivery: Hari ini
     - Status: preparing
   - [ ] Simpan

2. **Update Delivery Status**
   - [ ] Update status: preparing → shipped → in_transit → delivered
   - [ ] Set tanggal received_date saat delivered
   - [ ] Jika perlu, gunakan status lain: returned / cancelled

---

### 5. ACCOUNTING
**Login:** accounting@cashflow.test

#### Dashboard Test
- [ ] Verifikasi melihat: Stats Overview, Overdue Invoices, Cash Flow Chart, Expense Breakdown
- [ ] Tidak melihat: Latest Job Orders, PO Stats, Employee List

#### Expense Management
1. **Create Expense**
   - [ ] Klik menu "Expenses"
   - [ ] Buat expense baru:
     - Job Order: Pilih JO
     - Kategori: operational
     - Deskripsi: Biaya operasional testing
     - Jumlah: 500000
     - Tanggal: Hari ini
   - [ ] Simpan

2. **Categorize Expenses**
   - [ ] Buat expense dengan kategori berbeda:
     - operational, material, transport, utility, maintenance, other
   - [ ] Verifikasi di Expense Breakdown Chart

#### Invoice Management
1. **Create Invoice**
   - [ ] Buat invoice untuk JO:
     - No. Invoice: INV-2026-TEST
     - Job Order: Pilih JO
     - Amount: 50000000
     - Invoice Date: Hari ini
     - Due Date: 30 hari
     - Status: sent

2. **Update Invoice Status**
   - [ ] Update paid_amount untuk pembayaran sebagian
   - [ ] Update status: sent → paid
   - [ ] Set paid_date ketika paid

3. **Track Overdue**
   - [ ] Buat invoice dengan due_date lampau
   - [ ] Set status ke overdue
   - [ ] Verifikasi muncul di Overdue Invoices widget

#### Other Costs
1. **Create Other Cost**
   - [ ] Buat biaya lain-lain:
     - Kategori: shipping/insurance/tax/permit/consultant/misc
     - Deskripsi: Biaya admin testing
     - Amount: 250000

#### Salary Review
- [ ] Lihat data salary (read-only atau dengan permission)
- [ ] Verifikasi total salary di Stats Overview

#### Reports & Export
- [ ] Export Expenses ke Excel
- [ ] Export Invoices ke Excel
- [ ] Export Other Costs ke Excel

---

### 6. HRD (Human Resources Department)
**Login:** hrd@cashflow.test

#### Dashboard Test
- [ ] Verifikasi melihat: Salary Stats, Employee List
- [ ] Tidak melihat: Stats Overview, Job Orders, PO Stats, Cash Flow

#### Employee Management
1. **Create Employee**
   - [ ] Klik menu "Employees"
   - [ ] Buat karyawan baru:
     - NIK: EMP-2026-TEST
     - Nama: Test Employee
     - Posisi: Operator
     - Tipe: staff/daily/borongan
     - Gaji Harian: 150000
     - Tanggal Masuk: Hari ini
     - Status: active
   - [ ] Simpan

2. **Update Employee**
   - [ ] Edit employee
   - [ ] Ubah posisi atau gaji
   - [ ] Set status: active → inactive

#### Salary Management
1. **Create Salary Record**
   - [ ] Klik menu "Salaries"
   - [ ] Buat record gaji:
     - Employee: Pilih employee
     - Job Order: Pilih JO (opsional)
     - Periode: 2026-01
     - Hari Kerja: 22
     - Gaji Pokok: 3300000
     - Overtime: 500000
     - Bonus: 250000
     - Potongan: 100000
     - Total: 3950000
     - Status: pending

2. **Salary Approval Flow**
   - [ ] Update status: pending → approved
   - [ ] Update status: approved → paid
   - [ ] Set tanggal pembayaran

3. **View Salary Stats**
   - [ ] Verifikasi di Salary Stats widget:
     - Total Karyawan
     - Karyawan Aktif
     - Total Gaji Bulan Ini
     - Status Pending/Approved/Paid

#### Man Power Review
- [ ] Lihat data man power
- [ ] Verifikasi jam kerja karyawan

#### Rekap HRD (Absensi)
1. **Create Rekap**
   - [ ] Klik menu "Rekap HRD"
   - [ ] Input data:
     - Tanggal: Hari ini
     - Status: staff/daily/borongan
     - Jumlah Hadir
     - Jumlah Absen
     - Jumlah Pengurangan
     - Jumlah Karyawan Baru
   - [ ] Simpan

---

## 📝 Complete Testing Flow

### Scenario: Complete Project Cycle

```
STEP 1: MARKETING - Create Job Order
────────────────────────────────────
Login: marketing@cashflow.test

1. Buat Job Order baru
   - JO Number: JO-2026-001
   - Customer: PT ABC Indonesia
   - Project: Pembuatan Mesin Press
   - Value: Rp 100.000.000
   - Order Date: 2026-01-16
   - Due Date: 2026-02-28
   - Status: pending

2. Update status ke 'in_progress'


STEP 2: PURCHASING - Create Purchase Orders
───────────────────────────────────────────
Login: purchasing@cashflow.test

1. Buat PO untuk JO related
   - PO Number: PO-2026-001
   - Job Order: JO-2026-001
   - Supplier: PT Steel Indonesia
   - Category: jo_related
   - Value: Rp 30.000.000
   - Status: pending

2. Buat PO untuk JO related (consumable)
   - PO Number: PO-2026-002
   - Job Order: JO-2026-001
   - Supplier: PT Welding Supply
   - Category: jo_related
   - Value: Rp 5.000.000

3. Update status PO: pending → approved → received


STEP 3: HRD - Setup Employees
─────────────────────────────
Login: hrd@cashflow.test

1. Pastikan ada employee yang aktif
2. Verifikasi data karyawan untuk produksi


STEP 4: PPIC - Production & Man Power
─────────────────────────────────────
Login: ppic@cashflow.test

1. Assign Man Power
   - Assign 3 karyawan ke JO-2026-001
   - Set jam kerja masing-masing

2. Create Production Progress
   - Start: planning stage, 0%
   - Update: material_prep, 10%
   - Update: production, 30%
   - Update: quality_check, 60%
   - Update: finishing, 75%
   - Update: packing, 90%
   - Update: completed, 100%

3. Create Delivery
   - Buat surat jalan
   - Update status: preparing → shipped → in_transit → delivered


STEP 5: ACCOUNTING - Financial Records
──────────────────────────────────────
Login: accounting@cashflow.test

1. Record Expenses
   - Biaya operasional: Rp 2.000.000
   - Biaya transport: Rp 500.000

2. Create Invoice
   - Invoice Number: INV-2026-001
   - Amount: Rp 100.000.000
   - Status: sent

3. Track Payment
   - Update paid_amount: Rp 50.000.000 (status tetap sent)
   - Update status: sent → paid

4. Review Cash Flow
   - Check Cash Flow Chart
   - Check Expense Breakdown


STEP 6: HRD - Process Salary
────────────────────────────
Login: hrd@cashflow.test

1. Create Salary Records
   - Untuk setiap employee yang bekerja di JO
   - Hitung berdasarkan man power hours

2. Process Salary
   - Status: pending → approved → paid


STEP 7: MARKETING - Complete Job Order
──────────────────────────────────────
Login: marketing@cashflow.test

1. Verifikasi delivery sudah completed
2. Update JO status: in_progress → completed


STEP 8: SUPER_ADMIN - Final Review
──────────────────────────────────
Login: admin@cashflow.test

1. Review semua data di dashboard
2. Check:
   - JO completed
   - PO received
   - Invoice paid
   - Salary paid
   - Delivery completed
```

---

## ✅ Testing Checklist Summary

### Pre-Testing
- [ ] Database sudah di-seed dengan demo data
- [ ] Server berjalan di localhost:8000
- [ ] Semua user test sudah ada di database

### Core Features
- [ ] Login/Logout semua role
- [ ] Dashboard widgets sesuai role
- [ ] CRUD operations semua resource
- [ ] Export Excel berfungsi
- [ ] Filter dan search berfungsi

### Permission Testing
- [ ] Role tidak bisa akses menu yang tidak diizinkan
- [ ] Widget hanya tampil untuk role yang sesuai
- [ ] Bulk actions berfungsi sesuai permission

### Data Integrity
- [ ] Relasi antar tabel benar (JO → PO, JO → Invoice, dll)
- [ ] Soft delete berfungsi
- [ ] Activity log tercatat

---

## 🐛 Bug Report Template

```
**Role:** [super_admin/hrd/marketing/purchasing/accounting/ppic]
**Menu/Feature:** [nama menu atau fitur]
**Expected:** [yang diharapkan]
**Actual:** [yang terjadi]
**Steps to Reproduce:**
1. ...
2. ...
3. ...
**Screenshot:** [jika ada]
```

---

## 📞 Notes

- Semua password default: `password`
- Gunakan `php artisan db:seed --class=DemoDataSeeder` untuk reset demo data
- Cache clear: `php artisan optimize:clear`
