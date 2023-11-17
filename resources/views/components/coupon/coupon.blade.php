		
<div class="row" style="padding-left: 14px;">
	@forelse($coupons as $key => $coupon)
		<div class="col-md-6 mb-4">
			<div class="row">
				<div class="col-md-4">
					<img class="image" src="{{ $coupon->image }}" alt="productImage">
				</div>
				<div class="col-md-8 pl-0">
					<div class="mb-2">
						<a href="{{route('coupons.show',$coupon->id)}}"><strong>{{$coupon->title}}</strong></a>
					</div>
					@if(isset($coupon->bundle_price))
					<div class="d-flex">
						<p class="m-0">Bundle Price:</p>
						<strong class="ml-2">${{ $product->bundle_price }}</strong>
					</div>
					@endif
				</div>
			</div>
		</div>
	@empty
		<h5>No Coupon Found</h5>
	@endforelse
</div>
