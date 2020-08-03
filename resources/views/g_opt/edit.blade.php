@extends('layouts.adminlayout')
@section('title','Edit g_opt')

@section('content')
<div class="row">
<div class="col-lg-6">
	<div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                <a class="btn btn-info btn-sm" href="{{route('generaloption.index')}}"><i class="fa fa-angle-left"></i> back</a>
                </div>
                <div class="col-lg-8">
                    <h5 class="text-right">EDIT General Option</h5>
                </div>
            </div>
        </div>
    <div class="card-body">



    <form action="{{route('generaloption.update',$g_opt->id)}}" method="POST">
        @csrf
        @method('PUT')

       
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
            <table class="table">
              <tr>
                <td> Page Loader</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="pageloader" class="onoffswitch-checkbox" id="pageloader" value="1" @if($g_opt_value['pageloader'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="pageloader">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>
              
            </table>
      
      
        
            </div>
            
    

            
            <div class="form-group">
              <button type="submit" class="btn btn-success">Update</button>
          </div>

          

      </form>

    </div>

    </div>
  </div>
</div>


@endsection



