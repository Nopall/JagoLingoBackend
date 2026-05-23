@extends('layouts.index')

@section('title', 'Lesson')

@section('content')

@php $id = request()->route('id'); @endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('master.phase.list') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        <h4 class="fw-bold mb-1">Lesson</h4>
        <p class="text-muted mb-0 small">Kelola lesson dalam phase ini</p>
    </div>
    <a href="{{ route('phaselesson.form-create', ['id' => $id]) }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Tambah Lesson
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-datatable table-responsive pt-0">
        <table id="lesson-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Audio</th>
                    <th>Gambar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('js')
<script>
    var phaseId = {{ $id }};

    $(document).ready(function () {
        $('#lesson-table').DataTable({
            serverSide: true,
            ajax: '{{ route('phase.lesson', ['id' => $id]) }}',
            columns: [
                { data: 'title', name: 'title' },
                { data: 'type', name: 'type' },
                { data: 'audio', name: 'audio' },
                { data: 'image', name: 'image' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    async function confirmDeleteLesson(id) {
        const result = await Swal.fire({
            title: 'Hapus Lesson?',
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
            const response = await httpClient.delete(`/master/phase/lesson/${id}`);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            $('#lesson-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
        }
    }
</script>
@endpush
