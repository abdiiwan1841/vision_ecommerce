@extends('layouts.adminlayout')
@section('title','Stock Details')

@section('content')

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Stock Details
            </div>
            <div class="card-body">
                           

              <table class="table" id="jq_datatables">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">PID</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                      $i=1;
                  @endphp
                  @foreach ($stockinfo as $product_name => $info)
                      <tr>
                        <td>{{$i++}}</td>
                        <td># {{$info[0]}}</td>
                        <td>{{$product_name}}</td>
                        <td><b>{{($info[1]+$info[3])-($info[2]+$info[4]+$info[5])}}</b></td>
                      <td>
                        <a href="{{route('stock.orderhistory',$info[0])}}" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Order <span class="badge badge-success">{{$info[2]}}</span></a> | 
                        <a href="{{route('stock.purchasehistory',$info[0])}}" class="btn btn-link btn-sm"> <i class="fa fa-history"></i> Purchase <span class="badge badge-warning">{{$info[1]}}</span>  </a> | 
                        <a href="{{route('stock.returnhistory',$info[0])}}" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Return <span class="badge badge-dark">{{$info[3]}}</span></a> |  
                        <a href="{{route('stock.saleshistory',$info[0])}}" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Sale <span class="badge badge-info">{{$info[4]}}</span></a> | <a href="" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Damage <span class="badge badge-danger">{{$info[5]}}</span></a> </td>
                      </tr>
                  @endforeach
                  
                </tbody>
              </table>

            </div>
        </div>

     



    </div>
  </div>

@endsection

@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/dataTables.bootstrap4.min.css')}}">
@endpush

@push('js')

<script src="{{asset('public/assets/js/datatables.min.js')}}"></script>
<script src="{{asset('public/assets/js/dataTables.bootstrap4.min.js')}}"></script>
<script>
$('#jq_datatables').DataTable({
  "order": [ [2, 'asc'] ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
    },
});
</script>
@endpush


