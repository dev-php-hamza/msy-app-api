@extends('layouts.app')

@section('content')
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
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Create new Country</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('countries.store')}}" method="post">
						@csrf
						<div class="form-group">
						  <label class="control-label col-sm-2" for="name">Name:</label>
						  <div class="col-sm-10">
						    <input type="text" class="form-control" id="name" name="name" placeholder="Enter country name" required autofocus>
						  </div>
						</div>
						<div class="form-group"> 
						  <div class="col-sm-offset-2 col-sm-10">
						    <button type="submit" class="btn btn-default">Add</button>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection