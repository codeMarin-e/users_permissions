<?php
	return [
		'install' => [
            'php artisan db:seed --class="\Marinar\UsersPermissions\Database\Seeders\MarinarUsersPermissionsInstallSeeder"',
		],
		'remove' => [
            'php artisan db:seed --class="\Marinar\UsersPermissions\Database\Seeders\MarinarUsersPermissionsRemoveSeeder"',
        ]
	];
