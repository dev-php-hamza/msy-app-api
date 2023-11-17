@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Country detail</div>
				<div class="card-body">
					<form class="form-horizontal">
						<div class="form-group">
						  <label class="control-label col-sm-2" for="name">Name:</label>
						  <div class="col-sm-10">
						    <input type="text" class="form-control" id="name" value="{{$country->name}}" readonly>
						  </div>
						</div>

						<div class="form-group">
						  <label class="control-label col-sm-6" for="pCount">No. of Associate products</label>
						  
						  <div class="col-sm-10">
						    <input type="text" class="form-control" id="pCount" value="{{count($country->products)}}" readonly>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection