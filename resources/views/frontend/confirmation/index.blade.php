@if(Session::has('OrderCompleted'))
@extends('layouts.frontendlayout')
@section('title','Confirmation')
@section('content')
<div class="jumbotron text-center" style="background: #fff">
    <h1 style="color: #4cd137">   <i class="icon_check_alt2"></i>  </h1> 
    <h1 class="display-3">Thank You!</h1>
<h4>Mr. {{$orderinfo->user->name}}</h4>
    <p>Your Order has Been Completed Successfully</p>
    <div class="col-lg-4 offset-lg-4">

    <table class="table table-bordered" style="text-align: left">
        <tr>
            <th> Order ID </th>
            <th>{{$orderinfo->invoice_id}}</th>
        </tr>
        <tr>
            <th>Order Status</th>
            <th>{!!FashiOrderStatus($orderinfo->order_status)!!}</th>
        </tr>
        <tr>
            <th>Order Amount</th>
            <th>{{round($orderinfo->amount)}}</th>
        </tr>
        <tr>
            <th>Estimated Delivery Date: </th>
            <th>{{$orderinfo->shipping_date}}</th>
        </tr>
        
    </table>
</div>
    <p class="lead"><strong>Please check your email</strong> for further instructions</p>
    <hr>
    <p>
    Having trouble? <a href="{{route('contactpage.index')}}">Contact us</a>
    </p>
    <p class="lead">
      <a class="btn btn-primary btn-sm" href="{{route('orderinvoice.show',$orderinfo->id)}}"" role="button">Download Invoice</a>
    </p>
  </div>
@endsection

@push('js')
<script>
    localStorage.clear();
</script>

@endpush

@else
@php
    return false;
@endphp
@endif