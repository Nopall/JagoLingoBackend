@extends('layouts.index')

@section('content')
    <div>
        <a href="{{ route('package.phase.form-create', $package->id) }}" class="btn btn-md btn-primary">
            <span class="fa fa-plus"></span> Create
        </a>
    </div>
    <div class="card mt-3">
        <div class="card-datatable table-responsive">
            <table id="phase-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>Phase Name</th>
                        <th>Deskripsi</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($phases as $phase)
                        <tr>
                            <td>{{ $phase->phase_title }}</td>
                            <td>{{ $phase->description }}</td>
                            <td>{{ $phase->course->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('package.phase.form-edit', ['package_id' => $phase->package_id, 'id' => $phase->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('phase.lesson', $phase->id) }}" class="btn btn-sm btn-warning"><span class="fa fa-image"></span> Lesson</a>

                                <button class="btn btn-sm btn-danger" onclick="deleteById('{{ $phase->id }}')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        async function deleteById(id) {
            const confirmDelete = confirm("Yakin ingin menghapus phase ini?");
            if (!confirmDelete) return;

            try {
                const response = await httpClient.delete(`/master/phase/${id}`);
                alert(response.message);
                location.reload(); // Refresh halaman setelah delete
            } catch (e) {
                alert("Gagal menghapus phase.");
            }
        }
    </script>
@endpush
