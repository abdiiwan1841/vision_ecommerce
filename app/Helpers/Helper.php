<?php

use App\Order;
use App\Returnproduct;
use Illuminate\Support\Facades\DB;



 function SplitName($name){
$splitName = explode(' ', $name);
$modifiedName = $splitName[0]." ".$splitName[1];
return $modifiedName;

}

function FashiOrderStatus($argument){
    $status = "";
    if($argument == 0 ){
        $status = '<span class="badge badge-warning">pending</span>';
    }elseif($argument == 1){
        $status = '<span class="badge badge-success">approved</span>';
    }elseif($argument == 2){
        $status = '<span class="badge badge-danger">cancelled</span>';
    }
    return $status;
}

function FashiSalesStatus($argument){
    $status = "";
    if($argument == 0 ){
        $status = '<span class="badge badge-warning">pending</span>';
    }elseif($argument == 1){
        $status = '<span class="badge badge-success">Saled</span>';
    }elseif($argument == 2){
        $status = '<span class="badge badge-danger">cancelled</span>';
    }
    return $status;
}
function FashiPaymentStatus($argument){
    $status = "";
    if($argument == 0 ){
        $status = '<span class="badge badge-warning">pending</span>';
    }elseif($argument == 1){
        $status = '<span class="badge badge-success">paid</span>';
    }elseif($argument == 2){
        $status = '<span class="badge badge-danger">cancelled</span>';
    }
    return $status;
}
function FashiShippingStatus($argument){
    $status = "";
    if($argument == 0 ){
        $status = '<span class="badge badge-warning">pending</span>';
    }elseif($argument == 1){
        $status = '<span class="badge badge-success">Shipped</span>';
    }elseif($argument == 2){
        $status = '<span class="badge badge-danger">cancelled</span>';
    }
    return $status;
}

function FashiGetAmount($order_id){
    $order = Order::with('product')->findOrFail($order_id);
    
    $items = [];
    foreach($order->product as $single_product){
        $items[] = $single_product->pivot->price*$single_product->pivot->qty;
    }
    $subtotal = array_sum($items);

    $discount_amount = $subtotal*($order->discount/100);
    $taxableAmount = $subtotal-$discount_amount;
    $shipping_cahrges  = $order->shipping;
    $vat_amount = $taxableAmount*($order->vat/100);
    $tax_amount = $taxableAmount*($order->tax/100);
    $grand_total = ($taxableAmount+$vat_amount+$tax_amount+$shipping_cahrges)-($order->cash);
    
    return $grand_total;
}

function FashiGetSalesAmount($order_id){
    $order = Order::with('product')->findOrFail($order_id);
    
    $items = [];
    foreach($order->product as $single_product){
        $items[] = $single_product->pivot->price*$single_product->pivot->qty;
    }
    $subtotal = array_sum($items);

    $discount_amount = $subtotal*($order->discount/100);
    $taxableAmount = $subtotal-$discount_amount;
    $shipping_cahrges  = $order->shipping;
    $vat_amount = $taxableAmount*($order->vat/100);
    $tax_amount = $taxableAmount*($order->tax/100);
    $grand_total = ($taxableAmount+$vat_amount+$tax_amount+$shipping_cahrges);
    
    return $grand_total;
}






function FashiGetReturnAmount($return_id){
    $returns = Returnproduct::with('product')->findOrFail($return_id);
    
    $items = [];
    foreach($returns->product as $single_product){
        $items[] = $single_product->pivot->price*$single_product->pivot->qty;
    }
    $subtotal = array_sum($items);

    $discount_amount = $subtotal*($returns->discount_percent/100);
    $carrying_and_loading = $returns->carrying_and_loading;
    $grand_total = ( $subtotal+$carrying_and_loading) -($discount_amount);
    
    return $grand_total;
}

   

?>