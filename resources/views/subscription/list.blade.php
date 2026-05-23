@extends('layouts.index')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Subscription List (Paid Users)</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table id="subscription-table" class="display table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Package</th>
                        <th>Price</th>
                        <th>Payment Status</th>
                        <th>Subscription Status</th>
                        <th>Purchase Date</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#subscription-table').DataTable({
            serverSide: true,
            ajax: '{{ route('master.subscription.list') }}',
            columns: [
                {
                    data: 'user',
                    name: 'user',
                    render: function(data, type, row) {
                        return data ? data.name : 'User Not Found';
                    }
                },
                {
                    data: 'package_name',
                    name: 'package_name'
                },
                {
                    data: 'price',
                    name: 'price',
                    render: function(data, type, row) {
                        return data ? 'Rp ' + parseInt(data).toLocaleString('id-ID') : 'Free';
                    }
                },
                {
                    data: 'payment_status',
                    name: 'payment_status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'subscription_status',
                    name: 'subscription_status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'purchase_date',
                    name: 'purchase_date',
                    render: function(data, type, row) {
                        return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                    }
                },
                {
                    data: 'expiry_date',
                    name: 'expiry_date',
                    render: function(data, type, row) {
                        return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });

    async function activateSubscription(id) {
        try {
            const response = await fetch(`/master/subscription/${id}/activate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            Swal.fire({
                icon: 'success',
                title: result.message,
                showConfirmButton: false,
                timer: 1200
            });
            
            $('#subscription-table').DataTable().ajax.reload();
        } catch (error) {
            Swal.fire('Error', 'Failed to activate subscription', 'error');
        }
    }

    async function cancelSubscription(id) {
        if (!confirm('Are you sure you want to cancel this subscription?')) {
            return;
        }

        try {
            const response = await fetch(`/master/subscription/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: result.message,
                showConfirmButton: false,
                timer: 1500
            });
            
            $('#subscription-table').DataTable().ajax.reload();
        } catch (error) {
            Swal.fire('Error', 'Failed to cancel subscription', 'error');
        }
    }

    async function deleteSubscription(id) {
        if (!confirm('Are you sure you want to delete this subscription record?')) {
            return;
        }

        try {
            const response = await fetch(`/master/subscription/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: result.message,
                showConfirmButton: false,
                timer: 1500
            });
            
            $('#subscription-table').DataTable().ajax.reload();
        } catch (error) {
            Swal.fire('Error', 'Failed to delete subscription', 'error');
        }
    }
</script>
@endpush