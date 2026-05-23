@extends('layouts.index')

@section('title', 'Course')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Course</h4>
        <p class="text-muted mb-0 small">Kelola semua kursus yang tersedia di platform</p>
    </div>
    <a href="{{ route('course.form-create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Tambah Course
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-datatable table-responsive pt-0">
        <table id="course-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Premium</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#course-table').DataTable({
            serverSide: true,
            ajax: '{{ route('master.course.list') }}',
            columns: [
                { data: 'title', name: 'title' },
                { data: 'description', name: 'description' },
                { data: 'is_premium', name: 'is_premium' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    async function confirmDelete(id) {
        const result = await Swal.fire({
            title: 'Hapus Course?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });
        if (!result.isConfirmed) return;

        try {
            const response = await httpClient.delete(`/master/course/${id}`);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            $('#course-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
        }
    }
</script>
@endpush
