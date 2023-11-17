@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">Please Choose products for offer</div>
				<div class="card-body">
					<div class="row">
						<form class="form-horizontal" action="{{route('save_offer_products',$offer->id)}}" method="post">
							@csrf
							<div class="row">
								@forelse($products as $key => $product)
									<div class="col-md-4">
										<li style="list-style: none;"><p><input type="checkbox" name="products[{{$product->id}}]" value="{{$product->id}}">{{$product->desc}}</p></li>
									</div>
								@empty
									<h5>No Record Found</h5>
								@endforelse
							</div>
							<div class='form-group'>
								<div class='col-sm-offset-2 col-sm-10'>
									<button type='submit' class='btn btn-primary'>Next</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

