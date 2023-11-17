@extends('layouts.admin.dashboard')

@section('admin-content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="">
        <div class="card" style="width:800px;">
          <div class="card-header">Products Details</div>
          <div class="card-body">
            <form class="form-horizontal" action="#" method="post">
              <div class="form-group">
                <label class="control-label col-sm-2" for="upc">Upc:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="upc" value="{{$product->upc}}" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="desc">Desc:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="desc" value="{{$product->desc}}" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="size">Size:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="size" value="{{$product->size}}" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="item_packing">Item Packing:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="item_packing" value="{{$product->item_packing}}" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="unit_retail">Unit Retail:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="unit_retail" value="{{$product->unit_retail}}" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="regular_retail">Regular Retail:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="regular_retail" value="{{ isset($product->regular_retail) ? $product->regular_retail : $product->unit_retail }}" readonly>
                </div>
              </div>
            </form>

            @forelse($product->images as $key => $productImage)
              <img src="{{$productImage->getImage($product->upc)}}" alt="productImage" width="100" height="100" class="ml-3 mb-3 mt-2">
            @empty
              <h3>No file found!</h3>
            @endforelse

            <div class="ml-3">
              <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#product-stock-box" aria-expanded="false" aria-controls="product-stock-box">
              Product Stock Information
            </button>
            </div>
            <div class="collapse mt-4 mx-3" id="product-stock-box">
              <div class="card card-body">
                <div class="card-body">
                  <div class="card-header mt-4 bg-black">
                    <div class="row">
                      <div class="col-md-1">#</div>
                      <div class="col-md-2">Store</div>
                      <div class="col-md-2">Address</div>
                      <div class="col-md-2">Location/Area</div>
                      <div class="col-md-2">Country</div>
                      <div class="col-md-2">Quantity</div>
                    </div>
                  </div>
                  <div class="card-body pt-0">
                    @forelse($productStores as $key => $store)
                      <div class="row align-items-center bordered">
                        <div class="col-md-1">{{ $loop->index + 1 }}</div>
                        <div class="col-md-2">{{ $store->name }}</div>
                        <div class="col-md-2">{{ $store->address_line_one }}</div>
                        <div class="col-md-2">{{ $store->location->name }}</div>
                        <div class="col-md-2">{{ $store->country->name }}</div>
                        <div class="col-md-2">{{ $store->pivot->quantity }}</div>
                      </div>
                    @empty
                      No Stock Found!
                    @endforelse
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="pull-right">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
