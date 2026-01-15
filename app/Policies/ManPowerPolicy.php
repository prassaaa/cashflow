<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ManPower;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManPowerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ManPower');
    }

    public function view(AuthUser $authUser, ManPower $manPower): bool
    {
        return $authUser->can('View:ManPower');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ManPower');
    }

    public function update(AuthUser $authUser, ManPower $manPower): bool
    {
        return $authUser->can('Update:ManPower');
    }

    public function delete(AuthUser $authUser, ManPower $manPower): bool
    {
        return $authUser->can('Delete:ManPower');
    }

    public function restore(AuthUser $authUser, ManPower $manPower): bool
    {
        return $authUser->can('Restore:ManPower');
    }

    public function forceDelete(AuthUser $authUser, ManPower $manPower): bool
    {
        return $authUser->can('ForceDelete:ManPower');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ManPower');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ManPower');
    }

    public function replicate(AuthUser $authUser, ManPower $manPower): bool
    {
        return $authUser->can('Replicate:ManPower');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ManPower');
    }

}