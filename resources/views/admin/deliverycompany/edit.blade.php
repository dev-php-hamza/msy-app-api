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
				<div class="card-header">Edit Delivery Company</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('delivery-companies.update', $deliveryCompany->id) }}" method="post" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="form-group">
							 <label class="control-label col-sm-2" for="country">Choose Country</label>
							<div class="col-sm-10">
								<select name="country_id" id="country" class="form-control" required>
									<option value="">Choose</option>
									@foreach($countries as $country)
										<option value="{{$country->id}}" {{ ($country->id==$dCompanyCountryId)?'selected':'' }}>{{ $country->name }} ( {{$country->country_code}} )</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="dCompany_name">Name</label>
						  <div class="col-sm-10">
						    <input type="text" name="dCompany_name" id="dCompany_name" class="form-control" placeholder="Enter Delivery Company Name" value="{{ old('dCompany_name', $deliveryCompany->name) }}" required>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="dCompany_email">Email</label>
						  <div class="col-sm-10">
						    <input type="email" name="dCompany_email" id="dCompany_email" class="form-control" placeholder="Enter Delivery Company Email" value="{{ old('dCompany_email', $deliveryCompany->email) }}" required>
						  </div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-12" for="promo_image">Icon</label>
							<div class="col-sm-10">
							    @if($deliveryCompany->icon != null)
							      <img src="{{$deliveryCompany->icon}}" alt="dCompany_file" width="100" height="100">
							    @else
							      <h3>No file found!</h3>
							    @endif
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-12" for="file">Icon: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 375px width and 187px height)</strong></label>
						  	<div class="col-sm-10">
						    	<input type="file" name="file" id="file">
						  	</div>
						</div>
						<div class="form-group"> 
						  <div class="col-sm-offset-2 col-sm-10">
						    <button type="submit" class="btn btn-primary">Save</button>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection