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
				All Customer Care Information
				<a href="{{ route('customercares.create') }}" class="btn btn-primary" style="float: right;">Add New</a>
			</div>
				<div class="card-body">
					<table class="customerCare_table">
						<tr>
							<th>Customer Feedback Email</th>
							<th>Massycard Support Email</th>
							<th>Massyapp Tech Support Email</th>
							<th>Phone</th>
							<th>Country Name</th>
							<th class="action-last">Action</th>
						</tr>
						<tbody>
							@forelse($customercares as $key => $customercare)
								<tr>
									<td>{{ $customercare->customer_feedback_email }}</td>
									<td>{{ $customercare->massy_card_support_email }}</td>
									<td>{{ $customercare->massy_app_tech_support_email }}</td>
									<td>{{ $customercare->phone }}</td>
									<td>{{ $customercare->country->name }}</td>
									<td>
										<form action="{{route('customercares.destroy',$customercare->id)}}" method="post">
											@csrf
											@method('DELETE')
											
											<a href="{{route('customercares.show',$customercare->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
											<a href="{{route('customercares.edit',$customercare->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a> |
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
					{{ $customercares->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection