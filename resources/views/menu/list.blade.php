@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('menu.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="menu-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Menu Name</th>
                        <th>Icon</th>
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
            $('#menu-table').DataTable({
                serverSide: true,
                ajax: '{{ route('menu.list') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'icon',
                        name: 'icon'
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
            $("#btn-delete-menu").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/menu/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#menu-table').DataTable().draw();
        }
    </script>
@endpush
