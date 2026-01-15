<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProductionProgress;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductionProgressPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductionProgress');
    }

    public function view(AuthUser $authUser, ProductionProgress $productionProgress): bool
    {
        return $authUser->can('View:ProductionProgress');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductionProgress');
    }

    public function update(AuthUser $authUser, ProductionProgress $productionProgress): bool
    {
        return $authUser->can('Update:ProductionProgress');
    }

    public function delete(AuthUser $authUser, ProductionProgress $productionProgress): bool
    {
        return $authUser->can('Delete:ProductionProgress');
    }

    public function restore(AuthUser $authUser, ProductionProgress $productionProgress): bool
    {
        return $authUser->can('Restore:ProductionProgress');
    }

    public function forceDelete(AuthUser $authUser, ProductionProgress $productionProgress): bool
    {
        return $authUser->can('ForceDelete:ProductionProgress');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProductionProgress');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProductionProgress');
    }

    public function replicate(AuthUser $authUser, ProductionProgress $productionProgress): bool
    {
        return $authUser->can('Replicate:ProductionProgress');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProductionProgress');
    }

}