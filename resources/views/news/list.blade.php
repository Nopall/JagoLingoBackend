@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('news.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="news-table" class="display table table-striped">
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
            $('#news-table').DataTable({
                serverSide: true,
                ajax: '{{ route('master.news.list') }}',
                columns: [
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'image_url',
                        name: 'image_url'
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
            $("#btn-delete-news").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/news/${id}`);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response.message,
                showConfirmButton: true,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#news-table').DataTable().draw();
        }
    </script>
@endpush
