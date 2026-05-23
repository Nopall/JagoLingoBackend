@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('package.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="kecamatan-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Is Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kecamatan-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.package.list') }}',
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
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

        async function deleteById(id) {
            $("#btn-delete-kecamatan").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/package/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#kecamatan-table').DataTable().draw();
        }
        
        async function toggleActive(id) {
        try {
            const response = await axios.patch(`/master/package/toggle-active/${id}`);
            Swal.fire({
                icon: 'success',
                title: response.data.message,
                showConfirmButton: false,
                timer: 1200
            });
            $('#kecamatan-table').DataTable().ajax.reload();
        } catch (err) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat mengubah status.', 'error');
        }
    }


    </script>
@endpush
