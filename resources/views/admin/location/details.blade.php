@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Area details</div>
				<div class="card-body">
					<form class="form-horizontal">
						<div class="form-group">
						  <label class="control-label col-sm-2" for="name">Name:</label>
						  <div class="col-sm-10">
						    <input type="text" class="form-control" id="name" value="{{$location->name}}" readonly>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="name">Country</label>
						  <div class="col-sm-10">
						    <input type="text" class="form-control" id="name" value="{{$location->country->name}}" readonly>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection