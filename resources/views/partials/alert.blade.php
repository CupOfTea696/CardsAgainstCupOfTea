<div class="alert alert-{{ array_get($alert, 'level', 'info') }} alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ $alert['message'] }}
</div>
