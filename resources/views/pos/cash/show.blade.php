@extends('layouts.adminlayout')
@section('title','Inventory Cash')

@section('content')

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                  <div class="col-lg-6">
                    @if(Route::current()->getName() == 'invdashboard.cashdetails')
                    <a class="btn btn-info btn-sm" href="{{route('admin.inventorydashboard')}}">back to dashboard</a>
                    @else

                    <a class="btn btn-info btn-sm" href="{{route('cash.index')}}">back</a>

                    @endif
                  </div>
                    <div class="col-lg-6">
                     
                      <h5 class="card-title text-left">Cash Information</h5>
                    </div>
                    
                  </div>
                
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-lg-6">
                  <table class="table">
                      <tr>
                        <td>Name:</td>
                      <td>{{$cash->user->name}}</td>
                      </tr>
                      <tr>
                        <td>Amount:</td>
                      <td>{{$cash->amount}}</td>
                      </tr>
                      <tr>
                        <td>Status:</td>
                      <td>{!!InvCashStatus($cash->status)!!}</td>
                      </tr>
                      <tr>
                        <td>Cash Received Date:</td>
                      <td><strong>{{$cash->received_at->format('d F Y')}}</strong> </td>
                      </tr>
                
                      <tr>
                        <td>Posted By:</td>
                      <td>{{$cash->posted_by}} <br>  <small>at {{$cash->created_at->format('d M Y g : i a')}}</small></td>
                      </tr>
                      @if(Auth::user()->role->id == 1)
                      @if($cash->status == 0)
                      <tr>
                        <td>Action</td>
                        <td>
   
                          <form action="{{route('cash.approve',$cash->id)}}" method="POST" style="display: inline-block">
      
                          @csrf
                         
                              <button onclick="return confirm('Are you sure you want to Confirm This Cash')"  type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Approve</button>
                          </form> |
                          <form action="{{route('cash.cancel',$cash->id)}}" method="POST" style="display: inline-block">
                            @csrf
                            <button type="submit" onclick="return confirm('Are you sure you want to cancel this cash')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Cancel</button>
                          </form>
                        </td>
                      </tr>
                      @endif
                      @endif
                      @if($cash->status == 1)
                      <tr>
                        <td>Approved By</td>
                      <td>{{$admin->name}} <br> <small>at {{$cash->updated_at->format('d M Y g:i a')}}</small>
                      <img style="width: 200px" src="{{asset('public/uploads/admin/signature/'.$admin->signature)}}" alt="">
                    </td>
                      </tr>
                      @elseif($cash->status == 2)
                      <tr>
                        <td>Canceled By</td>
                      <td>{{$admin->name}} <br> <small>at {{$cash->updated_at->format('d M Y g:i a')}}</small>
                      <img style="width: 200px" src="{{asset('public/uploads/admin/signature/'.$admin->signature)}}" alt="">
                    </td>
                      </tr>
                      @endif
                  </table>
                </div>
              </div>

            </div>
        </div>

     



    </div>
  </div>

@endsection




