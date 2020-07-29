
@extends('layouts.adminlayout')
@section('title','Create Inventory Customer')

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
                    <h5 class="card-title float-right">Add Pages</h5>
                </div>
            </div>
        </div>
    <div class="card-body">
    <div class="row justify-content-center">
        <div class="col-lg-8">
    <form action="{{route('pages.store')}}" method="POST" enctype="multipart/form-data" style="border: 1px solid #ddd;padding: 20px;border-radius: 5px">
        @csrf
        <div class="row">
       
        <div class="col-lg-6">
            
            <div class="form-group">
                <label for="page_title">Page Title<span>*</span></label>
            <input type="text" id="page_title" placeholder="Enter Page Title" class="form-control @error('page_title') is-invalid @enderror" name="page_title" value="{{old('page_title')}}" required>
      
                @error('page_title')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>



            <div class="form-group">
                <label for="page_description">Page Description<span>*</span></label>
            <textarea cols="30" rows="10" id="page_description" placeholder="Enter Page Description" class="form-control @error('page_description') is-invalid @enderror" name="page_description" required>{{old('page_description')}}</textarea>
      
                @error('page_description')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="banner_image">Banner Image<span>*</span></label>
            <input type="file" id="banner_image" placeholder="Enter Page Heading" class="form-control @error('banner_image') is-invalid @enderror" name="banner_image" value="{{old('banner_image')}}">
      
                @error('banner_image')
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
<link rel="stylesheet" href="{{asset('public/assets/css/trix.css')}}">
@endpush


@push('js')
<script>
<script src="{{asset('public/assets/js/trix.js')}}"></script>

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





</script>
@endpush