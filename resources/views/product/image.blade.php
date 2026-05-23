@extends('layouts.index')

@section('content')
@php
    $id = $id; // Mendapatkan nilai 'id' dari controller
@endphp
    <div>
        <a href="{{ route('productimage.form-create', ['id' => $id]) }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Add Image</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="product-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
@endsection

@push('js')
    @php
        $id = request()->route('id'); // Mendapatkan nilai 'id' dari URL
    @endphp
    <script>
        var id = {{ $id }};
        $(document).ready(function() {
            $('#product-table').DataTable({
                serverSide: true,
                ajax: '{{ route('product.image', ['id' => $id]) }}',
                columns: [
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        async function deleteImageById(id) {
            $("#btn-delete-product").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/product/image/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#product-table').DataTable().draw();
        }
    </script>
@endpush
