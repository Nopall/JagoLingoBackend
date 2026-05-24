@extends('layouts.index')

@section('title', 'Subscription')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Subscription</h4>
        <p class="text-muted mb-0 small">Daftar pengguna beserta paket yang mereka miliki</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalGrantAccess">
        <i class="bx bx-plus me-1"></i> Beri Akses
    </button>
</div>

{{-- Filter Tabs + Search dalam satu card --}}
<div class="card border-0 shadow-sm mb-3">

    {{-- Tab Filter --}}
    <div class="border-bottom px-3">
        <ul class="nav nav-tabs border-0" style="margin-bottom: -1px;">
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'all' ? 'active fw-semibold' : 'text-muted' }}"
                   href="{{ route('master.subscription.list', array_filter(['search' => $search, 'filter' => 'all'])) }}">
                    Semua
                    <span class="badge {{ $filter === 'all' ? 'bg-primary' : 'bg-label-secondary' }} rounded-pill ms-1">
                        {{ $countAll }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'active' ? 'active fw-semibold' : 'text-muted' }}"
                   href="{{ route('master.subscription.list', array_filter(['search' => $search, 'filter' => 'active'])) }}">
                    Aktif
                    <span class="badge {{ $filter === 'active' ? 'bg-success' : 'bg-label-success' }} rounded-pill ms-1">
                        {{ $countActive }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'inactive' ? 'active fw-semibold' : 'text-muted' }}"
                   href="{{ route('master.subscription.list', array_filter(['search' => $search, 'filter' => 'inactive'])) }}">
                    Tidak Aktif
                    <span class="badge {{ $filter === 'inactive' ? 'bg-secondary' : 'bg-label-secondary' }} rounded-pill ms-1">
                        {{ $countInactive }}
                    </span>
                </a>
            </li>
        </ul>
    </div>

    {{-- Search --}}
    <div class="card-body py-3">
        <form method="GET" action="{{ route('master.subscription.list') }}">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="input-group" style="max-width: 380px;">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="bx bx-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0"
                       placeholder="Cari nama atau email pengguna..."
                       value="{{ $search ?? '' }}">
                @if($search)
                    <a href="{{ route('master.subscription.list', ['filter' => $filter]) }}" class="btn btn-outline-secondary">
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
                    $subs      = $user->subscriptions;
                    $hasActive = $subs->where('is_active', 1)->count() > 0;
                @endphp
                <tr>
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
                            {{ $subs->count() }} langganan &bull; {{ $subs->where('is_active', 1)->count() }} aktif
                        </div>
                    </td>

                    {{-- Status Akun --}}
                    <td>
                        @if($hasActive)
                            <span class="badge bg-label-success rounded-pill">
                                <i class="bx bx-check-circle me-1"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-label-danger rounded-pill">
                                <i class="bx bx-x-circle me-1"></i> Tidak Aktif
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center pe-3">
                        <a href="{{ route('subscription.show', $subs->first()->id) }}"
                           class="btn btn-sm btn-outline-primary" title="Lihat Detail">
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
                        @elseif($filter === 'active')
                            Tidak ada pengguna dengan subscription aktif
                        @elseif($filter === 'inactive')
                            Tidak ada pengguna dengan subscription tidak aktif
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
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Modal Beri Akses --}}
<div class="modal fade" id="modalGrantAccess" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-initial rounded bg-label-primary p-2" style="font-size: 1.2rem;">
                        <i class="bx bx-crown"></i>
                    </span>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold">Beri Akses Pembelajaran</h5>
                        <small class="text-muted">Berikan akses paket ke pengguna tertentu</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="mb-4">
                    <label class="form-label fw-medium" for="ga-user-search">
                        Pengguna <span class="text-danger">*</span>
                    </label>
                    {{-- Search box --}}
                    <div class="position-relative mb-2">
                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                            <i class="bx bx-search"></i>
                        </span>
                        <input type="text" id="ga-user-search" class="form-control ps-5"
                               placeholder="Ketik nama atau email untuk mencari...">
                    </div>
                    {{-- Select user --}}
                    <select id="ga-user-id" class="form-select" size="5" style="height: auto;">
                        @foreach($allUsers as $u)
                            <option value="{{ $u->id }}" data-label="{{ strtolower($u->name) }} {{ strtolower($u->email) }}">
                                {{ $u->name }} — {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text text-end" id="ga-user-count">{{ $allUsers->count() }} pengguna</div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-medium" for="ga-package-id">
                        Paket <span class="text-danger">*</span>
                    </label>
                    <select id="ga-package-id" class="form-select">
                        <option value="" disabled selected>Pilih paket...</option>
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" data-price="{{ $pkg->price }}">
                                {{ $pkg->name }}
                                — Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @if($packages->isEmpty())
                        <div class="form-text text-warning">
                            <i class="bx bx-error-circle me-1"></i>
                            Tidak ada paket aktif. Aktifkan paket terlebih dahulu.
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-top py-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btn-grant-access" class="btn btn-primary">
                    <div id="ga-loading" class="spinner-border spinner-border-sm d-none me-1" role="status"></div>
                    <i class="bx bx-check me-1" id="ga-icon"></i>
                    Beri Akses
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    // Filter user di select saat mengetik di search
    document.getElementById('ga-user-search').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const options = document.querySelectorAll('#ga-user-id option');
        let visible = 0;
        options.forEach(opt => {
            const match = opt.dataset.label.includes(keyword);
            opt.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        document.getElementById('ga-user-count').textContent = visible + ' pengguna';
    });

    // Submit grant access
    document.getElementById('btn-grant-access').addEventListener('click', async function () {
        const userId    = document.getElementById('ga-user-id').value;
        const packageId = document.getElementById('ga-package-id').value;

        if (!userId) {
            Swal.fire({ icon: 'warning', title: 'Pilih pengguna terlebih dahulu!', timer: 2000, showConfirmButton: false });
            return;
        }
        if (!packageId) {
            Swal.fire({ icon: 'warning', title: 'Pilih paket terlebih dahulu!', timer: 2000, showConfirmButton: false });
            return;
        }

        const btn  = this;
        const load = document.getElementById('ga-loading');
        const icon = document.getElementById('ga-icon');
        btn.disabled = true;
        load.classList.remove('d-none');
        icon.classList.add('d-none');

        try {
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('package_id', packageId);

            const response = await httpClient.post('{{ route('subscription.grant-access') }}', formData);

            Swal.fire({ icon: 'success', title: response.message, showConfirmButton: false, timer: 2000 });
            bootstrap.Modal.getInstance(document.getElementById('modalGrantAccess')).hide();
            setTimeout(() => location.reload(), 2000);
        } catch (err) {
            const msg = err?.response?.data?.message ?? 'Terjadi kesalahan, coba lagi.';
            Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
        } finally {
            btn.disabled = false;
            load.classList.add('d-none');
            icon.classList.remove('d-none');
        }
    });

    // Reset modal saat ditutup
    document.getElementById('modalGrantAccess').addEventListener('hidden.bs.modal', function () {
        document.getElementById('ga-user-search').value = '';
        document.getElementById('ga-user-id').value = '';
        document.getElementById('ga-package-id').value = '';
        document.querySelectorAll('#ga-user-id option').forEach(o => o.style.display = '');
        document.getElementById('ga-user-count').textContent = '{{ $allUsers->count() }} pengguna';
    });
</script>
@endpush
