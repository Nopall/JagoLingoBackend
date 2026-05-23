@extends('layouts.index')

@section('title', 'Subscription')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Subscription</h4>
        <p class="text-muted mb-0 small">Daftar pengguna beserta paket yang mereka miliki</p>
    </div>
    <span class="badge bg-label-primary fs-6">{{ $users->total() }} Pengguna</span>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('master.subscription.list') }}">
            <div class="input-group" style="max-width: 380px;">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="bx bx-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari nama atau email pengguna..."
                       value="{{ $search ?? '' }}">
                @if($search)
                    <a href="{{ route('master.subscription.list') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x"></i>
                    </a>
                @else
                    <button type="submit" class="btn btn-primary">Cari</button>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3" style="width: 40px;">#</th>
                    <th>Pengguna</th>
                    <th>Paket Langganan</th>
                    <th>Status Akun</th>
                    <th class="text-center pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $subs = $user->subscriptions;
                    $hasActive = $subs->where('is_active', 1)->count() > 0;
                @endphp
                <tr>
                    {{-- No --}}
                    <td class="ps-3 text-muted small">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>

                    {{-- User --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-label-primary fw-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Packages --}}
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @php $packageShown = false; @endphp
                            @foreach($subs as $sub)
                                @if($sub->payment && $sub->payment->package)
                                    @php $packageShown = true; @endphp
                                    <span class="badge bg-label-{{ $sub->is_active ? 'success' : 'secondary' }} rounded-pill"
                                          title="{{ $sub->is_active ? 'Aktif' : 'Tidak Aktif' }} — {{ $sub->payment->paid_at ? \Carbon\Carbon::parse($sub->payment->paid_at)->format('d M Y') : '-' }}">
                                        <i class="bx bx-crown me-1" style="font-size: 0.7rem;"></i>
                                        {{ $sub->payment->package->name }}
                                    </span>
                                @elseif($sub->payment && !$sub->payment->package)
                                    @php $packageShown = true; @endphp
                                    <span class="badge bg-label-warning rounded-pill" title="Paket tidak ditemukan">
                                        <i class="bx bx-error me-1" style="font-size: 0.7rem;"></i>
                                        Unknown Package
                                    </span>
                                @endif
                            @endforeach
                            @if(!$packageShown)
                                <span class="text-muted small">—</span>
                            @endif
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.72rem;">
                            {{ $subs->count() }} langganan &bull;
                            {{ $subs->where('is_active', 1)->count() }} aktif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td>
                        @if($user->is_premium)
                            <span class="badge bg-label-warning rounded-pill">
                                <i class="bx bx-crown me-1"></i> Premium
                            </span>
                        @else
                            <span class="badge bg-label-secondary rounded-pill">Gratis</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center pe-3">
                        <a href="{{ route('subscription.show', $subs->first()->id) }}"
                           class="btn btn-sm btn-outline-primary" title="Lihat Detail Subscription">
                            <i class="bx bx-show me-1"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bx bx-inbox bx-lg d-block mb-2"></i>
                        @if($search)
                            Tidak ada hasil untuk "<strong>{{ $search }}</strong>"
                        @else
                            Belum ada pengguna yang berlangganan
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between align-items-center py-3 px-3">
        <div class="text-muted small">
            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
        </div>
        <div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>

@endsection
