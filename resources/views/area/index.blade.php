@extends('layouts.adminlayout')
@section('title','Area')
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
      Add Area
    @endslot

    @slot('modal_form') 
       <form action="{{route('area.store')}}" method="POST" id="addForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')
          <div class="form-group">
            <label for="category">District</label>
            <select data-placeholder="Select a District" name="district" id="district" class="form-control @error('district') is-invalid @enderror">
              <option></option>
              @foreach ($districts as $district)
                <option value="{{$district->id}}" @if (old('district') == $district->id) selected  @endif>{{$district->name}}</option>
              @endforeach
            </select>
            @error('district')
            <small class="form-error">{{ $message }}</small>
            @enderror
          </div>
          <div class="form-group">
            <label for="name">Area Name</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Enter Area Name" value="{{old('name')}}">
            @error('name')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
      
    @endslot
@endcomponent
<!--End Insert Modal -->







@endsection
@section('content')
<div class="row">
<div class="col-lg-6">
	<div class="card">
    <div class="card-body table-responsive">
      <h5 class="card-title text-center">Area</h5>
    <button type="button" onclick="addMode('{{route('area.store')}}')" class="btn btn-info btn-sm"><i class="fas fa-plus"> ADD NEW</i></button>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">District</th>
            <th scope="col">Area Name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @php
              $i =1;
          @endphp
          @foreach ($areas as $area)
          <tr>
              <td>{{$i++}}</td>
              <td>{{$area->district->name}}</td>
              <td>{{$area->name}}</td>
              <td>
                <button class="btn btn-primary btn-sm" id="open_modal" onclick="EditProcess('{{route('area.edit',$area->id)}}','{{route('area.update',$area->id)}}')"  data-baseurl="{{asset('')}}" ><i class="fas fa-edit"></i></button> 
             
          
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
  <!-- Spectrum Css -->
  <link href="{{asset('public/assets/css/spectrum.min.css')}}" rel="stylesheet" />   
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
var putremove;






// Exit The Edit Mode 

function addMode(store_url){
  $('#addDataModal').modal('show');
  if (typeof(Storage) !== "undefined") {
    // Store
    sessionStorage.setItem("editMode",false);
    sessionStorage.setItem("store_url",store_url);
  }
  $('#addForm').attr('action', store_url);
  $('#addForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
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

$('#district').select2({
width: '100%',
  theme: "bootstrap"
});


function EditProcess(edit_url, update_url){
$(document).ready(function(){
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
    $('.modal-title').text('Edit Area');
    //assign data
    $('#name').val(data.name);
    $('#district').val(data.district_id).trigger('change');;
    if(putremove != undefined){
      $("#addForm").prepend(putremove);
      putremove = undefined;
    }
    $('#addDataModal').modal('show');
}) 
});
}
</script>

<!-- Success Alert After Product  Delete -->
@if(Session::has('delete_success'))
<script>
Swal.fire({
  icon: 'success',
  title: 'Your Data has Been Deleted Successfully',
  showConfirmButton: false,
  timer: 1500
})
</script>
@endif



<script>
function deleteItem(id){
         const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success btn-sm',
                cancelButton: 'btn btn-danger btn-sm'
            },
            buttonsStyling: true
            })

    swalWithBootstrapButtons.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-from-'+id).submit();
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