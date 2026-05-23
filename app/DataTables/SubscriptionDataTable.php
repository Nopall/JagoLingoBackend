<?php

namespace App\DataTables;

use App\Models\Subscription;
use Yajra\DataTables\Services\DataTable;

class SubscriptionDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return view('subscription.subscription-action', [
                    'id' => $row->id, 
                    'status' => $row->status,
                    'isPaid' => $row->payment ? $row->payment->status == 1 : false
                ])->render();
            })
            ->addColumn('user', function ($subscription) {
                // Handle jika user null atau deleted
                if (!$subscription->user || $subscription->user->is_deleted) {
                    return '<span class="text-muted">User Deleted</span>';
                }
                return $subscription->user->name;
            })
            ->addColumn('package_name', function ($subscription) {
                return $subscription->package ? $subscription->package->name : '<span class="text-muted">No Package</span>';
            })
            ->addColumn('price', function ($subscription) {
                return $subscription->price ? 'Rp ' . number_format($subscription->price, 0, ',', '.') : 'Free';
            })
            ->addColumn('payment_status', function ($subscription) {
                if (!$subscription->payment) {
                    return '<span class="badge bg-label-secondary">No Payment</span>';
                }
                
                return $subscription->payment->status == 1 
                    ? '<span class="badge bg-label-success">Paid</span>'
                    : '<span class="badge bg-label-warning">Pending</span>';
            })
            ->addColumn('subscription_status', function ($subscription) {
                $badgeClass = 'secondary';
                $statusText = ucfirst($subscription->status ?? 'unknown');
                
                switch($subscription->status) {
                    case 'active':
                        $badgeClass = 'success';
                        break;
                    case 'expired':
                        $badgeClass = 'danger';
                        break;
                    case 'pending':
                        $badgeClass = 'warning';
                        break;
                    case 'cancelled':
                        $badgeClass = 'secondary';
                        break;
                }
                
                return '<span class="badge bg-label-'.$badgeClass.'">'.$statusText.'</span>';
            })
            ->addColumn('purchase_date', function ($subscription) {
                return $subscription->purchase_date ? $subscription->purchase_date->format('d M Y') : '-';
            })
            ->addColumn('expiry_date', function ($subscription) {
                return $subscription->expiry_date ? $subscription->expiry_date->format('d M Y') : '-';
            })
            ->rawColumns(['user', 'package_name', 'payment_status', 'subscription_status', 'action']);
    }

    public function query(Subscription $model)
    {
        return $model->newQuery()
            ->with(['user', 'package', 'payment'])
            ->leftJoin('users', 'subscriptions.user_id', '=', 'users.id')
            ->leftJoin('payments', 'subscriptions.payment_id', '=', 'payments.id')
            ->where(function($query) {
                // Hanya tampilkan yang payment-nya paid ATAU tidak ada payment sama sekali
                $query->whereHas('payment', function($q) {
                    $q->where('status', 1);
                })->orWhereNull('subscriptions.payment_id');
            })
            ->select('subscriptions.*')
            ->orderBy('subscriptions.created_at', 'desc');
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('subscription-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0, 'desc')
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false,
                        'language' => [
                            'emptyTable' => 'No subscription data available',
                            'zeroRecords' => 'No matching subscriptions found'
                        ]
                    ]);
    }

    protected function getColumns()
    {
        return [
            'user',
            'package_name',
            'price',
            'payment_status',
            'subscription_status',
            'purchase_date',
            'expiry_date',
            'action'
        ];
    }
}