<div>
    @php
        $product_id = request()->route('id'); // Mendapatkan nilai 'id' dari URL
    @endphp
    <a href="{{ route('productimage.form-edit', ['id' => $product_id, 'id_image' => $id]) }}" class="btn btn-sm btn-warning"><span class="fa fa-pencil"></span> Edit</a>
    <button id="btn-delete-product" class="btn btn-sm btn-danger" onclick="deleteImageById({{ $id }})">
        <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none" role="status"></div>
        <span class="fa fa-trash"></span>
        Delete
    </button>
</div>
