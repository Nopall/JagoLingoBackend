@extends('layouts.index')

@section('title', 'Detail Subscription')

@section('content')

<div class="mb-3">
    <a href="{{ route('master.subscription.list') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">

    {{-- Info User --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="avatar mb-3" style="width: 64px; height: 64px; margin: 0 auto;">
                    <span class="avatar-initial rounded-circle bg-label-primary fw-bold"
                          style="width: 64px; height: 64px; font-size: 1.8rem; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($subscription->user->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
                <h5 class="fw-semibold mb-1">{{ $subscription->user->name ?? 'User tidak ditemukan' }}</h5>
                <p class="text-muted small mb-3">{{ $subscription->user->email ?? '-' }}</p>

                @if($subscription->user && $subscription->user->is_premium)
                    <span class="badge bg-label-warning rounded-pill px-3 py-2">
                        <i class="bx bx-crown me-1"></i> Akun Premium
                    </span>
                @else
                    <span class="badge bg-label-secondary rounded-pill px-3 py-2">Akun Gratis</span>
                @endif

                <hr class="my-3">

                <div class="text-start">
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Total Langganan</span>
                        <span class="fw-semibold">{{ $allSubscriptions->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Langganan Aktif</span>
                        <span class="fw-semibold text-success">{{ $allSubscriptions->where('is_active', 1)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Semua Subscription --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="bx bx-crown text-primary me-2"></i>
                    Riwayat Langganan
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Paket</th>
                            <th>Harga</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allSubscriptions as $sub)
                        @php
                            $payment = $sub->payment;
                            $package = $payment?->package;
                            $isPaid  = $payment && $payment->status == 1;
                        @endphp
                        <tr class="{{ $sub->id === $subscription->id ? 'table-primary' : '' }}">
                            <td class="ps-3">
                                <div class="fw-semibold small">
                                    {{ $package->name ?? '<span class="text-muted">Paket tidak ditemukan</span>' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                    ID #{{ $sub->id }}
                                    @if($sub->id === $subscription->id)
                                        <span class="badge bg-label-primary ms-1">Dipilih</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="small fw-medium">
                                    {{ $payment ? 'Rp ' . number_format($payment->amount, 0, ',', '.') : '-' }}
                                </span>
                            </td>
                            <td>
                                @if(!$payment)
                                    <span class="badge bg-label-secondary rounded-pill">Tidak Ada</span>
                                @elseif($isPaid)
                                    <span class="badge bg-label-success rounded-pill">
                                        <i class="bx bx-check me-1"></i>Lunas
                                    </span>
                                @else
                                    <span class="badge bg-label-warning rounded-pill">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($sub->is_active)
                                    <span class="badge bg-label-success rounded-pill">Aktif</span>
                                @else
                                    <span class="badge bg-label-secondary rounded-pill">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center gap-1">
                                    @if(!$sub->is_active && $isPaid)
                                        <button class="btn btn-sm btn-outline-success"
                                                onclick="activateSubscription({{ $sub->id }})" title="Aktifkan">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    @endif
                                    @if($sub->is_active && $isPaid)
                                        <button class="btn btn-sm btn-outline-warning"
                                                onclick="cancelSubscription({{ $sub->id }})" title="Batalkan">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDeleteSubscription({{ $sub->id }})" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bx bx-inbox bx-lg d-block mb-2"></i>
                                Tidak ada data langganan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('js')
<script>
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
            setTimeout(() => location.reload(), 1500);
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
            setTimeout(() => location.reload(), 1500);
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
            setTimeout(() => window.location = '{{ route('master.subscription.list') }}', 1500);
        } catch (e) {
            Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
        }
    }
</script>
@endpush
