@extends('layouts.adminlayout')

@section('title','Edit Inventory Customer')
@section('content')
<div class="row">
<div class="col-lg-12">
	<div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                <a class="btn btn-info btn-sm" href="{{route('customers.index')}}"><i class="fa fa-angle-left"></i> back</a>
                </div>
                <div class="col-lg-8">
                    <h5 class="text-right">EDIT - "<small>{{$customer->name}}</small>"</h5>
                </div>
            </div>
        </div>
    <div class="card-body">
    <form action="{{route('customers.update',$customer->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row justify-content-center">
       
        <div class="col-lg-4">
            
            <div class="form-group">
                <label for="name">Name<span>*</span></label>
            <input type="text" id="name" placeholder="Enter Your Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name',$customer->name)}}" required>
      
                @error('name')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="proprietor">Proprietor Name<span>(optional)</span></label>
            <input type="text" id="proprietor" placeholder="Enter Proprietor Name" class="form-control @error('proprietor') is-invalid @enderror" name="proprietor" value="{{old('proprietor',$customer->proprietor)}}">
      
                @error('proprietor')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="inventory_email">Email<span>(optional)</span></label>
                <input type="text" id="inventory_email" placeholder="Enter Your Email" class="form-control @error('inventory_email') is-invalid @enderror" name="inventory_email" value="{{old('inventory_email',$customer->inventory_email)}}">
                @error('inventory_email')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="phone">Phone<span>*</span></label>
            <input type="text" id="phone" placeholder="Enter Your phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone',$customer->phone)}}" required>
                @error('phone')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="company">Company<span>(optional)</span></label>
            <input type="text" id="company" placeholder="Enter Your Company Name" class="form-control @error('company') is-invalid @enderror" name="company" value="{{old('company',$customer->company)}}">
                @error('company')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      

        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="address">Address<span>*</span></label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="4" placeholder="Enter Your Addres" required>{{old('address',$customer->address)}}</textarea>
                
                @error('address')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="division">Division<span>*</span></label>
                <select name="division" id="division" class="form-control @error('division') is-invalid @enderror" required>
                    <option value="">Select Division</option>
                    @foreach ($divisions as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                    
                </select>
                @error('division')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
            


            <div class="form-group">
                <label for="district">District<span>*</span></label>
                <select name="district" id="district" class="form-control @error('district') is-invalid @enderror" required>
                    <option value="">Select District</option>
                   
                    
                </select>
                @error('district')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="area">Area<span>*</span></label>
                <select name="area" id="area" class="form-control @error('area') is-invalid @enderror" id="area" required>
                    <option value="">Select Area</option>
                </select>
                @error('area')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
            

        </div>
        <div class="col-lg-8">
            <div class="form-group">
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </div>
    
    </div>
</form>
    </div>
  </div>
</div>
</div>


@endsection

@push('css')

@endpush


@push('js')
<script>
$('#division').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Division",
});
$('#district').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a District",
});

$('#area').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Area",
});
var exist_division_id = '{{$customer->division_id}}';
var exist_district_id = '{{$customer->district_id}}';
var exist_area_id = '{{$customer->area_id}}';

$("#division").val(exist_division_id).trigger('change');
var district_id = $("#district").val();



    var base_url = '{{url('/')}}';
    var output = '';
    var division_id = $("#division").val();
    

    if(division_id.length > 0){
        $.get("{{asset('')}}api/district/"+division_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
                if(element.id == exist_district_id){
                    output += '<option value="'+element.id+'" selected>'+element.name+'</option>';
               
            }else{
                output += '<option value="'+element.id+'">'+element.name+'</option>';
            }
            });
                $("#district").html(output);
            }else{
                $("#district").html('<option value="">No Area Found</option>');
            }
        });
        }else{
            $("#area").html('<option value="">No Area Found</option>');
        }



     $.get("{{asset('')}}api/area/"+exist_district_id, function(data, status){
        if(data.length>0){
        output = '';
        $(data).each(function(index,areaelement){
            if(areaelement.id == exist_area_id){

                output += '<option value="'+areaelement.id+'" selected>'+areaelement.name+'</option>';
            }else{
                output += '<option value="'+areaelement.id+'">'+areaelement.name+'</option>';
            }
        
        });
        $("#area").html(output);
        }else{
            $("#area").html('<option value="">No Area Found</option>');
        }
    });

        
    $("#division").change(function(){
        var division_id = $("#division").val();
        if(division_id.length > 0){
          $.get("{{asset('')}}api/district/"+division_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
               output += '<option value="'+element.id+'">'+element.name+'</option>';
            });
               $("#district").html(output);
               $("#district").val(null).trigger('change');
               $('#area').html('');
 
            }else{
                $("#district").html('<option value="">No Area Found</option>');
            }
        });
        }else{
          $("#area").html('<option value="">No Area Found</option>');
        }
        });


       
    


       
        $("#district").change(function(){
      district_id = $("#district").val();
            
        if(district_id != null){
          $.get("{{asset('')}}api/area/"+district_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
               output += '<option value="'+element.id+'">'+element.name+'</option>';
            });
               $("#area").html(output);
            }else{
                $("#area").html('<option value="">No Area Found</option>');
            }
        });
        }else{
          $("#area").html('<option value="">No Area Found</option>');
        }
        });


</script>
@endpush