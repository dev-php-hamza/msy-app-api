@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		@if($promotion->type == 'product')
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Please Choose products for promotion (Optional)</div>
					<div class="card-body">
						<button type="button" class="add__btn" id="{{$promotion->country_id}}"><i class="fas fa-plus"></i></button>
						<div class="dup-wrapper">
							<form id="product_promotion_form" action="{{ route('save_promotion_products',$promotion->id) }}" method="post">
								<div class="row-duplicate">
									@csrf
									@forelse($promotion->products as $key => $product)
										<div class="row d-inputs">
											<div class="col-md-3">
												<label class="control-label" for="upc">UPC</label>
												<input type="number" placeholder="UPC" id="upc" value="{{ $product->upc }}" onchange="getProductByUPC(this)">
											</div>
											<div class="col-md-3">
												<label class="control-label" for="title">Title</label>
												<input type="text" placeholder="Title" id="title" value="{{ $product->desc }}" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="current_price">Current price</label>
												<input type="number" placeholder="Current price" id="current_price" value="{{ isset($product->regular_retail) ? $product->regular_retail : $product->unit_retail }}" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="sale_price">Sales price</label>
												<input type="number" placeholder="0.00" id="sale_price" name='products[{{ $product->id }}]' value="{{ $product->unit_retail }}" step="0.01" readonly>
											</div>
											<div class="col-md-2">
												<button class="remove__btn" id="{{ $promotion->id }}:{{ $product->id }}"><i class="fas fa-minus"></i></button>
											</div>
											<div class="row error">
												<div class="col-md-12">
													<div class="alert alert-danger append-error-div fade show" >
													    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
													</div>
												</div>
											</div>								
										</div>
									@empty
										<h5>No product record found for promotion</h5>
										<div class="row d-inputs">
											<div class="col-md-3">
												<label class="control-label" for="upc">UPC</label>
												<input type="number" placeholder="UPC" id="upc" onchange="getProductByUPC(this)">
											</div>
											<div class="col-md-3">
												<label class="control-label" for="title">Title</label>
												<input type="text" placeholder="Title" id="title" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="current_price">Current price</label>
												<input type="number" placeholder="Current price" id="current_price" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="sale_price">Sales price</label>
												<input type="number" placeholder="Sales price" step="0.01" id="sale_price" disabled>
											</div>
											<div class="col-md-2">
												<button type="button" class="remove__btn"><i class="fas fa-minus"></i></i></button>
											</div>	
											<div class="row error">
												<div class="col-md-12">
													<div class="alert alert-danger append-error-div fade show" >
													    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
													</div>
												</div>
											</div>								
										</div>
									@endforelse
								</div>
								<div class="attach__btn">
									<button type="submit" class="btn btn-primary">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		@else
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Please Choose Coupon Bundle</div>
					<div class="card-body">
						<div class="dup-wrapper">
							<form id="product_promotion_form" action="{{route('save_promotion_products',$promotion->id)}}"  method="post">
								@csrf
								<div class="form-group p-2">
									<button class="btn-primary" type='button' id='selectall'>Select All</button>
									<button class="btn-primary" type='button' id='deselectall'>De-Select All</button>
								</div>
								<div class="form-group">
					              @foreach($promotion->coupons as $coupon)
					              <div class="d-flex pl-2">
					              	<input type="checkbox" name="coupon[]" id="check-coupon" value="{{$coupon->id}}" checked style="margin-top: 4px; margin-right: 5px;">
					              	<p>{{$coupon->title}}</p>
					              </div>
					              @endforeach
					              @foreach(\App\Coupon::where('promotion_id', null)->where('country_id', $promotion->country_id)->where('active', 1)->where('coupon_type','!=', 'barcode')->get() as $coupon)
  					              <div class="d-flex pl-2">
  					              	<input type="checkbox" id="check-coupon" name="coupon[]" value="{{$coupon->id}}" style="margin-top: 4px; margin-right: 5px;">
  					              	<p>{{$coupon->title}}</p>
  					              </div>
  					              @endforeach
					          	</div>
					          	<div class="attach__btn">
					          		<button type="submit" class="btn btn-primary">Save</button>
					          	</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/promotion.js') }}"></script>
	<script type="text/javascript">
		$('#selectall').click(function() {
		    $('input[type="checkbox"]').prop("checked", true);
		});   

		$('#deselectall').click(function() {
		    $('input[type="checkbox"]').prop("checked", false);
		});
	</script>
@endsection