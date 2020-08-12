<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="ASOjne3KXoYfj3Bf24x8FUsRK920YOgbS7CcIRxa">

    <title>ecommerce</title>

    <!-- Bootstrap css -->
    <link href="{{asset('public/assets/css/bootstrap.css')}}" rel="stylesheet"/>
    <style>
      .table-bordered td, .table-bordered th{
        border: 1px solid #000;
      }
      .table thead th{
        border: 1px solid #000;
      }
    </style>

</head>
<body style="background: #fff;font-size: 12px;">
              <div style="width: 50%;margin: 0 auto">
                <h5 style="text-align: center;font-family: Tahoma,sans-serif">Cash Report</h5>
                @if($general_opt_value['inv_diff_invoice_heading'] == 1)
                <p style="font-weight: bold;text-align: center">{{$general_opt_value['inv_invoice_heading']}}</p>
                @else
                <p style="font-weight: bold;text-align: center">{{$CompanyInfo->company_name}}</p>
                @endif
                      <p style="text-align: center;font-size: 11px">{{$CompanyInfo->address}} <br> <b>Email :</b>  {{$CompanyInfo->email}} <br> <b>Phone:</b>  {{$CompanyInfo->phone}}</p>
              </div>


   


                  <div class="statement_table">
                    <p style="text-align: center;margin-bottom: 10px;font-weight: bold">From {{date("d-M-Y", strtotime($request->start) )}} To {{date("d-M-Y", strtotime($request->end) )}}</p>
                    <table class="table table-bordered table-striped">
       
                        <tr style="background: #ddd">
                          <td >Date</td>
                          <td class="align-middle">User</td>
                          <td class="align-middle">Amount</td>
                          <td class="align-middle">Ref</td>
                          <td class="align-middle">Source</td>
                        </tr>
      
                        @foreach ($datewise_sorted_data as $item)
                        @php
                        $username = DB::table('users')->where('id',$item['user_id'])->select('name')->first();
                       
                        @endphp
                        <tr>
                          <td class="align-middle" >{{$item['date']}}</td>
                          <td  class="align-middle" >{{ $username->name}}</td>
                          <td  class="align-middle" >{{$item['amount']}}</td>
                          <td  class="align-middle">{{$item['reference']}}</td>
                          <td  class="align-middle">{{$item['source']}}</td>
                        </tr>
                        @endforeach
      
      
                      </table>
                </div>


</body>
</html>

















