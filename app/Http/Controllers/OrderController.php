<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Charge;
use App\Company;
use App\Product;
use Carbon\Carbon;
use App\Deliveryinfo;
use App\Paymentmethod;
use Carbon\Traits\Timestamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('user')->get();
        return view('orders.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role->id != 1){
            Toastr::error('Only Super Admin Can Create This Order', 'error');
            return redirect()->back();
        }
        $charge = Charge::first();
        $users = User::where('user_type','ecom')->get();
        $products = Product::where('type','ecom')->get();
        return view('orders.create',compact('products','users','charge'));
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'order_date' => 'required|date',
            'user_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'discount_percent' => 'required|numeric',
            'shipping' => 'required|numeric',
            'item' => 'required|numeric',
            'product' => 'required',
        ]);


        $approval_info = json_encode(array('approved_by' => Auth::user()->name,'approved_at' => now())); 




        $charges = Charge::first();

        //Amount calculation
        $products = json_decode($request->product);

        $amount = [];
        foreach($products as  $item){
            $amount[] = ($item->count)*($item->price);
        }
        $netamount = array_sum($amount);
        $disc_amount = $netamount*($request->discount_percent/100);
        $subtotal = $netamount-$disc_amount;
        $vat_tax = $charges->vat + $charges->tax;
        $vat_tax_amount = $subtotal*($vat_tax/100);
        $shipping = $request->shipping;

        $amount_total =  ($subtotal+$vat_tax_amount+$shipping);


        $userinfo = User::findOrFail($request->user_id);
         

        $order = new Order;
        $order->user_id = $request->user_id;
        $order->discount = $request->discount_percent;
        $order->vat = $charges->vat;
        $order->tax = $charges->tax;
        $order->shipping = $request->shipping;
        $order->payment_method = 1;
        $order->payment_status = 0;
        $order->shipping_status = 0;
        $order->order_status = 1;
        $order->amount = $amount_total;
        $order->division_id = $userinfo->division_id;
        $order->district_id = $userinfo->district_id;
        $order->area_id =  $userinfo->area_id;
        $order->address =  $userinfo->address;
        $order->approval_info = $approval_info;
        $order->invoice_id = $request->user_id.time().rand(1,500);
        $order->ordered_at = $request->order_date." ".Carbon::now()->toTimeString();
        $order->save();

       
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['order_id' =>$order->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price, 'user_id' => $request->user_id,'ordered_at' => $request->order_date." ".Carbon::now()->toTimeString() ];   
        }
        $order->product()->attach($product_info);
        return $order->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $payment_methods = Paymentmethod::all();
        return view('orders.show',compact('order','payment_methods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->role->id != 1){
            Toastr::error('Only Super Admin Can Edit This Order', 'error');
            return redirect()->back();
        }

        $order =  Order::with('product')->findOrFail($id);
        $users = User::where('user_type','ecom')->get();
        $products = Product::where('type','ecom')->get();
        return view('orders.edit',compact('products','users','order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'order_date' => 'required|date',
            'user_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'discount_percent' => 'required|numeric',
            'shipping' => 'required|numeric',
            'item' => 'required|numeric',
            'product' => 'required',
        ]);
        $approval_info = json_encode(array('approved_by' => Auth::user()->name,'approved_at' => now()));
        $order = Order::findOrFail($id);

        //Amount calculation
        $products = json_decode($request->product);

        $amount = [];
        foreach($products as  $item){
            $amount[] = ($item->count)*($item->price);
        }
        $netamount = array_sum($amount);
        $disc_amount = $netamount*($request->discount_percent/100);
        $subtotal = $netamount-$disc_amount;
        $vat_tax = $order->vat + $order->tax;
        $vat_tax_amount = $subtotal*($vat_tax/100);
        $shipping = $request->shipping;
        $amount_total =  ($subtotal+$vat_tax_amount+$shipping);
        
         

        $order->user_id = $request->user_id;
        $order->approval_info = $approval_info;
        $order->discount = $request->discount_percent;
        $order->shipping = $request->shipping;
        $order->ordered_at = $request->order_date." ".Carbon::now()->toTimeString();
        $order->amount = $amount_total;
        $order->approval_info = $approval_info;
        $order->save();

        DB::table('order_product')->where('order_id', '=', $id)->delete();

        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['order_id' =>$order->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price, 'user_id' => $request->user_id,'ordered_at' => $request->order_date." ".Carbon::now()->toTimeString()];   
        }
        
        $order->product()->attach($product_info);


        Toastr::success('Order Updated Successfully', 'success');
        return $id;
    }








    public function destroy($id){
        $order = Order::findOrFail($id);
        $order->order_status = 2;
        $order->shipping_status = 2;
        $order->save();
        $order->product()->detach();
        Toastr::success('Order cancelled Successfully', 'success');
        return redirect()->back();
    }



    public function cashSubmit(Request $request, $id){
        $this->validate($request,[
            'cash_date' => 'required|date',
            'cash' => 'required|numeric',
            'payment_method' => 'required|numeric',
            'references' => 'required|max:50',
        ]);
         
        $order = Order::findOrFail($id);
        $order->paymented_at = $request->cash_date." ".Carbon::now()->toTimeString();
        $order->cash = $request->cash;
        $order->payment_method = $request->payment_method;
        $order->posted_by = Auth::user()->name;
        $order->references = $request->references;
        $order->payment_status = 1;
        $order->save();
        Toastr::success('Order Updated Successfully', 'success');
        return redirect()->back();
    }

    public function approval(Request $request,$id){
        $this->validate($request,[
            'approval' => 'required',
        ]);

        $approval_info = ['approved_by' => Auth::user()->name,'approved_at' => now() ]; 
        $order = Order::findOrFail($id);
        $order->order_status = $request->approval;
        $order->approval_info = $approval_info;
        $order->save();
        Toastr::success('Order Approved Successfully', 'success');
        return redirect()->back();
    }

    public function shipped(Request $request,$id){
        $this->validate($request,[
            'shipping' => 'required',
        ]);

        $order = Order::findOrFail($id);
        $order->shipping_status = $request->shipping;
        $order->shipped_at = now();
        $order->save();
        Toastr::success('Order Mark As Shipped Successfully', 'success');
        return redirect()->back();
    }

    public function OrderCancel(Request $request,$id){
        $this->validate($request,[
            'cancel' => 'required',
        ]);
        $cancelation_info = ['canceled_by' => Auth::user()->name,'canceled_at' => now() ]; 
        $order = Order::findOrFail($id);
        $order->order_status = $request->cancel;
        $order->cancelation_info =  $cancelation_info;
        $order->product()->detach();
        $order->save();
        Toastr::success('Order Cancel Successfully', 'success');
        return redirect()->back();
    }


    public function invoice($id){
       
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
                'email' => null//$order->user->email,
            ],
        ]);

        $items = [];
        foreach($order->product as $single_product){
   
            $items[] =     (new InvoiceItem())->title($single_product['product_name'])->quantity($single_product->pivot->qty)->pricePerUnit($single_product->pivot->price);
        }


        
        $invoice = Invoice::make('ORDER ID #'.now()->year.$order->id)
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
