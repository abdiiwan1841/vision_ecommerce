@extends('layouts.adminlayout')
@section('title','Inventory Cashes')
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
       <form action="{{route('cash.store')}}" method="POST" id="addForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')
          <div class="form-group">
            <label for="received_at">Cash Receive Date</label>
            @php
                $mytime = Carbon\Carbon::now();
            @endphp
            <input type="text" class="form-control @error('received_at') is-invalid @enderror" name="received_at" id="received_at" value="{{$mytime->toDateString()}}">
            @error('received_at')
            <small class="form-error">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group">
            <label for="user">Customer</label>
            <select data-placeholder="Select a User" name="user" id="user" class="form-control @error('user') is-invalid @enderror">
              <option></option>
              @foreach ($users as $user)
                <option value="{{$user->id}}" @if (old('user') == $user->id) selected  @endif>{{$user->name}}</option>
              @endforeach
            </select>
            @error('user')
            <small class="form-error">{{ $message }}</small>
            @enderror
            <div id="user-details"></div> 
          </div>
          <div class="form-group">
            <label for="amount">Amount</label>
          <input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" id="amount" placeholder="Enter Amount" value="{{old('amount')}}">
            @error('amount')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
          <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select data-placeholder="Select Payment Method" name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
              <option></option>
              @foreach ($payment_methods as $pmd)
                <option value="{{$pmd->id}}" @if (old('user') == $pmd->id) selected  @endif>{{$pmd->name}}</option>
              @endforeach
            </select>
            @error('payment_method')
            <small class="form-error">{{ $message }}</small>
            @enderror
          </div>

          <div class="form-group">
            <label for="reference">Reference</label>
          <input type="text" class="form-control @error('reference') is-invalid @enderror" name="reference" id="reference" placeholder="Enter Referance" value="{{old('reference')}}">
            @error('reference')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
      
    @endslot
@endcomponent
<!--End Insert Modal -->







@endsection
@section('content')

<div class="row">
<div class="col-lg-12">
	<div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-lg-6">
          <h5 class="card-title text-left">Inventory Cashes</h5>
        </div>
          <div class="col-lg-6">
            <button type="button" onclick="addMode('{{route('cash.store')}}')" class="btn btn-info btn-sm float-right"><i class="fas fa-plus"></i>  Add New Cashes</button>
           
          </div>
          
        </div>
      
      
    </div>
    <div class="card-body table-responsive">
      <form action="{{route('poscash.result')}}" method="POST">
        @csrf
          <div class="row mb-3 justify-content-center">
            <div class="col-lg-1">
              <div class="form-group">
                <strong>FROM : </strong>
              </div>
            </div>
            <div class="col-lg-3">
           
              <div class="form-group">
                <input type="text" class="form-control @error('start') is-invalid @enderror" name="start" id="start" placeholder="Select Start Date" value="{{$request->start}}">
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
                <input type="text" class="form-control @error('end') is-invalid @enderror" name="end" id="end" placeholder="Select End Date" value="{{$request->end}}">
                @error('end')
                <small class="form-error">{{ $message }}</small>
                @enderror
              </div>
            </div>


            <div class="col-lg-2">
              <div class="form-group">
                <button type="submit" class="btn btn-info">submit</button>
              </div>
             
            </div>
          </div>       
        </form>
      <table class="table table-bordered table-striped table-hover mt-3" id="jq_datatables">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">User</th>
            <th scope="col">Payment Method</th>
            <th scope="col">Amount</th>
            <th scope="col">Reference</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @php
              $i =1;
          @endphp
          @foreach ($cashes as $cash)
          <tr>
              <td>{{$i++}}</td>
              <td>{{$cash->received_at->format('d-m-Y g:i a')}}</td>
              <td>{{$cash->user->name}}</td>
              <td>{{$cash->paymentmethod->name}}</td>
              <td>{{$cash->amount}}</td>
              <td>{{$cash->reference}}</td>
              <td>
                <button class="btn btn-primary btn-sm" id="open_modal" onclick="EditProcess('{{route('cash.edit',$cash->id)}}','{{route('cash.update',$cash->id)}}')"  data-baseurl="{{asset('')}}" ><i class="fas fa-edit"></i></button> 
             
          
                  </td>
          </tr>
          @endforeach
          
    


         
          
        </tbody>
      </table>

    </div>
  </div>
