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
				Redeem Information Country Vice
				<a href="{{ route('redeemInfos.create') }}" class="btn btn-primary" style="float: right;">Add New</a>
			</div>
				<div class="card-body">
					<table class="locatin_table">
						<tr>
							<th>Title</th>
							<th>Description</th>
							<th>Country</th>
							<th class="action-last">Action</th>
						</tr>
						<tbody>
							@forelse($redeemInfos as $key => $redeemInfo)
								<tr>
									<td style="max-width: 100px">{{ $redeemInfo->title }}</td>
									<td style="max-width: 420px;min-width: 420px;">{{ $redeemInfo->description }}</td>
									<td>{{ $redeemInfo->country->name }}</td>
									<td>
										<form action="{{route('redeemInfos.destroy',$redeemInfo->id)}}" method="post">
											@csrf
											@method('DELETE')
											
											<a href="{{route('redeemInfos.show',$redeemInfo->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
											<a href="{{route('redeemInfos.edit',$redeemInfo->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a> |
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
					{{ $redeemInfos->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection