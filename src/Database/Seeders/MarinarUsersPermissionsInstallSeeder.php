<?php
    namespace Marinar\UsersPermissions\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\UsersPermissions\MarinarUsersPermissions;

    class MarinarUsersPermissionsInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_users_permissions';
            static::$packageDir = MarinarUsersPermissions::getPackageMainDir();
        }

        public function run() {

            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }
    }
