@extends('layouts.adminlayout')
@section('title','Purchase')
@section('content')

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4">
                        <h5 class="card-title">Admin List</h5>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{route('admininfo.create')}}" class="btn btn-info btn-sm float-right"><i class="fa fa-plus"></i>Add New Admin</a>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Sl.</td>
                        <td>Userid</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Role</td>
                        <td>Created At</td>
                        <td>Action</td>
                    </tr>
                    @foreach($admins as $key =>  $admin)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$admin->adminname}}</td>
                        <td>{{$admin->name}}</td>
                        <td>{{$admin->email}}</td>
                        <td>{{$admin->phone}}</td>
                        <td>{{$admin->role->name}}</td>
                        <td>{{$admin->created_at->format('d-M-Y g:i a')}}</td>
                    <td><a class="btn btn-sm btn-primary" href="{{route('admininfo.edit',$admin->id)}}"><i class="fas fa-edit"></i></a></td>
                    </tr>
                    @endforeach
                </table>

            </div>
        </div>

     



    </div>
  </div>

@endsection

@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/flatpicker.min.css')}}">
@endpush

@push('js')
<script>
  sessionStorage.clear();
  $('#user').select2({
width: '100%',
  theme: "bootstrap"
});
</script>
<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>
<script>
  $("#start").flatpickr({dateFormat: 'Y-m-d'});
  $("#end").flatpickr({dateFormat: 'Y-m-d'});
</script>

@endpush


