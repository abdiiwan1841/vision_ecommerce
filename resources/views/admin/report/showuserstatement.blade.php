@extends('layouts.adminlayout')

@section('title','Inventory User Statement')

@section('content')

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Customer  Statement
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form action="{{route('report.showuserstatement')}}" method="POST">
                    @csrf
                      <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                              <span>Customer : </span>
                            </div>
                            <div class="form-group">
                              <select data-placeholder="Select a User" name="user" id="user" class="form-control @error('user') is-invalid @enderror">
                                <option></option>
                                @foreach ($users as $user)
                                  <option value="{{$user->id}}" @if ($request['user'] == $user->id) selected @endif>{{$user->name}}</option>
                                @endforeach
                              </select>
                              @error('user')
                              <small class="form-error">{{ $message }}</small>
                              @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <span>Start Date : </span>
                          </div>
                          <div class="form-group">
                            <input type="text" class="form-control @error('start') is-invalid @enderror" name="start" id="start" placeholder="Select Start Date" value="{{$request['start']}}">
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
                          <input type="text" class="form-control @error('end') is-invalid @enderror" name="end" id="end" value="{{$request['end']}}" placeholder="Select End Date">
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
                <div class="col-lg-6">
                  <div style="margin: 30px 0;padding: 10px">
                    <h3 class="">Customer Information</h3>
                    <table class="table table-borderless">
                      <tr>
                        <th>Name :</th>
                      <td>{{$user_info->name}}</td>
                      </tr>
                      <tr>
                        <th>Email : </th>
                        <td>{{$user_info->email}}</td>
                      </tr>
                      <tr>
                        <th>Phone: </th>
                        <td>{{$user_info->email}}</td>
                      </tr>
                      <tr>
                        <th>Address: </th>
                        <td>{{$user_info->address}}</td>
                      </tr>
                    </table>
                  </div>

                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="card">
                    <div class="card-header">
                      <h5>Order & Return Information</h5>
                    </div>
                    <div class="card-body">
                      <h5 class="text-center mb-5">From {{date("d-M-Y", strtotime($request->start) )}} To {{date("d-M-Y", strtotime($request->end) )}}</h5>
                      
                      <table class="table table-hover table-bordered table-striped" id="order_table">
                        <thead>
                          <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Particulers</th>
                          
                          </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{date("d-m-Y", strtotime($request->start) )}}</td>
                        <td><b>{{$previousOrderAmount}}</b></td>
                        <td>(Previous Order)</td>
                        </tr>

                        <tr>
                          <td>{{date("d-m-Y", strtotime($request->start) )}}</td>
                      <td><b>{{$previousReturnAmount}}</b></td>
                      <td>(Previous Returns)</td>
                      </tr>
                        @php

                          $ordersum = 0;
                        @endphp

                          @foreach ($orders as $item)
                          @php
                              $orderamount = FashiGetSalesAmount($item->id);
                              $ordersum = $ordersum+$orderamount;
                          @endphp
                          
                          <tr>
                            <td>{{\Carbon\Carbon::parse($item->ordered_at)->format('d-m-Y')}}</td>
                            <td><b>{{round($orderamount)}}</b></td>
                            <td>Orders <a class="btn btn-link" target="_blank" href="{{route('order.show',$item->id)}}">(view)</a></td>
                          </tr>
                          @endforeach



                        @php
                        $returnsum = 0;
                        @endphp

                          @foreach($returns as $returnitem)
 
                          @php
                              $returnamount = FashiGetReturnAmount($returnitem->id);
                              $returnsum = $returnsum+$returnamount;
                          @endphp
                
                          <tr>
                            <td>{{\Carbon\Carbon::parse($returnitem->returned_at)->format('d-m-Y')}}</td>
                            <td><b>{{round($returnamount)}}</b></td>
                          <td>Returns <a class="btn btn-link" target="_blank" href="{{route('return.show',$returnitem->id)}}">(view)</a></td>
                          </tr>
                          @endforeach
      



      
  
                        </tbody>
                      </table>

                      <table class="table table-bordered">
                        <tr>
                          <td>Previous Order :</td>
                          <th> {{round($previousOrderAmount)}}</th>
                          
                        </tr>

                        <tr>
                          <td>Previous Return : </td>
                          <th>{{round($previousReturnAmount)}}</th>
                          
                        </tr>

                        <tr>
                        <td>Order ({{date("d-M", strtotime($request->start) )}} To {{date("d-M", strtotime($request->end) )}}) </td>
                          <th>{{round($ordersum)}}</th>
                        </tr>

                        <tr>
                          <td>Return ({{date("d-M", strtotime($request->start) )}} To {{date("d-M", strtotime($request->end) )}}) </td> 
                          <th>{{round($returnsum)}}</th>
                        </tr>

                        <hr>
                        <tr>
                            <td>Total Order: </td>
                            <th>{{$total_order_amount = round($previousOrderAmount+$ordersum)}}</th>
                        </tr>

                        <tr>
                          <td>Total Returns: </td>
                          <th>{{$total_return_amount = round($previousReturnAmount+$returnsum)}}</th>
                      </tr>
                    </table>
                    </div>
                  </div>



                </div>

                <div class="col-lg-6">
                  <div class="card">
                    <div class="card-header">
                      <h5>Cash Information</h5>
                    </div>
                    <div class="card-body">
                      <h5 class="text-center mb-5">From {{date("d-M-Y", strtotime($request->start) )}} To {{date("d-M-Y", strtotime($request->end) )}}</h5>
                      <table class="table table-hover table-bordered table-striped" id="cash_table">
                        <thead>
                          <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                          <td>{{date("d-m-Y", strtotime($request->start) )}}</td>
                            <td><b>{{$previous_cashes+$previous_order_cash}}</b> (Previous cash)</td>
         
                          </tr>
                          @php
                          $cashsum = 0;    

                          @endphp
                          @foreach ($cashes as $item)
                          <tr>
                            <td>{{\Carbon\Carbon::parse($item->received_at)->format('d-m-Y')}}</td>
                            <td><b>{{$item->amount}}</b> ({{$item->reference}})</td>
            
                          </tr>
                          @php
                              $cashsum = $cashsum+$item->amount;
                          @endphp
                          @endforeach


                          @php
                          $ordercashsum = 0;    

                          @endphp
                          @foreach ($order_cash as $oc_item)
                          <tr>
                            <td>{{\Carbon\Carbon::parse($oc_item->paymented_at)->format('d-m-Y')}}</td>
                            <td><b>{{$oc_item->cash}}</b> ({{$oc_item->references}})</td>
            
                          </tr>
                          @php
                              $ordercashsum = $ordercashsum+$oc_item->cash;
                          @endphp
                          @endforeach


                          
                          
                          
    
                        </tbody>
                       
                      </table>
                      <table class="table table-bordered table-hover">
                          <tr>
                            <td>Previous Cash : </td>
                            <th>{{$previous_cashes+$previous_order_cash}}</th>
                            
                          </tr>
                          <tr>
                            <td>Cash ({{date("d-M", strtotime($request->start) )}} To {{date("d-M", strtotime($request->end) )}}) :</td>
                            <th>{{$cashsum+$ordercashsum}}</th>
                          </tr>
                          <hr>
                          <tr>
                            <td>Total Cash: </td>
                              <th>{{ $total_cash_amount = ($previous_cashes+$previous_order_cash+$cashsum+$ordercashsum)}}</th>
                          </tr>
                      </table>
                    
                      
                    </div>
                  </div>

                </div>

              </div>


              <div class="row mt-5">
                <div class="col-lg-6 offset-lg-3">
                  <table class="table table-hover table-bordered table-striped">
                    <tr>
                      <th>Total Sales: </th>
                    <td>{{$total_order_amount}}</td>
                    </tr>
                    <tr>
                      <th>Total Return: </th>
                    <td>-{{$total_return_amount}}</td>
                    </tr>
                    <tr>
                      <th>Total Cash: </th>
                    <td>-{{$total_cash_amount}}</td>
                    </tr>
                    <tr>
                      <th>Current Due: </th>
                      @php
                          $current_due = ($total_order_amount) - ($total_cash_amount+$total_return_amount)
                      @endphp
                    <td @if($current_due < 1) style="color: green" @else style="color: red" @endif >{{$current_due}}</td>
                    </tr>
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
  $('#user').select2({
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


