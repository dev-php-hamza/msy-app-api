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
				<div class="card-header">
					Choose Users {{(isset($objectCountry) && $objectCountry != '')?'from ('.$objectCountry.')':''}}
				</div>
				<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<label for="search_filter" class="mb-0">Please choose</label>
							</div>
							<div class="col-md-6">
								<div id="toggles" style="margin-top: 0px !important;">
									<input type="checkbox" name="filters" onchange="handleNotificationUser(this)" id="filter" class="ios-toggle" {{(isset($userData))?'unchecked':'checked'}}/>
									<label for="filter" class="checkbox-label" data-off="Custom" data-on="All Selected"></label>
								</div>
							</div>
						</div>
					<hr class="mb-4">
					<div id="filter-container" style="visibility: {{(isset($userData))?'visible':'hidden'}}">
						@if(isset($userData))
						<form class="form-horizontal" action="{{ route('notifications_users_country_term') }}" method="post">
							@csrf
							<div class="row">
								<div class="form-group">
									<div class="p-1">
										<input type="text" class="form-control" name="name" placeholder="Name Search...">
									</div>
								</div>
								<div class="form-group">
									<div class="p-1">
										<input type="text" name="email" class="form-control" placeholder="Email Search...">
									</div>
								</div>
								<div class="form-group">
									<div class="p-1">
										<input type="text" name="phone" class="form-control" placeholder="Phone Search...">
									</div>
								</div>
								<input type="hidden" name="id" id="notificationId" value="{{$notification->id}}">
								<div class="form-group">
									<div class="p-1">
										<button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</form>
						<hr class="mb-4">
						@endif
					</div>
					<form class="form-horizontal" action="{{ route('notifications.save')}}" method="post">
						@csrf
						<div class="form-group" id="countryUsers">
							@if(isset($userData))
								<div id="customSearchData">
									<table>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>Phone</th>
										</tr>
										<tbody>
											@forelse($userData['users'] as $key => $user)
												<tr>
													<td> 
														<input type="checkbox" id="userIds" name="user[]" value="{{$user->id}}"> {{ ucwords($user->fullName()) }}
													</td>
													<td>{{ $user->email }}</td>
													<td>{{ $user->userInfo->phone_number }}</td>
												</tr>
											@empty
												<tr><td>No Record found!</td></tr>
											@endforelse
										</tbody>
									</table>
								</div>
							@else
								<div class="col-md-12" style="justify-content: center;" id="usersCount">
									<strong> Total {{ $usersCount }} users will receive this notification</strong>
									<input type="hidden" id="userIds" name="user[]" value="all">
								</div>
							@endif
							<input type="hidden" name="id" value="{{$notification->id}}">
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
  <script src="{{ asset('js/notification.js') }}"></script>
  <script>
  	function handleNotificationUser(elem){
  		if ($('#filter').prop('checked')) {
  			$('#customSearchData').empty();
  			$('#userIds').val(['all']);
  			$('#filter-container').empty();
  			/*Add div for usersCount into countryUsers div*/
  			let userCoutDiv = '<div class="col-md-12" style="justify-content: center;" id="usersCount"><strong> Total {{ $usersCount }} users will receive this notification</strong><input type="hidden" id="userIds" name="user[]" value="all"></div>';
  			$('#countryUsers').append(userCoutDiv);

  		}else{
  			$('#usersCount').remove();
  			$('#filter-container').css('visibility', 'visible');
  			let customSearchData = `<form class="form-horizontal" action="{{ route('notifications_users_country_term') }}" method="post">@csrf<div class="row"><div class="form-group"><div class="p-1"><input type="text" class="form-control" name="name" placeholder="Name Search..."></div></div><div class="form-group"><div class="p-1"><input type="text" name="email" class="form-control" placeholder="Email Search..."></div></div><div class="form-group"><div class="p-1"><input type="text" name="phone" class="form-control" placeholder="Phone Search..."></div></div><input type="hidden" name="id" id="notificationId" value="{{$notification->id}}"><div class="form-group"> <div class="p-1"><button type="submit" class="btn btn-primary">Search</button></div></div></div></form><hr class="mb-4">`;
  			$('#filter-container').append(customSearchData);
  		}
  	}
  </script>
@endsection