@extends('layouts.adminlayout')
@section('title','Sliders')
@section('modal')
<!--Insert Modal -->
@component('component.common.modal')

    @slot('modal_id')
        addDataModal
    @endslot
    @slot('modal_size')
    modal-lg
    @endslot


    @slot('submit_button')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Slider
    @endslot

    @slot('modal_form') 
       <form action="{{route('sliders.store')}}" method="POST" id="addForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')
    <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="title">Slider Title</label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Enter Slider Title" value="{{old('title')}}">
            @error('title')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
          <div class="form-group">
            <label for="title_color">Title Color: </label>
          <input class="@error('title_color') is-invalid @enderror" name="title_color" id="title_color" value="@if(old('title_color') == NULL) #000000 @else {{old('title_color')}} @endif">
            @error('title_color')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
          <div class="form-group">
            <label for="description">Slider Description</label>
          <textarea rows="6"  class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Enter Description">{{old('description')}}</textarea>
            @error('description')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
          <div class="form-group">
            <label for="description_color">Description Color: </label>
          <input class="@error('description_color') is-invalid @enderror" name="description_color" id="description_color" value="@if(old('description_color') == NULL) #636363 @else {{old('description_color')}} @endif">
            @error('description_color')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>

          
        <div class="icheck-turquoise form-group discount_check">
          
          <input type="checkbox" onclick="showInfo()" class="@error('box_status') is-invalid @enderror" name="box_status" id="box_status" value="@if(old('box_status') == null){{1}}@else{{old('box_status')}}
          @endif">
          <label for="box_status">Discount Box (optional): </label>
            @error('box_status')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
         
   
     
          <div id="discount_info">
          <div class="form-group">
            <label for="box_text">Box Text: </label>
          <input class="form-control @error('box_text') is-invalid @enderror" name="box_text" id="box_text" value="{{old('box_text')}}">
            @error('box_text')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>  
          
          
          <div class="form-group">
            <label for="colorpicker">Discount Box Color: </label>
          <input class="@error('discount_box_color') is-invalid @enderror" name="discount_box_color" id="discount_box_color" value="{{old('discount_box_color','#EA2027')}}">
            @error('discount_box_color')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
          </div>
        </div>
          <div class="col-lg-6">
         
    
        
        <div class="form-group">
          <label for="button_text">Button Text: </label>
        <input type="text" class="form-control @error('button_text') is-invalid @enderror" name="button_text" id="button_text" value="{{old('button_text')}}">
          @error('button_text')
         <small class="form-error">{{ $message }}</small>
         @enderror
        </div>
        <div class="form-group">
          <label for="colorpicker">Button Color (optional): </label>
        <input class="@error('color') is-invalid @enderror" name="color" id="colorpicker" value="@if(old('color') == NULL) #f1c40f @else {{old('color')}} @endif">
          @error('color')
         <small class="form-error">{{ $message }}</small>
         @enderror
        </div>
      
      
    
        <div class="form-group">
          <label for="button_link">Button Link: </label>
        <input type="text" class="form-control @error('button_link') is-invalid @enderror" name="button_link" id="button_link" value="{{old('button_link')}}">
          @error('button_link')
         <small class="form-error">{{ $message }}</small>
         @enderror
        </div>
        


 


      <div class="form-group">
        <label for="image">Slider Image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
        <small>Note: Please Keep Image Size 1919x725 </small>
        @error('image')
        <small class="form-error">{{ $message }}</small>
        @enderror

      </div>

      <div class="form-group">
        <img  style="padding: 10px;display:none" class="img-thumbnail rounded" src="" id="pd_image2" alt="">
      </div>

        </div>
    </div>

      
      
    @endslot
@endcomponent
<!--End Insert Modal -->







