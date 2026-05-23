<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div class="card-info">
                <p class="card-text">{{ $label }}</p>
                <div class="d-flex align-items-end mb-2">
                    <h4 class="card-title mb-0 me-2">{{ $value }}</h4>
                </div>
                <small>{{ $description }}</small>
            </div>
            <div class="card-icon">
                <span class="badge bg-label-primary rounded p-2">
                    <i class="bx {{ $icon }} bx-sm"></i>
                </span>
            </div>
        </div>
    </div>
</div>
