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
				All Delivery Companies
				<a href="{{ route('delivery-companies.create') }}" class="btn btn-primary" style="float: right;">Add New</a>
			</div>
				<div class="card-body">
					<table class="customerCare_table">
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Assigned To Stores</th>
							<th>Image</th>
							<th>Country</th>
							<th>Assign Store</th>
							<th class="action-last">Action</th>
						</tr>
						<tbody>
							@forelse($deliveryCompanies as $key => $deliveryCompany)
								<tr>
									<td>{{ $deliveryCompany->name }}</td>
									<td>{{ $deliveryCompany->email }}</td>
									<td>
									@foreach($deliveryCompany['stores'] as $key => $store)
										{{ $store->name }}<br>
									@endforeach
									</td>
									<td>
										@if(isset($deliveryCompany->icon))
											<img src="{{$deliveryCompany->icon}}" alt="dCompany_icon" width="70" height="70" class="ml-3 mb-3 mt-2">
										@endif
									</td>
									<td>{{ $deliveryCompany->country->name }}</td>
									<td><a href="{{route('delivery-companies_assign_store',$deliveryCompany->id)}}" class="btn btn-info"><i class="fas fa-store"></i></a></td>
									<td>
										<form action="{{route('delivery-companies.destroy',$deliveryCompany->id)}}" method="post">
											@csrf
											@method('DELETE')
											<a href="{{route('delivery-companies.show',$deliveryCompany->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
											<a href="{{route('delivery-companies.edit',$deliveryCompany->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
											{{-- <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button> --}}
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
					{{ $deliveryCompanies->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection