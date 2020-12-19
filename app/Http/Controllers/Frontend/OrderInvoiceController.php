<?php

namespace App\Http\Controllers\Frontend;

use App\Order;
use App\Company;
use Carbon\Carbon;
use App\Deliveryinfo;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use App\Http\Controllers\Controller;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\Seller;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class OrderInvoiceController extends Controller
{
    public function show($id){
        $delivery_info = Deliveryinfo::first();
        $delivery_delay = $delivery_info->delay;
        $companyinfo = Company::first();
        $order = Order::with('user','product')->findOrFail($id);
      
        $seller = new Party([
            'name'          => $companyinfo->company_name,
            'phone'         => $companyinfo->phone,
            'address'         => $companyinfo->address,
            'custom_fields' => [
                'email'        => $companyinfo->email,
                'business id' => $companyinfo->bin,
            ],
        ]);




        $customer = new Party([
            'name'          => $order->user->name,
            'phone'          => $order->user->phone,
            'address'          => $order->user->address,
            'custom_fields' => [
                'email' =>  ''//$order->user->email,
            ],
        ]);

        $items = [];
        foreach($order->product as $single_product){
   
            $items[] =     (new InvoiceItem())->title($single_product['product_name'])->quantity($single_product->pivot->qty)->pricePerUnit($single_product->pivot->price);
        }


        
        $invoice = Invoice::make('ORDER ID #'.$order->invoice_id)
            ->series('ECOM')
            ->sequence($order->invoice_id)
            ->buyer($customer)
            ->seller($seller)
            ->discountByPercent($order->discount)
            ->date($order->created_at)
            ->taxRate($order->vat+$order->tax)
            ->shipping($order->shipping)
            ->currencySymbol('TK.')
            ->currencyCode('BDT')
            ->logo(public_path('uploads/logo/cropped/'.$companyinfo->logo))
            ->filename($order->invoice_id)
            ->payUntilDays($delivery_delay)
            ->addItems($items)
            ->save('public');

        return $invoice->stream();
    }
}
