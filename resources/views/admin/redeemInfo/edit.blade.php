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

		@if (session('error'))
		  <div id="flash-message" class="">
		  	<ul>
		  		<li class="alert alert-danger">{{ session('error') }}</li>
		  	</ul>
		  </div>
		@endif

		<div class="col-md-10">
			<div class="card">
				<div class="card-header">Update Redeem Info. for Country</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('redeemInfos.update', $redeemInfo->id) }}" method="post" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="form-group">
							 <label class="control-label col-sm-2" for="country">Choose Country</label>
							<div class="col-sm-10">
								<select name="country_id" id="country" class="form-control" required>
									<option value="">Choose</option>
									@foreach($countries as $country)
										<option value="{{$country->id}}" {{ ($country->id == $redeemInfo->country_id)?'selected':'' }}>{{ $country->name }} ( {{$country->country_code}} )</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="title">Title</label>
						  <div class="col-sm-10">
						    <input type="text" name="title" class="form-control" placeholder="Enter Redeem Info. Title" value="{{ $redeemInfo->title }}">
						  </div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="description">Description</label>
							<div class="col-sm-10">
								<textarea rows="5" cols="97" name="description" required>{{ $redeemInfo->description }}</textarea>
							</div>
						</div>
						    <div class="form-group" style="padding-left: 14px;">
						      <label class="control-label" for="store_image"><b>RedeemInfo Image</b></label>
						       <div class="col-sm-10">
						           @if($redeemInfo->image != null)
						             <img src="{{$redeemInfo->getImage()}}" alt="redeemInfoImage" width="100" height="100">
						           @else
						             <h3>No file found!</h3>
						           @endif
						       </div>
						    </div>
						    <div class="form-group">
						      <div class="col-sm-10">
						        <input type="file" name="file">
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