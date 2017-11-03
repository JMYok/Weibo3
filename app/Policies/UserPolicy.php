<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    //限制不同用户间的恶意操作
    public function update(User $currentUser,User $user)
    {
        return $currentUser->id === $user->id;
    }

    //管理员权限
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
