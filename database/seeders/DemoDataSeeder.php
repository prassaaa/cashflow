<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\HrdAttendance;
use App\Models\Invoice;
use App\Models\JobOrder;
use App\Models\ManPower;
use App\Models\OtherCost;
use App\Models\ProductionProgress;
use App\Models\PurchaseOrder;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create PPIC users first
        $ppicUsers = $this->createPpicUsers();

        // Create Employees
        $employees = $this->createEmployees();

        // Create HRD attendance recap
        $this->createHrdAttendances();

        // Create Job Orders
        $jobOrders = $this->createJobOrders($ppicUsers);

        // Create related data for each Job Order
        foreach ($jobOrders as $index => $jobOrder) {
            $this->createPurchaseOrders($jobOrder, $index);
            $this->createExpenses($jobOrder, $index);
            $this->createInvoices($jobOrder, $index);
            $this->createDeliveries($jobOrder, $index);
            $this->createProductionProgress($jobOrder, $index);
            $this->createOtherCosts($jobOrder, $index);
            $this->createManPowers($jobOrder, $employees, $index);
            $this->createSalaries($jobOrder, $employees, $index);
        }
    }

    private function createPpicUsers(): array
    {
        $ppicUsersData = [
            [
                'name' => 'Rina Setiawan',
                'email' => 'rina.ppic@cashflow.test',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Agus Nugroho',
                'email' => 'agus.ppic@cashflow.test',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tina Wijaya',
                'email' => 'tina.ppic@cashflow.test',
                'password' => bcrypt('password'),
            ],
        ];

        $ppicUsers = [];
        foreach ($ppicUsersData as $data) {
            $user = User::create($data);
            $user->assignRole('ppic');
            $ppicUsers[] = $user;
        }

        return $ppicUsers;
    }

    private function createEmployees(): array
    {
        $employeesData = [
            [
                'employee_number' => 'EMP-001',
                'name' => 'Budi Santoso',
                'type' => 'staff',
                'position' => 'Operator Produksi',
                'department' => 'production',
                'base_salary' => 5000000,
                'join_date' => '2023-01-15',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-002',
                'name' => 'Siti Rahayu',
                'type' => 'staff',
                'position' => 'QC Staff',
                'department' => 'production',
                'base_salary' => 5500000,
                'join_date' => '2022-06-01',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-003',
                'name' => 'Ahmad Wijaya',
                'type' => 'staff',
                'position' => 'Supervisor Produksi',
                'department' => 'production',
                'base_salary' => 8000000,
                'join_date' => '2021-03-10',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-004',
                'name' => 'Dewi Lestari',
                'type' => 'staff',
                'position' => 'Admin Purchasing',
                'department' => 'purchasing',
                'base_salary' => 5000000,
                'join_date' => '2023-04-01',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-005',
                'name' => 'Eko Prasetyo',
                'type' => 'borongan',
                'position' => 'Helper Produksi',
                'department' => 'production',
                'base_salary' => 4000000,
                'join_date' => '2024-01-01',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-006',
                'name' => 'Fitri Handayani',
                'type' => 'staff',
                'position' => 'Staff Accounting',
                'department' => 'accounting',
                'base_salary' => 6000000,
                'join_date' => '2022-08-15',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-007',
                'name' => 'Gunawan Setiawan',
                'type' => 'staff',
                'position' => 'Sales Executive',
                'department' => 'marketing',
                'base_salary' => 6500000,
                'join_date' => '2021-11-01',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-008',
                'name' => 'Hendra Kusuma',
                'type' => 'daily',
                'position' => 'Teknisi Mesin',
                'department' => 'production',
                'base_salary' => 0,
                'join_date' => '2024-06-01',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-009',
                'name' => 'Indah Permata',
                'type' => 'staff',
                'position' => 'HRD Staff',
                'department' => 'hrd',
                'base_salary' => 5500000,
                'join_date' => '2023-02-01',
                'status' => 'active',
            ],
            [
                'employee_number' => 'EMP-010',
                'name' => 'Joko Susilo',
                'type' => 'borongan',
                'position' => 'Driver',
                'department' => 'warehouse',
                'base_salary' => 4500000,
                'join_date' => '2024-03-01',
                'status' => 'active',
            ],
        ];

        $employees = [];
        foreach ($employeesData as $data) {
            $employees[] = Employee::create($data);
        }

        return $employees;
    }

    private function createHrdAttendances(): void
    {
        $statuses = ['staff', 'daily', 'borongan'];

        for ($i = 0; $i < 7; $i++) {
            foreach ($statuses as $status) {
                HrdAttendance::create([
                    'date' => now()->subDays($i)->toDateString(),
                    'status' => $status,
                    'present_count' => rand(5, 20),
                    'absent_count' => rand(0, 5),
                    'deduction_count' => rand(0, 3),
                    'new_hires_count' => $i === 0 ? rand(0, 2) : 0,
                ]);
            }
        }
    }

    private function createJobOrders(array $ppicUsers): array
    {
        $containerNames = ['TEMU5046897', 'TCKU1234567', 'MSCU8901234', 'CMAU5678901', 'HLCU2345678'];
        $units = ['PC', 'PP', 'SET', 'PCS'];
        $pipaStatuses = ['pending', 'paid'];
        $cartonTypes = ['SWL', 'DWL', 'TWL', 'RSA', 'INHOUSE'];
        $paymentStatuses = ['unpaid', 'partial', 'paid'];

        $jobOrdersData = [
            [
                'jo_number' => 'JO-2025-0001',
                'customer_name' => 'PT. Maju Bersama',
                'project_name' => 'Produksi Kemasan Makanan',
                'description' => 'Pembuatan kemasan makanan untuk produk snack 1000 pcs',
                'value' => 50000000,
                'order_date' => '2025-10-01',
                'due_date' => '2025-11-15',
                'status' => 'completed',
                'user_id' => $ppicUsers[0]->id,
                'container_name' => $containerNames[0],
                'quantity' => 1000,
                'unit' => 'PCS',
                'pipa_status' => 'paid',
                'carton_type' => 'DWL',
                'payment_status' => 'paid',
            ],
            [
                'jo_number' => 'JO-2025-0002',
                'customer_name' => 'CV. Indah Printing',
                'project_name' => 'Cetak Brosur Promosi',
                'description' => 'Cetak brosur A4 full color 5000 lembar',
                'value' => 15000000,
                'order_date' => '2025-10-15',
                'due_date' => '2025-11-01',
                'status' => 'completed',
                'user_id' => $ppicUsers[1]->id,
                'container_name' => $containerNames[1],
                'quantity' => 5000,
                'unit' => 'PC',
                'pipa_status' => 'paid',
                'carton_type' => 'SWL',
                'payment_status' => 'paid',
            ],
            [
                'jo_number' => 'JO-2025-0003',
                'customer_name' => 'PT. Sukses Makmur',
                'project_name' => 'Packaging Premium Box',
                'description' => 'Pembuatan box packaging premium untuk produk kosmetik 500 pcs',
                'value' => 75000000,
                'order_date' => '2025-11-01',
                'due_date' => '2025-12-15',
                'status' => 'in_progress',
                'user_id' => $ppicUsers[2]->id,
                'container_name' => $containerNames[2],
                'quantity' => 500,
                'unit' => 'SET',
                'pipa_status' => 'paid',
                'carton_type' => 'TWL',
                'payment_status' => 'partial',
            ],
            [
                'jo_number' => 'JO-2025-0004',
                'customer_name' => 'PT. Global Elektronik',
                'project_name' => 'Label Produk Elektronik',
                'description' => 'Cetak label stiker untuk produk elektronik 10000 pcs',
                'value' => 25000000,
                'order_date' => '2025-11-10',
                'due_date' => '2025-12-01',
                'status' => 'in_progress',
                'user_id' => $ppicUsers[0]->id,
                'container_name' => $containerNames[3],
                'quantity' => 10000,
                'unit' => 'PCS',
                'pipa_status' => 'pending',
                'carton_type' => 'RSA',
                'payment_status' => 'partial',
            ],
            [
                'jo_number' => 'JO-2025-0005',
                'customer_name' => 'CV. Harmoni Textile',
                'project_name' => 'Hangtag Fashion',
                'description' => 'Pembuatan hangtag untuk produk fashion 3000 pcs',
                'value' => 18000000,
                'order_date' => '2025-11-20',
                'due_date' => '2026-01-05',
                'status' => 'in_progress',
                'user_id' => $ppicUsers[1]->id,
                'container_name' => $containerNames[4],
                'quantity' => 3000,
                'unit' => 'PCS',
                'pipa_status' => 'pending',
                'carton_type' => 'INHOUSE',
                'payment_status' => 'unpaid',
            ],
            [
                'jo_number' => 'JO-2026-0001',
                'customer_name' => 'PT. Natura Indonesia',
                'project_name' => 'Kemasan Produk Herbal',
                'description' => 'Pembuatan kemasan untuk produk herbal 2000 pcs',
                'value' => 40000000,
                'order_date' => '2026-01-02',
                'due_date' => '2026-02-15',
                'status' => 'pending',
                'user_id' => $ppicUsers[2]->id,
                'container_name' => $containerNames[0],
                'quantity' => 2000,
                'unit' => 'PCS',
                'pipa_status' => 'pending',
                'carton_type' => 'TWL',
                'payment_status' => 'unpaid',
            ],
            [
                'jo_number' => 'JO-2026-0002',
                'customer_name' => 'PT. Mega Food',
                'project_name' => 'Standing Pouch',
                'description' => 'Produksi standing pouch untuk makanan ringan 5000 pcs',
                'value' => 35000000,
                'order_date' => '2026-01-05',
                'due_date' => '2026-02-10',
                'status' => 'pending',
                'user_id' => $ppicUsers[0]->id,
                'container_name' => $containerNames[1],
                'quantity' => 5000,
                'unit' => 'SET',
                'pipa_status' => 'pending',
                'carton_type' => 'SWL',
                'payment_status' => 'unpaid',
            ],
            [
                'jo_number' => 'JO-2026-0003',
                'customer_name' => 'CV. Prima Jaya',
                'project_name' => 'Kartu Nama Premium',
                'description' => 'Cetak kartu nama premium emboss 2000 box',
                'value' => 20000000,
                'order_date' => '2026-01-08',
                'due_date' => '2026-01-25',
                'status' => 'pending',
                'user_id' => $ppicUsers[1]->id,
                'container_name' => $containerNames[2],
                'quantity' => 2000,
                'unit' => 'PP',
                'pipa_status' => 'pending',
                'carton_type' => 'DWL',
                'payment_status' => 'unpaid',
            ],
            [
                'jo_number' => 'JO-2026-0004',
                'customer_name' => 'PT. Sinar Abadi',
                'project_name' => 'Katalog Produk',
                'description' => 'Cetak katalog A4 48 halaman 1000 buku',
                'value' => 60000000,
                'order_date' => '2026-01-10',
                'due_date' => '2026-02-28',
                'status' => 'pending',
                'user_id' => $ppicUsers[2]->id,
                'container_name' => $containerNames[3],
                'quantity' => 1000,
                'unit' => 'SET',
                'pipa_status' => 'pending',
                'carton_type' => 'RSA',
                'payment_status' => 'unpaid',
            ],
            [
                'jo_number' => 'JO-2026-0005',
                'customer_name' => 'PT. Berkah Mandiri',
                'project_name' => 'Dus Makanan',
                'description' => 'Pembuatan dus makanan dengan laminating 3000 pcs',
                'value' => 28000000,
                'order_date' => '2026-01-12',
                'due_date' => '2026-02-20',
                'status' => 'pending',
                'user_id' => $ppicUsers[0]->id,
                'container_name' => $containerNames[4],
                'quantity' => 3000,
                'unit' => 'PCS',
                'pipa_status' => 'pending',
                'carton_type' => 'INHOUSE',
                'payment_status' => 'unpaid',
            ],
        ];

        $jobOrders = [];
        foreach ($jobOrdersData as $data) {
            $jobOrders[] = JobOrder::create($data);
        }

        return $jobOrders;
    }

    private function createPurchaseOrders(JobOrder $jobOrder, int $index): void
    {
        $categories = ['jo_related', 'asset', 'machine', 'facility', 'other'];
        $suppliers = [
            'PT. Kertas Jaya',
            'CV. Tinta Mandiri',
            'PT. Plastik Nusantara',
            'UD. Karton Sejahtera',
            'PT. Bahan Baku Indonesia',
        ];

        $poData = [
            [
                'supplier_name' => $suppliers[array_rand($suppliers)],
                'category' => 'jo_related',
                'description' => 'Bahan baku kertas art paper 150gsm',
                'value' => $jobOrder->value * 0.25,
                'status' => $index < 2 ? 'received' : ($index < 5 ? 'approved' : 'pending'),
            ],
            [
                'supplier_name' => $suppliers[array_rand($suppliers)],
                'category' => 'jo_related',
                'description' => 'Tinta cetak CMYK',
                'value' => $jobOrder->value * 0.1,
                'status' => $index < 2 ? 'received' : ($index < 5 ? 'approved' : 'pending'),
            ],
        ];

        foreach ($poData as $i => $data) {
            PurchaseOrder::create([
                'job_order_id' => $jobOrder->id,
                'po_number' => 'PO-'.date('Y', strtotime($jobOrder->order_date)).'-'.str_pad(($index * 2) + $i + 1, 4, '0', STR_PAD_LEFT),
                'supplier_name' => $data['supplier_name'],
                'category' => $data['category'],
                'description' => $data['description'],
                'value' => $data['value'],
                'po_date' => $jobOrder->order_date,
                'expected_delivery_date' => date('Y-m-d', strtotime($jobOrder->order_date.' +7 days')),
                'status' => $data['status'],
            ]);
        }
    }

    private function createExpenses(JobOrder $jobOrder, int $index): void
    {
        $expenseData = [
            [
                'category' => 'material',
                'description' => 'Pembelian bahan baku produksi',
                'amount' => $jobOrder->value * 0.2,
                'status' => $index < 4 ? 'paid' : 'pending',
            ],
            [
                'category' => 'operational',
                'description' => 'Biaya operasional mesin',
                'amount' => $jobOrder->value * 0.05,
                'status' => $index < 3 ? 'paid' : 'pending',
            ],
            [
                'category' => 'transport',
                'description' => 'Biaya pengiriman bahan',
                'amount' => 500000,
                'status' => $index < 4 ? 'paid' : 'pending',
            ],
        ];

        foreach ($expenseData as $i => $data) {
            Expense::create([
                'job_order_id' => $jobOrder->id,
                'expense_number' => 'EXP-'.date('Y', strtotime($jobOrder->order_date)).'-'.str_pad(($index * 3) + $i + 1, 4, '0', STR_PAD_LEFT),
                'category' => $data['category'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'expense_date' => date('Y-m-d', strtotime($jobOrder->order_date.' +'.($i * 3).' days')),
                'status' => $data['status'],
            ]);
        }
    }

    private function createInvoices(JobOrder $jobOrder, int $index): void
    {
        $shippers = ['PT GLOBALINDO', 'CV ABHINAYA', 'PT NUSANTARA SHIPPING', 'CV MAJU JAYA CARGO'];
        $poNumbers = ['PO-2025-001', 'PO-2025-002', 'PO-2025-003', 'PO-2025-004', 'PO-2026-001'];

        // DP Invoice
        $dpAmount = $jobOrder->value * 0.3; // 30% DP
        $dpDeposit = $dpAmount * 0.5; // 50% dari DP sebagai deposit
        $dpPaid = $index < 4 ? $dpAmount - $dpDeposit : 0;

        Invoice::create([
            'job_order_id' => $jobOrder->id,
            'invoice_number' => 'INV-'.date('Y', strtotime($jobOrder->order_date)).'-'.str_pad(($index * 2) + 1, 4, '0', STR_PAD_LEFT),
            'amount' => $dpAmount,
            'invoice_date' => $jobOrder->order_date,
            'due_date' => date('Y-m-d', strtotime($jobOrder->order_date.' +14 days')),
            'paid_date' => $index < 4 ? date('Y-m-d', strtotime($jobOrder->order_date.' +7 days')) : null,
            'status' => $index < 4 ? 'paid' : ($index < 6 ? 'sent' : 'draft'),
            'notes' => 'Invoice DP 30%',
            'shipped_date' => $index < 4 ? date('Y-m-d', strtotime($jobOrder->order_date.' +3 days')) : null,
            'shipper' => $shippers[array_rand($shippers)],
            'buyer' => $jobOrder->customer_name,
            'po_number' => $poNumbers[array_rand($poNumbers)],
            'container' => $jobOrder->container_name,
            'deposit_discount' => $dpDeposit,
            'paid_amount' => $dpPaid,
        ]);

        // Pelunasan Invoice (only for completed or in_progress)
        if ($index < 5) {
            $finalAmount = $jobOrder->value * 0.7; // 70% Pelunasan
            $finalDeposit = $finalAmount * 0.3; // 30% dari final sebagai deposit
            $finalPaid = $index < 2 ? $finalAmount - $finalDeposit : ($index < 4 ? $finalAmount * 0.5 : 0);

            Invoice::create([
                'job_order_id' => $jobOrder->id,
                'invoice_number' => 'INV-'.date('Y', strtotime($jobOrder->order_date)).'-'.str_pad(($index * 2) + 2, 4, '0', STR_PAD_LEFT),
                'amount' => $finalAmount,
                'invoice_date' => date('Y-m-d', strtotime($jobOrder->due_date.' -7 days')),
                'due_date' => date('Y-m-d', strtotime($jobOrder->due_date.' +7 days')),
                'paid_date' => $index < 2 ? date('Y-m-d', strtotime($jobOrder->due_date.' +3 days')) : null,
                'status' => $index < 2 ? 'paid' : 'sent',
                'notes' => 'Invoice Pelunasan 70%',
                'shipped_date' => date('Y-m-d', strtotime($jobOrder->due_date.' -2 days')),
                'shipper' => $shippers[array_rand($shippers)],
                'buyer' => $jobOrder->customer_name,
                'po_number' => $poNumbers[array_rand($poNumbers)],
                'container' => $jobOrder->container_name,
                'deposit_discount' => $finalDeposit,
                'paid_amount' => $finalPaid,
            ]);
        }
    }

    private function createDeliveries(JobOrder $jobOrder, int $index): void
    {
        if ($index > 5) {
            return; // No deliveries for draft JOs
        }

        $methods = ['courier', 'expedition', 'self_pickup', 'cargo'];

        Delivery::create([
            'job_order_id' => $jobOrder->id,
            'delivery_number' => 'DO-'.date('Y', strtotime($jobOrder->order_date)).'-'.str_pad($index + 1, 4, '0', STR_PAD_LEFT),
            'shipment_method' => $methods[array_rand($methods)],
            'tracking_number' => $index < 5 ? 'TRK'.strtoupper(substr(md5($jobOrder->jo_number), 0, 10)) : null,
            'delivery_date' => date('Y-m-d', strtotime($jobOrder->due_date.' -3 days')),
            'received_date' => $index < 2 ? date('Y-m-d', strtotime($jobOrder->due_date.' -1 days')) : null,
            'status' => $index < 2 ? 'delivered' : ($index < 4 ? 'shipped' : 'preparing'),
            'notes' => 'Pengiriman ke alamat customer',
        ]);
    }

    private function createProductionProgress(JobOrder $jobOrder, int $index): void
    {
        $stages = [
            ['stage' => 'planning', 'progress' => 10],
            ['stage' => 'material_prep', 'progress' => 25],
            ['stage' => 'production', 'progress' => 50],
            ['stage' => 'quality_check', 'progress' => 70],
            ['stage' => 'finishing', 'progress' => 90],
            ['stage' => 'packing', 'progress' => 95],
            ['stage' => 'completed', 'progress' => 100],
        ];

        $maxStage = match (true) {
            $index < 2 => 7,    // Completed - all stages
            $index < 5 => 4,    // In progress - up to QC
            $index < 7 => 2,    // Pending - up to material_prep
            default => 1,       // Draft - only planning
        };

        for ($i = 0; $i < $maxStage; $i++) {
            ProductionProgress::create([
                'job_order_id' => $jobOrder->id,
                'report_date' => date('Y-m-d', strtotime($jobOrder->order_date.' +'.($i * 3).' days')),
                'progress_percentage' => $stages[$i]['progress'],
                'stage' => $stages[$i]['stage'],
                'material' => $stages[$i]['stage'] === 'material_prep' ? 'Bahan baku utama' : null,
                'packing' => in_array($stages[$i]['stage'], ['packing', 'completed'], true) ? 'Carton standar' : null,
                'description' => 'Progress tahap '.ucfirst(str_replace('_', ' ', $stages[$i]['stage'])),
                'issues' => $i === 2 && $index === 3 ? 'Terjadi delay karena mesin maintenance' : null,
                'solution' => $i === 2 && $index === 3 ? 'Jadwalkan maintenance dan alihkan mesin cadangan' : null,
            ]);
        }
    }

    private function createOtherCosts(JobOrder $jobOrder, int $index): void
    {
        $categories = ['maintenance', 'utilities', 'admin', 'other'];

        $otherCostData = [
            [
                'category' => 'shipping',
                'description' => 'Biaya maintenance mesin untuk produksi',
                'amount' => 750000,
            ],
            [
                'category' => 'tax',
                'description' => 'Biaya listrik produksi',
                'amount' => 500000,
            ],
        ];

        foreach ($otherCostData as $i => $data) {
            if ($index < 5) { // Only for non-draft JOs
                OtherCost::create([
                    'job_order_id' => $jobOrder->id,
                    'cost_number' => 'OC-'.date('Y', strtotime($jobOrder->order_date)).'-'.str_pad(($index * 2) + $i + 1, 4, '0', STR_PAD_LEFT),
                    'category' => $data['category'],
                    'description' => $data['description'],
                    'amount' => $data['amount'],
                    'cost_date' => date('Y-m-d', strtotime($jobOrder->order_date.' +'.($i * 5).' days')),
                    'status' => $index < 3 ? 'paid' : 'approved',
                ]);
            }
        }
    }

    private function createManPowers(JobOrder $jobOrder, array $employees, int $index): void
    {
        if ($index > 6) {
            return; // No manpower for newest drafts
        }

        $productionEmployees = array_filter($employees, fn ($e) => $e->department === 'production');
        $productionEmployees = array_values($productionEmployees);

        $daysWorked = match (true) {
            $index < 2 => 10,   // Completed
            $index < 5 => 5,    // In progress
            default => 2,       // Pending/Draft
        };

        for ($day = 0; $day < $daysWorked; $day++) {
            $employee = $productionEmployees[array_rand($productionEmployees)];

            // Determine payment type based on employee type
            if ($employee->type === 'borongan') {
                $quantity = rand(10, 50);
                $ratePerUnit = rand(5000, 15000);

                ManPower::create([
                    'job_order_id' => $jobOrder->id,
                    'employee_id' => $employee->id,
                    'work_date' => date('Y-m-d', strtotime($jobOrder->order_date.' +'.($day + 1).' days')),
                    'payment_type' => 'borongan',
                    'hours_worked' => 0,
                    'rate_per_hour' => 0,
                    'quantity' => $quantity,
                    'rate_per_unit' => $ratePerUnit,
                    'total_cost' => $quantity * $ratePerUnit,
                    'description' => 'Pekerjaan borongan hari ke-'.($day + 1),
                ]);
            } else {
                $hoursWorked = rand(6, 10);
                $ratePerHour = 50000;

                ManPower::create([
                    'job_order_id' => $jobOrder->id,
                    'employee_id' => $employee->id,
                    'work_date' => date('Y-m-d', strtotime($jobOrder->order_date.' +'.($day + 1).' days')),
                    'payment_type' => 'hourly',
                    'hours_worked' => $hoursWorked,
                    'rate_per_hour' => $ratePerHour,
                    'quantity' => 0,
                    'rate_per_unit' => 0,
                    'total_cost' => $hoursWorked * $ratePerHour,
                    'description' => 'Pekerjaan produksi hari ke-'.($day + 1),
                ]);
            }
        }
    }

    private function createSalaries(JobOrder $jobOrder, array $employees, int $index): void
    {
        if ($index > 4) {
            return; // Only create salaries for older JOs
        }

        $periods = ['2025-10', '2025-11', '2025-12', '2026-01'];
        $period = $periods[min($index, count($periods) - 1)];

        // Only create for a few employees per JO to avoid duplicates
        $selectedEmployees = array_slice($employees, $index * 2, 2);

        foreach ($selectedEmployees as $employee) {
            if ($employee->type === 'daily') {
                continue;
            }

            $basicSalary = $employee->base_salary;
            $allowance = rand(500000, 1000000);
            $overtime = rand(0, 5) * 100000;
            $deduction = rand(0, 3) * 100000;
            $total = $basicSalary + $allowance + $overtime - $deduction;

            Salary::create([
                'job_order_id' => rand(0, 1) ? $jobOrder->id : null, // Some salaries not linked to JO
                'employee_id' => $employee->id,
                'period' => $period,
                'basic_salary' => $basicSalary,
                'allowance' => $allowance,
                'overtime' => $overtime,
                'deduction' => $deduction,
                'total' => $total,
                'payment_date' => $index < 3 ? date('Y-m-d', strtotime($period.'-25')) : null,
                'status' => $index < 3 ? 'paid' : ($index < 4 ? 'approved' : 'pending'),
            ]);
        }
    }
}
