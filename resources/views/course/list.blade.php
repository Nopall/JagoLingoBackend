@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('course.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="course-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Deksripsi</th>
                        <th>Premium</th>
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
            $('#course-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.course.list') }}',
                columns: [
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'is_premium',
                        name: 'is_premium'
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
            $("#btn-delete-course").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/course/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: true,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#course-table').DataTable().draw();
        }
    </script>
@endpush
