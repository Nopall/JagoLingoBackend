@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('car.form-create') }}" class="btn btn-md btn-primary"><span class="fa fa-plus"></span> Create</a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="car-brand-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>Brand Name</th>
                        <th>Logo</th>
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
            $('#car-brand-table').DataTable({
                serverSide: true,
                ajax: '{{ route('car.list') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'logo',
                        name: 'logo'
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
            $("#btn-delete-brand").prop("disabled", true);
            $("#loading-indicator").removeClass("d-none");
            const response = await httpClient.delete(`/master/car/brand/${id}`);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: response.message,
                showConfirmButton: false,
                timer: 1500
            });
            $("#submit-btn").prop("disabled", false);
            $("#loading-indicator").addClass("d-none");
            $('#car-brand-table').DataTable().draw();
        }
    </script>
@endpush
