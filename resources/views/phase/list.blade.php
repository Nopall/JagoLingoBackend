@extends('layouts.index')

@section('title', 'Phase')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Phase</h4>
        <p class="text-muted mb-0 small">Kelola fase/bab dari setiap kursus</p>
    </div>
    <a href="{{ route('phase.form-create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Tambah Phase
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-datatable table-responsive pt-0">
        <table id="phase-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Phase</th>
                    <th>Deskripsi</th>
                    <th>Course</th>
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
        $('#phase-table').DataTable({
            serverSide: true,
            ajax: '{{ route('master.phase.list') }}',
            columns: [
                { data: 'phase_title', name: 'phase_title' },
                { data: 'description', name: 'description' },
                { data: 'course', name: 'course' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    async function confirmDelete(id) {
        const result = await Swal.fire({
            title: 'Hapus Phase?',
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
            const response = await httpClient.delete(`/master/phase/${id}`);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            $('#phase-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
        }
    }
</script>
@endpush
