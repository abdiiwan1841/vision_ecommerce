@extends('layouts.adminlayout')
@section('title','Show Orders')


@section('modal')
<!--Insert Modal -->
@component('component.common.modal')

    @slot('modal_id')
        addDataModal
    @endslot
    @slot('modal_size')
    modal-md
    @endslot


    @slot('submit_button')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Cash
    @endslot

    @slot('modal_form') 
       <form action="{{route('order.cashsubmit',$order->id )}}" method="POST" id="cashForm" enctype="multipart/form-data">
        @csrf
    @endslot

    

    @slot('modal_body')
          <div class="form-group">
            <label for="cash_date">Cash Receive Date</label>
            @php
                $mytime = Carbon\Carbon::now();
            @endphp
            <input type="text" class="form-control @error('cash_date') is-invalid @enderror" name="cash_date" id="cash_date" value="{{$mytime->toDateString()}}">
            @error('cash_date')
            <small class="form-error">{{ $message }}</small>
            @enderror
          </div>

          <div class="form-group">
            <label for="cash">Amount</label>
          <input type="text" class="form-control @error('cash') is-invalid @enderror" name="cash" id="cash" placeholder="Enter Amount" value="{{old('cash')}}" readonly>
            @error('cash')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
          <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select data-placeholder="Select Payment Method" name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
              @foreach ($payment_methods as $pmd)
                <option value="{{$pmd->id}}" @if (old('user') == $pmd->id) selected  @endif>{{$pmd->name}}</option>
              @endforeach
            </select>
            @error('payment_method')
            <small class="form-error">{{ $message }}</small>
            @enderror
          </div>

          <div class="form-group">
            <label for="references">Reference</label>
          <input type="text" class="form-control @error('references') is-invalid @enderror" name="references" id="references" placeholder="Enter Referance" value="{{old('references')}}">
            @error('references')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
      
    @endslot
@endcomponent
<!--End Insert Modal -->
@endsection

@section('content')



