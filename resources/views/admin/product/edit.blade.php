@extends('layouts.admin.dashboard')

@section('admin-content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="">
        @if(count($errors) > 0)
          <ul>
            @foreach($errors->all() as $error)
              <li class="alert alert-danger">{{$error}}</li>
            @endforeach
          </ul>
        @endif
        <div class="card" style="width:800px;">
          <div class="card-header">Update Product</div>
          <div class="card-body">
            <form class="form-horizontal" action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label class="control-label col-sm-2" for="upc">Upc:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="upc" name="upc" value="{{$product->upc}}" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="desc">Desc:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="desc" name="desc" value="{{$product->desc}}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="size">Size:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="size" name="size" value="{{$product->size}}">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="item_packing">Item Packing:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="item_packing" name="item_packing" value="{{$product->item_packing}}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="unit_retail">Unit Retail:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="unit_retail" name="unit_retail" value="{{$product->unit_retail}}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="regular_retail">Regular Retail:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="regular_retail" name="regular_retail" value="{{ isset($product->regular_retail) ? $product->regular_retail : $product->unit_retail }}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="name">Country:</label>
                <div class="col-sm-10">
                  <select name="country_id" id="countries" class="form-control" required>
                    <option value="">Please Choose Country</option>
                    @foreach($countries as $key => $country)
                      <option value="{{ $country->id }}" {{ ($country->id == $product->country_id)?'selected':'' }}>{{ $country->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-12" for="file">Product Images: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 218px width and 218px height)</strong></label>
                <div class="col-sm-10">
                  <input type="file" multiple="true" name="file[]">
                </div>
              </div>
              <div class="row" style="padding-left: 14px;">
                @forelse($product->images as $key => $productImage)
                  <div class="col-md-6 mb-4">
                    <div class="row">
                      <div class="col-md-4">
                        <img class="image" src="{{ $productImage->getImage($product->upc) }}" alt="productImage">
                      </div>
                      <div class="col-md-8 pl-0">
                        <div class="mb-2">
                          <a href="{{ route('product_image_remove', $productImage->id ) }}"><strong>Delete Image</strong></a>
                        </div>
                      </div>
                    </div>
                  </div>
                @empty
                  <img class="ml-3 mb-2" src="{{ asset('assets/images/no-image.png') }}" width="80" height="80">
                @endforelse
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
