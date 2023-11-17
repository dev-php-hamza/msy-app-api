@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Redeem Info For Country details</div>
				<div class="card-body">
					<form class="form-horizontal">
						<div class="form-group">
						  <label class="control-label col-sm-12" for="country">Country Name</label>
						  <div class="col-sm-12">
						    <input type="text" class="form-control" id="country" value="{{$countryName}}" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="title">Title</label>
						  <div class="col-sm-12">
						   	<input type="text" class="form-control" id="title" value="{{$redeemInfo->title}}" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12" for="description">Description</label>
						  <div class="col-sm-12">
						  	<textarea readonly style="width: 100%;padding: 10px 5px;resize: none;">{{$redeemInfo->description}}</textarea>
						  </div>
						</div>
						<div class="form-group" style="padding-left: 14px;">
						    <label class="control-label" for="store_image"><b>Redeem Info. Image</b></label>
						    <div class="col-sm-10">
						        @if($redeemInfo->image != null)
						          <img src="{{$redeemInfo->getImage()}}" alt="redeemInfoImage" width="100" height="100">
						        @else
						          <h3>No file found!</h3>
						        @endif
						    </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection