@extends('layouts.adminlayout')
@section('title','Ecommerce Dashboard')

	@section('modal')
			<!-- Modal -->
	<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title" id="orderModalLabel"></h5>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div id="ordermodalbody" class="modal-body">
			  
			</div>
		  </div>
		</div>
	  </div>
	@endsection

@section('content')

<div class="row">
	<div class="col-12 col-md-4 col-lg-4">
		<div class="card px-3 py-3 mb-3">
			<div class="text-center">
				<img src="{{asset('public/uploads/logo/cropped/'.$CompanyInfo->logo)}}" alt="">
			<h5>{{$CompanyInfo->company_name}}</h5>
			<small>{{$CompanyInfo->email}} <br> {{$CompanyInfo->phone}} <br>{{$CompanyInfo->address}}</small>

			<h5>E-commerce Dashboard </h5>
	
			</div>
		</div>
	</div>
	<div class="col-12 col-md-8 col-lg-8">

	<div class="row">
		@can('Ecommerce Dashboard')
	<div class="col-6 col-md-4 col-lg-4">
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

	  <div class="col-6 col-md-4 col-lg-4">
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

	  <div class="col-6 col-md-4 col-lg-4">
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
	  
	  <div class="col-6 col-md-4 col-lg-4">
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



	<div class="col-6 col-md-4 col-lg-4">
		<!-- small box -->
		<div class="small-box" style="background: #B53471">
		  <div class="inner">
		
		  <h3>{{round($current_month_order)}}</h3>

		  <span class="text-white"  >{{\Carbon\Carbon::now()->format('F')}} Ecommerce Order upto <h5 class="badge badge-dark">{{\Carbon\Carbon::now()->format('d-m-Y g:i a')}} </h5></span>
		  </div>
		  
		  
		</div>
	  </div>


	  <div class="col-6 col-md-4 col-lg-4">
		<!-- small box -->
		<div class="small-box" style="background: #A3CB38">
		  <div class="inner">
		
		  <h3>{{round($current_year_order)}}</h3>

		  <span class="text-white"  >Total Order In Year <b> {{\Carbon\Carbon::now()->format('Y')}} </b> upto <h5 class="badge badge-dark">{{\Carbon\Carbon::now()->format('d-m-Y g:i a')}} </h5></span>
		  </div>
		  
		  
		</div>
	  </div>
	  @endcan


	</div>
	</div>
</div>

<div class="row">
	@can('Ecommerce Dashboard')
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header bg-light-green">
				<span class="card-title text-white"> <strong>Ecommerce Section</strong></span>
			</div>
			<div class="card-body">
				<h4 class="mb-3">Orders awaiting processing</h4>
				@if(count($pending_orders) > 0)
				<table class="table table-bordered table-striped" style="background: #f7f1e3;font-family: 'Courier New', Courier, monospace">
					<tr class="thead-light">
						<th>Sl</th>
						<th>Pending List</th>
					</tr>
					@foreach ($pending_orders as $key => $pending_item)
					 <tr id="order-{{$pending_item->id}}">
					    <th class="align-middle">{{$key+1}}</th>
						<td><a style="text-decoration: underline;color: #000" onclick="ShowOrderDetails('{{route('admin.orderdetails',$pending_item->id)}}','{{route('order.approval',$pending_item->id)}}','{{route('order.cancel',$pending_item->id)}}')" href="javascript:void(0)" style="color: #000" href=""><b>ORDER ID:</b> {{$pending_item->invoice_id}} <br> <b>Date:</b> {{$pending_item->ordered_at->format('d-m-Y g:i a')}} <br><b>Customer:</b> {{$pending_item->user->name}} <br><b>Amount:</b> {{$pending_item->amount}} <br><b>Status: </b> <span class="badge badge-warning">pending</span></a></td>
						</tr>

					@endforeach
					
				</table>

				@else
				<p class="alert alert-success">No Pending Order Found</p>
				@endif

		<hr>

		<h4 class="mb-3">Pending Cash</h4>

		@if(count($pending_cash) > 0)
		<table class="table table-bordered">
			<tr class="thead-light">
				<th>Sl</th>
				<th>Cashinfo</th>
			</tr>

			@foreach($pending_cash as $key =>  $item)
				
			
		<tr id="cashes-{{$item->id}}">
			    <th class="align-middle">{{$key+1}}</th>
				<td><table class="table table-sm">
					<tr>
						<td>Date:</td>
					<td>{{$item->ordered_at->format('d-M-Y g:i a')}}</td>
					</tr>
					<tr>
						<td>Customer:</td>
						<td>{{$item->user->name}}</td>
					</tr>
					<tr>
						<td>Amount:</td>
						<th>{{round($item->cash)}}/-</th>
					</tr>
					<tr>
						<td>References:</td>
					    <td><small>{{$item->references}}</small></td>
					</tr>
					<tr>
						<td>Action: </td>
					<td><button onclick="CashPopupAlert('{{route('order.cashapprove',$item->id)}}','{{$item->user->name}}',{{round($item->cash)}})" type="button" class="btn btn-warning btn-sm">Approve</button></td>
					</tr>
				</table></td>
			</tr>

			@endforeach
		</table>
		@else

		<p class="alert alert-success">No Pending Cash Found</p>
		@endif
	


			<h4 class="mb-3">Last 10 Ecommerce Orders</h4>

			@if(count($last_ten_orders) > 0)
				
			<table class="table table-sm table-bordered" style="font-size: 14px;">
			  <thead class="thead-light">
				<tr>
				  <th class="align-middle">Sl</th>
				  <th class="align-middle">Order Info</th>
				</tr>
			  </thead>
			  <tbody>

				  @foreach ($last_ten_orders as $key =>  $order_item)
			  <td class="align-middle"><h4>{{$key+1}}</h4></td>
					  <td class="align-middle">
							<table class="table table-striped">
								<tr>
									<th>Date: </th>
									<td><a href="{{route('order.show',$order_item->id)}}">{{$order_item->ordered_at->format('d-m-Y g:i a')}} </a></td>
								</tr>
								<tr>
									<th>ID: </th>
									<td>{{$order_item->invoice_id}}</td>
								</tr>
								<tr>
									<th>Customer</th>
									<td>{{$order_item->user->name}}</td>	
								</tr>
								<tr>
									<th>Phone</th>
									<td>{{$order_item->user->phone}}</td>
								</tr>
								<tr>
									<th>Address</th>
									<td>{{$order_item->user->address}}</td>
								</tr>
								<tr>
									<th>Order Status</th>
									<td>{!!FashiOrderStatus($order_item->order_status)!!}</td>
								</tr>
								@if($order_item->order_status != 2)
								<tr>
									 <th>Payment Status</th>
									 <td>{!!FashiPaymentStatus($order_item->payment_status)!!}</td>
								</tr>
								
								@endif

								@if($order_item->payment_status == 1)
								<tr>
									<th>Amount: </th>
									<td><h4>{{round($order_item->amount)}}</h4></td>
								</tr>
								@endif
							</table>  
						
						</td>

			
					  </tr>
				  @endforeach
				  

			  </tbody>
			</table>
		  @else
		  <div class="row">
			  <span class="alert alert-success">No Order Found</span>
		  </div>
		  
		  @endif

		  	<hr>

				<h4 class="mb-3">Todays Orders</h4>

				@php 

				$approveorder_amount_sum = 0;
				$pendingorder_amount_sum = 0;
				$cancelorder_amount_sum = 0;
				@endphp

				@if(count($todays_order) > 0)
				
				  <div class="table-responsive">
				  <table class="table table-sm table-bordered" style="font-size: 14px;">
					<thead class="thead-light">
					  <tr>
						<th style="width: 120px;" class="align-middle">Date</th>
						<th class="align-middle">Customer</th>
						<th class="align-middle">Address</th>
						<th class="align-middle">Amount</th>
						 <th class="align-middle">Status</th>
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
							<td class="align-middle"><a href="{{route('order.show',$todays_order_item->id)}}">{{$todays_order_item->ordered_at->format('d-m-Y g:i a')}} </a></td>
							
							<td class="align-middle">{{$todays_order_item->user->name}}</td>
							<td class="align-middle"><small>{{$todays_order_item->address}}</small></td>
							<td class="align-middle">{{round($todays_order_item->amount)}}</td>
							<td>{!!FashiOrderStatus($todays_order_item->order_status)!!}</td>
							</tr>
						@endforeach
						

					</tbody>
				  </table>
				  <div class="row justify-content-end">
				  <div class="col-lg-6">
				
				  <table class="table table-sm">
					<tr>
						<td>Total Approve Order Amount</td>
						<td>{{round($approveorder_amount_sum)}}</td>

					</tr>

					<tr>
						<td>Total Approve Order Amount</td>
						<td>{{round($cancelorder_amount_sum)}}</td>

					</tr>

					<tr>
						<td>Total Pending Order Amount</td>
						<td>{{round($pendingorder_amount_sum)}}</td>

					</tr>
				  </table>
				</div>
				  </div>
				  </div>
	
				@else
				<div class="row">
					<span class="alert alert-success">No Order Found Today</span>
				</div>
				
				@endif
				<hr>

				<h4 class="mb-3">Todays Ecommerce Cash</h4>
			    @php
					$cashsum =0;
				@endphp
				@if(count($todays_ecom_cash) > 0)
				  
				<table class="table table-sm table-bordered" style="font-size: 14px;">
				  <thead class="thead-light">
					<tr>
					  <th style="width: 120px;" class="align-middle">Date</th>
					  <th class="align-middle">Customer</th>
					  <th class="align-middle">Amount</th>
					  <th class="align-middle">Payment Status</th>
					</tr>
				  </thead>
				  <tbody>
					
  
					  @foreach ($todays_ecom_cash as $todays_ecom_cash_item)
						  @php 
						  	$cashsum = $cashsum+$todays_ecom_cash_item->cash;
						  @endphp
						  <tr>
						  <td class="align-middle">{{$todays_ecom_cash_item->paymented_at}}</td>
						  <td class="align-middle">{{$todays_ecom_cash_item->user->name}}</td>
						  <td class="align-middle">{{round($todays_ecom_cash_item->cash)}}</td>
						  <td class="align-middle">{!!FashiPaymentStatus($todays_ecom_cash_item->payment_status)!!}</td>
	
						  </tr>
					  @endforeach
					  

				  </tbody>
				</table>
			  @else
			  <div class="row">
				<span class="alert alert-success">No Cash Found Today</span>
			  </div>
			  
			  @endif

			  <hr>
			  <h4 class="mb-3">Todays Ecommerce Returns</h4>

			  @if(count($todays_ecom_returns) > 0)
				  
				<table class="table table-sm table-bordered" style="font-size: 14px;">
				  <thead class="thead-light">
					<tr>
					  <th class="align-middle">Date</th>
					  <th class="align-middle">Customer</th>
					  <th class="align-middle">Amount</th>
					</tr>
				  </thead>
				  <tbody>
		
					@foreach ($todays_ecom_returns as $todays_ecom_return_item)
					<tr>
					<td class="align-middle">{{$todays_ecom_return_item->returned_at->format('d-m-Y g:i a')}}</td>
					<td class="align-middle">{{$todays_ecom_return_item->user->name}}</td>
					<td class="align-middle">{{round($todays_ecom_return_item->amount)}}</td>
	
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
			  

			
			</div>
		</div>

		
	</div>
	<div class="col-lg-4">
		<div class="card">
			<div class="card-header bg-warning">
				<span class="card-title text-white"> <strong>Pending Shipping For  Ecommerce Order</strong></span>
			</div>
			<div class="card-body">
				@if(count($pending_shipping) > 0)

				@foreach ($pending_shipping as $shipping_item)
					
				
				<table class="table table-sm table-bordered">

					<tr>
					  <td><small> Order #{{$shipping_item->id}} - {{$shipping_item->user->name}} -  <b> {{$shipping_item->user->address}} </b> </small> <form id="shipped-{{$shipping_item->id}}"  action="{{route('order.shipped',$shipping_item->id)}}" method="POST" style="display: inline">
						@csrf
						@method('PUT')
						<input type="hidden" name="shipping" value="1">
						<button onclick="Confirm({{$shipping_item->id}},'shipped','Are you sure You Want To Mark As Shipped For   Order ID # {{$shipping_item->id}} ?','Yes Confirm','question','')" type="button" class="btn btn-sm btn-dark"><i class="fas fa-check"></i> Mark As Delivered</button>
					</form></td>
					</tr>
				</table>
				@endforeach
				@endif

		</div>
	</div>
	@endcan
