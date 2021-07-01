<?php

namespace App\Exports;
use App\Models\OrderVendor;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;


class OrderVendorListTaxExport implements FromCollection,WithHeadings,WithMapping{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        return OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->get();
    }

    public function headings(): array{
        return [
            'Order Id',
            'Date & Time',
            'Customer Name',
            'Vendor Name',
            'Subtotal Amount',
            'Promo Code Discount',
            'Admin Commission [Fixed]',
            'Admin Commission [%Age]',
            'Final Amount',
            'Payment Method'
        ];
    }

    public function map($order_vendors): array
    {
        return [
            $order_vendors->orderDetail ? $order_vendors->orderDetail->order_number : '',
            $order_vendors->orderDetail ? $order_vendors->orderDetail->created_at : '',
            $order_vendors->user ? $order_vendors->user->name : '',
            $order_vendors->vendor ? $order_vendors->vendor->name : '',
            $order_vendors->subtotal_amount,
            $order_vendors->discount_amount,
            $order_vendors->admin_commission_fixed_amount,
            $order_vendors->admin_commission_fixed_amount,
            $order_vendors->payable_amount,
            $order_vendors->orderDetail ? $order_vendors->orderDetail->paymentOption->title : '',
        ];
    }
}