@endsection
@section('content')
<div class="col-lg-12">
	<div class="card">
    <div class="card-body table-responsive">
      <h5 class="card-title text-center">Sliders</h5>
    <button type="button" onclick="addMode('{{route('sliders.store')}}')" class="btn btn-info btn-sm"><i class="fas fa-plus"> ADD NEW</i></button>
      <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Image</th>
            <th scope="col">Button</th>
            <th scope="col">Button Color</th>
            <th scope="col">Discount Box</th>
            <th scope="col">Box Color</th>
            <th scope="col">%</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($sliders as $key => $slider)
            <tr>
                <th scope="row">{{$sliders->firstItem() + $key}}</th>
                <td>{{$slider['title']}}</td>
                <td> <img style="height: 50px;" class="img-responsive img-thumbnail" src="{{asset('public/uploads/sliders/thumb/'.$slider['image'])}}" alt=""></td>
                <td><a href="{{$slider['button_link']}}" class="btn btn-link">{{$slider['button_text']}}</a></td>
                <td><div class="color_div" style="background-color:{{$slider['button_color']}}"></div></td>
                <td>
                  @if($slider['box_status'] == 1) 
                  <span class="badge badge-success">true</span> 
                  @else <span class="badge badge-danger">disabled</span> 
                  @endif
                </td>
                <td>@if($slider['box_status'] == 1) 
                  <div class="color_div" style="background-color:{{$slider['discount_box_color']}}"></div>
                  @else 
                  <span class="badge badge-danger">disabled</span> 
                  @endif
                </td>
                <td>
                  @if($slider['box_status'] == 1) 
                  <span class="badge badge-info">{{$slider['box_text']}}</span>
                  @else 
                  <span class="badge badge-danger">disabled</span> 
                  @endif
                  </td>
                
            <td>
            <button class="btn btn-primary btn-sm" id="open_modal" onclick="EditProcess({{$slider['id']}},'{{route('sliders.update',$slider['id'])}}')"  data-baseurl="{{asset('')}}" ><i class="fas fa-edit"></i></button> 
         
            
              <form id="delete-from-{{$slider['id']}}" style="display: inline-block" action="{{route('sliders.destroy',$slider['id'])}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="deleteItem({{$slider['id']}})"  class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
        
                </form> 
              </td>
            </tr>
        @endforeach

         
          
        </tbody>
      </table>
      <div class="links">
        {{$sliders->links()}}
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

<!-- Spectrum js -->
<script src="{{asset('public/assets/js/spectrum.min.js')}}"></script>

<script>
var putremove;
$(document).ready(function() {
// Spectrum colorpicker code

  $("#colorpicker").spectrum({
    type: "color",
  showInput: "true",
  hideAfterPaletteSelect: "true"
  });

  $("#title_color").spectrum({
    type: "color",
  showInput: "true",

  hideAfterPaletteSelect: "true"
  });
  $("#description_color").spectrum({
    type: "color",
  showInput: "true",
  hideAfterPaletteSelect: "true"
  });

  $("#discount_box_color").spectrum({
    type: "color",
  showInput: "true",
  hideAfterPaletteSelect: "true"
  });






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



});




// Discount Box Toggle

function showInfo(){
  var element = document.getElementById("discount_info");
   element.classList.toggle('showInfo');
}
function editInfo(){
  var element = document.getElementById("edit_discount_info");
   element.classList.toggle('showInfo');
}
</script>





<script>
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
  $('#subcategories').val([]).trigger('change');
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
  $('#pd_image2').hide().attr('src',' ');
  $('#box_status').prop("checked",false );
  $('#discount_info').removeClass("showInfo");
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


$("#image").change(function() {
  addProductreadURL(this);
  $('#pd_image2').show();
});

</script>



<script>


function EditProcess(s_id, update_url){
$(document).ready(function(){
//reset form
$('#addForm').trigger("reset");
  $('#subcategories').val([]).trigger('change');
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
// Go to Edit Mode If Click Edit Button
if (typeof(Storage) !== "undefined") {

  sessionStorage.setItem("editMode",true);
  sessionStorage.setItem("update_url",update_url);
}


var url = "sliders";
var base_url = $('#open_modal').attr("data-baseurl");
$.get(url + '/' + s_id+'/edit', function (data) {
    //Change Form Action
    $('#addForm').attr('action', update_url);
    //assign data
    $('#title').val(data.title);
    $('.modal-title').text('Edit Your  Slider');
    $('#title_color').spectrum("set",data.title_color);
    $('#description').val(data.description);
    $('#description_color').spectrum("set",data.description_color);
    $('#button_text').val(data.button_text);
    $('#button_link').val(data.button_link);
    $('#colorpicker').spectrum("set", data.button_color);
    if(data.box_status == 1){
      $('#box_status').prop("checked",true );
      $('#discount_info').addClass("showInfo");
      $('#discount_box_color').spectrum("set", data.box_color);
      console.log(data.box_color);
    }else{
      $('#box_status').prop("checked",false );
      $('#discount_info').removeClass("showInfo");
    }
    $('#box_text').val(data.box_text);
    $('#discount_box_color').spectrum("set", data.discount_box_color);
    $('#pd_image2').show().attr('src',base_url+'public/uploads/sliders/thumb/'+data.image);
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