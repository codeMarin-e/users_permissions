@if(isset($chUser) && $authUser->can("users_permissions_view"))
    <button class="btn btn-primary mr-2 js_usersAddonForm"
            data-toggle="collapse"
            id="users_permissions_btn"
            data-target="#users_permissions"
            type="button"
            role="button"
            aria-expanded="false"
            aria-controls="users_permissions">@lang('admin/users/user_permissions.button')</button>
@endif
