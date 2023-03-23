<?php
use App\Models\User;
use Illuminate\Support\Facades\Gate;

Gate::define('users_permissions_view', function (User $user) {
    if($user->hasRole('Super Admin', 'admin') ) return true;
    return $user->hasPermissionTo('users_permissions.view', request()->whereIam());
});
Gate::define('users_permissions_update', function (User $user) {
    if($user->hasRole('Super Admin', 'admin') ) return true;
    return $user->hasPermissionTo('users_permissions.update', request()->whereIam());
});
