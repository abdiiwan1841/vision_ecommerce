@extends('layouts.adminlayout')
@section('title','Tags')
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
      Add Tags
    @endslot

    @slot('modal_form') 
       <form action="{{route('tags.store')}}" method="POST" id="addForm">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="tag_name">Tag Name</label>
      <input type="text" class="form-control @error('tag_name') is-invalid @enderror" name="tag_name" id="tag_name" placeholder="Enter Tag Name" value="{{old('tag_name')}}">
        @error('tag_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
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
      Edit Tag
    @endslot

    @slot('modal_form') 
       <form action="" method="POST" id="editForm">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="edit_tag_name">Tag Name</label>
      <input type="text" class="form-control @error('edit_tag_name') is-invalid @enderror" name="edit_tag_name" id="edit_tag_name" placeholder="Enter tag Name" value="{{old('edit_tag_name')}}">
        @error('edit_tag_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>    
      
    @endslot
@endcomponent
<!--Edit  Modal -->








@endsection
@section('content')
<div class="col-lg-6">
	<div class="card">
    <div class="card-body table-responsive">
      <h5 class="card-title text-center">Tags</h5>
      <button type="button" onclick="addMode()" class="btn btn-info btn-sm"><i class="fas fa-plus"> ADD NEW</i></button>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tag Name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($tags as $key => $tag)
            <tr>
                <th scope="row">{{$tags->firstItem() + $key}}</th>
                <td>{{$tag['tag_name']}}</td>
            <td>
            <button class="btn btn-primary btn-sm" id="open_modal" onclick="EditProcess({{$tag['id']}},'{{route('tags.update',$tag['id'])}}')"  data-baseurl="{{asset('')}}" ><i class="fas fa-edit"></i></button> 
         
            
              <form id="delete-from-{{$tag['id']}}" style="display: inline-block" action="{{route('tags.destroy',$tag['id'])}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="deleteProduct({{$tag['id']}})"  class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
        
                </form> 
              </td>
            </tr>
        @endforeach

         
          
        </tbody>
      </table>
      <div class="links">
        {{$tags->links()}}
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
  $('#addForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
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
  $('#editForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();

// Go to Edit Mode If Click Edit Button
if (typeof(sessionStorage) !== "undefined") {

  sessionStorage.setItem("editMode",true);
  sessionStorage.setItem("update_url",update_url);
  console.log(sessionStorage.getItem("editMode"));
}


var url = "tags";
var base_url = $('#open_modal').attr("data-baseurl");
$.get(url + '/' + s_id+'/edit', function (data) {

    //Change Form Action
    $('#editForm').attr('action', update_url);
    //assign data
    $('#edit_tag_name').val(data.tag_name);
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
function deleteProduct(id){
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