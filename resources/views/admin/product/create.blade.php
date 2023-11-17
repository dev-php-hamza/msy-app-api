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
          <div class="card-header">Create New product</div>
          <div class="card-body">
            <form class="form-horizontal" action="{{route('products.store')}}" method="post" enctype="multipart/form-data" id="productForm">
              @csrf
              <div class="form-group">
                <label class="control-label col-sm-2" for="upc">Upc:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="upc" name="upc" placeholder="Enter UPC" value="{{ old('upc') }}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="desc">Desc:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="desc" name="desc" placeholder="Enter description" value="{{ old('desc') }}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="size">Size:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="size" name="size" placeholder="Enter Size 10ML" value="{{ old('size') }}">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="item_packing">Item Packing:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="item_packing" name="item_packing" placeholder="Enter Item Packing" value="{{ old('item_packing') }}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="unit_retail">Unit Retail:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="unit_retail" name="unit_retail" placeholder="Enter Unit Retail" value="{{ old('unit_retail') }}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="regular_retail">Regular Retail:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="regular_retail" name="regular_retail" placeholder="Enter Regular Retail" value="{{ old('regular_retail') }}" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="name">Country:</label>
                <div class="col-sm-10">
                  <select name="country_id" id="countries" class="form-control" required>
                  	<option value="">Please Choose Country</option>
                  	@foreach($countries as $key => $country)
                      <option value="{{ $country->id }}">{{ $country->name }}</option>
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
              <!-- Remove this button when you need country's location data -->
              <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

<!--  @section('scripts')
  <script src="{{ asset('js/products.js') }}"></script> 
@endsection -->