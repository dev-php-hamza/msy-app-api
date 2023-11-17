@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Customer Care details</div>
				<div class="card-body">
					<form class="form-horizontal">
						<div class="form-group">
						  <label class="control-label col-sm-12" for="country">Country Name</label>
						  <div class="col-sm-12">
						    <input type="text" class="form-control" id="country" value="{{$countryName}}" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="email">Customer Feedback Email</label>
						  <div class="col-sm-12">
						   	<input type="text" class="form-control" id="email" value="{{$customercare->customer_feedback_email}}" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="email">Massycard Support Email</label>
						  <div class="col-sm-12">
						   	<input type="text" class="form-control" id="email" value="{{$customercare->massy_card_support_email}}" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="email">Massyapp Tech Support Email</label>
						  <div class="col-sm-12">
						   	<input type="text" class="form-control" id="email" value="{{$customercare->massy_app_tech_support_email}}" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="phone">Phone</label>
						  <div class="col-sm-12">
						   	<input type="text" class="form-control" id="phone" value="{{$customercare->phone}}" readonly>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection