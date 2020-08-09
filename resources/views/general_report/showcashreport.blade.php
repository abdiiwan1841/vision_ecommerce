@extends('layouts.adminlayout')
@section('title','Inventory Divisionwise Statements')
@section('content')

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Cash Report
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form action="{{route('report.showcashreport')}}" method="POST">
                    @csrf
                      <div class="row">

                        <div class="col-lg-3">
                          <div class="form-group">
                            <span>Start Date : </span>
                          </div>
                          <div class="form-group">
                          <input type="text" class="form-control @error('start') is-invalid @enderror" name="start" id="start" placeholder="Select Start Date" value="{{$request->start}}">
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
                            <input type="text" class="form-control @error('end') is-invalid @enderror" name="end" id="end" placeholder="Select End Date" value="{{$request->end}}">
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

              <div class="row">
                <div class="col-lg-12">
                  <div class="statement_table table-responsive">
                    <h4 style="text-align: center;text-transform: uppercase;padding: 30px 0;font-family:Sans-serif">Cash Report</h4>
                    <h5 class="text-center mb-5">From {{date("d-M-Y", strtotime($request->start) )}} To {{date("d-M-Y", strtotime($request->end) )}}</h5>

                </div>

                <table class="table table-bordered table-striped">
       
                  <tr style="background: #ddd">
                    <td style="width: 200px" class="align-middle">Date</td>
                    <td style="width: 150px" class="align-middle">User</td>
                    <td class="align-middle">Amount</td>
                    <td class="align-middle">Ref</td>
                    <td class="align-middle">Source</td>
                  </tr>

                  @foreach ($datewise_sorted_data as $item)
                  @php
                  $username = DB::table('users')->where('id',$item['user_id'])->select('name')->first();
                 
                  @endphp
                  <tr>
                    <td class="align-middle"  style="width: 200px">{{$item['date']}}</td>
                    <td  class="align-middle"style="width: 150px">{{ $username->name}}</td>
                    <td  class="align-middle"style="width: 150px">{{$item['amount']}}</td>
                    <td  class="align-middle"style="width: 150px">{{$item['reference']}}</td>
                    <td  class="align-middle"style="width: 150px">{{$item['source']}}</td>
                  </tr>
                  @endforeach


                </table>



                </div>

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
  $('#division').select2({
width: '100%',
  theme: "bootstrap"
});
</script>
<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>
<script src="{{asset('public/assets/js/datatables.min.js')}}"></script>
<script src="{{asset('public/assets/js/dataTables.bootstrap4.min.js')}}"></script>


<script>
  $("#start").flatpickr({dateFormat: 'Y-m-d'});
  $("#end").flatpickr({dateFormat: 'Y-m-d'});

  $('#order_table').DataTable({});
  $('#cash_table').DataTable({});
</script>

@endpush

