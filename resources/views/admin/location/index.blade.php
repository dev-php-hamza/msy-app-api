@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container container-body">
	<div class="row justify-content-center">
		@if (session('message'))
		  <div id="flash-message" class="">
		        {{ session('message') }}
		  </div>
		@endif
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
				All Areas
				<a href="{{ route('locations.create') }}" class="btn btn-primary" style="float: right;">Add New</a>
			</div>
				<div class="card-body">
					<table class="locatin_table">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Country Name</th>
							<th class="action-last">Action</th>
						</tr>
						<tbody>
							@forelse($locations as $key => $location)
								<tr>
			               			<td>{{ $loop->index + $locations->firstItem() }}</td>
									<td>{{ $location->name }}</td>
									<td>{{ $location->country->name }}</td>
									<td>
										<!-- <form action="{{ route('locations.destroy',$location->id) }}" method="POST"> -->
											<a href="{{ route('locations.show', $location->id) }}" class="btn btn-info"><i class="fa fa-info"></i></a> |
											<a href="{{ route('locations.edit', $location->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a><!--  |
											@csrf
											@method('DELETE')
											<input type="submit" value="Delete" class="btn btn-danger" disabled="disabled"> -->
										<!-- </form> -->
									</td>
								</tr>
							@empty
								<tr><td>No Record found!</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="pull-right ml-3">
					{{ $locations->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection