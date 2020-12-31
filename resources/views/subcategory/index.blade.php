@extends('layouts.adminlayout')
@section('title','Product Type')
@section('modal')

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal-data">
            
      </div>
      
    </div>
  </div>
</div>

@endsection


@section('content')
<div class="col-lg-8">
	<div class="card">
    <div class="card-body table-responsive">
      <h5 class="card-title text-center">Type</h5>
      <button type="button" onclick="SubCategoryPopup('{{route('subcategories.store')}}')" class="btn btn-info btn-sm"><i class="fas fa-plus"> ADD NEW</i></button>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Type Name</th>
            <th scope="col">Image</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($subcategories as $key => $subcategory)
            <tr>
                <th scope="row">{{$subcategories->firstItem() + $key}}</th>
               
                <td>{{$subcategory['subcategory_name']}}</td>
                <td><img style="width: 100px" src="{{asset('public/uploads/product_type/frontend/'.$subcategory->image)}}" alt=""></td>
            <td>
            <button class="btn btn-primary btn-sm" onclick="EditSubCategoryPopup('{{route('subcategories.edit',$subcategory['id'])}}','{{route('subcategories.update',$subcategory['id'])}}')"><i class="fas fa-edit"></i></button>  
              </td>
            </tr>
        @endforeach

         
          
        </tbody>
      </table>
      <div class="links">
        {{$subcategories->links()}}
      </div>
    </div>
  </div>
</div>




@endsection

@push('js')

<script src="{{asset('public/assets/js/axios.min.js')}}"></script>
<script>


let baseurl = '{{url('/')}}';
function CategoryImageShow(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#cat_image').attr('src', e.target.result).show();
    }
    reader.readAsDataURL(input.files[0]); 
  }
}



function SubCategoryImageShow(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#subcat_image').attr('src', e.target.result).show();
    }
    reader.readAsDataURL(input.files[0]); 
  }
}

  function SubCategoryPopup(url){
  $("#productModalLabel").text('Add Product Types');
  $("#modal-data").html(`<form id="subcategory_form">
  <div class="form-group">
        <label for="subcategory_name">Product Types</label>
      <input type="text"  class="form-control" name="subcategory_name" id="subcategory_name" placeholder="Enter Product Types" required>
      <small class="text-danger subcategory_name_err"></small>
      </div>
      <div class="form-group">
        <label for="subcategory_image">Product Type Image</label>
        <input onchange="SubCategoryImageShow(this)" type="file" class="form-control" name="subcategory_image" id="subcategory_image" required>
        <small class="text-danger subcategory_image_err"></small>
        <img  style="padding: 10px;display:none" class="img-thumbnail rounded" src="" id="subcat_image" alt="">
         

      </div> <button type="button" id="subcategory_form_button" onclick="addSubcateogry('${url}')" class="btn btn-success">+ Add</button></div> </form>`);
  $('#productModal').modal('show');
}






function EditSubCategoryPopup(editurl,updateurl){
  // Make a request for a user with a given ID
axios.get(editurl)
  .then(function (response) {
    console.log(response);
    $("#productModalLabel").text('Edit '+response.data.subcategory_name);
    $("#modal-data").html(`<form id="subcategory_form">
  <div class="form-group">
        <label for="subcategory_name">Product Types</label>
      <input type="text"  class="form-control" name="subcategory_name" id="subcategory_name" placeholder="Enter Product Types" value="${response.data.subcategory_name}" required>
      <small class="text-danger subcategory_name_err"></small>
      </div>
      <div class="form-group">
        <label for="subcategory_image">Product Type Image</label>
        <input onchange="SubCategoryImageShow(this)" type="file" class="form-control" name="subcategory_image" id="subcategory_image" required>
        <small class="text-danger subcategory_image_err"></small>
        <img  style="padding: 10px;" class="img-thumbnail rounded" src="${baseurl}/public/uploads/product_type/frontend/${response.data.image}" id="subcat_image" alt="">
         

      </div> <button type="button" id="subcategory_form_button" onclick="updateSubCategory('${updateurl}')" class="btn btn-success">Update</button></div> </form>`);
  $('#productModal').modal('show');
  })
  .catch(function (error) {
    // handle error
    console.log(error);
    toastr.error(error.response.data.message,error.response.status);
  })



}



  function addSubcateogry(url){
  $(".text-danger").hide().text("");
	$(".red-border").removeClass("red-border");
	$('#subcategory_form_button').html('<i class="fas fa-spinner fa-spin"></i> Please Wait...').attr('disabled',true);
	let frm  = $("#subcategory_form");
  let formData = new FormData(frm[0]);
  console.log(formData);
  axios.post(url,formData)
		.then(res => {
		  $('#productModal').modal('hide');
     toastr.success(res.data);
     $('#subcategory_form_button').text('+ Add').attr('disabled',false);
     location.reload();
		})
			
		.catch(err => {
      toastr.error(err.response.data.message,err.response.status)
			let errors = err.response.data.errors;
			Object.keys(errors).forEach(function(value){
				$("#"+value+"").addClass("red-border");
				$("."+value+"_err").text(errors[value][0]);
      })
      $('#subcategory_form_button').text('+ Add').attr('disabled',false);



			});
			$(".text-danger").show();
      

 }


 
function updateSubCategory(url){
  $(".text-danger").hide().text("");
	$(".red-border").removeClass("red-border");
	$('#subcategory_form_button').html('<i class="fas fa-spinner fa-spin"></i> Please Wait...').attr('disabled',true);
	let frm  = $("#subcategory_form");
  let formData = new FormData(frm[0]);
  console.log(formData);
  axios.post(url,formData)
		.then(res => {
		  $('#productModal').modal('hide');
     toastr.success(res.data);
     $('#subcategory_form_button').text('update').attr('disabled',false);
     location.reload();
		})
			
		.catch(err => {
      toastr.error(err.response.data.message,err.response.status)
			let errors = err.response.data.errors;
			Object.keys(errors).forEach(function(value){
				$("#"+value+"").addClass("red-border");
				$("."+value+"_err").text(errors[value][0]);
      })
      $('#subcategory_form_button').text('update').attr('disabled',false);



			});


			$(".text-danger").show();
      

 }

</script>
@endpush