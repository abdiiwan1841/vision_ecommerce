@extends('layouts.adminlayout')
@section('title','Inventory expense')
@section('modal')
      <!-- Modal -->
      <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="expenseModalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div id="expense-form">

                </div>
            </div>
    
          </div>
        </div>
      </div>
@endsection
@section('content')
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4">
                        <h5 class="card-title">Expenses</h5>
                    </div>
                    <div class="col-lg-8">
                    <button type="button" onclick="AdExpense('{{route('expense.store')}}')" class="btn btn-info btn-sm float-right"><i class="fa fa-plus"></i> New </button>
                       
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                <form action="{{route('expense.datewise')}}" method="POST">
                    @csrf
                      <div class="row mb-3 justify-content-center">
                        <div class="col-lg-1">
                          <div class="form-group">
                            <strong>FROM : </strong>
                          </div>
                        </div>
                        <div class="col-lg-3">
                       
                          <div class="form-group">
                            <input type="text" class="form-control @error('start') is-invalid @enderror" name="start" id="start" placeholder="Select Start Date" value="{{Carbon\Carbon::now()->toDateString()}}">
                                @error('start')
                                <small class="form-error">{{ $message }}</small>
                                @enderror
                          </div>
                        </div>
                        <div class="col-lg-1">
                          <div class="form-group">
                            <strong>To : </strong>
                          </div>
                        </div>
    
                        <div class="col-lg-3">
                          <div class="form-group">
                            <input type="text" class="form-control @error('end') is-invalid @enderror" name="end" id="end" placeholder="Select End Date" value="{{Carbon\Carbon::now()->toDateString()}}">
                            @error('end')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                          </div>
                        </div>
    
    
                        <div class="col-lg-2">
                          <div class="form-group">
                            <button type="submit" class="btn btn-info">filter</button>
                          </div>
                         
                        </div>
                      </div>       
                    </form>

                    <div class="row">
                      <div class="col-lg-12">
                      <h3 class="mt-3 mb-5 text-uppercase text-center">FROM {{$request['start']}}  TO {{$request['end']}}</h3>
                        <table class="table table-bordered">
                          <thead class="thead-light">
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Date</th>
                              <th scope="col">Amount</th>
                              <th scope="col">Reasons</th>
                              <th scope="col">Posted By</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody id="datewise_expenses">
                            @foreach($expenses as $key => $item)
                                <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->expense_date->format('d-m-Y')}}</td>
                                <td>{{$item->amount}}</td>
                                <td>{{$item->reasons}}</td>
                                <td>{{$item->admin->name}} <br><small>At {{$item->created_at->format('d-m-Y g:i a')}}</small></td>
                                <td><a class="btn btn-warning btn-sm" onclick="EditExpense({{$item->id}})" href="javascript:void(0)"><i class="fas fa-edit"></i></a></td>
                                </tr>
                            @endforeach
                           
                            
                          </tbody>
                        </table>
                      </div>
                    </div>

            </div>
        </div>

    </div>
  </div>




@endsection

@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/flatpicker.min.css')}}">
@endpush

@push('js')

<script src="{{asset('public/assets/js/axios.min.js')}}"></script>
<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>
<script>
  $("#start").flatpickr({dateFormat: 'Y-m-d'});
  $("#end").flatpickr({dateFormat: 'Y-m-d'});

  var start = '{{$request['start']}}';
  var end = '{{$request['end']}}';
  var url = '{{url('/')}}';
  sessionStorage.clear();
  $('#user').select2({
width: '100%',
  theme: "bootstrap"
});


  function AdExpense(storeurl){
    $("#expenseModalLabel").text('Add New Expense');
    $("#expense-form").html(`  <form id="expense_form">
      <div class="form-group">
        <label for="expense_date">Date</label>
        <input type="text" class="form-control" placeholder="Select Date" name="expense_date" id="expense_date">
        <small class="text-danger expense_date_err"></small>
    </div>

    <div class="form-group">
        <label for="amont">Amount</label>
        <input type="number" class="form-control" placeholder="Enter Amount" name="amount" id="amount">
        <small class="text-danger amount_err"></small>
    </div>

    <div class="form-group">
      <label for="reason">Reasons ( <small>Max 30 Charecters Allowed</small>)</label>
      <textarea class="form-control" name="reason" id="reason" placeholder="Enter Expesne Reasons"></textarea>
     
      <small class="text-danger reason_err"></small>
  </div>
  <div class="form-group">
      <button type="button" id="send_form" onclick="StoreExpense('${storeurl}')" class="btn btn-danger">Submit</button>
  </div>
</form>`);
$("#expense_date").flatpickr({dateFormat: 'Y-m-d'});
    $("#expenseModal").modal('show');
  }







