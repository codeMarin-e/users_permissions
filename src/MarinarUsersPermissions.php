<?php
namespace Marinar\UsersPermissions;

use Marinar\UsersPermissions\Database\Seeders\MarinarUsersPermissionsInstallSeeder;

class MarinarUsersPermissions {

    public static function getPackageMainDir() {
        return __DIR__;
    }

    public static function injects() {
        return MarinarUsersPermissionsInstallSeeder::class;
    }
}
