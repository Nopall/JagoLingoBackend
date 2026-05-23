@extends('layouts.index')

@section('title', 'Subscription')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Subscription</h4>
        <p class="text-muted mb-0 small">Kelola data berlangganan pengguna</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-datatable table-responsive pt-0">
        <table id="subscription-table" class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Package</th>
                    <th>Harga</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Tgl Beli</th>
                    <th>Tgl Expired</th>
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
        $('#subscription-table').DataTable({
            serverSide: true,
            ajax: '{{ route('master.subscription.list') }}',
            columns: [
                {
                    data: 'user', name: 'user',
                    render: function (data) { return data ? data.name : '<span class="text-muted">User Not Found</span>'; }
                },
                { data: 'package_name', name: 'package_name' },
                { data: 'price', name: 'price' },
                { data: 'payment_status', name: 'payment_status', orderable: false, searchable: false },
                { data: 'subscription_status', name: 'subscription_status', orderable: false, searchable: false },
                { data: 'purchase_date', name: 'purchase_date' },
                { data: 'expiry_date', name: 'expiry_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    async function activateSubscription(id) {
        const result = await Swal.fire({
            title: 'Aktifkan Subscription?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        });
        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/master/subscription/${id}/activate`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            });
            const data = await response.json();
            Swal.fire({ icon: 'success', title: data.message, showConfirmButton: false, timer: 1500 });
            $('#subscription-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
        }
    }

    async function cancelSubscription(id) {
        const result = await Swal.fire({
            title: 'Batalkan Subscription?',
            text: 'Subscription pengguna akan dinonaktifkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Kembali'
        });
        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/master/subscription/${id}/cancel`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            });
            const data = await response.json();
            Swal.fire({ icon: 'success', title: data.message, showConfirmButton: false, timer: 1500 });
            $('#subscription-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
        }
    }

    async function confirmDeleteSubscription(id) {
        const result = await Swal.fire({
            title: 'Hapus Subscription?',
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
            const response = await fetch(`/master/subscription/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            });
            const data = await response.json();
            Swal.fire({ icon: 'success', title: data.message, showConfirmButton: false, timer: 1500 });
            $('#subscription-table').DataTable().ajax.reload();
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
        }
    }
</script>
@endpush
