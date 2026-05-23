@extends('layouts.index')

@section('content')
@php
    $id = $id; // Mendapatkan nilai 'id' dari controller
@endphp
    <div>
        <a href="{{ route('phaselesson.form-create', ['id' => $id]) }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Add Lesson</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="lesson-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Audio</th>
                        <th>Image</th>
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
            $('#lesson-table').DataTable({
                serverSide: true,
                ajax: '{{ route('phase.lesson', ['id' => $id]) }}',
                columns: [
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'audio',
                        name: 'audio'
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

        async function deleteLessonById(id) {
            $("#btn-delete-lesson").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/phase/lesson/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#lesson-table').DataTable().draw();
        }
    </script>
@endpush
