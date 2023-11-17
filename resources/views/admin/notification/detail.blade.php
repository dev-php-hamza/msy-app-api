@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">Notification details</div>
				<div class="card-body">
					<form class="form-horizontal">
						<div class="form-group">
						  <label class="control-label col-sm-12" for="name">Type</label>
						  <div class="col-sm-12">
						    <input type="text" class="form-control" id="name" value="{{ucwords($notification->object)}}" readonly>
						  </div>
						</div>
						@if(!empty($notification->object) && $notification->object != 'text' && $notification->object != '')
							<div class="form-group">
							  <label class="control-label col-sm-12" for="name">Attached Module</label>
							  <div class="col-sm-12">
							  	@include('components.notification.attachment',['type' => $notification->object, 'attachment' => $object])
							  </div>
							</div>
						@endif
						<div class="form-group">
						  <label class="control-label col-sm-12" for="name">Notification Message</label>
						  <div class="col-sm-12">
						   <textarea readonly style="width: 100%;padding: 10px 5px;resize: none;">{{$notification->text}}</textarea>
						  </div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-12 font-weight-bold" for="name">Attached Users</label>
						  <div class="col-sm-12">
						  	<div class="row">
						  	@if($users)
						  		<div class="col-md-12" style="justify-content: center;" id="usersCount">
						  			<strong>Notification created for Total {{ $users }} users</strong>
						  		</div>
							  	{{-- @foreach($users as $user)
							  		<li class="col-md-3 mb-3">{{$user->first_name}} {{$user->last_name}}</li>
							  	@endforeach --}}
						  	@endif
						  </div>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection