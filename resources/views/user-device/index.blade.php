@extends('layouts.index')

@section('content')
@php
    $id = $id; // Mendapatkan nilai 'id' dari controller
@endphp
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="product-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Device ID</th>
                        <th>Device Type</th>
                        <th>Device Brand</th>
                        <th>Device Model</th>
                        <th>Os Version</th>
                        <th>Build Number</th>
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
                ajax: '{{ route('user.userdevice.list', ['id' => $id]) }}',
                columns: [
                    {
                        data: 'device_id',
                        name: 'device_id'
                    },
                    {
                        data: 'device_type',
                        name: 'device_type'
                    },
                    {
                        data: 'device_brand',
                        name: 'device_brand'
                    },
                    {
                        data: 'device_model',
                        name: 'device_model'
                    },
                    {
                        data: 'os_version',
                        name: 'os_version'
                    },
                    {
                        data: 'build_number',
                        name: 'build_number'
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
            const response = await httpClient.delete(`${id}`);
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
