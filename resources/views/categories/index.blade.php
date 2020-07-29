@extends('layouts.adminlayout')
@section('title','Categories')
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
      Add Category
    @endslot

    @slot('modal_form') 
       <form action="{{route('categories.store')}}" method="POST" id="addForm" enctype="multipart/form-data">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="category_name">Category Name</label>
      <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="category_name" id="category_name" placeholder="Enter Category Name" value="{{old('category_name')}}">
        @error('category_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>

      <div class="form-group">
        <label for="image">Category Image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
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
      Edit Category
    @endslot

    @slot('modal_form') 
       <form action="" method="POST" id="editForm"  enctype="multipart/form-data">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="edit_category_name">Category Name</label>
      <input type="text" class="form-control @error('edit_category_name') is-invalid @enderror" name="edit_category_name" id="edit_category_name" placeholder="Enter category Name" value="{{old('edit_category_name')}}">
        @error('edit_category_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>

      <div class="form-group">
        <label for="edit_image">Category Image</label>
      <input type="file" class="form-control @error('edit_image') is-invalid @enderror" name="edit_image" id="edit_image">
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
      <h5 class="card-title text-center">CATEGORIES</h5>
      <button type="button" onclick="addMode()" class="btn btn-info btn-sm"><i class="fas fa-plus"> ADD NEW</i></button>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Image</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($categories as $key => $category)
            <tr>
                <th scope="row">{{$categories->firstItem() + $key}}</th>
                <td>{{$category['category_name']}}</td>
                <td> <img style="height: 50px;" class="img-responsive img-thumbnail" src="{{asset('public/uploads/category/thumb/'.$category['image'])}}" alt=""></td>
                
            <td>
            <button class="btn btn-primary btn-sm" id="open_modal" onclick="EditProcess({{$category['id']}},'{{route('categories.update',$category['id'])}}')"  data-baseurl="{{asset('')}}" ><i class="fas fa-edit"></i></button> 
         
              </td>
            </tr>
        @endforeach

         
          
        </tbody>
      </table>
      <div class="links">
        {{$categories->links()}}
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


var url = "categories";
var base_url = $('#open_modal').attr("data-baseurl");
$.get(url + '/' + s_id+'/edit', function (data) {

    //Change Form Action
    $('#editForm').attr('action', update_url);
    //assign data
    $('#edit_category_name').val(data.category_name);
    $('#pd_image').attr('src',base_url+'public/uploads/category/thumb/'+data.image);
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

<script>
  //select 2
$(document).ready(function() {
//For Addmodal
var colors = ["#eb4d4b", "#A3CB38", "#f1c40f", "#f39c12", "#2980b9", "#ff7979", "purple"];
$('#subcategories').select2({
      width: '100%',
      theme: "bootstrap",templateSelection: function (data, container) {
    $(container).css("background-color", colors[1]);
    $(container).css("color", "#ffffff");
    return data.text;
}
    });

  //Fot EditModal
  $('#edit_subcategories').select2({
      width: '100%',
      theme: "bootstrap",templateSelection: function (data, container) {
    $(container).css("background-color", colors[2]);
    $(container).css("color", "#ffffff");
    return data.text;
}
    });

});
</script>
@endpush