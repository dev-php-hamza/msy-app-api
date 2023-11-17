@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	@if(count($errors) > 0)
	  <ul>
	    @foreach($errors->all() as $error)
	      <li class="alert alert-danger">{{$error}}</li>
	    @endforeach
	  </ul>
	@endif
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">Create new coupon</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('coupons.store') }}" method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="create" value="1">
						<div class="col-sm-10 d-flex justify-content-between">
							<div id="toggles" style="margin-left: 20px;">
								<label class="pb-2">Status</label>
								<input type="checkbox" name="checkbox[]" id="on" class="ios-toggle" value="1" checked/>
								<label for="on" class="checkbox-label" data-off="Off" data-on="On"></label>
							</div>

							<div id="toggles" style="margin-left: 20px;">
								<label class="pb-2">Featured</label>
								<input type="checkbox" name="featured_checkbox[]" id="on-featured" class="ios-toggle" onchange="setValue(this)" />
								<label for="on-featured" class="checkbox-label" data-off="Off" data-on="On"></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2" for="coupon_name">Title</label>
							<div class="col-sm-10">
							    <input type="text" class="form-control" id="coupon_name" name="title" value="{{ old('title') }}" placeholder="Enter coupon name" required autofocus>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="country">Choose country</label>
							<div class="col-sm-10">
							    <select name="country_id" class="form-control" required="required">
							    	<option value="">Please choose country</option>
							    	@foreach($countries as $key => $country)
						    		  	<option value="{{ $country->id }}">{{ $country->name }}</option>
							    	@endforeach
							    </select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="country">Coupon type</label>
							<div class="col-sm-10">
							    <select name="coupon_type" class="form-control" id="coupon-select" required="required" onchange="couponFunction()">
							    	<option value="">Please choose coupon</option>
							    	<option value="barcode">Barcode</option>
							    	<option value="std_bundle">Standard Bundle</option>
							    	<option value="mix_and_match">Mix & Match</option>
							    </select>
							</div>
						</div>
						<!-- <div class="row pl-3">
							<div class="col-sm-5">
								<label class="control-label" for="start_date_ux">Start Date</label>
								<div class="">
								    <input type="text" class="form-control" id="start_date_ux" name="start_date_ux" placeholder="dd/mm/yyyy" value="{{ old('start_date_ux') }}" required autocomplete="off">
								    <input type="hidden" class="form-control" id="start_date" name="start_date">
								</div>
							</div>
							<div class="col-sm-5">
							  <label class="control-label" for="snday">Start Time:</label>
							  <div class="">
					             <div class="row">
					                 <div class="d-flex align-items-center">
					                    <label class="control-label mr-2"></label>
					                     <div class="form-group">
					                        <input type="time" class="form-control" name="start_time" required>
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
								    <input type="text" class="form-control" id="end_date_ux" name="end_date_ux" placeholder="dd/mm/yyyy" value="{{ old('end_date_ux') }}" required autocomplete="off">
								    <input type="hidden" class="form-control" id="end_date" name="end_date">
								</div>
							</div>
							<div class="col-sm-5">
							  <label class="control-label" for="snday">End Time</label>
							  <div class="">
					             <div class="row">
					                 <div class="d-flex align-items-center">
					                    <label class="control-label mr-2"></label>
					                     <div class="form-group">
					                        <input type="time" class="form-control" name="end_time" required>
					                    </div>
					                 </div>
					             </div>
							  </div>
							</div>
						</div> -->
						<div class="form-group" id="barcode-div">
							<label class="control-label col-sm-12" for="barcode">Barcode <strong>(Only 11 or 13 digits allowed)</strong></label>
							<div class="col-sm-10">
							    <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode') }}" placeholder="Enter UPC barcode number">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="short-description">Short Description</label>
							<div class="col-sm-10">
							    <textarea rows="2" class="form-control" cols="97" name="short_description" id="short-description" required placeholder="Enter Short Description here...">{{ old('short_description') }}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="description">Description</label>
							<div class="col-sm-10">
							    <textarea rows="5" class="form-control" cols="97" name="description" id="description" required placeholder="Enter Description here...">{{ old('description') }}</textarea>
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="control-label col-sm-12" for="file">Coupon Barcode Image: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 242px width and 184px height)</strong></label>
						  	<div class="col-sm-10">
						    	<input type="file" name="barcode_image" id="barcode_image">
						  	</div>
						</div> -->
						<div class="form-group">
							<label class="control-label col-sm-12" for="file">Coupon Image: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 218px width and 218px height)</strong></label>
						  	<div class="col-sm-10">
						    	<input type="file" name="file" id="file">
						  	</div>
						</div>
						<div class="form-group"> 
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-primary">Next</button>
						    </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		// $(document).ready(function(){
		// 	$("#start_date_ux").datepicker({ 
		// 		dateFormat: "dd/mm/yy",
		// 		altField: "#start_date",
		// 		altFormat: "yy-mm-dd" 
		// 	});
		// 	$("#end_date_ux").datepicker({ 
		// 		dateFormat: "dd/mm/yy",
		// 		altField: "#end_date",
		// 		altFormat: "yy-mm-dd" 
		// 	});
		// });
	</script>
	<script type="text/javascript">
		$('#barcode-div').hide();
		$("#barcode").removeAttr("required");
	</script>
	<script type="text/javascript">
		function couponFunction(argument) {
			var coupon = $('#coupon-select').val();
			if (coupon == 'barcode') {
				$('#barcode-div').show();
				$("#barcode").attr("required", "true");
			}
			else{
				$('#barcode-div').hide();
				$("#barcode").removeAttr("required");	
			}
		}
		function setValue(elem) {
			if (elem.value == 'on') {
				$('#on-featured').val(1);
			}
			if (elem.value == 'off') {
				$('#on-featured').val(0);
			}
		}
	</script>
@endsection

