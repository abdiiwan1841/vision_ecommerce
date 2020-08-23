@extends('layouts.adminlayout')
@section('title','Admin Dashboard')

@section('content')
@php
	$sales_summation = 0;	
	$cash_summation = 0;	
	$return_summation = 0;	
	$pending_sales_summation = 0;	
@endphp

<div class="row">
	<div class="col-lg-4">
		<div class="card px-3 py-3 mb-3">
			<div class="text-center">
				@if($general_opt_value['inv_diff_invoice_heading'] == 1)
				<img style="width: 300px" src="{{asset('public/uploads/logo/invoicelogo/'.$general_opt_value['inv_invoice_logo'])}}" alt="">
				<h5>{{$general_opt_value['inv_invoice_heading']}}</h5>
				<small>{{$general_opt_value['inv_invoice_email']}}  <br>{{$general_opt_value['inv_invoice_address']}}</small>

				@else
				<img src="{{asset('public/uploads/logo/cropped/'.$CompanyInfo->logo)}}" alt="">
			<h5>{{$CompanyInfo->company_name}}</h5>
			<small>{{$CompanyInfo->email}} <br> {{$CompanyInfo->phone}} <br>{{$CompanyInfo->address}}</small>
			@endif

			<h5>Inventory Dashboard </h5>
	
			</div>
		</div>
	</div>
	<div class="col-lg-8">

	<div class="row">
	<div class="col-lg-4">
		<!-- small box -->
		<div class="small-box bg-info">
		  <div class="inner">
		
		  <h3 id="sales"></h3>

			<p>Todays Sales Amount</p>
		  </div>
		  <div class="icon">
			<i class="fas fa-balance-scale-right"></i>
		  </div>
		  
		</div>
	  </div>



	  <div class="col-lg-4">
		<!-- small box -->
		<div class="small-box bg-light-green">
		  <div class="inner">
			<h3 id="cashes"></h3>

			<p>Todays Cash Amount</p>
		  </div>
		  <div class="icon">
			<i class="fas fa-money-bill-alt"></i>
		  </div>
		  
		</div>
	  </div>

	  <div class="col-lg-4">
		<!-- small box -->
		<div class="small-box bg-danger">
		  <div class="inner">
			<h3 id="returns"></h3>

			<p>Todays Return Amount</p>
		  </div>
		  <div class="icon">
			<i class="fas fa-undo-alt"></i>
		  </div>
		  
		</div>
	  </div>


	  

	<div class="col-lg-4">
		<div class="info-box-4 hover-expand-effect">
			<div class="icon">
				<i class="fas fa-undo-alt"></i>
			</div>
			<div class="content">
				<div class="text">Toral Return Count</div>
			<div class="number">{{count($todays_pos_returns)}}</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="info-box-4 hover-expand-effect">
			<div class="icon">
				<i class="fas fa-balance-scale-right"></i>
			</div>
			<div class="content">
				<div class="text">Toral Sales Count</div>
			<div class="number">{{count($todays_pos_sales)}}</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="info-box-4 hover-expand-effect">
			<div class="icon">
				<i class="fas fa-money-bill-alt"></i>
			</div>
			<div class="content">
				<div class="text">Toral Cash Count</div>
			<div class="number">{{count($todays_pos_cash)}}</div>
			</div>
		</div>
	</div>


	</div>
	</div>
</div>

