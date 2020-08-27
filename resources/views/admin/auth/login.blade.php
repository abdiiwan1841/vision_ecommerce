
@extends('layouts.app')
@push('css')
  <link rel="stylesheet" href="{{asset('public/asset/css/login.css')}}">
  <link rel="stylesheet" href="{{asset('public/css/icheck-bootstrap.min.css')}}">
@endpush
@section('content')

<div class="row justify-content-center">
  
<div class="col-lg-3" style="margin: 0 30px">
 

<div class="login-box mt-5">

  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card">
      <h4 class="text-center"><b>ADMIN LOGIN</b></h4>
      <div  style="width: 100%;text-align:center;margin: 20px 0">
      <a href="{{route('homepage.index')}}"><img src="{{asset('public/uploads/logo/cropped/'.$CompanyInfo->logo)}}" alt=""></a>
      </div>
    
      @if (Session::has('error'))
            <span class="text-danger">{{ Session::get('error') }}</span>
      @endif
      <form action="" method="post">
        @csrf
        <label for="username">Email/Phone Or Username</label>
        <div class="input-group mb-3">
         
          <input type="text" class="form-control" placeholder="Enter Email,Phone Or Username" id="username" name="adminname" value="{{old('adminname')}}"> 
          <div class="input-group-append">
            <div class="input-group-text">
                <i class="fa fa-user"></i>
            </div>
          </div>
            @error('adminname')
            <span style="color: red">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
        </div>
        <label for="password">Password</label>
        <div class="input-group mb-3">
          <input type="password" class="form-control" id="password" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="fa fa-lock"></i>
            </div>
          </div>
          @error('password')
          <span style="color: red">
            <strong>{{ $message }}</strong>
        </span>
      @enderror
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-success">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-sm">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>



      
    </div>
    @if(Session::has('success'))
    <span class="alert alert-success">
      {{Session::get('success')}}
    </span>
    @endif
    <!-- /.login-card-body -->
  </div>
</div>
</div>
</div>
@endsection
