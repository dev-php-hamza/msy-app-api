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
			<!-- <div>
				<a href="{{ route('show_import_stores_form') }}" class="btn btn-primary">Import Massy stores</a>
			</div> -->
			<div class="card">
				<div class="card-header">
					Stores
					<a href="{{ route('stores.create') }}" class="btn btn-primary" style="float: right;">Add new</a>
				</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('stores.index') }}" method="GET">
						<div class="row mb-3">
			                <div class="col-md-4 pr-0">
			                  <div class="form-group mb-0">
			                    <label class="control-label" for="prodName">Name</label>
			                    <div class="">
			                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter Store Name" value="{{ (request()->get('name') !== null)?request()->get('name'): '' }}">
			                    </div>
			                  </div>
			                </div>
			                <div class="col-md-4 pr-0">
				                <div class="form-group mb-0">
				                    <label class="control-label" for="name">Country</label>
				                    <div class="">
				                      <select name="country_id" id="country_id" class="form-control">
				                        <option value="">Please Choose Country</option>
				                        @foreach(\App\Country::all() as $key => $country)
				                          <option value="{{ $country->id }}" {{ ($country->id == request()->get('country_id'))?'selected':''}}>{{ $country->name }}</option>
				                        @endforeach
				                      </select>
				                    </div>
				                </div>
			                </div>
			                <div class="col-md-1 d-flex align-items-end pl-4">
			                  <div class="form-group mb-0">        
			                    <div class="col-sm-offset-2 ">
			                      <button type="submit" class="btn btn-primary" id="btnFormSubmit">Search</button>
			                    </div>
			                  </div>
			                </div>
			            </div>
		            </form>
					<div class="stores-table">
						<table>
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Store Code</th>
									<!-- <th>Address Line 1</th> -->
									<th>Orders</th>
									<th>Country</th>
									<th>Locality</th>
									<!-- <th style="width: 130px;">Phone</th> -->
									<!-- <th>Primary Category</th> -->
									<th class="action-last">Action</th>
								</tr>
							</thead>
							<tbody>
								@forelse($stores as $store)
									<tr>
										<td>{{ $loop->index + $stores->firstItem() }}</td>
										<td>{{ $store->name }}</td>
										<td>{{ ($store->storecode == 0)?'?':$store->storecode }}</td>
										<td>{{ (isset($store->orders))?count($store->orders):0 }}</td>
										<!-- <td>{{ $store->address_line_one }}</td> -->
										<td>{{ $store->country->name }}</td>
										<td>{{ $store->location->name }}</td>
										<!-- <td>{{ $store->storeInfo->phone_number }}</td> -->
										<!-- <td>{{ $store->storeInfo->primary_category }}</td> -->
										<td>
											<form action="{{ route('stores.destroy',$store->id) }}" method="POST">
												<a href="{{route('stores.show',$store->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
												<a href="{{route('stores.edit',$store->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a> |
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
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
					{{ $stores->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div> 
	</div>
</div>
@endsection