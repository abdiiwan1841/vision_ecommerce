@extends('layouts.adminlayout')
@section('title','Inventory Products')
@section('content')
<div class="row">
<div class="col-lg-12">
	<div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-lg-8">
          <h5 class="card-title text-left">Inventory PRODUCTS</h5>
        </div>
        <div class="col-lg-4">
        <a href="{{route('posproducts.create')}}" class="btn btn-info btn-sm float-right">+ Add New Product</a>
         
        </div>
      </div>
    </div>
    <div class="card-body table-responsive">
      
     
      <table class="table table-bordered table-striped table-hover mt-3" id="jq_datatables">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Image</th>
            <th>size</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($products as $key => $product)
        
            <tr>
            <td>{{$key+1}}</td>
                <td>{{$product['product_name']}}</td>
                <td> <img style="height: 50px;" class="img-responsive img-thumbnail" src="{{asset('public/uploads/products/thumb/'.$product['image'])}}" alt=""></td>
            <td>{{$product->size->name}}</td>
                <td> @if($product->discount_price == NULL)
                  Tk.{{$product->price}}
                  @else Tk.{{$product->discount_price}} 
                  <small> <del>Tk.({{$product->price}})</del></small>
                  @endif</td>
            <td>
            <a href="{{route('posproducts.edit',$product->id)}}" class="btn btn-primary btn-sm" ><i class="fas fa-edit"></i></a> 
         
              <a class="btn btn-info btn-sm"  href="{{route('posproducts.show',$product['id'])}}"><i class="fas fa-eye"></i></a>
              <form id="delete-from-{{$product['id']}}" style="display: inline-block" action="{{route('posproducts.destroy',$product['id'])}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="deleteProduct({{$product['id']}})"  class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
        
                </form> 
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
<link rel="stylesheet" href="{{asset('public/assets/css/dataTables.bootstrap4.min.css')}}">
@endpush

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

<script>
 


  var colors = ["#eb4d4b", "#A3CB38", "#f1c40f", "#f39c12", "#2980b9", "#ff7979", "purple"];
  //For Addmodal


</script>
<script src="{{asset('public/assets/js/datatables.min.js')}}"></script>
<script src="{{asset('public/assets/js/dataTables.bootstrap4.min.js')}}"></script>
<script>
$('#jq_datatables').DataTable({
  "order": [ [2, 'desc'] ],
  "columnDefs": [
        { "targets": [2,5], "searchable": false }
    ],
});
</script>

@endpush