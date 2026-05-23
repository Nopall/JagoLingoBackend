@extends('layouts.index')

@section('title', 'Setting')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Setting</h4>
        <p class="text-muted mb-0 small">Konfigurasi pengaturan aplikasi</p>
    </div>
    <a href="{{ route('setting.form-create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Tambah Setting
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-datatable table-responsive pt-0">
        <table id="setting-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Key</th>
                    <th>Content</th>
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
        $('#setting-table').DataTable({
            serverSide: true,
            ajax: '{{ route('master.setting.list') }}',
            columns: [
                { data: 'teks', name: 'teks' },
                { data: 'content', name: 'content' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    async function confirmDelete(id) {
        const result = await Swal.fire({
            title: 'Hapus Setting?',
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
            const response = await httpClient.delete(`/master/setting/${id}`);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            $('#setting-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
        }
    }
</script>
@endpush
