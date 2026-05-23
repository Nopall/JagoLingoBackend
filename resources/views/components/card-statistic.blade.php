<div class="card border-0 shadow-sm h-100">
    <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
                <span class="text-muted fw-medium d-block mb-1" style="font-size: 0.85rem;">{{ $label }}</span>
                <h4 class="card-title mb-1 fw-bold">{{ $value }}</h4>
                <small class="text-muted">{{ $description }}</small>
            </div>
            <div class="avatar ms-2 flex-shrink-0">
                <span class="avatar-initial rounded-circle bg-label-{{ $color ?? 'primary' }}" style="width: 48px; height: 48px; font-size: 1.4rem; display: flex; align-items: center; justify-content: center;">
                    <i class="bx {{ $icon }}"></i>
                </span>
            </div>
        </div>
    </div>
</div>