<div class="row">


	<div class="col-lg-8 mt-5">

		<div class="card">
			<div class="card-header bg-light-red">
				<span class="card-title text-white"> <strong>Inventory Section</strong></span>
			</div>
			<div class="card-body">

				@if(Auth::user()->role->id != 4)
				<h5>Today's Sale</h5>
				@if(count($todays_pos_sales) > 0)
				  
				<table class="table table-sm table-bordered" style="font-size: 14px;">
				  <thead class="thead-light">
					<tr>
					  <th class="align-middle">ID</th>
					  <th class="align-middle">Date</th>
					  <th class="align-middle">Customer</th>
					  <th class="align-middle">Amount</th>
					</tr>
				  </thead>
				  <tbody>
					
					@foreach ($todays_pos_sales as $todays_sales_item)
					@php
						$sales_amount = round($todays_sales_item->amount);
						$sales_summation = $sales_summation+$sales_amount;	
					@endphp
					<tr @if($todays_sales_item->sales_status == 2) style="background: #f8a5c2" @endif>
					<td class="align-middle">#{{$todays_sales_item->id}}</td>
					<td  class="align-middle"><a data-toggle="tooltip" data-placement="top" title="Service Provided by {{$todays_sales_item->provided_by}}  at {{$todays_sales_item->created_at->format('d-M-Y g:i a')}}    Click Here For Details"  class="btn btn-link" href="{{route('viewsales.show',$todays_sales_item->id)}}">{{$todays_sales_item->sales_at->format('d-M-Y g:i a')}} </a></td>
					<td class="align-middle">{{$todays_sales_item->user->name}}</td>
					<td class="align-middle">{{$sales_amount}}</td>
					
					</tr>
					@endforeach
					  
					
				  </tbody>
				  
				</table>
				<div class="row justify-content-end">
					<div class="col-lg-3 ">
						<table class="table">
							<tr>
								<th>Total</th>
								<th>{{$sales_summation}}</th>
							</tr>
						</table>
					</div>
				</div>
				
			  @else
				<div class="row">
				<span class="alert alert-success">No Sales Found Today</span>
			</div>
		
			  @endif
			  <hr>


			  <h5>Todays Cash</h5>
			  @if(count($todays_pos_cash) > 0)
			  
			<table class="table table-sm table-bordered" style="font-size: 14px;">
			  <thead class="thead-light">
				<tr>
				  <th class="align-middle">ID</th>
				  <th class="align-middle">Date</th>
				  <th class="align-middle">Customer</th>
				  <th class="align-middle">Amount</th>
				  <th class="align-middle">Ref</th>
				</tr>
			  </thead>
			  <tbody>
				
				@foreach ($todays_pos_cash as $todays_pos_cash_item)
			   @php
					$cash_amount = round($todays_pos_cash_item->amount);
					$cash_summation = $cash_summation+$cash_amount;	
				@endphp
				<tr @if($todays_pos_cash_item->status == 2) style="background: #f8a5c2" @elseif($todays_pos_cash_item->status == 1)  style="background: #b8e994" @endif>
				<td class="align-middle">#{{$todays_pos_cash_item->id}}</td>
				<td data-toggle="tooltip" data-placement="top" title="Posted By {{$todays_pos_cash_item->posted_by}}   At {{$todays_pos_cash_item->created_at->format('d-M-Y g:i a')}}  class="align-middle">{{$todays_pos_cash_item->received_at->format('d-m-Y g:i a')}}</td>
				<td class="align-middle">{{$todays_pos_cash_item->user->name}}</td>
				<td class="align-middle">{{$cash_amount}}</td>
				<td class="align-middle">{{$todays_pos_cash_item->reference}}</td>
				
				</tr>
				@endforeach
				  

			  </tbody>
			</table>
			<div class="row justify-content-end">
				<div class="col-lg-3 ">
					<table class="table">
						<tr>
							<th>Total</th>
							<th>{{$cash_summation}}</th>
						</tr>
					</table>
				</div>
			</div>
		  @else
		  <div class="row">
			<span class="alert alert-success">No Cash Found Today</span>
		  </div>
		  
		  
		  @endif

		  <hr>
		  <h5>Todays Returns</h5>

		  @if(count($todays_pos_returns) > 0)
		  
		<table class="table table-sm table-bordered" style="font-size: 14px;">
		  <thead class="thead-light">
			<tr>
			  <th class="align-middle">ID</th>
			  <th class="align-middle">Date</th>
			  <th class="align-middle">Customer</th>
			  <th class="align-middle">Amount</th>
			</tr>
		  </thead>
		  <tbody>

			@foreach ($todays_pos_returns as $todays_pos_return_item)
			@php
			$return_amount = round($todays_pos_return_item->amount);
			$return_summation = $return_summation+$return_amount;	
		    @endphp
			<tr>
			<td class="align-middle">#{{$todays_pos_return_item->id}}</td>
			<td class="align-middle"><a data-toggle="tooltip" data-placement="top" title="Service Provided by {{$todays_pos_return_item->returned_by}}     at {{$todays_pos_return_item->created_at->format('d-M-Y g:i a')}} - Click Here For Details" href="{{route('viewreturns.show',$todays_pos_return_item->id)}}">{{$todays_pos_return_item->returned_at->format('d-m-Y g:i a')}}</a></td>
			<td class="align-middle">{{$todays_pos_return_item->user->name}}</td>
			<td class="align-middle">{{$return_summation}}</td>
			
			</tr>
			@endforeach
			  

		  </tbody>
		</table>
		<div class="row justify-content-end">
			<div class="col-lg-3 ">
				<table class="table">
					<tr>
						<th>Total</th>
						<th>{{$return_summation}}</th>
					</tr>
				</table>
			</div>
		</div>
	  @else
		<div class="row">
			<span class="alert alert-success">No Returns Found Today</span>
		</div>
		

	  
	  @endif


	  @endif


	  <hr>

	    <h5>Last Ten Delivery </h5>
				@if(count($last_ten_dlv) > 0)
				  
				<table class="table table-sm table-bordered" style="font-size: 14px;">
				  <thead class="thead-light">
					<tr>
					  <th class="align-middle">ID</th>
					  <th class="align-middle">Invoice Date</th>
					  <th class="align-middle">Customer</th>
					  <th class="align-middle">Delivery Status</th>
					</tr>
				  </thead>
				  <tbody>
					
					@foreach ($last_ten_dlv as $last_ten_item)
	
					<tr @if($last_ten_item->sales_status == 2) style="background: #f8a5c2" @endif>
					<td class="align-middle">#{{$last_ten_item->id}}</td>
					<td  class="align-middle"><a data-toggle="tooltip" data-placement="top" title="Service Provided by {{$last_ten_item->provided_by}}  at {{$last_ten_item->created_at->format('d-M-Y g:i a')}}    Click Here For Details"  class="btn btn-link" href="{{route('viewsales.show',$last_ten_item->id)}}">{{$last_ten_item->sales_at->format('d-F-Y')}} </a></td>
					<td class="align-middle">{{$last_ten_item->user->name}}</td>
					<td class="align-middle">{!!FashiShippingStatus($last_ten_item->delivery_status)!!}</td>

					
					</tr>
					@endforeach
					  
					
				  </tbody>
				  
				</table>

				
			  @else
				<div class="row">
				<span class="alert alert-success">No Delivery Found</span>
			</div>
		
			  @endif
			  <hr>


			</div>
	</div>



