
@extends('layouts.adminlayout')
@section('title','Create Inventory Customer')

@section('content')
<div class="row">
<div class="col-lg-12">
	<div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                <a class="btn btn-info btn-sm" href="{{route('employee.index')}}"><i class="fa fa-angle-left"></i> back</a>
                </div>
                <div class="col-lg-8">
                    <h5 class="card-title float-right">Add New Employee</h5>
                </div>
            </div>
        </div>
    <div class="card-body">
    <div class="row justify-content-center">
        <div class="col-lg-6">
    <form action="{{route('employee.store')}}" method="POST" style="border: 1px solid #ddd;padding: 20px;border-radius: 5px">
        @csrf
        <div class="row">
        
        <div class="col-lg-12">
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            
            <div class="form-group">
                <label for="name">Employee Name<span>*</span></label>
            <input type="text" id="name" placeholder="Enter Employee name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" required>
      
                @error('name')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email<span>*</span></label>
            <input type="text" id="email" placeholder="Enter Employee email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" required>
      
                @error('email')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>


            <div class="form-group">
                <label for="phone">Phone<span>*</span></label>
            <input type="text" id="phone" placeholder="Enter Employee phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone')}}" required>
      
                @error('phone')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

               <div class="form-group">
                <label for="address">Address<span>*</span></label>
            <textarea   rows="8" id="address" placeholder="Enter Employee address" class="form-control @error('address') is-invalid @enderror" name="address" required>{{old('address')}}</textarea>
      
                @error('address')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="joining_date">Joining Date<span>*</span></label>
            <input type="text" id="joining_date" placeholder="Enter Employee joining_date" class="form-control @error('joining_date') is-invalid @enderror" name="joining_date" value="{{old('joining_date')}}" required>
      
                @error('joining_date')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>



            
            <div class="form-group">
                <label for="salary">Salary<span>*</span></label>
            <input type="text" id="salary" placeholder="Enter Employee salary" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{old('salary')}}" required>
      
                @error('salary')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

               
            <div class="form-group">
                <label for="nid">NID Number</label>
            <input type="text" id="nid" placeholder="Enter Employee nid" class="form-control @error('nid') is-invalid @enderror" name="nid" value="{{old('nid')}}">
      
                @error('nid')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="employee_type_id">Employee Type</label>
            
            <select id="employee_type_id" class="form-control @error('employee_type_id') is-invalid @enderror" name="employee_type_id" required>
            <option value="">-select employee type-</option>
            @foreach ($emp_types as $item)
           
            <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
            </select>
      
                @error('employee_type_id')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Address<span>*</span></label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="7" placeholder="Enter Your Addres" required>{{old('address')}}</textarea>
                
                @error('address')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>


            

        </div>
        <div class="col-lg-8">
            <div class="form-group">
                <button type="submit" class="btn btn-success">Create</button>
            </div>
        </div>
    
    </div>
</form>
</div>
</div>
    </div>
  </div>
</div>
</div>


@endsection
@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/flatpicker.min.css')}}">
@endpush

@push('js')
<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>
<script>
  $("#joining_date").flatpickr({dateFormat: 'Y-m-d', allowInput: true});
</script>

@endpush

