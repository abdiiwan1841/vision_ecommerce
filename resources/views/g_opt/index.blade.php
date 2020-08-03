@extends('layouts.adminlayout')
@section('title','General Option')

@section('content')
<div class="row">
<div class="col-lg-12">
	<div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-lg-6">
          <h5 class="card-title">General Information</h5>
        </div>
        <div class="col-lg-6 text-right">
        <a  href="{{route('generaloption.edit',$g_opt->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>
        </div>
        
      </div>
      
      
      
    </div>
    <div class="card-body table-responsive">
      
    
      <table class="table table-bordered table-striped table-hover mt-3" id="jq_datatables">
            <tr>
              <th>Loader</th>
              <td>{{$g_opt->options}}</td>
            </tr>
      </table>

    </div>
  </div>
</div>
</div>


@endsection

@push('css')

@endpush


@push('js')

@endpush