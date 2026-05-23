@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('setting.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="setting-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Content</th>
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
            $('#setting-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.setting.list') }}',
                columns: [
                    {
                        data: 'teks',
                        name: 'teks'
                    },
                    {
                        data: 'content',
                        name: 'content'
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
            $("#btn-delete-setting").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/setting/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: true,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#setting-table').DataTable().draw();
        }
    </script>
@endpush
