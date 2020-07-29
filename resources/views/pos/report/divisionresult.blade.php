@extends('layouts.adminlayout')
@section('title','Inventory Divisionwise Statements')
@section('content')

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Inventory Divisionwise  Due Report
            </div>
            <div class="card-body">
            <form action="{{route('report.divisionreportresult')}}" method="POST">
              @csrf
                <div class="row">
                  <div class="col-lg-4">
                      <div class="form-group">
                        <span>Division : </span>
                      </div>
                      <div class="form-group @error('division') is-invalid @enderror">
                        <select data-placeholder="Select a Division" name="division" id="division" class="form-control">
                          <option></option>
                          @foreach ($divisions as $division)
                            <option value="{{$division->id}}" @if (old('division') == $division->id) selected  @endif>{{$division->name}}</option>
                          @endforeach
                        </select>
                        @error('division')
                        <small class="form-error">{{ $message }}</small>
                        @enderror
                      </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <span>Start Date : </span>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control @error('start') is-invalid @enderror" name="start" id="start" placeholder="Select Start Date" value="{{old('start')}}">
                          @error('start')
                          <small class="form-error">{{ $message }}</small>
                          @enderror
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <span>End Date : </span>
                    </div>
                    <div class="form-group">
                    <input type="text" class="form-control @error('end') is-invalid @enderror" name="end" id="end" placeholder="Select End Date" value="{{old('end')}}">
                      @error('end')
                      <small class="form-error">{{ $message }}</small>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div style="margin-top: 40px;">
                      <button type="submit" class="btn btn-info">submit</button>
                    </div>
                   
                  </div>
                </div>       
              </form>

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
  $('#division').select2({
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


