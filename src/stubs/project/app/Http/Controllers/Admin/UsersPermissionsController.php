<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersPermissionsController extends Controller {
    public function __construct() {
        if(!request()->route()) return;

        $this->table = Permission::getModel()->getTable();
        $this->roles_table = Role::getModel()->getTable();
        $this->routeNamespace = Str::before(request()->route()->getName(), '.users');
        View::composer('admin/users/*', function($view)  {
            $viewData = [
                'route_namespace' => $this->routeNamespace,
            ];
            // @HOOK_VIEW_COMPOSERS
            $view->with($viewData);
        });
        // @HOOK_CONSTRUCT
    }

    private function groupingPermissions($permissions) {
        $return = [];
        foreach($permissions as $permission) {
            $nameParts = explode('.', $permission->name);
            $nameGroup = \Illuminate\Support\Str::plural($nameParts[0]);
            if(!isset($return[$nameGroup])) $return[$nameGroup] = [];
            $return[$nameGroup][] = $permission;
        }
        return $return;
    }

    public function permissions($chUser) {
        $viewData = [];
        $viewData['inputBag'] = 'permissions';
        $viewData['guards'] = array_keys( config("auth.guards") );
        $viewData['permissions'] = Permission::query();
        $usedGuard = reset( $viewData['guards'] );
        if(request()->has('guard')) {
            $filterGuard = request()->get('guard');
            if (in_array($filterGuard, $viewData['guards'])) {
                $usedGuard = $filterGuard;
            }
        }
        $viewData['permissions'] = $this->groupingPermissions( $viewData['permissions']
            ->where('guard_name', $usedGuard)
            ->orderBy($this->table.".id", 'ASC')
            ->get() );

        $viewData['chUser'] = $chUser;
        $viewData['chGuard'] = $usedGuard;
        $viewData['rolesPermissionIds'] = $chUser->getPermissionsViaRoles()->pluck('id');
        if($chUser->hasRole('Super Admin', 'admin')) {
            $viewData['checkedPermissions'] = $viewData['permissions'];
        } else {
            $viewData['checkedPermissions'] = $this->groupingPermissions( $chUser->getAllPermissions()
                ->filter(function($permission) use ($usedGuard) {
                    return $permission->guard_name  == $usedGuard;
                })->sortBy('id') );
        }
        if(session('permissionsUpdated')) {
            session()->forget('permissionsUpdated');
            $viewData['permissionsUpdated'] = 1;
        }

        // @HOOK_PERMISSIONS
        return view('admin/users/user_permissions_permissions', $viewData);

    }

    public function setPermissions($chUser, Request $request) {
        $inputs = $request->all();
        $messages = Arr::dot((array)trans('admin/users/users_permissions.validation'));
        $validatedMergeData = [];
        $rules = [
            'guard' => ['required', Rule::in( array_keys( config('auth.guards' )) )],
            'permissions' => ['nullable']
        ];

        // @HOOK_VALIDATE

        $validatedData = array_merge(Validator::make($inputs, $rules, $messages)->validateWithBag('permissions'), $validatedMergeData);
        $chUser->permissions()->detach($chUser->permissions()->where('guard_name', $validatedData['guard'])->get()->pluck('id')->toArray());
        $chUser->givePermissionTo($validatedData['permissions']?? []);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // @HOOK_UPDATE_VALIDATE

        // @HOOK_UPDATE_END
        session()->flash('permissionsUpdated', 1);
        return $this->permissions($chUser);
    }
}
