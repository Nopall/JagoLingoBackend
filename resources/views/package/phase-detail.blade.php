@extends('layouts.index')

@section('title', 'Phase Package')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('master.package.list') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        <h4 class="fw-bold mb-1">Phase — {{ $package->name ?? '' }}</h4>
        <p class="text-muted mb-0 small">Kelola fase yang tersedia dalam paket ini</p>
    </div>
    <a href="{{ route('package.phase.form-create', $package->id) }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Tambah Phase
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Nama Phase</th>
                    <th>Deskripsi</th>
                    <th>Course</th>
                    <th class="text-center pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($phases as $phase)
                <tr>
                    <td class="ps-3 fw-medium">{{ $phase->phase_title }}</td>
                    <td class="text-muted small">{{ $phase->description ?? '-' }}</td>
                    <td>
                        <span class="badge bg-label-primary">{{ $phase->course->name ?? '-' }}</span>
                    </td>
                    <td class="text-center pe-3">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('package.phase.form-edit', ['package_id' => $phase->package_id, 'id' => $phase->id]) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <a href="{{ route('phase.lesson', $phase->id) }}" class="btn btn-sm btn-outline-info" title="Lesson">
                                <i class="bx bx-book-bookmark"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDeletePhase('{{ $phase->id }}')" title="Hapus">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bx bx-inbox bx-lg d-block mb-2"></i>
                        Belum ada phase dalam paket ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('js')
<script>
    async function confirmDeletePhase(id) {
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
            setTimeout(() => location.reload(), 1500);
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
        }
    }
</script>
@endpush
