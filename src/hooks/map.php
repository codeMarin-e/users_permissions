<?php
return [
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'config','marinar_users.php']) => [
        "// @HOOK_USER_CONFIGS_ADDONS" => "\t\t\\Marinar\\UsersPermissions\\MarinarUsersPermissions::class, \n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Http', 'Controllers', 'Admin', 'UserController.php']) => [
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'resources', 'views', 'admin', 'users', 'user.blade.php']) => [
        "{{-- @HOOK_USER_ADDON_BUTTONS --}}" => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_USER_ADDON_BUTTONS.blade.php']),
        "{{-- @HOOK_USER_ADDONS --}}" => "@if(isset(\$chUser) && \$authUser->can('users_permissions_view')) \n @include('admin/users/user_permissions') \n @endif",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'routes', 'admin', 'users.php']) => [
        "// @HOOK_USERS_ROUTES" => implode(DIRECTORY_SEPARATOR, [__DIR__, 'HOOK_USERS_ROUTES.php']),
    ],
];
