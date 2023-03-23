<?php
Route::get('/permissions/{chUser}', [\App\Http\Controllers\Admin\UsersPermissionsController::class, 'permissions'])
    ->middleware('can:users_permissions_view')
    ->name('permissions');
Route::patch('/permissions/{chUser}', [\App\Http\Controllers\Admin\UsersPermissionsController::class, 'setPermissions'])
    ->middleware('can:users_permissions_update')
    ->name('set_permissions');
