@extends('layouts.employeelayout')
@section('title','Employee Dashboard')

@section('content')


<div class="row">
	<div class="col-lg-4">
		<div class="card px-3 py-3 mb-3">
			<div class="text-center">
				<img src="{{asset('public/uploads/logo/cropped/'.$CompanyInfo->logo)}}" alt="">
			<h5>{{$CompanyInfo->company_name}}</h5>
			<small>{{$CompanyInfo->email}} <br> {{$CompanyInfo->phone}} <br>{{$CompanyInfo->address}}</small>

			<h5>E-commerce Dashboard </h5>
			<h5>{{Auth::user()->name}}</h5>
	
			</div>
		</div>
	</div>
	<div class="col-lg-8">

	<div class="row">
	<div class="col-lg-4">
		<!-- small box -->
		<div class="small-box bg-light-green">
		  <div class="inner">
			<h3 id="approve_order"></h3>

			<p>Todays Total Approve Order Amount</p>
		  </div>
		  <div class="icon">
			<i class="fas fa-cart-plus"></i>
		  </div>
		 
		</div>
	  </div>

	  <div class="col-lg-4">
		<!-- small box -->
		<div class="small-box bg-warning">
		  <div class="inner">
			<h3 id="pending_order"></h3>

			<p>Todays Total Pending Order Amount</p>
		  </div>
		  <div class="icon">
			<i class="fas fa-hammer"></i>
		  </div>
		 
		</div>
	  </div>

	  <div class="col-lg-4">
		<!-- small box -->
		<div class="small-box bg-danger">
		  <div class="inner">
			<h3 id="canceled_order"></h3>

			<p>Todays Total  Return  Amount</p>
		  </div>
		  <div class="icon">
			<i class="fas fa-undo-alt"></i>
		  </div>
		 
		</div>
	  </div>
	  
	  <div class="col-lg-4">
		<!-- small box -->
		<div class="small-box bg-info">
		  <div class="inner">
			<h3 id="cashsum">0</h3>

			<p>Todays Total Cash Amount</p>
		  </div>
		  <div class="icon">
			<i class="fas fa-dollar-sign"></i>
		  </div>
		 
		</div>
	  </div>
	</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-9">
		<div class="card">
			<div class="card-header bg-light-green">
				<span class="card-title text-white"> <strong>Ecommerce Section</strong></span>
			</div>
			<div class="card-body">

				<p>Todays Orders</p>


				@php 

				$approveorder_amount_sum = 0;
				$pendingorder_amount_sum = 0;
				$cancelorder_amount_sum = 0;
				@endphp

				@if(count($todays_order) > 0)
				
				  
				  <table class="table table-sm table-bordered" style="font-size: 14px;">
					<thead class="thead-light">
					  <tr>
						<th class="align-middle">Order ID</th>
						<th style="width: 120px;" class="align-middle">Date</th>
				
						<th class="align-middle">Customer</th>
						<th class="align-middle">Phone</th>
						<th class="align-middle">Area</th>
						<th class="align-middle">Amount</th>
						<th style="width: 150px;" class="align-middle">Action</th>
					  </tr>
					</thead>
					<tbody>
						
						@foreach ($todays_order as $todays_order_item)
							@if($todays_order_item->order_status == 1)
							@php
							 $approveorder_amount_sum = $approveorder_amount_sum+$todays_order_item->amount;
							@endphp
							@endif

							@if($todays_order_item->order_status == 0)
							@php
							 $pendingorder_amount_sum = $pendingorder_amount_sum+$todays_order_item->amount;
							@endphp
							@endif

							@if($todays_order_item->order_status == 2)
							@php
							 $cancelorder_amount_sum = $cancelorder_amount_sum+$todays_order_item->amount;
							@endphp
							@endif


							<tr @if($todays_order_item->order_status == 2) style="background: #f8a5c2" @elseif($todays_order_item->order_status == 1)  style="background: #b8e994" @endif>
							<td class="align-middle">#{{$todays_order_item->id}}</td>
							<td data-toggle="tooltip" data-placement="top" data-html="true" title='Order: status: {!!FashiOrderStatus($todays_order_item->order_status)!!} Payment: status: {!!FashiPaymentStatus($todays_order_item->payment_status)!!} ' style="width: 120px;" class="align-middle">{{$todays_order_item->ordered_at->format('d-m-Y g:i a')}}</td>
							
							<td class="align-middle">{{$todays_order_item->user->name}}</td>
							<td class="align-middle">{{$todays_order_item->user->phone}}</td>
							<td class="align-middle">{{$todays_order_item->area->name}}</td>
							<td class="align-middle">{{round($todays_order_item->amount)}}</td>
							<td class="align-middle" style="width: 150px;">
							@if($todays_order_item->order_status == 1 || $todays_order_item->order_status == 2)

							@if(!empty($todays_order_item->approval_info))
							<a data-toggle="tooltip" data-placement="top" title="@php $ApprovalInfo = json_decode($todays_order_item->approval_info); @endphp 
								Approved By: {{$ApprovalInfo->approved_by}} At {{date(' d-F-Y g:i a', strtotime($ApprovalInfo->approved_at))}}" class="btn btn-link btn-sm" href="{{route('order.view',$todays_order_item->id)}}"><i class="fas fa-eye"></i>	 </a>
							@endif
							

							@if(!empty($todays_order_item->cancelation_info))

							<a data-toggle="tooltip" data-placement="right" title="@php $CancelationInfo = json_decode($todays_order_item->cancelation_info); @endphp 
								Canceled By: {{$CancelationInfo->canceled_by}} At {{date(' d-F-Y g:i a', strtotime($CancelationInfo->canceled_at))}}" class="btn btn-link btn-sm" href="{{route('order.view',$todays_order_item->id)}}"> <i class="fas fa-eye" </a>

							@endif

							
							@else
							<form id="approval-{{$todays_order_item->id}}"  action="{{route('order.approval',$todays_order_item->id)}}" method="POST" style="display: inline">
								@csrf
								@method('PUT')
								<input type="hidden" name="approval" value="1">
								<button onclick="Confirm({{$todays_order_item->id}},'approval','Are you sure You Want To confirm Order ID # {{$todays_order_item->id}} ?','Yes Confirm','question','')" type="button" class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
							</form>  
							<form id="cancel-{{$todays_order_item->id}}"  action="{{route('order.cancel',$todays_order_item->id)}}" method="POST" style="display: inline">
									@csrf
									@method('PUT')
									<input type="hidden" name="cancel" value="2">
									
									<button onclick="Confirm({{$todays_order_item->id}},'cancel','Are you sure You Want To Cancel Order ID # {{$todays_order_item->id}} ?','Yes Confirm')" type="button" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
									</form>


								<a class="btn btn-info btn-sm" href="{{route('order.view',$todays_order_item->id)}}"> <i class="fa fa-eye"></i> </a>
							@endif 
						</td>
							</tr>
						@endforeach
						

					</tbody>
				  </table>
				  <div class="row justify-content-end">
				  <div class="col-lg-6">
				
				  <table class="table">
					<tr>
						<th>Total Approve Order Amount</th>
						<th>{{round($approveorder_amount_sum)}}</th>

					</tr>

					<tr>
						<th>Total Approve Order Amount</th>
						<th>{{round($cancelorder_amount_sum)}}</th>

					</tr>

					<tr>
						<th>Total Pending Order Amount</th>
						<th>{{round($pendingorder_amount_sum)}}</th>

					</tr>
				  </table>
				  </div>
				  </div>
	
				@else
				<div class="row">
					<span class="alert alert-success">No Order Found Today</span>
				</div>
				
				@endif
				<hr>

				<p>Todays Ecommerce Cash</p>
			    @php
					$cashsum =0;
				@endphp
				@if(count($todays_ecom_cash) > 0)
				  
				<table class="table table-sm table-bordered" style="font-size: 14px;">
				  <thead class="thead-light">
					<tr>
					  <th class="align-middle">ID</th>
					  <th style="width: 120px;" class="align-middle">Date</th>
					  <th class="align-middle">Payment Status</th>
					  <th class="align-middle">Customer</th>
					  <th class="align-middle">Phone</th>
					  <th class="align-middle">Amount</th>
					  <th class="align-middle">Action</th>
					</tr>
				  </thead>
				  <tbody>
					
  
					  @foreach ($todays_ecom_cash as $todays_ecom_cash_item)
						  @php 
						  	$cashsum = $cashsum+$todays_ecom_cash_item->cash;
						  @endphp
						  <tr>
						  <td class="align-middle">#{{$todays_ecom_cash_item->id}}</td>
						  <td style="width: 120px;" class="align-middle">{{$todays_ecom_cash_item->paymented_at}}</td>
						  <td class="align-middle">{!!FashiPaymentStatus($todays_ecom_cash_item->payment_status)!!}</td>
		  
						  <td class="align-middle">{{$todays_ecom_cash_item->user->name}}</td>
						  <td class="align-middle">{{$todays_ecom_cash_item->user->phone}}</td>
						  <td class="align-middle">{{round($todays_ecom_cash_item->cash)}}</td>
	  
					  <td>
						  <a class="btn btn-info btn-sm" href=""> <i class="fa fa-eye"></i> </a>
						  
					  </td>
						  </tr>
					  @endforeach
					  

				  </tbody>
				</table>
			  @else
			  <div class="row">
				<span class="alert alert-success">No Order Cash Found Today</span>
			  </div>
			  
			  @endif

			  <hr>

			  <p>Todays Ecommerce Returns</p>
			  @if(count($todays_ecom_returns) > 0)
				  
				<table class="table table-sm table-bordered" style="font-size: 14px;">
				  <thead class="thead-light">
					<tr>
					  <th class="align-middle">ID</th>
					  <th class="align-middle">Date</th>
					  <th class="align-middle">Customer</th>
					  <th class="align-middle">Amount</th>
					  <th class="align-middle">Action</th>
					</tr>
				  </thead>
				  <tbody>
		
					@foreach ($todays_ecom_returns as $todays_ecom_return_item)
					<tr>
					<td class="align-middle">#{{$todays_ecom_return_item->id}}</td>
					<td class="align-middle">{{$todays_ecom_return_item->returned_at->format('d-m-Y g:i a')}}</td>
					<td class="align-middle">{{$todays_ecom_return_item->user->name}}</td>
					<td class="align-middle">{{round($todays_ecom_return_item->amount)}}</td>
					<td class="align-middle">
					<a class="btn btn-info btn-sm" href=""> <i class="fa fa-eye"></i> </a>
					</td>
					</tr>
					@endforeach
					  
		
				  </tbody>
				</table>
			  @else
				<div class="row">
					<span class="alert alert-success">No Returns Found Today</span>
				</div>
			  
			  @endif

			  <hr>
			  <p>Orders awaiting processing</p>

			  @if(count($pending_orders) > 0)
				  
			  <table class="table table-sm table-bordered" style="font-size: 14px;">
				<thead class="thead-light">
				  <tr>
					<th class="align-middle">Order ID</th>
					<th style="width: 140px;" class="align-middle">Date</th>
					<th class="align-middle">Customer</th>
					<th class="align-middle">Phone</th>
					<th class="align-middle">Area</th>
					<th class="align-middle">Amount</th>
					<th class="align-middle" style="width: 170px;">Action</th>
				  </tr>
				</thead>
				<tbody>

					@foreach ($pending_orders as $pending_item)
						<tr>
						<td>#{{$pending_item->id}}</td>
						<td data-toggle="tooltip" data-placement="top" data-html="true" title='Order: status: {!!FashiOrderStatus($pending_item->order_status)!!} Payment: status: {!!FashiPaymentStatus($pending_item->payment_status)!!} ' style="width: 120px;" class="align-middle">{{$pending_item->ordered_at->format('d-m-Y g:i a')}}</td>

						<td class="align-middle">{{$pending_item->user->name}}</td>
						<td class="align-middle">{{$pending_item->user->phone}}</td>
						<td class="align-middle">{{$pending_item->area->name}}</td>
						<td class="align-middle">{{round($pending_item->amount)}}</td>
						<td class="align-middle" style="width: 170px;">
						<form id="approval-{{$pending_item->id}}"  action="{{route('order.approval',$pending_item->id)}}" method="POST" style="display: inline">
							@csrf
							@method('PUT')
							<input type="hidden" name="approval" value="1">
							<button onclick="Confirm({{$pending_item->id}},'approval','Are you sure You Want To confirm Order ID # {{$pending_item->id}} ?','Yes Confirm','success','')" type="button" class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
						</form>  | 
						<form id="cancel-{{$pending_item->id}}"  action="{{route('order.cancel',$pending_item->id)}}" method="POST" style="display: inline">
								@csrf
								@method('PUT')
								<input type="hidden" name="cancel" value="2">
								
								<button onclick="Confirm({{$pending_item->id}},'cancel','Are you sure You Want To Cancel Order ID # {{$pending_item->id}} ?','Yes Confirm')" type="button" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
								</form>
								|  <a class="btn btn-info btn-sm" href="{{route('order.view',$pending_item->id)}}"> <i class="fa fa-eye"></i> </a></td>
						</tr>
					@endforeach
					

				</tbody>
			  </table>
			@else
			<div class="row">
				<span class="alert alert-success">No Pending Order Found</span>
			</div>
			
			@endif


			</div>
		</div>

		
	</div>
	<div class="col-lg-3">
		<div class="card">
			<div class="card-header bg-warning">
				<span class="card-title text-white"> <strong>Pending Shipping For  Ecommerce Order</strong></span>
			</div>
			<div class="card-body">
				@if(count($pending_shipping) > 0)

				@foreach ($pending_shipping as $shipping_item)
					
				
				<table class="table table-sm table-bordered">

					<tr>
					  <td><small> Order #{{$shipping_item->id}} - {{$shipping_item->user->name}} -  <b> {{$shipping_item->area->name}} </b> </small> <form id="shipped-{{$shipping_item->id}}"  action="{{route('order.shipped',$shipping_item->id)}}" method="POST" style="display: inline">
						@csrf
						@method('PUT')
						<input type="hidden" name="shipping" value="1">
						<button onclick="Confirm({{$shipping_item->id}},'shipped','Are you sure You Want To Mark As Shipped For   Order ID # {{$shipping_item->id}} ?','Yes Confirm','question','')" type="button" class="btn btn-sm btn-green-rounded"><i class="fas fa-check"></i></button>
					</form></td>
					</tr>
				</table>
				@endforeach
				@endif
			</div>
		</div>
	</div>
	