</div>


@endsection


@push('js')
<script src="{{asset('public/assets/js/axios.min.js')}}"></script>
<script>
	var approvepermission = 0;
	var cancelpermission = 0;
	const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-warning  mr-3'
  },
  buttonsStyling: false
});


	@can('Ecom Order Cancel')
	cancelpermission = 1;
	@endcan

	@can('Ecom Order Approval')
	approvepermission = 1;
	@endcan

	@can('Ecommerce Dashboard')
	var approveorder_amount_sum =  '{{round($approveorder_amount_sum)}}';
	var pendingorder_amount_sum = 	'{{round($pendingorder_amount_sum)}}';
	var cancelorder_amount_sum = '{{round($cancelorder_amount_sum)}}';
	var totalcash = '{{round($cashsum)}}';

	$("#approve_order").text(approveorder_amount_sum);
	$("#pending_order").text(pendingorder_amount_sum);
	$("#canceled_order").text(cancelorder_amount_sum);
	$("#cashsum").text(totalcash);

	@endcan

	function ApproveOrder(approveurl){
		axios.put(approveurl)
	    .then(function (response) {
			var orderinfo = response.data;
			$("#order-"+orderinfo.id).html(`<th>Notify: </th><td><p class="alert alert-success">Order Successfully Approved Order Id: ${orderinfo.invoice_id} Amount: ${orderinfo.amount}</p></td>`);
			$("#orderModal").modal('hide');
		})

		.catch(function (error) {
			toastr.error(error.response.data.message,error.response.status)
			console.log(error.response);			
		});
	}


	function CancelOrder(cancelurl){

		let conf = confirm('Are you sure you want to cancel the order');

		if(conf){

		axios.put(cancelurl)
	    .then(function (response) {
			var orderinfo = response.data;
			$("#order-"+orderinfo.id).html(`<th>Notifications: </th><td><p class="alert alert-danger">Order Successfully Canceled Order Id: ${orderinfo.invoice_id} Amount: ${orderinfo.amount}</p></td>`);
			$("#orderModal").modal('hide');
		})

		.catch(function (error) {
			toastr.error(error.response.data.message,error.response.status)
			console.log(error.response);			
		})

		}
	}



	function ShowOrderDetails(orderdetailsurl,orderapproveurl,ordercancelurl){
    axios.get(orderdetailsurl)
	.then(function (response) {

	let orderdata =  response.data;
	let vat = Math.round(orderdata.vat)+Math.round(orderdata.tax)
	let discount = Math.round(orderdata.discount);
	let shipping = orderdata.shipping;
	let productdata = response.data.product;
	let productinfo = "";
	$("#orderModalLabel").html('<small>'+response.data.user.name+'</small>')
	$(".modal-header").css("background","#fed330");
	let pdsum= 0;
	productdata.forEach(function(item, index,arr){
		let qty = item.pivot.qty;
		let price = Math.round(item.pivot.price)
		let stotal = qty*price
		pdsum += stotal;
		productinfo += `<tr>
		<td>${index+1}</td>
		<td>${item.product_name}</td>
		<td>${qty}</td>
		<td>${price}</td>
		<td>${qty*price}</td>
		</tr>`;
	})

	let grandtotal = (pdsum+parseFloat(shipping)+((vat*pdsum)/100)-((pdsum*discount)/100));
	let discountamount = (pdsum*discount)/100;

	var approvebutton = "";
	var cancelbutton = "";
	if(approvepermission == true){
		 approvebutton = `<button type="button" onclick="ApproveOrder('${orderapproveurl}')" class="btn btn-success btn-sm">Approve</button>`;
	}

	if(cancelpermission == true){
		 cancelbutton = `<button type="button" onclick="CancelOrder('${ordercancelurl}')" class="btn btn-danger btn-sm">Cancel</button>`;
	}



	let customerinfo = `<table class="table table-sm">
	<tr>
		<td>Date</td>
		<td>${new Date(orderdata.ordered_at)}</td>
	</tr>
	<tr>
		<td>Order ID:</td>
		<td>${orderdata.invoice_id}</td>
	</tr>
	<tr>
		<td>Customer:</td>
		<td>${orderdata.user.name}</td>
	</tr>
	<tr>
		<td>Phone:</td>
		<td>${orderdata.user.phone}</td>
	</tr>
	<tr>
		<td>Address:</td>
		<td>${orderdata.address}</td>
	</tr>
</table> <br> <h5 class="text-center">Product Information</h5><br><table class="table table-sm">
	
	<tr>
		<th>Sl.</th>
		<th>Name</th>
		<th>Qty</th>
		<th>Price</th>
		<th>Total</th>
	</tr>

	${productinfo}
	
	</table> 	<table class="table table-sm">
		<tr>
			<th>Subtotal: </th>
			<th>${pdsum}</th>
		</tr>
		<tr>
			<th>Discount ( ${discount}%) </th>
			<th>${Math.round(discountamount)}</th>
		</tr>
		<tr>
			<th>Shipping: </th>
			<th>${shipping}</th>
		</tr>
		<tr>
			<th>Vat: (${vat}%) </th>
			<th>${(vat*pdsum)/100}</th>
		</tr>
		<tr>
			<th>Total: </th>
			<th>${Math.round(grandtotal)}</th>
		</tr>
	</table>  <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button> ${cancelbutton} ${approvebutton}
		 </div>`;

			$("#ordermodalbody").html(customerinfo).css('background', '#F8EFBA');
		})

		$("#orderModal").modal('show');
	}



	function CashApprove(cash_aprove_url){
		axios.post(cash_aprove_url)
		.then(function (response) {
			
			$("#cashes-"+response.data.id).html(`<th>Notify: </th><td><p class="alert alert-success">Cash Successfully Approved  Amount: ${response.data.cash}</td>`);

		swalWithBootstrapButtons.fire(
			'Approved Successfully!',
			'Your Data Has Been Stored',
			'success'
			)
			
		})
		.catch(function (error) {
			toastr.error(error.response.data.message,error.response.status)
			console.log(error.response);			
		});

	}






	function CashPopupAlert(cash_aprove_url="",customer="",amount=""){
	swalWithBootstrapButtons.fire({
  title: 'Are you sure? '+customer+' Amount: '+Math.round(amount)+'/-',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Yes, approve it!',
  cancelButtonText: 'Later',
  reverseButtons: true
}).then((result) => {
  if (result.value) {
	 CashApprove(cash_aprove_url);
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {
    swalWithBootstrapButtons.fire(
      'Denied',
      'No More Changes On Database :)',
      'error'
    )
  }
});

	}



	function Confirm(id,unique_form_name,msg,btn_text,icon='warning',subtext='You won\'t be able to revert this!',){

	
		swalWithBootstrapButtons.fire({
	  title: msg,
	  text: subtext,
	  icon: icon,
	  showCancelButton: true,
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
					'Denied',
					'Your Data  is safe :)',
					'error'
					)
				}
				});
			}
	</script>
@endpush