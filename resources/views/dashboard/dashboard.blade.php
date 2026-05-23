@extends('layouts.index')

@section('title', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 text-white overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body py-4 px-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-white mb-1">Selamat Datang, Admin! 👋</h4>
                        <p class="mb-0" style="opacity: 0.85;">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <div class="d-none d-md-block" style="opacity: 0.2;">
                        <i class="bx bx-bar-chart-alt-2" style="font-size: 6rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <x-card-statistic
            label="Total Users"
            :value="number_format($totalUsers)"
            description="Pengguna terdaftar"
            icon="bx-user"
            color="primary"
        />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-card-statistic
            label="Total Courses"
            :value="number_format($totalCourses)"
            description="Kursus tersedia"
            icon="bx-book-open"
            color="success"
        />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-card-statistic
            label="Total Packages"
            :value="number_format($totalPackages)"
            description="Paket pembelajaran"
            icon="bx-package"
            color="info"
        />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-card-statistic
            label="Subscription Aktif"
            :value="number_format($activeSubscriptions)"
            description="Berlangganan saat ini"
            icon="bx-crown"
            color="warning"
        />
    </div>
</div>

{{-- Quick Actions & Recent Subscriptions --}}
<div class="row g-4">

    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pb-0">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="bx bx-zap text-primary me-2"></i>Akses Cepat
                </h5>
            </div>
            <div class="card-body pt-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('master.course.list') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2">
                        <i class="bx bx-book-open"></i> Kelola Course
                    </a>
                    <a href="{{ route('master.phase.list') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
                        <i class="bx bx-list-ul"></i> Kelola Phase
                    </a>
                    <a href="{{ route('master.package.list') }}" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2">
                        <i class="bx bx-package"></i> Kelola Package
                    </a>
                    <a href="{{ route('master.subscription.list') }}" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-2">
                        <i class="bx bx-crown"></i> Kelola Subscription
                    </a>
                    <a href="{{ route('user.list') }}" class="btn btn-outline-info btn-sm d-flex align-items-center gap-2">
                        <i class="bx bx-group"></i> Kelola Users
                    </a>
                    <a href="{{ route('master.setting.list') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2">
                        <i class="bx bx-cog"></i> Pengaturan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Subscriptions --}}
    <div class="col-lg-8">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="bx bx-time-five text-primary me-2"></i>Subscription Terbaru
                </h5>
                <a href="{{ route('master.subscription.list') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">User</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th class="pe-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSubscriptions as $sub)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-primary fw-semibold">
                                                {{ strtoupper(substr($sub->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold small">{{ $sub->user->name ?? '-' }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $sub->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small">{{ $sub->package->name ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($sub->is_active && $sub->status === 'active')
                                        <span class="badge bg-label-success rounded-pill">Aktif</span>
                                    @elseif($sub->status === 'pending')
                                        <span class="badge bg-label-warning rounded-pill">Pending</span>
                                    @else
                                        <span class="badge bg-label-danger rounded-pill">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="pe-3">
                                    <span class="text-muted small">{{ $sub->created_at->format('d M Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bx bx-inbox bx-lg d-block mb-2 text-muted"></i>
                                    Belum ada subscription
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