function EditExpense(id){
    $("#expenseModalLabel").text('Edit Expense');
    axios.get(url+'/admin/expense/'+id+'/edit')
    .then(res => { 
      let data = res.data;
      $("#expense-form").html(`<form id="expense_form">

<div class="form-group">
  <label for="expense_date">Date</label>
  <input type="text" class="form-control" placeholder="Select Date" name="expense_date" value="${data.expense_date}" id="expense_date">
  <small class="text-danger expense_date_err"></small>
</div>

<div class="form-group">
  <label for="amont">Amount</label>
  <input type="number" class="form-control" placeholder="Enter Amount" name="amount" id="amount" value="${data.amount}">
  <small class="text-danger amount_err"></small>
</div>

<div class="form-group">
<label for="reason">Reasons ( <small>Max 30 Charecters Allowed</small>)</label>
<textarea class="form-control" name="reason" id="reason" placeholder="Enter Expesne Reasons">${data.reasons}</textarea>

<small class="text-danger reason_err"></small>
</div>
<div class="form-group">
<button type="button" id="send_form" onclick="UpdateExpense(${id})" class="btn btn-danger">Update</button>
</div>
</form>`);
$("#expense_date").flatpickr({dateFormat: 'Y-m-d'});
$("#expenseModal").modal('show');
datewiseExpense();
    })
    .catch(err => {
  console.log(err);
  });



  }


  
function datewiseExpense(){
  axios.get(url+'/admin/expense/datewise/'+start+'/'+end)
  .then(res => {  
  let  data = res.data.data;
  let  expensedata = "";
  data.forEach(function(data,key){
    expensedata += `<tr>
                      <td>${key+1}</td>
                      <td>${data.expense_date}</td>
                      <td>${data.amount}</td>
                      <td>${data.reasons}</td>
                      <td>${data.posted_by} <br><small>At ${data.created_at}</small></td>
                      <td><a class="btn btn-warning btn-sm" onclick="EditExpense(${data.id})" href="javascript:void(0)"><i class="fas fa-edit"></i></a></td>
                    </tr>`;
  })
  $("#datewise_expenses").html(expensedata);
  })

  .catch(err => {
  console.log(err);
  })

}



  function StoreExpense(storeurl){
  $(".text-danger").hide().text("");
	$(".red-border").removeClass("red-border");
	$('#send_form').html('<i class="fas fa-spinner fa-spin"></i> Please Wait...').attr('disabled',true);
	let data = $("#expense_form").serialize();
	axios.post(storeurl,data)
		.then(res => {
		  $('#expenseModal').modal('hide');
     toastr.success(res.data);
     window.location = url+'/admin/expense';
    
		})
			
		.catch(err => {
			let errors = err.response.data.errors;
			console.log(errors);
			Object.keys(errors).forEach(function(value){
				$("#"+value+"").addClass("red-border");
				$("."+value+"_err").text(errors[value][0]);
			});

			$('#send_form').html('submit').attr('disabled',false);
			$(".text-danger").show();
			

		});

  }

  function UpdateExpense(id){
  $(".text-danger").hide().text("");
	$(".red-border").removeClass("red-border");
	$('#send_form').html('<i class="fas fa-spinner fa-spin"></i> Please Wait...').attr('disabled',true);
	let data = $("#expense_form").serialize();
  axios.put(url+'/admin/expense/'+id,data)
		.then(res => {
		  $('#expenseModal').modal('hide');
      datewiseExpense();
     toastr.success(res.data);
		})
			
		.catch(err => {
			let errors = err.response.data.errors;
			console.log(errors);
			Object.keys(errors).forEach(function(value){
				$("#"+value+"").addClass("red-border");
				$("."+value+"_err").text(errors[value][0]);
			});

			$('#send_form').html('update').attr('disabled',false);
			$(".text-danger").show();
			

		});

  }
 
</script>

@endpush


