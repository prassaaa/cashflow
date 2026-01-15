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

            'management' => [
                // View only for all modules
                'ViewAny:JobOrder', 'View:JobOrder',
                'ViewAny:PurchaseOrder', 'View:PurchaseOrder',
                'ViewAny:Expense', 'View:Expense',
                'ViewAny:Salary', 'View:Salary',
                'ViewAny:Invoice', 'View:Invoice',
                'ViewAny:Delivery', 'View:Delivery',
                'ViewAny:ManPower', 'View:ManPower',
                'ViewAny:ProductionProgress', 'View:ProductionProgress',
                'ViewAny:Employee', 'View:Employee',
                'ViewAny:OtherCost', 'View:OtherCost',
            ],

            'sales' => [
                // Full CRUD for JobOrder
                'ViewAny:JobOrder', 'View:JobOrder', 'Create:JobOrder', 'Update:JobOrder', 'Delete:JobOrder',
                // View only for related modules
                'ViewAny:PurchaseOrder', 'View:PurchaseOrder',
                'ViewAny:Invoice', 'View:Invoice',
                'ViewAny:Delivery', 'View:Delivery',
                'ViewAny:ProductionProgress', 'View:ProductionProgress',
            ],

            'purchasing' => [
                // Full CRUD for PurchaseOrder
                'ViewAny:PurchaseOrder', 'View:PurchaseOrder', 'Create:PurchaseOrder', 'Update:PurchaseOrder', 'Delete:PurchaseOrder',
                // View only for JobOrder
                'ViewAny:JobOrder', 'View:JobOrder',
            ],

            'finance' => [
                // Full CRUD for Expense, Salary, Invoice, Delivery, OtherCost
                'ViewAny:Expense', 'View:Expense', 'Create:Expense', 'Update:Expense', 'Delete:Expense',
                'ViewAny:Salary', 'View:Salary', 'Create:Salary', 'Update:Salary', 'Delete:Salary',
                'ViewAny:Invoice', 'View:Invoice', 'Create:Invoice', 'Update:Invoice', 'Delete:Invoice',
                'ViewAny:Delivery', 'View:Delivery', 'Create:Delivery', 'Update:Delivery', 'Delete:Delivery',
                'ViewAny:OtherCost', 'View:OtherCost', 'Create:OtherCost', 'Update:OtherCost', 'Delete:OtherCost',
                // View only for JobOrder
                'ViewAny:JobOrder', 'View:JobOrder',
                // View only for Employee (for salary reference)
                'ViewAny:Employee', 'View:Employee',
            ],

            'hrd' => [
                // Full CRUD for Employee, ManPower
                'ViewAny:Employee', 'View:Employee', 'Create:Employee', 'Update:Employee', 'Delete:Employee',
                'ViewAny:ManPower', 'View:ManPower', 'Create:ManPower', 'Update:ManPower', 'Delete:ManPower',
                // View only for JobOrder, Salary
                'ViewAny:JobOrder', 'View:JobOrder',
                'ViewAny:Salary', 'View:Salary',
            ],

            'production' => [
                // Full CRUD for ProductionProgress
                'ViewAny:ProductionProgress', 'View:ProductionProgress', 'Create:ProductionProgress', 'Update:ProductionProgress', 'Delete:ProductionProgress',
                // View only for JobOrder, ManPower
                'ViewAny:JobOrder', 'View:JobOrder',
                'ViewAny:ManPower', 'View:ManPower',
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
