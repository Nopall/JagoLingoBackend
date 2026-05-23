<div>
    <a href="{{ route('product.form-edit', $id) }}" class="btn btn-sm btn-warning"><span class="fa fa-pencil"></span> Edit</a>
    <a href="{{ route('product.image', $id) }}" class="btn btn-sm btn-warning"><span class="fa fa-image"></span> Image</a>
    <button id="btn-delete-product" class="btn btn-sm btn-danger" onclick="deleteById({{ $id }})">
        <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none" role="status"></div>
        <span class="fa fa-trash"></span>
        Delete
    </button>
</div>
