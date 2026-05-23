<div>
    <a href="{{ route('user.userdevice.list', $id) }}" class="btn btn-sm btn-warning"><span class="fa fa-image"></span> Device</a>
    <a href="{{ route('user.form-edit', $id) }}" class="btn btn-sm btn-warning"><span class="fa fa-key"></span> Password</a>
    <button id="btn-delete" class="btn btn-sm btn-danger" onclick="deleteById({{ $id }})">
        <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none" role="status"></div>
        <span class="fa fa-trash"></span>
        Delete
    </button>
</div>
