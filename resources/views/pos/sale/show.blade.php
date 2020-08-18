@extends('layouts.adminlayout')
@section('title','Show Inventory Sales')
@section('content')




<section class="invoice_content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">



            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-lg-8">
                @if(Route::current()->getName() == 'viewsales.show')

                <a href="{{route('admin.inventorydashboard')}}" class="btn btn-info btn-sm mb-5"><i class="fa fa-angle-left"></i> back to dashboard</a> 
                @else
                <a href="{{route('sale.index')}}" class="btn btn-info btn-sm mb-5"><i class="fa fa-angle-left"></i> back</a> 
                @endif
              </div>
              <div class="col-lg-4">
                @if($sale->sales_status == 0)
                @if(Auth::user()->role->id == 1)
                <form action="{{route('sale.approve',$sale->id)}}" method="POST" >
                  @csrf
                  <button onclick="return confirm('Are You Sure You Want To Approve Order')" type="submit" class="btn btn-warning btn-sm mb-3 float-right" style="margin-right: 5px;">
                    <i class="fas fa-check"></i> APPROVE THIS ORDER ?
                  </button>
                </form>

                <form action="{{route('sale.destroy',$sale->id)}}" method="POST" >
                  @csrf
                  @method('DELETE')
                  <button onclick="return confirm('Are You Sure You Want To Cancel This Order')" type="submit" class="btn btn-danger btn-sm mb-5" style="margin-right: 5px;">
                    <i class="fas fa-trash"></i> Cancel
                  </button>
                </form>
                @endif
                @else
              <button  disabled type="button" class="btn btn-success mb-5"><i class="fas fa-check"></i>  Approved by {{$signature->name}} <br><span class="badge badge-warning">{{$sale->updated_at->format('d-F-Y g:i a')}}</span></button>
                @endif
              </div>

                <div class="col-12">
                  
                  <h4>
                    <i class="fas fa-money-bill-alt"></i> Sales Details
                  <small class="float-right">Date:  {{$sale->sales_at->format('d-M-Y g:i a')}}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                  From
                  <table class="table table-borderless">
                    
                    <tr>
                      <th>Name: </th>
                      <td><strong>{{$sale->user->name}}</strong></td>
                    </tr>
                    <tr>
                      <th>Email: </th>
                      <td>{{$sale->user->inventory_email}}</td>
                    </tr>
                    <tr>
                      <th>Phone: </th>
                      <td>{{$sale->user->phone}}</td>
                    </tr>
                   
                    <tr>
                      <th> Address: </th>
                      <td>{{$sale->user->address}}</td>
                    </tr>
       

                   
                  </table>
                </div>
                <div class="col-sm-6">
                  <P>Other Infotmation</P>
                  <table class="table table-bordered">
                    <tr>
                      <th>Customer Division: </th>
                      <td>{{$sale->user->division->name}}</td>
                    </tr>
                    <tr>
                      <th>Customer District: </th>
                      <td>{{$sale->user->district->name}}</td>
                    </tr>

                    <tr>
                      <th>Customer Area: </th>
                      <td>{{$sale->user->area->name}}</td>
                    </tr>
                  </table>
                </div>


                <!-- /.col -->
              </div>
              <br>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Sl</th>
                      <th>Product</th>
                      <th>Size</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                      @php
                      $i=1;
                      $sum =0;
                      @endphp
                      @foreach ($sale->product as $sales_info)
      
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$sales_info->product_name}} @if($sales_info->pivot->free > 0) + {{$sales_info->pivot->free}} Pc Free @endif</td>
                        <td>{{$sales_info->size->name}} </td>
                        <td>{{$sales_info->pivot->qty}}</td>
                        <td>{{$sales_info->pivot->price}}</td>
                        <td>{{($sales_info->pivot->price)*($sales_info->pivot->qty)}}</td>
                      </tr>

                      @php
                      $sum = ($sum) +($sales_info->pivot->price)*($sales_info->pivot->qty);
                      @endphp
                      @endforeach
                    
           
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6 mt-5">
                  Service Provided By :  <hr> <strong> {{$sale->provided_by}}  <small> <br> at {{$sale->created_at->format('d-M-Y g:i a')}}</small> </strong>
          
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                      <td>{{$sum}}</td>
                      </tr>
                      <tr>
                        <th>Discount</th>
                      <td>{{$sale->discount}}</td>
                      </tr>
                      <tr>
                        <th>Carrying & Loading:</th>
                      <td>{{$sale->carrying_and_loading}}</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>{{round(($sum+$sale->carrying_and_loading)-($sale->discount))}}</td>
                      </tr>
                    </table>
                    
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">

                  @if($sale->sales_status == 1)
                  <form action="{{route('sale.invoice',$sale->id)}}" method="POST" >
                    @csrf
                    <button type="submit" class="btn btn-primary float-right" style="margin-right: 5px;">
                      <i class="fas fa-download"></i> Generate PDF
                    </button>
                    </form>
                    @else
                    <span class="badge badge-danger float-right">This Order Is Waiting For Approval</span>
                 
                  @endif
                 
                  
                </div>
              </div>


            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    @endsection