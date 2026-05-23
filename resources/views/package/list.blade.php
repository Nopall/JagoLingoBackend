@extends('layouts.index')

@section('title', 'Package')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Package</h4>
        <p class="text-muted mb-0 small">Kelola paket berlangganan yang tersedia</p>
    </div>
    <a href="{{ route('package.form-create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Tambah Package
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-datatable table-responsive pt-0">
        <table id="package-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Status</th>
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
        $('#package-table').DataTable({
            serverSide: true,
            ajax: '{{ route('master.package.list') }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price' },
                { data: 'is_active', name: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    async function confirmDelete(id) {
        const result = await Swal.fire({
            title: 'Hapus Package?',
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
            const response = await httpClient.delete(`/master/package/${id}`);
            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 1500 });
            $('#package-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
        }
    }

    async function toggleActive(id) {
        try {
            const response = await axios.patch(`/master/package/toggle-active/${id}`);
            Swal.fire({ icon: 'success', title: response.data.message, showConfirmButton: false, timer: 1200 });
            $('#package-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan saat mengubah status.', 'error');
        }
    }
</script>
@endpush
