<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OtherCost;
use Illuminate\Auth\Access\HandlesAuthorization;

class OtherCostPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OtherCost');
    }

    public function view(AuthUser $authUser, OtherCost $otherCost): bool
    {
        return $authUser->can('View:OtherCost');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OtherCost');
    }

    public function update(AuthUser $authUser, OtherCost $otherCost): bool
    {
        return $authUser->can('Update:OtherCost');
    }

    public function delete(AuthUser $authUser, OtherCost $otherCost): bool
    {
        return $authUser->can('Delete:OtherCost');
    }

    public function restore(AuthUser $authUser, OtherCost $otherCost): bool
    {
        return $authUser->can('Restore:OtherCost');
    }

    public function forceDelete(AuthUser $authUser, OtherCost $otherCost): bool
    {
        return $authUser->can('ForceDelete:OtherCost');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OtherCost');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OtherCost');
    }

    public function replicate(AuthUser $authUser, OtherCost $otherCost): bool
    {
        return $authUser->can('Replicate:OtherCost');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OtherCost');
    }

}