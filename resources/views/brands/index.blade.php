@extends('layouts.adminlayout')
@section('title','Brands')
@section('modal')
<!--Insert Modal -->
@component('component.common.modal')

    @slot('modal_id')
        addDataModal
    @endslot
    @slot('submit_button')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Brand
    @endslot

    @slot('modal_form') 
       <form action="{{route('brands.store')}}" method="POST" id="addForm" enctype="multipart/form-data">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="brand_name">Brand Name</label>
      <input type="text" class="form-control @error('brand_name') is-invalid @enderror" name="brand_name" id="brand_name" placeholder="Enter Brand Name" value="{{old('brand_name')}}">
        @error('brand_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>

      <div class="form-group">
        <label for="image">Brand Logo</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">

        <small>Please Maintain aspect ratio 137x44 px otherwise it wiil be cropped</small>
        @error('image')
        <small class="form-error">{{ $message }}</small>
        @enderror

      </div>

      <div class="form-group">
        <img  style="padding: 10px;display:none" class="img-thumbnail rounded" src="" id="pd_image2" alt="">
      </div>


      
      
    @endslot
@endcomponent
<!--End Insert Modal -->





<!--Edit Modal -->
@component('component.common.modal')

    @slot('modal_id')
    editDataModal
    @endslot
    @slot('submit_button')
        edit_modal_submit
    @endslot
    @slot('modal_title')
      Edit Brand
    @endslot

    @slot('modal_form') 
       <form action="" method="POST" id="editForm"  enctype="multipart/form-data">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="edit_brand_name">Brand Name</label>
      <input type="text" class="form-control @error('edit_brand_name') is-invalid @enderror" name="edit_brand_name" id="edit_brand_name" placeholder="Enter brand Name" value="{{old('edit_brand_name')}}">
        @error('edit_brand_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>

      <div class="form-group">
        <label for="edit_image">Brand Logo</label>
      <input type="file" class="form-control @error('edit_image') is-invalid @enderror" name="edit_image" id="edit_image">
      
      <small>Please Maintain aspect ratio 137x44 px otherwise it wiil be cropped</small>
      @error('edit_image')
        <small class="form-error">{{ $message }}</small>
        @enderror

      </div>
      <div class="form-group">
        <img style="padding: 10px;" class="img-thumbnail rounded" src="" id="pd_image" alt="">
      </div>
      
    
      
    @endslot
@endcomponent
<!--Edit  Modal -->








@endsection
@section('content')
<div class="col-lg-6">
	<div class="card">
    <div class="card-body table-responsive">
      <h5 class="card-title text-center">BRANDS</h5>
      <button type="button" onclick="addMode()" class="btn btn-info btn-sm"><i class="fas fa-plus"> ADD NEW</i></button>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">BRAND LOGO</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($brands as $key => $brand)
            <tr>
                <th scope="row">{{$brands->firstItem() + $key}}</th>
                <td>{{$brand['brand_name']}}</td>
                <td> <img style="height: 50px;" class="img-responsive img-thumbnail" src="{{asset('public/uploads/brand/thumb/'.$brand['image'])}}" alt=""></td>
                
            <td>
            <button class="btn btn-primary btn-sm" id="open_modal" onclick="EditProcess({{$brand['id']}},'{{route('brands.update',$brand['id'])}}')"  data-baseurl="{{asset('')}}" ><i class="fas fa-edit"></i></button> 
         
            
              <form id="delete-from-{{$brand['id']}}" style="display: inline-block" action="{{route('brands.destroy',$brand['id'])}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="deleteItem({{$brand['id']}})"  class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
        
                </form> 
              </td>
            </tr>
        @endforeach

         
          
        </tbody>
      </table>
      <div class="links">
        {{$brands->links()}}
      </div>
    </div>
  </div>
</div>




@endsection

@push('js')


@if ($errors->any())
{{-- prevent The Modal Close If Any Error In the Form --}}
<script>

  if(sessionStorage.getItem("editMode") === 'true'){
    $('#editDataModal').modal('show');
    $('#editForm').attr('action', sessionStorage.getItem("update_url"));

  }else{
    $('#addDataModal').modal('show');
    console.log(sessionStorage.getItem("editMode"));
  }

</script>
@endif




<script>
// Exit The Edit Mode 

function addMode(){
  $('#addDataModal').modal('show');
  if (typeof(Storage) !== "undefined") {
    // Store
    sessionStorage.setItem("editMode",false);
    // Retrieve
    console.log(sessionStorage.getItem("editMode"));
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

function EditProductreadURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#pd_image').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#image").change(function() {
  addProductreadURL(this);
  $('#pd_image2').show();
});
$("#edit_image").change(function() {
  EditProductreadURL(this);
});

</script>



<script>


function EditProcess(s_id, update_url){
$(document).ready(function(){

// Go to Edit Mode If Click Edit Button
if (typeof(Storage) !== "undefined") {

  sessionStorage.setItem("editMode",true);
  sessionStorage.setItem("update_url",update_url);
  console.log(sessionStorage.getItem("editMode"));
}


var url = "brands";
var base_url = $('#open_modal').attr("data-baseurl");
$.get(url + '/' + s_id+'/edit', function (data) {

    //Change Form Action
    $('#editForm').attr('action', update_url);
    //assign data
    $('#edit_brand_name').val(data.brand_name);
    $('#pd_image').attr('src',base_url+'public/uploads/brand/thumb/'+data.image);
    $('#editDataModal').modal('show');
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