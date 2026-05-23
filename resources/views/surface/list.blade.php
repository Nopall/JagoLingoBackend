@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('pestisida.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="pestisida-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Seller</th>
                        <th>Contact Person</th>
                        <th>Photo</th>
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
            $('#pestisida-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.pestisida.list') }}',
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'seller',
                        name: 'seller'
                    },
                    {
                        data: 'contact_no',
                        name: 'contact_no'
                    },
                    {
                        data: 'photo',
                        name: 'photo'
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
            $("#btn-delete-pestisida").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/pestisida/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#pestisida-table').DataTable().draw();
        }
    </script>
@endpush
