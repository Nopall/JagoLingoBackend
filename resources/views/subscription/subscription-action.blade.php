<div class="d-flex gap-1">
    <a href="{{ route('subscription.show', $id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
        <i class="bx bx-show"></i>
    </a>

    @if(in_array($status, ['pending', 'cancelled']) && $isPaid)
    <button type="button" class="btn btn-sm btn-outline-success"
            onclick="activateSubscription({{ $id }})" title="Aktifkan Subscription">
        <i class="bx bx-check"></i>
    </button>
    @endif

    @if($status === 'active' && $isPaid)
    <button type="button" class="btn btn-sm btn-outline-warning"
            onclick="cancelSubscription({{ $id }})" title="Batalkan Subscription">
        <i class="bx bx-x"></i>
    </button>
    @endif

    <button type="button" class="btn btn-sm btn-outline-danger"
            onclick="confirmDeleteSubscription({{ $id }})" title="Hapus">
        <i class="bx bx-trash"></i>
    </button>
</div>
