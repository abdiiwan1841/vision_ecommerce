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
                <div class="row">
                @foreach($admins as $key =>  $admin)
                <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>{{$admin->name}}</strong>
                    </div>
                    <div class="card-body" @if($admin->status == 0) style="background: #ffcccc"  @endif>
                        <table class="table">
                            <tr>
                                <td>Userid</td>
                                <td>{{$admin->adminname}}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td><strong>{{$admin->name}}</strong></td>
                            </tr>
                            <tr>   
                                <td>Email</td>
                                <td>{{$admin->email}}</td>
                            </tr> 
                            <tr>
                                <td>Phone</td>
                                <td>{{$admin->phone}}</td>
                            </tr>
                            <tr>
                                <td>Role</td>     
                                <td>
                                @foreach ($admin->roles as $item)
                                    <span class="badge badge-success">{{$item->name}}</span>
                                @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>signature</td>
                                <td><img style="width: 100px" src="{{asset('public/uploads/admin/signature/'.$admin->signature)}}" alt=""></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>{!!AdminLoginStatus($admin->status)!!}</td>
                            </tr>
                            <tr>
                                <td>Created At</td>
                                <td>{{$admin->created_at->format('d-M-Y g:i a')}}</td>
                            </tr>
                           <tr>
                                <td>Action</td>
                                <td><a class="btn btn-sm btn-primary" href="{{route('admininfo.edit',$admin->id)}}"><i class="fas fa-edit"></i> Edit</a> |
                                @if($admin->status == 1)
                                <form action="{{route('admin.changeloginstatus',$admin->id)}}" style="display: inline" method="POST">
                                    @csrf
                                    <button onclick="return confirm('Are you sure you want to disabled this user Login')" type="submit" class="btn btn-warning btn-sm"><i class="fas fa-delete"></i> Disable Login</button>
                                </form>

                                @else
                                <form action="{{route('admin.changeloginstatus',$admin->id)}}" style="display: inline" method="POST">
                                    @csrf
                                    <button onclick="return confirm('Are you sure you want to Enable this user Login')" type="submit" class="btn btn-success btn-sm"><i class="fas fa-delete"></i> Re-Enable Login</button>
                                </form>

                                @endif
                                
                                </td>

                            </tr>
         
                        </table>
                    </div>
                </div>
            </div>
                @endforeach
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