</div>
<div class="col-lg-4 mt-5">

	@if(Auth::user()->role->id != 4)
	<!-- Card Start -->
	<div class="card">
		<div class="card-header">
			<strong>Sales Invoice Pending For Approval</strong>
		</div>
	<div class="card-body">

	@if(count($pending_sales) > 0)
	  
	<table class="table table-sm table-bordered" style="font-size: 14px;">
	  <thead class="thead-light">
		<tr>
		  <th class="align-middle">Sl</th>
		  <th class="align-middle">Pending  List</th>
		</tr>
	  </thead>
	  <tbody>
		
		@foreach ($pending_sales as $key => $pending_sales_item)
		@php
			$sales_amount = round($pending_sales_item->amount);
		@endphp
		<tr style="background: #f6e58d">
		<td  class="align-middle"><strong>{{$key+1}}</strong></td>
		<td  class="align-middle"><a style="color: #000;text-decoration: underline" data-toggle="tooltip" data-placement="top" title="Service Provided by {{$pending_sales_item->provided_by}}  at {{$pending_sales_item->created_at->format('d-M-Y g:i a')}}   - Click Here For Details"  class="btn btn-link" href="{{route('viewsales.show',$pending_sales_item->id)}}"> <small>{{$pending_sales_item->sales_at->format('d-M-Y g:i a')}} </small> <br> <strong>{{$pending_sales_item->user->name}} =  {{$sales_amount}} </strong>  </a></td>
		
		</tr>
		@endforeach
		  
		
	  </tbody>
	  
	</table>

	
  @else
<div class="row">
	<span class="alert alert-success">No Pending Sales Found</span>
</div>
  @endif
  <hr>
</div>
	</div>
<!-- End -->





	<div class="card mt-3">
		<div class="card-header bg-dark text-white">
			<strong>Cash Pending For Approval</strong>
		</div>
	<div class="card-body">

	@if(count($pending_cash) > 0)
	  
	<table class="table table-sm table-bordered" style="font-size: 14px;">
	  <thead class="thead-light">
		<tr>
		  <th class="align-middle">Sl</th>
		  <th class="align-middle">Pending Cash</th>
		</tr>
	  </thead>
	  <tbody>
		
		@foreach ($pending_cash as $key => $pending_cash_item)
		@php
			$sales_amount = round($pending_cash_item->amount);
		@endphp
		<tr style="background: #ffcccc">
		<td  class="align-middle"><strong>{{$key+1}}</strong></td>
		<td  class="align-middle"><a style="color: #000;text-decoration: underline" data-toggle="tooltip" data-placement="top" title="Service Provided by {{$pending_cash_item->posted_by}} at {{$pending_cash_item->created_at->format('d-M-Y g:i a')}} - Click Here For Details"  class="btn btn-link" href="{{route('invdashboard.cashdetails',$pending_cash_item->id)}}"> <b> {{$pending_cash_item->user->name}} = {{$pending_cash_item->amount}} </b> <br> ( <small>{{$pending_cash_item->received_at->format('d-M-Y')}}</small> )  </a></td>
		</tr>
		@endforeach
		  
		
	  </tbody>
	  
	</table>

	
  @else
