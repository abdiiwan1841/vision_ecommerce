
@extends('layouts.app')
@push('css')
  <link rel="stylesheet" href="{{asset('public/asset/css/login.css')}}">
  <link rel="stylesheet" href="{{asset('public/css/icheck-bootstrap.min.css')}}">
@endpush
@section('content')

<div class="row justify-content-center">
  
<div class="col-lg-3">


<div class="login-box mt-5">

  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
    	<h4 class="text-center">Employee <b>Login</b></h4>
      @if (Session::has('error'))
            <span class="text-danger">{{ Session::get('error') }}</span>
      @endif
    <form action="{{route('employee.loginsubmit')}}" method="post">
      @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        @csrf
        <label for="username">Phone</label>
        <div class="input-group mb-3">
         
          <input type="text" class="form-control" placeholder="Enter Phone Number" id="phone" name="phone" value="{{old('phone')}}"
            > 
          <div class="input-group-append">
            <div class="input-group-text">
                <i class="fa fa-user"></i>
            </div>
          </div>
            
        </div>
        <label for="password">Password</label>
        <div class="input-group mb-3">
          <input type="password" class="form-control" id="password" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="fa fa-lock"></i>
            </div>
          </div>
         
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
