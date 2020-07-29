@extends('layouts.adminlayout')
@section('title','Admin Dashboard')

@section('content')


<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
        <table class="table table-striped table-borderless">
          <thead>
            <tr>
              <th>Photo</th>
            <td><img  width="100px" src="{{asset('public/uploads/user/thumb/'.Auth::user()->image)}}" alt=""></td>
            </tr>
            <tr>
              <th>Name : </th>
               <td>{{Auth::user()->name}}</td>
            </tr>
            <tr>
              <th>Email : </th>
              <td>{{Auth::user()->email}}</td>
            </tr>
              
            <tr>
              <th>Phone : </th>
              <td>{{Auth::user()->phone}}</td>
            </tr>
            <tr>
            <th>Action</th>
            <th><a href="{{route('admin.editprofile')}}">Edit Profile</a></th>
            </tr>

          </thead>
          <tbody>
          
  
          </tbody>
        </table>
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection