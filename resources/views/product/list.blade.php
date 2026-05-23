@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('product.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="product-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Deskripsi</th>
                        <th style="width: 150px;">Harga</th>
                        <th>Berat</th>
                        <th>Satuan</th>
                        <th>Contact</th>
                        <th>Komoditas</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#product-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.product.list') }}',
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data) {
                            // Format harga Rupiah
                            return formatNumber(data);
                        }
                    },
                    {
                        data: 'weight',
                        name: 'weight'
                    },
                    {
                        data: 'uom',
                        name: 'uom'
                    },
                    {
                        data: 'contact',
                        name: 'contact'
                    },
                    {
                        data: 'commodity',
                        name: 'commodity'
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

        function formatNumber(num) {
            return 'Rp ' + num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
        async function deleteById(id) {
            $("#btn-delete-product").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/product/${id}`);
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
