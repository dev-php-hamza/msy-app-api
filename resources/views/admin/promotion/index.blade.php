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
				All Promotions
				<a href="{{ route('promotions.create') }}" class="btn btn-primary" style="float: right;">Add new</a>
			</div>
			<div class="form-inline mt-3 ml-4">
				<label for="filter" style="margin-right: 10px;">Filter By </label>
				<select class="form-control" id="filter" onchange="getFilterdPromotions(this)">
					<option value="">All</option>
					<option value="active" {{(isset($filter)  && $filter == 'active')?'selected':''}}>Active</a></option>
					<option value="expired" {{(isset($filter)  && $filter == 'expired')?'selected':''}}>Expired</a></option>
				</select>
			</div>
				<div class="card-body">
					<table class="promotions_table">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Country</th>
							<th>Type</th>
							<th>Start Date</th>
							<th>End Date</th>
							<!-- <th>Created On</th> -->
							<th class="action-last">Action</th>
						</tr>
						<tbody>
							@forelse($promotions as $promotion)
								<tr>
									<td>{{ $loop->index + $promotions->firstItem() }}</td>
									<td>{{ $promotion->title }}</td>
									<td>{{ $promotion->country->country_code }}</td>
									<td>{{ ucwords($promotion->type) }}</td>
									<td>{{ date_format(date_create($promotion->start_date),"d M Y h:i A")}}</td>
									<td>{{ date_format(date_create($promotion->end_date),"d M Y h:i A")}}</td>
									<!-- <td>{{ $promotion->created_at }}</td> -->
									<td>
										<form action="{{route('promotions.destroy',$promotion->id)}}" method="post">
											@csrf
											@method('DELETE')
											
											<a href="{{route('promotions.show',$promotion->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
											<a href="{{route('promotions.edit',$promotion->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a> |
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
					{{ $promotions->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	function getFilterdPromotions(elem) {
		let filter = elem.value;
    	let base_url = window.location.origin;
    	if (filter !='') {
    		location.href = base_url+"/admin/promotion/"+filter;
    	}else{
    		location.href = base_url+"/admin/promotions";
    	}
	}
</script>
@endsection