{{-- resources/views/subscription/subscription-action.blade.php --}}
<div class="d-flex gap-2">
    {{-- View Details Button --}}
    <a href="{{ route('subscription.show', $id) }}" class="btn btn-sm btn-info" title="View Details">
        <i class="fa fa-eye"></i>
    </a>
    
    {{-- Activate Button (show only for pending or cancelled subscriptions yang sudah bayar) --}}
    @if(in_array($status, ['pending', 'cancelled']) && $isPaid)
    <button type="button" class="btn btn-sm btn-success" 
            onclick="activateSubscription({{ $id }})" title="Activate Subscription">
        <i class="fa fa-check"></i>
    </button>
    @endif
    
    {{-- Cancel Button (show only for active subscriptions yang sudah bayar) --}}
    @if($status === 'active' && $isPaid)
    <button type="button" class="btn btn-sm btn-warning" 
            onclick="cancelSubscription({{ $id }})" title="Cancel Subscription">
        <i class="fa fa-times"></i>
    </button>
    @endif
    
    {{-- Delete Button --}}
    <button type="button" class="btn btn-sm btn-danger" 
            onclick="deleteSubscription({{ $id }})" title="Delete Record">
        <i class="fa fa-trash"></i>
    </button>
</div>