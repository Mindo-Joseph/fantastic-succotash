<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderLoyaltyExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::all();
    }
    public function headings(): array{
        return [
            'Order Id',
            'Date & Time',
            'Customer Name',
            'Final Amount',
            'Loyalty Used',
            'Loyality Membership',
            'Loyality Earned',
            'Payment Method',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at,
            $order->user ? $order->user->name : '',
            $order->payable_amount,
            $order->loyalty_points_used != '' ? $order->loyalty_points_used : 0,
            $order->discount_amount,
            $order->loyalty_amount_saved != '' ? $order->loyalty_amount_saved : 0,
            $order->paymentOption ? $order->paymentOption->title: '',
        ];
    }
}
