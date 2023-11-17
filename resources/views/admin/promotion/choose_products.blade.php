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
		@if($promotion->type == 'product')
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Please Choose products for promotion (Optional)</div>
					<div class="card-body">
						<button type="button" class="add__btn" id="{{$promotion->country_id}}"><i class="fas fa-plus"></i></button>
						<div class="dup-wrapper">
							<form id="product_promotion_form" action="{{route('save_promotion_products',$promotion->id)}}"  method="post">
								<div class="row-duplicate">
									@csrf
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
											<label class="control-label" for="sale_price">Sales price</label>
											<input type="number" placeholder="0.00" id="sale_price" step="0.01" disabled readonly>
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

