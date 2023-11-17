@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Coupon Details</div>
        <div class="card-body">
          <form class="form-horizontal">
          	@csrf
            <div class="col-sm-10 d-flex justify-content-between">
              <div class="form-group">
                <label class="pb-2">Status: </label>
                @if($coupon->active == 1)
                  <strong style="color: green;">Active</strong>
                @else
                  <strong style="color: red;">Inactive</strong>
                @endif
              </div>
              <div class="form-group">
                <label class="pb-2">Featured: </label>
                @if($coupon->is_featured == 1)
                  <strong style="color: green;">Active</strong>
                @else
                  <strong style="color: red;">Inactive</strong>
                @endif
              </div>              
            </div>

          	<div class="form-group">
          		<label class="control-label col-sm-2" for="coupon_name">Name</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="coupon_name" value="{{$coupon->title}}" readonly>
          		</div>
          	</div>
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="country">Country</label>
          		<div class="col-sm-10">
          			<input type="text" class="form-control" id="country" value="{{ $coupon->country->name}}" readonly>
          		</div>
          	</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="barcode">Coupon Type</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="barcode" name="barcode" value="{{ ($coupon->coupon_type == 'std_bundle') ? 'Standard Bundle' : (($coupon->coupon_type == 'mix_and_match') ? 'Mix & Match' : 'Barcode') }}" required readonly>
              </div>
            </div>
            <!-- <div class="row pl-3">
              <div class="col-sm-5">
                <label class="control-label" for="start_date_ux">Start Date</label>
                <div class="">
                  <input type="text" class="form-control" id="start_date" value="{{$coupon->start_date}}" readonly>
                </div>
              </div>
              <div class="col-sm-5">
                <label class="control-label" for="snday">Start Time:</label>
                <div class="">
                   <div class="row">
                       <div class="d-flex align-items-center">
                          <label class="control-label mr-2"></label>
                           <div class="form-group">
                              <input type="time" value="{{date_format(date_create($coupon->start_time), 'H:i')}}" name="start_time" class="form-control" readonly>
                          </div>
                       </div>
                   </div>
                </div>
              </div>
            </div>
            <div class="row pl-3">
              <div class="col-sm-5">
                <label class="control-label" for="end_date_ux">End Date</label>
                <div class="">
                    <input type="text" class="form-control" id="end_date" value="{{$coupon->end_date}}" readonly>
                </div>
              </div>
              <div class="col-sm-5">
                <label class="control-label" for="snday">End Time</label>
                <div class="">
                 <div class="row">
                     <div class="d-flex align-items-center">
                        <label class="control-label mr-2"></label>
                         <div class="form-group">
                            <input type="time" value="{{date_format(date_create($coupon->end_time), 'H:i')}}" name="end_time" class="form-control" readonly>
                        </div>
                     </div>
                 </div>
                </div>
              </div>
            </div> -->
            @if($coupon->coupon_type == 'std_bundle')
              <div class="form-group">
                <label class="control-label col-sm-2" for="barcode">Standrad Bundle</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="barcode" name="barcode" value="{{ (isset($coupon->bundle)) ? $coupon->bundle->number : '' }}" required readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="barcode">Bundle Price</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="barcode" name="barcode" value="{{ (isset($coupon->bundle)) ? $coupon->bundle->bundle_price : '' }}" required readonly>
                </div>
              </div>
            @endif
            @if($coupon->coupon_type == 'barcode')
              <div class="form-group">
                <label class="control-label col-sm-2" for="barcode">Barcode</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="barcode" name="barcode" value="{{$coupon->barcode}}" required readonly>
                </div>
              </div>
            @endif
            <div class="form-group">
              <label class="control-label col-sm-4" for="short-description">Short Description</label>
              <div class="col-sm-10">
                <textarea class="promo-description" readonly>{{ $coupon->short_description }}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="description">Description</label>
              <div class="col-sm-10">
                <textarea class="promo-description" readonly>{{ $coupon->description }}</textarea>
              </div>
            </div>
            <!-- <div class="form-group mb-4 hlight-section">
              <label class="control-label col-sm-4" for="promo_image"><b>Coupon Barcode Image</b></label>
              <div class="col-sm-10">
                @if($coupon->barcode_image != null)
                  <img src="{{-- asset('coupon/images/'.$coupon->barcode_image) --}}" alt="couponBarcodeImage" width="100" height="100">
                @else
                  <h3>No file found!</h3>
                @endif
              </div>
            </div> -->
            <div class="form-group mb-4 hlight-section">
              <label class="control-label col-sm-4" for="promo_image"><b>Coupon Image</b></label>
              <div class="col-sm-10">
                @if($coupon->image != null)
                  <img src="{{$coupon->getImage()}}" alt="couponImage" width="100" height="100">
                @else
                  <h3>No file found!</h3>
                @endif
              </div>
            </div>
            <hr>
          	<div class="form-group hlight-section">
          		<label class="control-label col-sm-4" for="products"><b>Attached Products</b></label>
          	</div>
          </form>
          @include('components.product.product',['products'=> $coupon->products])
           <hr>
           @if($coupon->coupon_type == 'std_bundle')
             <table class="table table-striped">
              <thead>
              <tr>
                <th>UPC</th>
                <th>Unit Retail Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Discount Type</th>
                <th>Discount</th>
                <th>Discounted Price</th>
              </tr>  
              </thead>
              <tbody>
                @foreach($coupon->products as $key => $product)
                  <tr>
                    <td>{{$product->upc}}</td>
                    <td>${{$product->unit_retail }}</td>
                    <td>{{$product->pivot->quantity }}</td>
                    <td>${{$product->pivot->total_price }}</td>
                    <td>{{ucfirst($product->pivot->discount_type)}}</td>
                    @if($product->pivot->discount_type == 'percentage')
                      <td>{{$product->pivot->discount_percentage }}%</td>
                    @else
                      <td>${{$product->pivot->discount_percentage }}</td>
                    @endif
                    <td>${{number_format($product->pivot->discount_price, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
             </table>
            @endif

            @if($coupon->coupon_type == 'mix_and_match')
             <table class="table table-striped">
              <thead>
              <tr>
              </tr>  
              </thead>
              <tbody>
                <tr><td><strong style="font-size: 16px;">Mix & Match Type: </strong>{{ ($coupon->mix_and_match_type == 'same_cost_products') ? 'Match ANY of the items (Products are same cost)' : (($coupon->mix_and_match_type == 'different_cost_products') ? 'Match ANY of item X with ANY of another (Products are different costs)' : 'Missing') }}</td></tr>
                @if(isset($conditions_data['conditions']))
                  @foreach($conditions_data['conditions'] as $key => $condition)
                    <tr>
                      <td>{{$condition}}</td>
                    </tr>
                  @endforeach
                @endif
                @if(isset($conditions_data['conditions']))
                  <tr>
                    <td>{{$conditions_data['selection_quantity']}}</td>
                  </tr>
                @endif
              </tbody>
             </table>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection