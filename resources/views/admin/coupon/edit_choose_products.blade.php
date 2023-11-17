@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">

		@if($coupon->coupon_type == 'std_bundle')
		<div class="col-md-12">
			<div class="card">
				<!-- <div class="card-header">Please Choose products for coupon (Optional)</div> -->
				<div class="card-header">Bundles Information</div>
				<div class="card-body">
					<form id="coupon_product_form" action="{{route('save_coupon_products',$coupon->id)}}"  method="post">
						@csrf
					<div class="card-header bg-white mt-4 font-weight-bold">
						Please Choose products for bundle
					</div>
					<div class="card-body">
					<button type="button" class="add__btn" id="{{$coupon->country_id}}"><i class="fas fa-plus"></i></button>
					<div class="dup-wrapper">

						<input type="hidden" name="cupn_type" id="type-of-coupon" value="{{$coupon->coupon_type}}">
							@if(isset($coupon->products))
								<div class="row-duplicate">
									@forelse($coupon->products as $key => $product)
										<div class="row d-inputs">
											<div class="col-md-2">
												<label class="control-label" for="upc">UPC</label>
												<input type="number" placeholder="UPC" id="upc" name="products[{{ $product->id }}]" value="{{ $product->upc }}" onchange="getProductByUPC(this)">
											</div>
											<div class="col-md-2">
												<label class="control-label" for="title">Title</label>
												<input type="text" placeholder="Title" id="title" value="{{ $product->desc }}" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="current_price">Current price</label>
												<input type="number" placeholder="Current price" id="current_price" value="{{ $product->unit_retail }}" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="quantity">Quantity</label>
												<input type="number" value="{{$product->pivot->quantity}}" name="quantity[{{ $product->id }}]" placeholder="quantity" id="quantity" onchange="getDiscountedPrice(this)">
											</div>
											<div class="col-md-2">
												<label class="control-label" for="total_price">Total price</label>
												<input type="price" placeholder="total price" id="total_price" readonly value="{{$product->pivot->total_price}}" name="total_price[{{ $product->id }}]">
											</div>
											<div class="col-md-2">
												<label class="control-label" for="discount_type">Discount Type</label>
												<select name="discount_type[{{ $product->id }}]" id="discount_type" onchange="discountValue(this)">
													<option value="percentage" {{($product->pivot->discount_type == "percentage")?'selected':''}}>Percentage</option>
													<option value="price"
													{{($product->pivot->discount_type == "price")?'selected':''}}>Price</option>
												</select>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="discount_percentage">Discount</label>
												<input type="price" placeholder="%" id="discount_percentage" value="{{$product->pivot->discount_percentage}}" name="discount_percentage[{{ $product->id }}]" onchange="getDiscountedpercentage(this)">
											</div>
											<div class="col-md-2">
												<label class="control-label" for="discounted_price">Discounted price</label>
												<input type="price" placeholder="discounted price" id="discounted_price" value="{{$product->pivot->discount_price}}" name="discounted_price[{{ $product->id }}]" readonly>
											</div>
											<div class="col-md-2">
												<button class="remove__btn" id="{{ $coupon->id }}:{{ $product->id }}"><i class="fas fa-minus"></i></button>
											</div>
											<div class="row error">
												<div class="col-md-12">
													<div class="alert alert-danger append-error-div fade show" >
													    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
													</div>
												</div>
											</div>								
										</div>
										<hr>
									@empty
										<h5>No product record found for coupon</h5>
										<div class="row d-inputs">
											<div class="col-md-2">
												<label class="control-label" for="upc">UPC</label>
												<input type="number" placeholder="UPC" id="upc" name="" onchange="getProductByUPC(this)">
											</div>
											<div class="col-md-2">
												<label class="control-label" for="title">Title</label>
												<input type="text" placeholder="Title" id="title" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="current_price">Current price</label>
												<input type="number" placeholder="Current price" id="current_price" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="quantity">Quantity</label>
												<input type="number" value="" placeholder="quantity" id="quantity">
											</div>
											<div class="col-md-2">
												<label class="control-label" for="discounted_price">Discounted price</label>
												<input type="price" placeholder="discounted price" id="discounted_price" readonly>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="total_price">Total price</label>
												<input type="price" placeholder="total price" id="total_price" readonly >
											</div>
											<div class="col-md-2">
												<label class="control-label" for="discount_percentage">Discount %</label>
												<input type="price" placeholder="discount %" id="discount_percentage"  onchange="getDiscountedpercentage(this)">
											</div>
											<div class="col-md-2">
												<button type="button" class="remove__btn"><i class="fas fa-minus"></i></button>
											</div>	
											<div class="row error">
												<div class="col-md-12">
													<div class="alert alert-danger append-error-div fade show" >
													    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
													</div>
												</div>
											</div>								
										</div>
										<hr>
									@endforelse
								</div>
							@endif
							<div class="attach__btn">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						
					</div>
					</div>
					</form>
				</div>
			</div>
		</div>
		@elseif($coupon->coupon_type == 'mix_and_match')
			<!-- If mix and match -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Edit Details and Products</div>
					<div class="card-body">
						<form id="coupon_product_form" action="{{route('save_coupon_products',$coupon->id)}}"  method="post">
							@csrf
							<input type="hidden" name="cupn_type" id="type-of-coupon" value="{{$coupon->coupon_type}}">
							<!-- <div class="dup-wrapper mb-4"> -->
								<div class="row d-inputs">
									<div class="col-md-6">
										<label class="control-label" for="mix_and_match_type">Mix & Match Type</label>
										<div class="">
										  <select name="mix_and_match_type" id="mix_and_match_type" onchange="show_products_and_conditions_section()">
										      <!-- <option value="">Please Choose</option> -->
										      <option value="same_cost_products" {{($coupon->mix_and_match_type == "same_cost_products")?'selected':''}}>Match any of the item (Products are same cost)</option>
										      <option value="different_cost_products" {{($coupon->mix_and_match_type == "different_cost_products")?'selected':''}}>Match ANY of item X with ANY of another (Products are different costs)</option>
										  </select>
										</div>
									</div>							
								</div>
							<!-- </div> -->
							<hr>
							<div>
								<div class="dup-wrapper">
									<div class="row-duplicate">
										@foreach($coupon->products as $product)
												<div class="row d-inputs">
													<div class="col-md-3">
														<label class="control-label" for="upc">UPC</label>
														<input type="number" placeholder="UPC" id="upc" name="products[{{ $product->id }}]" value="{{ $product->upc }}" onchange="getProductByUPC(this)">
													</div>
													<div class="col-md-3">
														<label class="control-label" for="title">Title</label>
														<input type="text" placeholder="Title" id="title" value="{{ $product->desc }}" disabled>
													</div>
													<div class="col-md-2">
														<label class="control-label" for="current_price">Current price</label>
														<input type="number" placeholder="Current price" id="current_price" value="{{ $product->unit_retail }}" disabled>
													</div>
													@if($coupon->mix_and_match_type == 'different_cost_products')
														<div class="col-md-2 mix-and-match-product-type">
															<label class="control-label" for="mix_and_match_product_type">Type</label>
															<select name="mix_and_match_product_type[]" id="mix_and_match_product_type">
																<option value="buy" {{ ($product->pivot->type == 'buy')?'selected':'' }}>Buy</option>
																<option value="select" {{ ($product->pivot->type == 'select')?'selected':'' }}>Select</option>
															</select>
														</div>
													@else
														<div class="col-md-2 mix-and-match-product-type" style="display: none;">
															<label class="control-label" for="mix_and_match_product_type">Type</label>
															<select name="mix_and_match_product_type[]" id="mix_and_match_product_type" required>
																<option value="buy">Buy</option>
																<option value="select">Select</option>
															</select>
														</div>
													@endif
													<div class="col-md-2">
														<button class="remove__btn" id="{{ $coupon->id }}:{{ $product->id }}"><i class="fas fa-minus"></i></button>
													</div>
													<div class="row error">
														<div class="col-md-12">
															<div class="alert alert-danger append-error-div fade show" >
															    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
															</div>
														</div>
													</div>								
												</div>
										@endforeach
									</div>
								</div>
								<div class="row d-inputs">
									<div class="col-md-6">
										<strong>Add Products</strong>
									</div>							
								</div>
								<div class="row d-inputs">
									<div class="col-md-6">
										<button type="button" class="add__btn" id="{{$coupon->country_id}}"><i class="fas fa-plus"></i></button>
									</div>							
								</div>
								<hr>
								<div class="row d-inputs">
									<div class="col-md-6">
										<strong>Add Conditions/Criteria</strong>
									</div>							
								</div>
								<!-- <div class="row d-inputs">
									<div class="col-md-6">
										<button type="button" class="add_cond_btn" id="add_cond_btn"><i class="fas fa-plus"></i></button>
									</div>							
								</div> -->
								<div class="condition-row-duplicate">
									@if(isset($conditions_data['conditions']))
										@foreach($conditions_data['conditions'] as $key => $condition)
											<div class="row d-inputs">
												<div class="col-md-2">
													<label class="control-label" for="buy_q">Buy ?</label>
													<input type="number" name="buy_q[]" id="buy_q" value="{{isset($condition['buy']) ? $condition['buy'] : ''}}" required>
												</div>
												<div class="row">
													<div class="col-md-10" style="padding-left: 80px;">
														<label class="control-label" for="sel_q">Select ? to get for free</label>
														<input type="number" name="sel_q" id="sel_q" value="{{isset($conditions_data['selection_quantity'])?$conditions_data['selection_quantity']:''}}"required>
													</div>		
												</div>
												<!-- <div class="col-md-2">
													<label class="control-label" for="prod_q">of ? Product(s)</label>
													<input type="number" name="prod_q[]" id="prod_q" value="{{--$condition['products']--}}" required>
												</div> -->
												<!-- <div class="col-md-2">
													<button class="remove_cond_btn"><i class="fas fa-minus"></i></button>
												</div> -->						
											</div>	
										@endforeach
									@else
										<div class="row d-inputs">
											<div class="col-md-2">
												<label class="control-label" for="buy_q">Buy ?</label>
												<input type="number" name="buy_q[]" id="buy_q" value="" required>
											</div>
											<div class="row">
												<div class="col-md-10" style="padding-left: 80px;">
													<label class="control-label" for="sel_q">Select ? to get for free</label>
													<input type="number" name="sel_q" id="sel_q" value=""required>
												</div>		
											</div>					
										</div>
									@endif
								</div>
								<div class="row d-inputs">
									<div class="col-md-6">
										<div class="attach__btn">
											<button type="submit" class="btn btn-primary">Save</button>
										</div>
									</div>							
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		@else
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">Please Choose products for coupon (Optional)</div>
				<div class="card-body">
					<button type="button" class="add__btn" id="{{$coupon->country_id}}"><i class="fas fa-plus"></i></button>
					<div class="dup-wrapper">
						<form id="product_coupon_form" action="{{ route('save_coupon_products',$coupon->id) }}" method="post">
							<input type="hidden" name="cupn_type" id="type-of-coupon" value="{{$coupon->coupon_type}}">
							@if(isset($coupon->products))
								<div class="row-duplicate">
									@csrf
									@forelse($coupon->products as $key => $product)
										<div class="row d-inputs">
											<div class="col-md-3">
												<label class="control-label" for="upc">UPC</label>
												<input type="number" placeholder="UPC" id="upc" name="products[{{ $product->id }}]" value="{{ $product->upc }}" onchange="getProductByUPC(this)">
											</div>
											<div class="col-md-3">
												<label class="control-label" for="title">Title</label>
												<input type="text" placeholder="Title" id="title" value="{{ $product->desc }}" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="current_price">Current price</label>
												<input type="number" placeholder="Current price" id="current_price" value="{{ $product->unit_retail }}" disabled>
											</div>
											<div class="col-md-2">
												<button class="remove__btn" id="{{ $coupon->id }}:{{ $product->id }}"><i class="fas fa-minus"></i></button>
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
										<h5>No product record found for coupon</h5>
										<div class="row d-inputs">
											<div class="col-md-3">
												<label class="control-label" for="upc">UPC</label>
												<input type="number" placeholder="UPC" id="upc" name="" onchange="getProductByUPC(this)">
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
												<button type="button" class="remove__btn"><i class="fas fa-minus"></i></button>
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
							@endif
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
	<script src="{{ asset('js/coupon.js') }}"></script>
@endsection