</div>

</div>

@endsection

@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/flatpicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('public/assets/css/dataTables.bootstrap4.min.css')}}"> 
@endpush

@push('js')
@if ($errors->any())
{{-- prevent The Modal Close If Any Error In the Form --}}
<script>

if(sessionStorage.getItem("editMode") === 'true'){
    $('#addDataModal').modal('show');
    $('#addForm').attr('action', sessionStorage.getItem("update_url"));

  }else{
    $('#addDataModal').modal('show');
    $('#addForm').attr('action', sessionStorage.getItem("store_url"));
    putremove = $('input[value="PUT"]').detach();
   
  }

</script>
@endif



<script>
$( "#user" ).change(function() {
    var user_id = $("#user option:selected").val();
    $.get("{{url('/')}}/api/userinfo/"+user_id, function(data, status){
      if(status === 'success'){
        $('#user-details').show();
        $("#user-details").html("<div class='user-deatils'><h4 class='text-center'> "+data.name+"</h4><br><b>Address :</b> "+data.address+"<br><b>Phone :</b> "+data.phone+"<br><b>Email :</b>"+data.inventory_email+"</div>");
        
      }
    });
});


var putremove;

// Exit The Edit Mode 

function addMode(store_url){
  $('#addDataModal').modal('show');
  $('.modal-title').text('Posting Cash');
  if (typeof(Storage) !== "undefined") {
    // Store
    sessionStorage.setItem("editMode",false);
    sessionStorage.setItem("store_url",store_url);
  }
  $('#addForm').attr('action', store_url);
  $('#addForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
  $('#user').val('').trigger('change');
  $('#user-details').hide();
  if(putremove == undefined){
    putremove = $('input[value="PUT"]').detach();
  }
  
}

// Show Current Image On the Form Before Upload

function addProductreadURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#pd_image2').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}


//Select 2

$('#user').select2({
width: '100%',
  theme: "bootstrap"
});
$('#payment_method').select2({
width: '100%',
  theme: "bootstrap"
});


function EditProcess(edit_url, update_url){
$(document).ready(function(){
  $('#user-details').show();
//reset form
$('#addForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
// Go to Edit Mode If Click Edit Button
if (typeof(Storage) !== "undefined") {
  sessionStorage.setItem("editMode",true);
  sessionStorage.setItem("update_url",update_url);
}
$.get(edit_url, function (data) {
    //Change Form Action
    $('#addForm').attr('action', update_url);
    $('.modal-title').text('Edit Previously  Posted Cash');
    //assign data
    $('#received_at').val(data.received_at.substring(0, 10)).trigger('change');
    $('#amount').val(data.amount);
    $('#user').val(data.user_id).trigger('change');
    $('#payment_method').val(data.paymentmethod_id).trigger('change');
    $('#reference').val(data.reference);
    if(putremove != undefined){
      $("#addForm").prepend(putremove);
      putremove = undefined;
    }
    $('#addDataModal').modal('show');
}) 
});
}
</script>



<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>
<script src="{{asset('public/assets/js/datatables.min.js')}}"></script>
<script src="{{asset('public/assets/js/dataTables.bootstrap4.min.js')}}"></script>
<script>

$("#received_at").flatpickr({dateFormat: 'Y-m-d'});
$("#start").flatpickr({dateFormat: 'Y-m-d'});
$("#end").flatpickr({dateFormat: 'Y-m-d'});

$('#jq_datatables').DataTable();

</script>
@endpush