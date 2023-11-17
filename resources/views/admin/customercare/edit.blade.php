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
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">Update Customer Care</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('customercares.update', $customercare->id) }}" method="post">
						@csrf
						@method('PUT')
						<div class="form-group">
							 <label class="control-label col-sm-2" for="country">Choose Country</label>
							<div class="col-sm-10">
								<select name="country_id" id="country" class="form-control" required>
									<option value="">Choose</option>
									@foreach($countries as $country)
										<option value="{{$country->id}}" {{ ($country->id == $customercare->country_id)?'selected':'' }}>{{ $country->name }} ( {{$country->country_code}} )</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="customer_feedback_email">Customer Feedback Email</label>
						  <div class="col-sm-10">
						    <input type="text" name="customer_feedback_email" class="form-control" placeholder="Enter Customer Feedback Email" value="{{ $customercare->customer_feedback_email }}">
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="massy_card_support_email">Massycard Support Email</label>
						  <div class="col-sm-10">
						    <input type="text" name="massy_card_support_email" class="form-control" placeholder="Enter Massycard Support Email" value="{{ $customercare->massy_card_support_email }}">
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="massy_app_tech_support_email">Massyapp Tech Support Email</label>
						  <div class="col-sm-10">
						    <input type="text" name="massy_app_tech_support_email" class="form-control" placeholder="Enter Massyapp Tech Support Email" value="{{ $customercare->massy_app_tech_support_email }}">
						  </div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="phone">Phone</label>
							<div class="col-sm-10">
								<input type="text" name="phone" class="form-control" placeholder="Enter Customer Care Phone" value="{{ $customercare->phone }}">
							</div>
						</div>
						<div class="form-group"> 
						  <div class="col-sm-offset-2 col-sm-10">
						    <button type="submit" class="btn btn-primary">Update</button>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection