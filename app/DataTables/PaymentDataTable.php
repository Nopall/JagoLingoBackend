<?php

namespace App\DataTables;

use App\Models\Payment;
use Yajra\DataTables\Services\DataTable;

class PaymentDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return view('payment.payment-action', [
                    'id' => $row->id, 
                    'status' => $row->status,
                    'isActive' => $row->isActive()
                ])->render();
            })
            ->addColumn('user', function ($payment) {
                return $payment->user ? $payment->user->name : 'User Not Found';
            })
            ->addColumn('package_name', function ($payment) {
                return $payment->package ? $payment->package->name : 'Package Not Found';
            })
            ->addColumn('price', function ($payment) {
                return $payment->amount;
            })
            ->addColumn('payment_status', function ($payment) {
                return $payment->isSubscribed() 
                    ? '<span class="badge bg-label-success">Subscribed</span>'
                    : '<span class="badge bg-label-warning">Pending</span>';
            })
            ->addColumn('subscription_status', function ($payment) {
                if ($payment->isActive()) {
                    return '<span class="badge bg-label-success">Active</span>';
                } elseif ($payment->status === Payment::STATUS_EXPIRED) {
                    return '<span class="badge bg-label-danger">Expired</span>';
                } else {
                    return '<span class="badge bg-label-secondary">Inactive</span>';
                }
            })
            ->addColumn('payment_date', function ($payment) {
                return $payment->payment_date;
            })
            ->addColumn('expiry_date', function ($payment) {
                return $payment->expiry_date;
            })
            ->rawColumns(['payment_status', 'subscription_status', 'action']);
    }

    public function query(Payment $model)
    {
        return $model->newQuery()
            ->with(['user', 'package'])
            ->subscribed() // Hanya yang status = 1 (sudah bayar/subscribe)
            ->select('payments.*');
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('payment-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(5, 'desc') // Order by payment_date descending
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false,
                        'dom' => 'Bfrtip',
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
            'payment_date',
            'expiry_date',
            'action'
        ];
    }
}