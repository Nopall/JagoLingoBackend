<div class="d-flex gap-1">
    <a href="{{ route('course.form-edit', $id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bx bx-edit-alt"></i>
    </a>
    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $id }})" title="Hapus">
        <i class="bx bx-trash"></i>
    </button>
</div>
