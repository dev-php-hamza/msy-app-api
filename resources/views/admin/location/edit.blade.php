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
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">Update Area</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('locations.update', $location->id)}}" method="POST">
						@csrf
						@method('PUT')
						<div class="form-group">
						  <label class="control-label col-sm-2" for="name">Name</label>
						  <div class="col-sm-10">
						    <input type="text" class="form-control" id="name" name="name" placeholder="Enter location name" required autofocus value="{{ $location->name }}">
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="name">Country</label>
						  <div class="col-sm-10">
						    <select name="country_id" class="form-control" required>
						    	<option value="">Please Choose Country</option>
						    	@foreach($countries as $key => $country)
						    		@if($country->name == $location->country->name)
						    			<option value="{{ $country->id }}" selected>{{ $country->name }}</option>
						    		@else
						    			<option value="{{ $country->id }}">{{ $country->name }}</option>
						    		@endif
						    	@endforeach
						    </select>
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