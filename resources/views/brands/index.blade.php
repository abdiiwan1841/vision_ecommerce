@extends('layouts.adminlayout')
@section('title','Brands')

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
<div class="col-lg-6">
	<div class="card">
    <div class="card-body table-responsive">
      <h5 class="card-title text-center">BRANDS</h5>
      <button type="button" onclick="BrandPopup('{{route('brands.store')}}')" class="btn btn-info btn-sm"><i class="fas fa-plus"> ADD NEW</i></button>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($brands as $key => $brand)
            <tr>
                <th scope="row">{{$brands->firstItem() + $key}}</th>
                <td>{{$brand['brand_name']}}</td>
            <td>
            <button class="btn btn-primary btn-sm" onclick="EditbrandPopup('{{route('brands.edit',$brand['id'])}}','{{route('brands.update',$brand['id'])}}')"><i class="fas fa-edit"></i></button> 
         
          
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
<script src="{{asset('public/assets/js/axios.min.js')}}"></script>
<script>

function BrandPopup(url){
  $("#productModalLabel").text('Add Brand');
  $("#modal-data").html(`<form id="brand_form"> <div class="form-group">
        <label for="brand_name">Brand Name</label>
      <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Enter Brand Name" required>
      <small class="text-danger brand_name_err"></small>
      </div> <div class="form-group"><button type="button" id="send_form" onclick="addBrand('${url}')" class="btn btn-success">+ Add</button></div> </form>`);
  $('#productModal').modal('show');
}

function EditbrandPopup(editurl,updateurl){
  // Make a request for a user with a given ID
axios.get(editurl)
  .then(function (response) {
    $("#productModalLabel").text('Edit '+response.data.brand_name);
    $("#modal-data").html(`<form id="brand_form"> <div class="form-group">
        <label for="brand_name">Brand Name</label>
      <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Enter Brand Name" value="${response.data.brand_name}" required>
      <small class="text-danger brand_name_err"></small>
      </div> <div class="form-group"><button type="button" id="send_form" onclick="updateBrand('${updateurl}')" class="btn btn-success">+ Update</button></div> </form>`);
  $('#productModal').modal('show');
  })
  .catch(function (error) {
    // handle error
    console.log(error);
    toastr.error(error.response.data.message,error.response.status);
  })



}





function updateBrand(url){
  $(".text-danger").hide().text("");
	$(".red-border").removeClass("red-border");
	$('#send_form').html('<i class="fas fa-spinner fa-spin"></i> Please Wait...').attr('disabled',true);
	let data = $("#brand_form").serialize();
  axios.put(url,data)
		.then(res => {
		  $('#productModal').modal('hide');
     toastr.success(res.data);
     $('#send_form').text('+ Add').attr('disabled',false);
     location.reload();
		})
			
		.catch(err => {
      toastr.error(err.response.data.message,err.response.status)
			let errors = err.response.data.errors;
			Object.keys(errors).forEach(function(value){
				$("#"+value+"").addClass("red-border");
				$("."+value+"_err").text(errors[value][0]);
      })
      $('#send_form').text('+ Add').attr('disabled',false);



			});


			$(".text-danger").show();
      

 }



  function addBrand(url){
  $(".text-danger").hide().text("");
	$(".red-border").removeClass("red-border");
	$('#send_form').html('<i class="fas fa-spinner fa-spin"></i> Please Wait...').attr('disabled',true);
	let data = $("#brand_form").serialize();
  axios.post(url,data)
		.then(res => {
		  $('#productModal').modal('hide');
     toastr.success(res.data);
     $('#send_form').text('+ Add').attr('disabled',false);
     location.reload();
		})
			
		.catch(err => {
      toastr.error(err.response.data.message,err.response.status)
			let errors = err.response.data.errors;
			Object.keys(errors).forEach(function(value){
				$("#"+value+"").addClass("red-border");
				$("."+value+"_err").text(errors[value][0]);
      })
      $('#send_form').text('+ Add').attr('disabled',false);



			});


			$(".text-danger").show();
      

 }

</script>

@endpush