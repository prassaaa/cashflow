<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\HrdAttendance;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class HrdAttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HrdAttendance');
    }

    public function view(AuthUser $authUser, HrdAttendance $hrdAttendance): bool
    {
        return $authUser->can('View:HrdAttendance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HrdAttendance');
    }

    public function update(AuthUser $authUser, HrdAttendance $hrdAttendance): bool
    {
        return $authUser->can('Update:HrdAttendance');
    }

    public function delete(AuthUser $authUser, HrdAttendance $hrdAttendance): bool
    {
        return $authUser->can('Delete:HrdAttendance');
    }

    public function restore(AuthUser $authUser, HrdAttendance $hrdAttendance): bool
    {
        return $authUser->can('Restore:HrdAttendance');
    }

    public function forceDelete(AuthUser $authUser, HrdAttendance $hrdAttendance): bool
    {
        return $authUser->can('ForceDelete:HrdAttendance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HrdAttendance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HrdAttendance');
    }

    public function replicate(AuthUser $authUser, HrdAttendance $hrdAttendance): bool
    {
        return $authUser->can('Replicate:HrdAttendance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HrdAttendance');
    }
}
