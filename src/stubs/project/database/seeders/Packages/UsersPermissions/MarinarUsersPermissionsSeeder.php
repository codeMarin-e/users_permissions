<?php
namespace Database\Seeders\Packages\UsersPermissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MarinarUsersPermissionsSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::upsert([
            ['guard_name' => 'admin', 'name' => 'users_permissions.view'],
            ['guard_name' => 'admin', 'name' => 'users_permissions.update'],
        ], ['guard_name','name']);
    }
}