@endsection


@push('js')
<script>
	var approveorder_amount_sum =  '{{round($approveorder_amount_sum)}}';
	var pendingorder_amount_sum = 	'{{round($pendingorder_amount_sum)}}';
	var cancelorder_amount_sum = '{{round($cancelorder_amount_sum)}}';
	var totalcash = '{{round($cashsum)}}';

	$("#approve_order").text(approveorder_amount_sum);
	$("#pending_order").text(pendingorder_amount_sum);
	$("#canceled_order").text(cancelorder_amount_sum);
	$("#cashsum").text(totalcash);




	function Confirm(id,unique_form_name,msg,btn_text,icon='warning',subtext='You won\'t be able to revert this!',){
			 const swalWithBootstrapButtons = Swal.mixin({
				customClass: {
					confirmButton: 'btn btn-success btn-sm',
					cancelButton: 'btn btn-danger btn-sm'
				},
				buttonsStyling: true
				})
	
		swalWithBootstrapButtons.fire({
	  title: msg,
	  text: subtext,
	  icon: icon,
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: btn_text
	}).then((result) => {
				if (result.value) {
					event.preventDefault();
					document.getElementById(unique_form_name+'-'+id).submit();
				} else if (
					/* Read more about handling dismissals below */
					result.dismiss === Swal.DismissReason.cancel
				) {
					swalWithBootstrapButtons.fire(
					'Cancelled',
					'Your Data  is safe :)',
					'error'
					)
				}
				});
			}
	</script>
@endpush