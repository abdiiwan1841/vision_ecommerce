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
                  @php
                  $stock = ($info['purchase']+$info['return']) -  ($info['order']+$info['sale']+$info['free']+$info['damage']);    
                  @endphp
                      <tr>
                        <td>{{$i++}}</td>
                        <td># {{$info['id']}}</td>
                        <td>{{$product_name}}</td>
                        <td><b>{{$stock}}</b></td>
                      <td>
                        <a href="{{route('stock.orderhistory',$info['id'])}}" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Order <span class="badge badge-success">{{$info['order']}}</span></a> | 


                        <a href="{{route('stock.purchasehistory',$info['id'])}}" class="btn btn-link btn-sm"> <i class="fa fa-history"></i> Purchase <span class="badge badge-warning">{{$info['purchase']}}</span>  </a>
                        
                        | <a href="{{route('stock.returnhistory',$info['id'])}}" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Return <span class="badge badge-dark">{{$info['return']}}</span></a> |  
                        <a href="{{route('stock.saleshistory',$info['id'])}}" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Sale <span class="badge badge-info">{{$info['sale']}}</span></a> | <a href="" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Damage <span class="badge badge-danger">{{$info['damage']}}</span></a> | <a href="" class="btn btn-link btn-sm"><i class="fa fa-history"></i> Free <span class="badge badge-danger">{{$info['free']}}</span></a> </td>
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


