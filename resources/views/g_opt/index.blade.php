@extends('layouts.adminlayout')
@section('title','Edit g_opt')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
	<div class="card">
        <div class="card-header">
          

          <h5 class="text-center">General Option</h5>

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
              <tr><th> <h4>General  Section</h4></th></tr>
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
              <tr>
                <td> Show Before Footer Infobox</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="before_footer_infobox" class="onoffswitch-checkbox" id="before_footer_infobox" value="1" @if($g_opt_value['before_footer_infobox'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="before_footer_infobox">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>
              <tr><th> <h4>Homepage Section</h4> <br></th></tr>
              <tr>
                <td> Show Slider</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="slider" class="onoffswitch-checkbox" id="slider" value="1" @if($g_opt_value['slider'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="slider">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>
              <tr>
              <td> Show Product Types</td>
              <td>  <div class="onoffswitch">
                <input type="checkbox" name="product_types" class="onoffswitch-checkbox" id="product_types" value="1" @if($g_opt_value['product_types'] == 1) checked @endif>
                <label class="onoffswitch-label" for="product_types">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div></td>
            </tr>
            <tr>
              <td> Show Product Types Counter</td>
              <td>  <div class="onoffswitch">
                <input type="checkbox" name="product_types_counter" class="onoffswitch-checkbox" id="product_types_counter" value="1" @if($g_opt_value['product_types_counter'] == 1) checked @endif>
                <label class="onoffswitch-label" for="product_types_counter">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div></td>
            </tr>

            <tr>
            <td>Product Types Number Of Items</td>
            <td><input type="number" class="form-control" name="pd_type_noi" style="width: 80px" value="{{$g_opt_value['pd_type_noi']}}"></td>
              </tr>

              <tr>
                <td> Show Brands</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="brands" class="onoffswitch-checkbox" id="brands" value="1" @if($g_opt_value['brands'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="brands">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>

              <tr>
                <td> Show Brands Counter</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="brands_counter" class="onoffswitch-checkbox" id="brands_counter" value="1" @if($g_opt_value['brands_counter'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="brands_counter">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>

              <tr>
                <td>Brands Number Of Items</td>
            <td><input type="number" class="form-control" name="pd_brands_noi" style="width: 80px" value="{{$g_opt_value['pd_brands_noi']}}"></td>
              </tr>

              <tr>
                <td> Show New Products</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="new_pd" class="onoffswitch-checkbox" id="new_pd" value="1" @if($g_opt_value['new_pd'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="new_pd">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>

              <tr>
                <td>New Product Number Of Items</td>
            <td><input type="number" class="form-control" name="new_pd_noi" style="width: 80px" value="{{$g_opt_value['new_pd_noi']}}"></td>
              </tr>

              <tr>
                <td> Show Hot Products</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="hot_pd" class="onoffswitch-checkbox" id="hot_pd" value="1" @if($g_opt_value['hot_pd'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="hot_pd">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>

              <tr>
                <td>Hot Product Number Of Items</td>
            <td><input type="number" class="form-control" name="hot_pd_noi" style="width: 80px" value="{{$g_opt_value['hot_pd_noi']}}"></td>
              </tr>

              <tr>
                <td> Show Product Collection</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="pd_collection" class="onoffswitch-checkbox" id="pd_collection" value="1" @if($g_opt_value['pd_collection'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="pd_collection">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>

              <tr>
                <td> Show Collection Counter</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="collection_counter" class="onoffswitch-checkbox" id="collection_counter" value="1" @if($g_opt_value['collection_counter'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="collection_counter">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>

              <tr>
                <td>Product Collection Number Of Items</td>
            <td><input type="number" class="form-control" name="pd_collection_noi" style="width: 80px" value="{{$g_opt_value['pd_collection_noi']}}"></td>
              </tr>


              <tr><th> <h4>Inventory  Section</h4></th></tr>
              <tr>
                <td>Differnet Inventory Invoice Heading</td>
                <td>  <div class="onoffswitch">
                  <input type="checkbox" name="inv_diff_invoice_heading" class="onoffswitch-checkbox" id="inv_diff_invoice_heading" onchange="opt_show('inv_diff_invoice_heading','inv_heading_input')" value="1" @if($g_opt_value['inv_diff_invoice_heading'] == 1) checked @endif>
                  <label class="onoffswitch-label" for="inv_diff_invoice_heading">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </div></td>
              </tr>

              <tr id="inv_heading_input">
                <td>Invoice Heaing</td>
            <td><input type="text" class="form-control" id="inv_invoice_heading" name="inv_invoice_heading"  value="{{$g_opt_value['inv_invoice_heading']}}"></td>
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

@push('js')
<script>
function opt_show(parent_status,child_id){
  var element = document.getElementById(parent_status);
  if(element == 1){
    $("#".child_id).show();
  }else{
    $("#".child_id).hide();
  }
}
</script>
@endpush


