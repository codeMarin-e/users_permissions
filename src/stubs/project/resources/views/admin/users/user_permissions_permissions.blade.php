<div id="users_permissions">
    @isset($permissionsUpdated)
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>@lang('admin/users/user_permissions.updated')</strong>
            </div>
        </div>
    </div>
    @endisset
    <div class="row">
        {{-- GUARDS--}}
        <div class="form-group row col-lg-5">
            <label for="js_permissions_guard" class="col-form-label col-sm-2">@lang('admin/users/user_permissions.guard'):</label>
            <div class="col-sm-10">
                <select id="js_permissions_guard"
                        class="form-control">
                    @foreach($guards as $guard)
                        <option value="{{ $guard }}"
                                @if($chGuard === $guard) selected="selected" @endif
                        >{{$guard}}
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div id="js_permissions"
         class="row"
         data-src="{{route("{$route_namespace}.users.set_permissions", [$chUser])}}">
        @foreach($permissions as $group => $groupedPermissions)
            @php $checkedInThisGroup = collect($checkedPermissions[$group]?? [])->pluck('id');
            @endphp
            <div class="col-3">
                <div class="card" style="margin-bottom: 15px;">
                    <div class="card-header">
                        <div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox"
                                       id="{{$inputBag}}[permissions_all][{{$group}}]"
                                       value="1"
                                       data-group="{{$group}}"
                                       class="js_permissions_all form-check-input"
                                       @if($checkedInThisGroup->count() == count($groupedPermissions))checked="checked"@endif
                                />
                                <label for="{{$inputBag}}[permissions_all][{{$group}}]" class="form-check-label">@lang('admin/users/user_permissions.group_all') {{$group}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($groupedPermissions as $permission)
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox"
                                           data-group="{{$group}}"
                                           id="{{$inputBag}}[permissions][{{$permission->id}}]"
                                           data-id="{{$permission->id}}"
                                           value="1"
                                           class="form-check-input js_permission"
                                           @if($rolesPermissionIds->contains($permission->id))disabled="disabled"@endif
                                           @if($checkedInThisGroup->contains($permission->id))checked="checked"@endif
                                    />
                                    <label for="{{$inputBag}}[permissions][{{$permission->id}}]" class="form-check-label">{{$permission->name}}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form-group row">
        @can('users_permissions_update')
            <button class='btn btn-info mr-2'
                    type='button'
                    data-src="{{route("{$route_namespace}.users.set_permissions", [$chUser])}}"
                    id="js_permissions_update">@lang('admin/users/user_permissions.update')</button>
        @endcan
    </div>
</div>
