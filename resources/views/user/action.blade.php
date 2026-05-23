<div class="d-flex gap-1">
    <a href="{{ route('user.userdevice.list', $id) }}" class="btn btn-sm btn-outline-info" title="Device">
        <i class="bx bx-devices"></i>
    </a>
    <a href="{{ route('user.form-edit', $id) }}" class="btn btn-sm btn-outline-warning" title="Ubah Password">
        <i class="bx bx-key"></i>
    </a>
    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $id }})" title="Hapus">
        <i class="bx bx-trash"></i>
    </button>
</div>