<section class="invoice_content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">



            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                @if(Route::current()->getName() == 'order.view')

                <a href="{{route('admin.dashboard')}}" class="btn btn-sm btn-info"><i class="fa fa-angle-left"></i> back to dashboard</a>

                @else

                <a href="{{route('order.index')}}" class="btn btn-info"><i class="fa fa-angle-left"></i> back</a>
                @endif

                <div class="col-12">
                  <h3 class="text-center">Order ID # {{$order->id}} </h3> <br>
                  <h4>
                    <i class="fas fa-money-bill-alt"></i> Order Details
                  <small class="float-right">Date:  {{\Carbon\Carbon::parse($order->ordered_at)->format('d-m-Y')}}</small>
                  </h4> <br>
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-lg-9">
                  <h5>From</h5> <hr>
                  <table class="table table-borderless">
                    
                    <tr>
                      <th>Name: </th>
                      <td><strong>{{$order->user->name}}</strong></td>
                    </tr>
                    <tr>
                      <th>Email: </th>
                      <td>{{$order->user->email}}</td>
                    </tr>
                    <tr>
                      <th>Phone: </th>
                      <td>{{$order->user->phone}}</td>
                    </tr>
                    <tr>
                      <th>Division: </th>
                      <td>{{$order->division->name}}</td>
                    </tr>
                    <tr>
                      <th>District: </th>
                      <td>{{$order->district->name}}</td>
                    </tr>

                    <tr>
                      <th>Area: </th>
                      <td>{{$order->area->name}}</td>
                    </tr>
                    <tr>
                      <th>Address: </th>
                      <td>{{$order->address}}</td>
                    </tr>
                    <tr>
                      <th>Order Status</th>
                      <th>{!!FashiOrderStatus($order->order_status) !!} </th>
                    </tr>
                    @if(!empty($order->approval_info))
                    <tr>
                      <th>Approved By </th>
                      <td>  <p>  @php $approval_info = json_decode($order->approval_info); @endphp 
                        {{$approval_info->approved_by}} At {{date(' d-F-Y g:i a', strtotime($approval_info->approved_at))}} </p></td>
                    </tr>
                    @endif
                    @if($order->shipping_status == 1)
           
                        <tr>
                          <th>Shipping Status:</th>
                          <td><img style="width: 200px" class="img-fluid" src="{{asset('public/assets/images/shipped.png')}}" alt=""></td>
                        </tr>
                        <tr>
                          <th>Shipped At:</th>
                          <td>{{$order->shipped_at->format('d-m-Y') }}</td>
                        </tr>
                      @else
                      <tr>
                        <th>Shipping Status:</th>
                        <td><span class="badge badge-warning">pending</span></td>
                      </tr>
                  
                    @endif
                   
                  </table>
        
    
              
                 
                </div>
                  <div class="col-lg-3">
                  
                    
                    @if($order->order_status == 2)

                    <img class="img-fluid img-thumbnail" src="{{asset('public/assets/images/canceled.jpg')}}" alt="">

                    @if(!empty($order->cancelation_info))

                    <p>  @php $CancelationInfo = json_decode($order->cancelation_info); @endphp 
                      Canceled By: {{$CancelationInfo->canceled_by}} At {{date(' d-F-Y g:i a', strtotime($CancelationInfo->canceled_at))}} </p>
    
                    @endif



                    @else
                    @if($order->order_status == 0)
  
                    <form  action="{{route('order.cancel',$order->id)}}" method="POST" style="margin: 20px 0">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="cancel" value="2">
                      
                      <button type="submit" onclick="return confirm('Are You Sure You Want To Cancel The Order? You Cant revert this in future')" class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Cancel Order</button>
                      </form>
  
                    <form action="{{route('order.approval', $order->id)}}" method="POST" style="margin: 20px 0" >
                      @csrf
                      @method('PUT')
                      <input type="hidden" value="1" name="approval">
                      <button type="submit" class="btn btn-sm btn-warning">Approve Order ?</button>
                    </form>
                    @else
                    @if($order->payment_status == 0)
                    <button style="margin: 20px 0" type="button" onclick="cashSubmit('{{route('order.cashsubmit',$order->id)}}')" class="btn btn-sm btn-success"><i class="far fa-credit-card"></i> Submit
                      Payment
                    </button>
                    <form  action="{{route('order.cancel',$order->id)}}" method="POST" style="margin-bottom: 20px">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="cancel" value="2">
                      
                      <button type="submit" onclick="return confirm('Are You Sure You Want To Cancel The Order? You Cant revert this in future')" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Cancel Order</button>
                      </form>
                    @else
                    <button style="margin: 20px 0" type="button" class="btn btn-success"  disabled><i class="far fa-check-circle"></i> PAID
                    </button>
                    @endif
                    @endif

                   
                    
                  <a target="_blank" href="{{route('order.invoice',$order->id)}}" class="btn btn-sm btn-primary">
                      <i class="fas fa-download"></i> Generate PDF
                  </a>
                  @endif




                  </div>
                

                
                <!-- /.col -->
              </div>
              <br>
              <!-- /.row -->
              @if($order->order_status != 2)

              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Sl</th>
                      <th>Product</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                      @php
                      $i=1;
                      $sum=0;    
                      @endphp
                      @foreach ($order->product as $order_pd_info)
                      @php
                        $p_price = $order_pd_info->pivot->price;
                        $p_qty = $order_pd_info->pivot->qty;
                        $s_total =  $p_price*$p_qty;
                        $sum = $sum+$s_total;
                      @endphp
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$order_pd_info->product_name}}</td>
                        <td>{{$order_pd_info->pivot->qty}}</td>
                        <td>{{$order_pd_info->pivot->price}}</td>
                        <td>{{$s_total}}</td>
                      </tr>
                      @endforeach
                    
           
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-3">

                  @if($order->payment_status == 1)
                 
                <img class="img-fluid img-thumbnail" src="{{asset('public/assets/images/paid.png')}}" alt="">
                  <table class="table table-hover table-bordered">
                  <tr>
                    <th>Cash: </th>
                  <td>{{$order->cash}}</td>
                  </tr>
                  <tr>
                    <td>Reference</td>
                    <td>({{$order->references}})</td>
                  </tr>
                  <tr>
                    <th>Cash Date:</th>
                    <td>{{\Carbon\Carbon::parse($order->paymented_at)->format('d-m-Y g:i a')}}</td>
                  </tr>
                  <tr>
                    <th>Posted By:</th>
                    <td>{{$order->posted_by}}</td>
                  </tr>
                </table>
           
                  @endif

                </div>
                <div class="col-lg-3"></div>
                <!-- /.col -->
                <div class="col-6">
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                      <td>{{$sum}}</td>
                      </tr>
                      <tr>
                        <th>Discount ({{$order->discount}}%)</th>
                      <td>
                        @php
                            $discount = $sum*($order->discount/100);
                            echo "-".$discount;
                        @endphp
                        </td>
                      </tr>
                      <tr>
                        <th>Taxable Amount</th>
                      <td>
                        @php
                            $taxablecash = $sum-$discount;
                            echo $taxablecash;
                        @endphp
                        </td>
                      </tr>
                      <tr>
                        <th>Shipping</th>
                      <td>
                        @php
                            $shipping = $order->shipping;
                            echo "+".$shipping;
                        @endphp
                        </td>
                      </tr>
                      <tr>
                        <th>Vat ({{$order->vat}}%)</th>
                      <td>
                        @php
                            $vat = $taxablecash*($order->vat/100);
                            echo "+".$vat;
                        @endphp
                        </td>
                      </tr>
                      <tr>
                        <th>Tax ({{$order->tax}}%)</th>
                      <td>
                        @php
                            $tax = $taxablecash*($order->tax/100);
                            echo "+".$tax;
                        @endphp
                        </td>
                      </tr>
                     
                      <tr>
                        <th>Total:</th>
                        <td>{{$grandToatl =  round($taxablecash+$vat+$tax+$shipping)}}</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              @endif
  

            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    @endsection

@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/flatpicker.min.css')}}">
@endpush

@push('js')
@if($order->order_status != 2)

<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>

@if ($errors->any())
{{-- prevent The Modal Close If Any Error In the Form --}}
<script>
    $('#addDataModal').modal('show');
</script>
@endif




<script>
  $("#cash_date").flatpickr({dateFormat: 'Y-m-d'});

function cashSubmit(store_url){
 
  $('.modal-title').text('Submit Cash');
  $('#cashForm').attr('action', store_url);
  $('#cashForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
  $("#cash").val({{$grandToatl}});
  $('#addDataModal').modal('show');


}
    </script>
    @endif
    @endpush