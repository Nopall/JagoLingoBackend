@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('leaflet.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="leaflet-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Deksripsi</th>
                        <th>Gambar</th>
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
            $('#leaflet-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.leaflet.list') }}',
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

        async function deleteById(id) {
            $("#btn-delete-leaflet").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/leaflet/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: true,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#leaflet-table').DataTable().draw();
        }
    </script>
@endpush
