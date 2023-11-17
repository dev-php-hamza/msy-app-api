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
				<div class="card-header">Create App Account</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('apps.store')}}" method="post">
						@csrf
						<div class="form-group">
						  <label class="control-label col-sm-2" for="app_name">App Name</label>
						  <div class="col-sm-10">
						    <input type="text" class="form-control" id="app_name" name="app_name" placeholder="Enter App name" required>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-2" for="auth_token">Auth token</label>
						  <div class="col-sm-10">
						  	<input type="hidden" id="appData" name="appData">
						    <input type="text" class="form-control" id="auth_token" name="auth_token" required readonly>
						    <p onclick="getAuthToken()" class="linkToken">Generate Token</p>
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
@section('scripts')
<script>
	function getAuthToken(){
	let base_url = window.location.origin;
	let app_name = $('#app_name').val();
		if(app_name != ''){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'GET',
				url: base_url+'/admin/apps/auth-token/'+app_name,
				dataType: 'json',
				beforeSend: function(){
				},
				success: function(res){
					$('#appData').val(JSON.stringify(res));
					$('#auth_token').val(res['appData']['auth_token']);
				}
			});
		}else{
			alert('Please Enter App Name First');
		}
		
	}
</script>
@endsection