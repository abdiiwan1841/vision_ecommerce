@extends('layouts.adminlayout')
@section('title','Inventory Role')
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
      Role
    @endslot

    @slot('modal_form') 
       <form action="{{route('role.store')}}" method="POST" id="addForm">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')
        <div class="form-group">
           <h5 id="due" style="color: red;text-align: right"></h5>
        </div>



          <div class="form-group">
            <label for="name">Role Name</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Enter Role Name" value="{{old('name')}}" required>
            @error('name')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>


          <div class="form-group">
            <label for="permissions">Permissions</label>
            <select  data-placeholder="Select Some Permission" class="js-example-responsive" multiple="multiple" name="permissions[]" id="permissions" class="form-control @error('permissions') is-invalid @enderror" >
              <option></option>
              @foreach ($permissions as $permission)
            <option value="{{$permission->name}}" @if(old('permissions') != null) @foreach(old('permissions') as $single_permission) @if($single_permission == $permission->id) selected @endif  @endforeach @endif>{{$permission->name}}</option>
              @endforeach
            </select>
            @error('permissions')
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
                    <h5 class="card-title text-left">Admin Role Module</h5>
                  </div>
                    <div class="col-lg-6">
                      <button type="button" onclick="addMode('{{route('role.store')}}')" class="btn btn-info btn-sm float-right"><i class="fas fa-plus"></i>  Add New Roles</button>
                     
                    </div>
                    
                  </div>
                
            </div>
            <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <h3 class="mt-3 mb-5 text-uppercase text-center">Admin Roles</h3>
                        <table class="table table-bordered table-striped table-hover mt-3">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Role</th>
                              <th scope="col">Guard</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @php
                                $i =1;
                            @endphp
                            @foreach ($roles as $role)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$role->name}}</td>
                                <td><span class="badge badge-danger">{{$role->guard_name}}</span></td>
                                <td>
                                <button class="btn btn-primary btn-sm" id="open_modal" onclick="EditProcess('{{route('role.edit',$role->id)}}','{{route('role.update',$role->id)}}')"  data-baseurl="{{asset('')}}" ><i class="fas fa-edit"></i></button>
                                    </td>
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
<style>
.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove{
  color: #fff !important;
}
.select2-container--bootstrap .select2-results__option[aria-selected="true"]{
  background: #FFC312 !important;
}
</style>
@endpush

@push('js')
<script src="{{asset('public/assets/js/axios.min.js')}}"></script>



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



$('#permissions').select2({
      width: '100%',
      closeOnSelect: false,
      theme: "bootstrap",templateSelection: function (data, container) {
    $(container).css("background-color", "#4cd137");
    $(container).css("color", "#ffffff");
    return data.text;
}
});


var baseurl = '{{url('/')}}';

var putremove;

// Exit The Edit Mode 

function addMode(store_url){
  $('#addDataModal').modal('show');
  $('.modal-title').text('Add New Role');
  if (typeof(Storage) !== "undefined") {
    // Store
    sessionStorage.setItem("editMode",false);
    sessionStorage.setItem("store_url",store_url);
  }
  $('#addForm').attr('action', store_url);
  $('#addForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
  $("#permissions").val([]).trigger('change');
  $('#user-details').hide();
  if(putremove == undefined){
    putremove = $('input[value="PUT"]').detach();
  }
  
}





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

axios.get(edit_url)
.then(function (response) {
  console.log(response)
       //Change Form Action
    $('#addForm').attr('action', update_url);
    $('.modal-title').text('Edit Previously Posted Role');
    //assign data
    $('#name').val(response.data.name);

    let allpermissions =  response.data.permissions;

    permissionsarray = [];
    allpermissions.forEach(function(data){
      permissionsarray.push(data.name);
    });

    $("#permissions").val(permissionsarray).trigger('change');

    if(putremove != undefined){
      $("#addForm").prepend(putremove);
      putremove = undefined;
    }
    $('#addDataModal').modal('show');
  })


  .catch(function (error) {
    // handle error
    console.log(error);
  })




});
}
</script>
@endpush


