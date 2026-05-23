@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('phase.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="phase-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Phase Name</th>
                        <th>Deskripsi</th>
                        <th>Course</th>
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
            $('#phase-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.phase.list') }}',
                columns: [
                    {
                        data: 'phase_title',
                        name: 'phase_title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'course',
                        name: 'course'
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
            $("#btn-delete-phase").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/phase/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#phase-table').DataTable().draw();
        }
    </script>
@endpush
