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
				<div class="card-header">Create new Redeem Info. for Country</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('redeemInfos.store')}}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="form-group">
							 <label class="control-label col-sm-2" for="country">Choose Country</label>
							<div class="col-sm-10">
								<select name="country_id" id="country" class="form-control" required>
									<option value="">Choose</option>
									@foreach($countries as $country)
										<option value="{{$country->id}}">{{ $country->name }} ( {{$country->country_code}} )</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="title">Title</label>
						  <div class="col-sm-10">
						    <input type="text" name="title" class="form-control" placeholder="Enter Redeem Title" value="{{ old('title') }}">
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="description">Description</label>
						  <div class="col-sm-10">
						  	<textarea id="simple-text" rows="5" cols="97" name="description" id="description" required placeholder="Enter Redeem Description"></textarea>
						  </div>
						</div>
						<div class="form-group">
						  <div class="col-sm-10">
						    <input type="file" name="file">
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