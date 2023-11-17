@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container container-content">
	@if (session('message'))
	  <div id="flash-message" class="">
	        {{ session('message') }}
	  </div>
	@endif
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					Countries
					<a href="{{ route('countries.create') }}" class="btn btn-primary disabled" style="float: right;">Add new</a>
				</div>
				<div class="card-body">
					<div class="countires-table">
						<table>
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>No. Location</th>
									<th>Master Switch</th>
									<th class="action-last">Action</th>
								</tr>
							</thead>
							<tbody>
								@forelse($countries as $country)
									<tr>
										<td>{{ $loop->index + $countries->firstItem() }}</td>
										<td>{{ $country->name }}</td>
										<td>{{ count($country->locations) }}</td>
										<td>
									  		<div id="toggles">
								  				<input type="checkbox" name="{{$country->country_code}}_switch" onchange="handleClick(this)" id="{{$country->country_code}}_switch" class="ios-toggle" 
								  				@if(isset($country->switch))
								  					{{($country->switch)?'checked':''}}
								  				@else
								  					disabled
								  				@endif
								  				/>
								  				<label for="{{$country->country_code}}_switch" class="checkbox-label" data-off="Off" data-on="On"></label>
									  		</div>
										</td>
										<td>
											<!-- <a href="{{route('countries.show',$country->id)}}" class="btn btn-info disabled">Details</a> |
											<a href="{{route('countries.edit',$country->id)}}" class="btn btn-primary disabled">Edit</a> | -->
											<form action="{{ route('countries.destroy',$country->id) }}" method="POST">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger" disabled="disabled"><i class="fa fa-trash-alt"></i></button>
											</form>
										</td>
									</tr>
								@empty
									<tr><td style="border-bottom: none;">No Record Found!</td></tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
				<div class="pull-right ml-3">
					{{ $countries->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	function handleClick(elem){
		let base_url = window.location.origin;
		console.log(base_url);
		let countryCode = elem.id;
		countryCode = countryCode.split('_');
		console.log(countryCode[0]);
		// return false;
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: base_url+'/admin/country/status',
			data:{
				'country_code':countryCode[0],
				 },
			dataType: 'json',
			beforeSend: function(){
			},
			success: function(res){
				
			}
		});
	}
</script>
@endsection