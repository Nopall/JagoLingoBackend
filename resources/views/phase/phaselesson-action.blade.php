<div class="d-flex gap-1">
    @php $product_id = request()->route('id'); @endphp
    <a href="{{ route('phaselesson.form-edit', ['id' => $product_id, 'id_image' => $id]) }}" class="btn btn-sm btn-outline-primary" title="Edit">
        <i class="bx bx-edit-alt"></i>
    </a>
    <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteLesson({{ $id }})" title="Hapus">
        <i class="bx bx-trash"></i>
    </button>
</div>
