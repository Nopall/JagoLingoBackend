@extends('layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dropzone.css') }}" />
@endpush

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-xl-6">
            <div class="menud mb-4">
                <div class="menud-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product</h5>
                    <small class="text-muted float-end">Create new Product</small>
                </div>
                <div class="menud-body">
                    <form id="productForm" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                        <div class="mb-3">
                            <label class="form-label" for="name">Product name</label>
                            <input type="text" class="form-control" id="name" placeholder="Product name"
                                name="name" value="{{ $product->name ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="commodity">Pilih Commodity</label>
                            <select class="form-select" id="commodity" name="commodity">
                                @foreach ($commodities as $commodity)
                                    <option value="{{ $commodity->id }}" {{ isset($product) && $product->commodity_id == $commodity->id ? 'selected' : '' }}>
                                        {{ $commodity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Product Description</label>
                            <input type="text" class="form-control" id="description" placeholder="Product description"
                                name="description" value="{{ $product->description ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Product Price</label>
                            <input type="number" class="form-control" id="price" placeholder="Product price"
                                name="price" value="{{ $product->price ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Product Weight</label>
                            <input type="number" class="form-control" id="weight" placeholder="Product weight"
                                name="price" value="{{ $product->weight ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Product Uom/Satuan</label>
                            <input type="text" class="form-control" id="uom" placeholder="Product uom / satuan"
                                name="uom" value="{{ $product->uom ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Contact Person</label>
                            <input type="text" class="form-control" id="contact" placeholder="Contact Person"
                                name="contact" value="{{ $product->contact ?? '' }}">
                        </div>
                        <button id="btn-submit-product" type="submit" class="btn btn-primary">
                            <div id="loading-indicator" class="spinner-border spinner-border-sm text-default d-none"
                                role="status"></div> Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/libs/dropzone/dropzone.js') }}"></script>
    <script>
        (function() {
            document.querySelector('#productForm').addEventListener('submit', async function(event) {
                    $("#btn-submit-product").prop("disabled", true);
                    $("#loading-indicator").removeClass("d-none");
                    const isEdit = '{{ isset($product) }}'

                    event.preventDefault();

                    const productName = document.querySelector('#name').value;
                    const productDescription = document.querySelector('#description').value;
                    const productPrice = document.querySelector('#price').value;
                    const productWeight = document.querySelector('#weight').value;
                    const productUom = document.querySelector('#uom').value;
                    const contact = document.querySelector('#contact').value;
                    const commodity_id = document.querySelector('#commodity').value;

                    const formData = new FormData();
                    formData.append('name', productName);
                    formData.append('description', productDescription);
                    formData.append('price', productPrice);
                    formData.append('weight', productWeight);
                    formData.append('uom', productUom);
                    formData.append('contact', contact);
                    formData.append('commodity_id', commodity_id);

                    

                    let url = "{{ route('product.create') }}";
                    @if (isset($product->id))
                        url = "{{ route('product.update', $product->id) }}";
                    @endif

                    const response = await httpClient.post(url, formData);

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $("#btn-submit-product").prop("disabled", false);
                    $("#loading-indicator").addClass("d-none");
                    window.location = '{{ route('master.product.list') }}';
                });
        })();
    </script>
@endpush
