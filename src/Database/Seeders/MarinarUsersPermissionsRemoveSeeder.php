<?php
    namespace Marinar\UsersPermissions\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\UsersPermissions\MarinarUsersPermissions;
    use Spatie\Permission\Models\Permission;

    class MarinarUsersPermissionsRemoveSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_users_permissions';
            static::$packageDir = MarinarUsersPermissions::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoRemove();

            $this->refComponents->info("Done!");
        }

        public function clearMe() {
            $this->refComponents->task("Clear DB", function() {
                Permission::whereIn('name', [
                    'users_permissions.view',
                    'users_permissions.update',
                ])
                    ->where('guard_name', 'admin')
                    ->delete();
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                return true;
            });
        }
    }
