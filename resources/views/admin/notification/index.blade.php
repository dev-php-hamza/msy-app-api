@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container container-body">
	<div class="row justify-content-center">
		@if (session('message'))
		  <div id="flash-message" class="">
		        {{ session('message') }}
		  </div>
		@endif

		@if (session('errorMessage'))
		  <div id="flash-message" class="">
		        {{ session('errorMessage') }}
		  </div>
		@endif
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
				All Notifications
				<a href="{{ route('notifications.create') }}" class="btn btn-primary" style="float: right;">Add New</a>
			</div>
				<div class="card-body">
					<table class="locatin_table">
						<tr>
							<th>Type</th>
							<th>Attachment Title</th>
							<th>Country Name</th>
							<th>Created on</th>
							<th class="action-last">Action</th>
						</tr>
						<tbody>
							@forelse($notifications as $key => $notification)
								<tr>
									<td>{{ ucwords($notification->object) }}</td>
									<td>{{ ucwords($notification->objectTitle()) }}</td>
									<td>{{ $notification->objectCountry() }}</td>
									<td>{{ $notification->created_at }}</td>
									<td>
										<form action="{{route('notifications.destroy',$notification->id)}}" method="post">
											@csrf
											@method('DELETE')
											
											<a href="{{route('notifications.show',$notification->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
											<button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
										</form>
									</td>
								</tr>
							@empty
								<tr><td>No Record found!</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="pull-right ml-3">
					{{ $notifications->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection