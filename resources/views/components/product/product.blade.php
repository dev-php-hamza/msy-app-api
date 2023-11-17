		
<div class="row" style="padding-left: 14px;">
	@forelse($products as $key => $product)
		<div class="col-md-6 mb-4">
			<div class="row">
				<div class="col-md-4">
					@forelse($product->images as $key => $prodImage)
						<img class="image" src="{{ $prodImage->getImage($product->upc) }}" alt="productImage">
						@break
					@empty
						<img class="image" src="{{ asset('assets/images/no-image.png') }}">
					@endforelse
				</div>
				<div class="col-md-8 pl-0">
					<div class="mb-2">
						<a href="{{ route('products.show', $product->id ) }}"><strong>{{$product->desc}}</strong></a>
					</div>
					<div class="d-flex">
						<p class="m-0">Regular Price: </p>
						<strong class="ml-2">${{ isset($product->regular_retail) ? $product->regular_retail : $product->unit_retail }}</strong>
					</div>
					{{-- @if(isset($product->pivot->sale_price)) --}}
					<div class="d-flex">
						<p class="m-0">Sale Price:</p>
						<strong class="ml-2">${{ $product->unit_retail }}</strong>
					</div>
					{{-- @endif --}}
				</div>
			</div>
		</div>
	@empty
		<h5>No Products Found</h5>
	@endforelse
</div>
