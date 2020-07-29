@extends('layouts.adminlayout')
@section('title','Inventory Product Show')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-5">
  
  <div class="card">
    <div class="card-header">
    <a href="{{route('posproducts.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-angle-double-left"></i> back</a>
    </div>
    
    <img style="text-align: center;margin: 0 auto;padding: 20px" class="card-img-top img-thumbnail img-responsive" src="{{asset('public/uploads/products/original/'.$product['image'])}}" alt="Card image cap">
    <div class="card-body">
      <h3 class="card-title">{{$product['product_name']}}</h3><hr>

      <p>Price : 
        @if($product->discount_price == NULL)
                          Tk.{{$product->price}}
                          @else Tk.{{$product->discount_price}} - 
                          <del>Tk.{{$product->price}}</del>
                          @endif </p>
      <p>Size: {{$product->size->name}}</p>
     
      
    
      
    </div>
  </div>
  
</div>
</div>
@endsection
