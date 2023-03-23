@pushonceOnReady('below_js_on_ready')
<script>
    var $permisionsCon = $('#users_permissions');
    $permisionsCon.on('hide.bs.collapse', function () {
        $permisionsCon.removeClass('btn-warning');
        $permisionsCon.addClass('btn-primary');
    });
    $permisionsCon.on('show.bs.collapse', function () {
        $('.js_usersAddonForm.btn-warning').first().click();
        $('#users_permissions_btn').removeClass('btn-primary');
        $('#users_permissions_btn').addClass('btn-warning');
        $permisionsCon.trigger('show_permissions');
    });

    var permissionsLoader = $permisionsCon.html();
    var permissionsLoading = false;
    $permisionsCon.on('show_permissions', function(e, data) {
        if(permissionsLoading) return false;
        $permisionsCon.html( permissionsLoader );
        permissionsLoading = true;
        $.ajax({
            url: $permisionsCon.attr('data-src'),
            method: 'GET',
            timeout: 0,
            data: data || {},
            dataType: 'html',
            error: function(jqXHR, textStatus, errorThrown){
                if(jqXHR.responseJSON && jqXHR.responseJSON.message) alert(jqXHR.responseJSON.message);
                else alert('Error');
            },
            success: function(response) {
                $permisionsCon.html( $(response).filter('#users_permissions').first().html() );
                permissionsLoading = false;
            }
        });
    });

    $(document).on('change', '.js_permissions_all', function() {
        var $this = $(this);
        var group = $this.attr('data-group');
        if($this.prop('checked')) {
            $('input[data\\-group="'+ group +'"]').prop('checked', true);
        }
    });
    $(document).on('change', '.js_permission', function() {
        var $this = $(this);
        var group = $this.attr('data-group');
        if(!$this.prop('checked')) {
            $('.js_permissions_all[data\\-group="'+ group +'"]').prop('checked', false);
        }
    });
    $(document).on('change', '#js_permissions_guard', function() {
        var $this = $(this);
        $permisionsCon.trigger('show_permissions', {
            'guard': $this.val()
        });
    });
</script>
@endpushonceOnReady


@can('users_permissions_update')
@pushonceOnReady('below_js_on_ready')
<script>
    $(document).on('click', '#js_permissions_update', function() {
        var $this = $(this);
        var data = {
            guard: $("#js_permissions_guard").val(),
            permissions: [],
        };
        $('.js_permission:checked').each(function(index, el) {
            if($(el).is('[disabled]')) return;
            data['permissions'].push( parseInt( $(el).attr('data-id') ) );
        });

        $permisionsCon.html( permissionsLoader );
        $.ajax({
            url: $this.attr('data-src'),
            method: 'PATCH',
            timeout: 0,
            data: JSON.stringify( data ),
            contentType: "application/json",
            dataType: 'html',
            error: function(jqXHR, textStatus, errorThrown){
                if(jqXHR.responseJSON && jqXHR.responseJSON.message) alert(jqXHR.responseJSON.message);
                else alert('Error');
            },
            success: function(response) {
                $permisionsCon.html( $(response).filter('#users_permissions').first().html() );
            }
        });
    });
</script>
@endpushonceOnReady
@endcan

<div class="card card-body collapse mt-2" id="users_permissions"
     data-src="{{route("{$route_namespace}.users.permissions", [$chUser])}}">
    <div class="spinner-border spinner-border-sm text-warning" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
