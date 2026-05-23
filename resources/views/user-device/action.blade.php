<div>
    @php
        $product_id = request()->route('id'); // Mendapatkan nilai 'id' dari URL
    @endphp
    
    <button id="btn-delete-product" class="btn btn-sm btn-danger" onclick="deleteImageById({{ $id }})">
        <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none" role="status"></div>
        <span class="fa fa-trash"></span>
        Delete
    </button>
</div>
