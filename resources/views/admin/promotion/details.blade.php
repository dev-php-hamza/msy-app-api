@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Promotion Details</div>
        <div class="card-body">
          <form class="form-horizontal" action="{{ route('promotions.store') }}" method="post">
          	@csrf
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="promo_name">Name</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="promo_name" value="{{$promotion->title}}" readonly>
          		</div>
          	</div>
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="country">Country</label>
          		<div class="col-sm-10">
          			<input type="text" class="form-control" id="country" value="{{$promotion->country->name}}" readonly>
          		</div>
          	</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="country">Type</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="country" value="{{ucfirst($promotion->type)}}" readonly>
              </div>
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="start_date">Start Date</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="start_date" value="{{date_format(date_create($promotion->start_date), 'd M Y h:i A')}}" readonly>
          		</div>
          	</div>
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="end_date">End Date</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="end_date" value="{{date_format(date_create($promotion->end_date), 'd M Y h:i A')}}" readonly>
          		</div>
          	</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="description">Description</label>
              <div class="col-sm-10">
                <textarea class="promo-description" readonly>{{ $promotion->description }}</textarea>
              </div>
            </div>
            <div class="form-group mb-4 hlight-section">
              <label class="control-label col-sm-4" for="promo_image"><b>Promotion Image</b></label>
              <div class="col-sm-10">
                @if($promotion->image != null)
                  <img src="{{$promotion->getImage()}}" alt="promotionImage" width="100" height="100">
                @else
                  <h3>No file found!</h3>
                @endif
              </div>
            </div>
            <hr>
          	<div class="form-group hlight-section">
              @if($promotion->type == 'product')
                <label class="control-label col-sm-4" for="products"><b>Attached Products</b></label>
              @endif

              @if($promotion->type == 'bundle')
                <label class="control-label col-sm-4" for="products"><b>Attached Bundles</b></label>
              @endif
          	</div>
          </form>

          @if($promotion->type == 'product')
            @include('components.product.product',['products'=> $promotion->products])
          @endif

          @if($promotion->type == 'bundle')
            @include('components.coupon.coupon',['coupons'=> $promotion->coupons])
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection