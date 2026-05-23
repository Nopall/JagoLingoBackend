@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('pembiayaan.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="pembiayaan-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>CP 1</th>
                        <th>WA 1</th>
                        <th>CP 2</th>
                        <th>WA 2</th>
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
            $('#pembiayaan-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.pembiayaan.list') }}',
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'cp_1',
                        name: 'cp_1'
                    },
                    {
                        data: 'wa_1',
                        name: 'wa_1'
                    },
                    {
                        data: 'cp_2',
                        name: 'cp_2'
                    },
                    {
                        data: 'wa_2',
                        name: 'wa_2'
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
            $("#btn-delete-pembiayaan").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/pembiayaan/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#pembiayaan-table').DataTable().draw();
        }
    </script>
@endpush
