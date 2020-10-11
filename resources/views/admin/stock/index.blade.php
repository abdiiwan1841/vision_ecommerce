@extends('layouts.adminlayout')
@section('title','Stock Details')

@section('content')

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
            <form action="{{route('stock.export')}}" method="POST" style="display: inline">
              @csrf
                <button type="submit" class="btn btn-success">Export</button>
              </form> 
              <b>Stock Details</b> 
            </div>
            <div class="card-body">
                           
              <div class="table-responsive">
              <table class="table table-sm table-striped" id="jq_datatables">
                <thead class="thead-dark">
                  <tr>
                    <th>Product Name</th>
                    <th >Qty</th>
                    <th>Action</th>
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
                        <td class="align-middle">{{$product_name}}</td>
                        <td class="align-middle"><h4 style="border-radius: 10px;background-color: #f9ca24 !important;padding: 19px 6px;text-align:center"><b>{{$stock}}</b> </h4> </td>
                      <td class="align-middle">
                        <table class="table">
                          <tr>
                            <td><a class="btn btn-sm btn-success" href="{{route('stock.orderhistory',$info['id'])}}" > Ecom <span class="badge badge-dark">{{$info['order']}}</span></a></td>
                            <td> <a class="btn btn-sm btn-primary" href="{{route('stock.purchasehistory',$info['id'])}}" >  Purchase <span class="badge badge-warning">{{$info['purchase']}}</span>  </a></td>
                          </tr>
                          <tr>
                            
                          </tr>
                          <tr>
                            <td><a class="btn btn-sm btn-warning" href="{{route('stock.returnhistory',$info['id'])}}" > Return <span class="badge badge-dark">{{$info['return']}}</span></a></td>
                            <td><a class="btn btn-sm btn-info" href="{{route('stock.saleshistory',$info['id'])}}" > Sales <span class="badge badge-dark">{{$info['sale']}}</span></a></td>
                          </tr>
                          <tr>
                            <td><a class="btn btn-sm btn-danger" href="{{route('stock.damagehistory',$info['id'])}}" > Damage <span class="badge badge-dark">{{$info['damage']}}</span></a></td>
                            <td><a  class="btn btn-secondary btn-sm" href="{{route('stock.freehistory',$info['id'])}}" > Free <span class="badge badge-danger">{{$info['free']}}</span></a></td>
                          </tr>
                        </table>
                        </td>
                      </tr>
                  @endforeach
                  
                </tbody>
              </table>
            </div>
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
$('#jq_datatables').DataTable();
</script>
@endpush


