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
	@if (session('message'))
	  <div id="flash-message" class="">
	        {{ session('message') }}
	  </div>
	@endif
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">Create new promotion</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('promotions.store') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="form-group">
							<label class="control-label col-sm-2" for="promo_name">Title</label>
							<div class="col-sm-10">
							    <input type="text" class="form-control" id="promo_name" name="title" value="{{ (old('title') !== null )?old('title'):$promoName }}" autofocus>
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
							<label class="control-label col-sm-2" for="country">Choose type</label>
							<div class="col-sm-10">
							    <select name="promotion_type" class="form-control" required="required">
							    	<option value="">Please choose type</option>
							    	<option value="product">Product</option>
							    	<option value="bundle">Bundle</option>
							    </select>
							</div>
						</div>
						<div class="row pl-3">
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
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="description">Description</label>
							<div class="col-sm-10">
							    <textarea rows="5" cols="97" name="description" id="description" required placeholder="Enter Description here...">{{ old('description') }}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-12" for="file">Promotion Banner Image: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 375px width and 187px height)</strong></label>
						  	<div class="col-sm-10">
						    	<input type="file" name="file" id="file" required>
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
		$(document).ready(function(){
			$("#start_date_ux").datepicker({ 
				dateFormat: "dd/mm/yy",
				altField: "#start_date",
				altFormat: "yy-mm-dd" 
			});
			$("#end_date_ux").datepicker({ 
				dateFormat: "dd/mm/yy",
				altField: "#end_date",
				altFormat: "yy-mm-dd" 
			});
		});
	</script>
@endsection

