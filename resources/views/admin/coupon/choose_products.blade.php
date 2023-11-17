@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		@if(count($errors) > 0)
		  <ul>
		    @foreach($errors->all() as $error)
		      <li class="alert alert-danger">{{$error}}</li>
		    @endforeach
		  </ul>
		@endif
		@if (session('message'))
		  <div id="flash-message" class="">
		        {{ session('message') }}
		  </div>
		@endif

		@if($coupon->coupon_type == 'std_bundle')
			<!-- If standrard bundles -->
			<div class="col-md-12">
				<div class="card">
					<!-- <div class="card-header">Please Choose products for coupon (Optional)</div> -->
					<!-- <div class="card-header">Bundles Information</div> -->
					<div class="card-body">
						<form id="coupon_product_form" action="{{route('save_coupon_products',$coupon->id)}}"  method="post">
							@csrf
						<div class="d-flex justify-content-start">
						</div>
						<div class="card-header bg-white mt-4 font-weight-bold">
							Please Choose products for bundle
						</div>
						<div class="card-body">
						<button type="button" class="add__btn" id="{{$coupon->country_id}}"><i class="fas fa-plus"></i></button>
						<div class="dup-wrapper">
							
								<div class="row-duplicate">
									<input type="hidden" name="cupn_type" id="type-of-coupon" value="{{$coupon->coupon_type}}">
									<input type="hidden" name="create" value="1">
									<div class="row d-inputs">
										<div class="col-md-2">
											<label class="control-label" for="upc">UPC</label>
											<input type="number" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)">
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
											<input type="number" placeholder="quantity" id="quantity" required onchange="getDiscountedPrice(this)">
										</div>
										<div class="col-md-2">
											<label class="control-label" for="total_price">Total price</label>
											<input type="price" placeholder="total price" id="total_price" readonly>
										</div>
										<div class="col-md-2">
											<label class="control-label" for="discount_type">Discount Type</label>
											<select name="discount_type" id="discount_type" onchange="discountValue(this)">
												<option value="percentage">Percentage</option>
												<option value="price">Price</option>
											</select>
										</div>
										<div class="col-md-2">
											<label class="control-label" for="discount_percentage">Discount</label>
											<input type="price" placeholder="%" id="discount_percentage" required onchange="getDiscountedpercentage(this)">
										</div>
										<div class="col-md-2">
											<label class="control-label" for="discounted_price">Discounted price</label>
											<input type="price" placeholder="discounted price" id="discounted_price" readonly>
										</div>
										<div class="col-md-2">
											<button class="remove__btn"><i class="fas fa-minus"></i></button>
										</div>
										<div class="row error">
											<div class="col-md-12">
												<div class="alert alert-danger append-error-div fade show" >
												    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
												</div>
											</div>
										</div>								
									</div>
								</div>
								<hr>
								<div class="attach__btn">
									<!-- <div class="col-md-3 pl-0">
										<label class="control-label" for="sum">Bundle Price</label>
										<div>
											<input type="price" name="bundle_price" class="form-control" placeholder="" id="bundle_sum" required readonly>
										</div>
									</div> -->	
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
					<div class="card-header">Add Details and Products</div>
					<div class="card-body">
						<form id="coupon_product_form" action="{{route('save_coupon_products',$coupon->id)}}"  method="post">
							@csrf
							<input type="hidden" name="cupn_type" id="type-of-coupon" value="{{$coupon->coupon_type}}">
							<!-- <div class="dup-wrapper mb-4"> -->
								<input type="hidden" name="create" value="1">
								<div class="row d-inputs">
									<div class="col-md-6">
										<label class="control-label" for="mix_and_match_type">Select Mix & Match Type</label>
										<select name="mix_and_match_type" id="mix_and_match_type" onchange="show_products_and_conditions_section()">
											<option value="" selected disabled>Please Choose</option>
											<option value="same_cost_products">Match ANY of the items (Products are same cost)</option>
											<option value="different_cost_products">Match ANY of item X with ANY of another (Products are different costs)</option>
										</select>
									</div>							
								</div>
							<!-- </div> -->
							<hr>
							<div id="products-and-conditions-section" style="display: none;">
								<div class="dup-wrapper">
									<div class="row-duplicate">
										<div class="row d-inputs">
											<div class="col-md-3">
												<label class="control-label" for="upc">UPC</label>
												<input type="number" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)">
											</div>
											<div class="col-md-3">
												<label class="control-label" for="title">Title</label>
												<input type="text" placeholder="Title" id="title" disabled>
											</div>
											<div class="col-md-2">
												<label class="control-label" for="current_price">Current price</label>
												<input type="number" placeholder="Current price" id="current_price" disabled>
											</div>
											<div class="col-md-2 mix-and-match-product-type" style="display: none;">
												<label class="control-label" for="mix_and_match_product_type">Type</label>
												<select name="mix_and_match_product_type[]" id="mix_and_match_product_type">
													<option value="buy">Buy</option>
													<option value="select">Select</option>
												</select>
											</div>
											<div class="col-md-2">
												<button class="remove__btn" disabled><i class="fas fa-minus"></i></button>
											</div>
											<div class="row error">
												<div class="col-md-12">
													<div class="alert alert-danger append-error-div fade show" >
													    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
													</div>
												</div>
											</div>								
										</div>
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
									<div class="row d-inputs">
										<div class="col-md-2">
											<label class="control-label" for="buy_q">Buy ?</label>
											<input type="number" name="buy_q[]" id="buy_q" value="1" required>
										</div>
										<div class="row">
											<div class="col-md-10" style="padding-left: 80px;">
												<label class="control-label" for="sel_q">Select ? to get for free</label>
												<input type="number" name="sel_q" id="sel_q" value="1" required>
											</div>		
										</div>
										<!-- <div class="col-md-2">
											<label class="control-label" for="prod_q">of ? Product(s)</label>
											<input type="number" name="prod_q[]" id="prod_q" value="1" required>
										</div> -->
										<!-- <div class="col-md-2">
											<button class="remove_cond_btn" disabled><i class="fas fa-minus"></i></button>
										</div> -->						
									</div>
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
			<!-- If barcode -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Please Choose products for coupon (Optional)</div>
					<div class="card-body">
						<button type="button" class="add__btn" id="{{$coupon->country_id}}"><i class="fas fa-plus"></i></button>
						<div class="dup-wrapper">
							<form id="coupon_product_form" action="{{route('save_coupon_products',$coupon->id)}}"  method="post">
								<div class="row-duplicate">
									@csrf
									<input type="hidden" name="cupn_type" id="type-of-coupon" value="{{$coupon->coupon_type}}">
									<div class="row d-inputs">
										<div class="col-md-3">
											<label class="control-label" for="upc">UPC</label>
											<input type="number" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)">
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
											<button class="remove__btn"><i class="fas fa-minus"></i></button>
										</div>
										<div class="row error">
											<div class="col-md-12">
												<div class="alert alert-danger append-error-div fade show" >
												    <strong>Error!</strong> Entered Upc is not found. Try different UPC.
												</div>
											</div>
										</div>								
									</div>
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
	<script src="{{ asset('js/coupon.js') }}"></script>
@endsection