<div class="row">
	<span class="alert alert-success">No Pending Cash Found</span>
</div>
  @endif
  <hr>
</div>
	</div>



<!-- End -->

	<!-- Card Start -->
	<div class="card mt-3">
		<div class="card-header bg-primary text-white">
			<strong>Return Invoice Pending For Approval</strong>
		</div>
	<div class="card-body">

	@if(count($pending_returns) > 0)
	  
	<table class="table table-sm table-bordered" style="font-size: 14px;">
	  <thead class="thead-light">
		<tr>
		  <th class="align-middle">Sl</th>
		  <th class="align-middle">Pending  List</th>
		</tr>
	  </thead>
	  <tbody>
		
		@foreach ($pending_returns as $key => $pending_returns_item)
		@php
			$sales_amount = round($pending_returns_item->amount);
		@endphp
		<tr style="background: #f8c291">
		<td  class="align-middle"><strong>{{$key+1}}</strong></td>
		<td  class="align-middle"><a style="color: #000;text-decoration: underline" data-toggle="tooltip" data-placement="top" title="Service Provided by {{$pending_returns_item->provided_by}}  at {{$pending_returns_item->created_at->format('d-M-Y g:i a')}}   - Click Here For Details"  class="btn btn-link" href="{{route('returnproduct.show',$pending_returns_item->id)}}"> <small>{{$pending_returns_item->returned_at->format('d-M-Y g:i a')}} </small> <br> <strong>{{$pending_returns_item->user->name}} =  {{$sales_amount}} </strong>  </a></td>
		
		</tr>
		@endforeach
		  
		
	  </tbody>
	  
	</table>

	
  @else
<div class="row">
	<span class="alert alert-success">No Pending Returns Found</span>
</div>
  @endif
  <hr>
</div>
	</div>
<!-- End -->

@endif




	
	<!-- Card Start -->
	<div class="card mt-3">
		<div class="card-header bg-warning">
			<strong>Invoice Pending For Delivery</strong> <br><small>N.B: Only a deliverman user can mark this as delivered</small>
		</div>
	<div class="card-body">

	@if(count($pending_delivery) > 0)
	  
	<table class="table table-sm table-bordered" style="font-size: 14px;">
	  <thead class="thead-light">
		<tr>
		  <th class="align-middle">Sl</th>
		  <th class="align-middle">Pending  List</th>
		</tr>
	  </thead>
	  <tbody>
		
		@foreach ($pending_delivery as $key => $pending_delivery_item)
		@php
			$sales_amount = round($pending_delivery_item->amount);
		@endphp
		<tr style="background: #b8e994">
		<td  class="align-middle"><strong>{{$key+1}}</strong></td>
		<td  class="align-middle"><a style="color: #000;text-decoration: underline" data-toggle="tooltip" data-placement="top" title="Service Provided by {{$pending_delivery_item->provided_by}}  at {{$pending_delivery_item->created_at->format('d-M-Y g:i a')}}   - Click Here For Details"  class="btn btn-link" href="{{route('viewsales.show',$pending_delivery_item->id)}}"> <small>{{$pending_delivery_item->sales_at->format('d-M-Y g:i a')}} </small> <br> <strong>{{$pending_delivery_item->user->name}} </strong> <br> <small>Delivery Status:   </small> {!!FashiShippingStatus($pending_delivery_item->delivery_status)!!} </a></td>
		
		</tr>
		@endforeach
		  
		
	  </tbody>
	  
	</table>

	
  @else
<div class="row">
	<span class="alert alert-success">No Pending Delivery Found</span>
</div>
  @endif
  <hr>
</div>
	</div>
<!-- End -->


	

	




</div>

</div>


@endsection


@push('js')
<script>
	var sales_amount = '{{$sales_summation}}';
	var cash_amount = '{{$cash_summation}}';
	var return_amount = '{{$return_summation}}';

	$("#sales").text(sales_amount);
	$("#cashes").text(cash_amount);
	$("#returns").text(return_amount);

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