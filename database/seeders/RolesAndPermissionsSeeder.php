<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all models
        $models = [
            'User',
            'Role',
            'JobOrder',
            'PurchaseOrder',
            'Expense',
            'Salary',
            'Invoice',
            'Delivery',
            'ManPower',
            'ProductionProgress',
            'Employee',
            'OtherCost',
        ];

        // Define all permission actions
        $actions = [
            'ViewAny',
            'View',
            'Create',
            'Update',
            'Delete',
            'Restore',
            'ForceDelete',
            'DeleteAny',
            'RestoreAny',
            'ForceDeleteAny',
        ];

        // Create all permissions
        foreach ($models as $model) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}:{$model}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Define role permissions matrix
        $rolePermissions = [
            'super_admin' => '*', // All permissions

            'hrd' => [
                // Full CRUD for Employee, ManPower, Salary
                'ViewAny:Employee', 'View:Employee', 'Create:Employee', 'Update:Employee', 'Delete:Employee',
                'ViewAny:ManPower', 'View:ManPower', 'Create:ManPower', 'Update:ManPower', 'Delete:ManPower',
                'ViewAny:Salary', 'View:Salary', 'Create:Salary', 'Update:Salary', 'Delete:Salary',
                // View only for JobOrder
                'ViewAny:JobOrder', 'View:JobOrder',
            ],

            'marketing' => [
                // Full CRUD for JobOrder, Invoice, Delivery
                'ViewAny:JobOrder', 'View:JobOrder', 'Create:JobOrder', 'Update:JobOrder', 'Delete:JobOrder',
                'ViewAny:Invoice', 'View:Invoice', 'Create:Invoice', 'Update:Invoice', 'Delete:Invoice',
                'ViewAny:Delivery', 'View:Delivery', 'Create:Delivery', 'Update:Delivery', 'Delete:Delivery',
                // View only for related modules
                'ViewAny:PurchaseOrder', 'View:PurchaseOrder',
                'ViewAny:ProductionProgress', 'View:ProductionProgress',
            ],

            'purchasing' => [
                // Full CRUD for PurchaseOrder
                'ViewAny:PurchaseOrder', 'View:PurchaseOrder', 'Create:PurchaseOrder', 'Update:PurchaseOrder', 'Delete:PurchaseOrder',
                // View only for JobOrder
                'ViewAny:JobOrder', 'View:JobOrder',
            ],

            'accounting' => [
                // Full CRUD for Expense, Invoice, OtherCost
                'ViewAny:Expense', 'View:Expense', 'Create:Expense', 'Update:Expense', 'Delete:Expense',
                'ViewAny:Invoice', 'View:Invoice', 'Create:Invoice', 'Update:Invoice', 'Delete:Invoice',
                'ViewAny:OtherCost', 'View:OtherCost', 'Create:OtherCost', 'Update:OtherCost', 'Delete:OtherCost',
                // View for Salary (untuk verifikasi pembayaran)
                'ViewAny:Salary', 'View:Salary',
                // View only for JobOrder
                'ViewAny:JobOrder', 'View:JobOrder',
                // View only for Employee (for salary reference)
                'ViewAny:Employee', 'View:Employee',
                // View for Delivery
                'ViewAny:Delivery', 'View:Delivery',
            ],

            'ppic' => [
                // Full CRUD for ProductionProgress, ManPower
                'ViewAny:ProductionProgress', 'View:ProductionProgress', 'Create:ProductionProgress', 'Update:ProductionProgress', 'Delete:ProductionProgress',
                'ViewAny:ManPower', 'View:ManPower', 'Create:ManPower', 'Update:ManPower', 'Delete:ManPower',
                // Create & update Delivery, view only for JobOrder & PurchaseOrder
                'ViewAny:JobOrder', 'View:JobOrder',
                'ViewAny:PurchaseOrder', 'View:PurchaseOrder',
                'ViewAny:Delivery', 'View:Delivery', 'Create:Delivery', 'Update:Delivery',
            ],
        ];

        // Create roles and assign permissions
        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($permissions === '*') {
                // Super admin gets all permissions
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($permissions);
            }
        }
    }
}
