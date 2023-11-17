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
				All Coupons
				<a href="{{ route('coupons.create') }}" class="btn btn-primary" style="float: right;">Add new</a>
			</div>
			<div class="form-inline mt-3 ml-4">
				<label for="filter" style="margin-right: 10px; ">Filter By </label>
				<select class="form-control" id="filter" onchange="getFilterdCoupons(this)">
					<option value="">All</option>
					<option value="active" {{(isset($filter)  && $filter == 'active')?'selected':''}}>Active</a></option>
					<option value="expired" {{(isset($filter) && $filter == 'expired')?'selected':''}}>Expired</a></option>
				</select>
			</div>
				<div class="card-body">
					<table class="coupons_table">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Type</th>
							<th>Country</th>
							<th>Status</th>
							<!-- <th>Start Date</th>
							<th>End Date</th> -->
							<th class="action-last">Action</th>
						</tr>
						<tbody>
							@forelse($coupons as $coupon)
								<tr>
									<td>{{ $loop->index + $coupons->firstItem() }}</td>
									<td>{{ $coupon->title }}</td>
									<td>{{ ($coupon->coupon_type == 'std_bundle') ? 'Standard Bundle' : (($coupon->coupon_type == 'mix_and_match') ? 'Mix & Match' : 'Barcode') }}</td>
									<td>{{ $coupon->country->country_code }}</td>
									<td>{{($coupon->active)?'Active':'Inactive'}}</td>
									<!-- <td>{{ date_format(date_create($coupon->start_time),"d M Y h:i A") }}</td>
									<td>{{ date_format(date_create($coupon->end_time),"d M Y h:i A") }}</td> -->
									<td>
										<form action="{{route('coupons.destroy',$coupon->id)}}" method="post">
											@csrf
											@method('DELETE')
											
											<a href="{{route('coupons.show',$coupon->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
												<a href="{{route('coupons.edit',$coupon->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a> |
											<button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
										</form>
									</td>
								</tr>
							@empty
								<tr><td>No Record Found!</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<div class="pull-right ml-3">
				  {{ $coupons->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	function getFilterdCoupons(elem) {
		let filter = elem.value;
    	let base_url = window.location.origin;
    	if (filter !='') {
    		location.href = base_url+"/admin/coupon/"+filter;
    	}else{
    		location.href = base_url+"/admin/coupons";
    	}
	}
</script>
@endsection