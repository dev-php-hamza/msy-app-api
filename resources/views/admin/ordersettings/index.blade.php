@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container container-body">
	<div class="row justify-content-center">
		@if(count($errors) > 0)
		  <ul>
		    @foreach($errors->all() as $error)
		      <li class="alert alert-danger">{{$error}}</li>
		    @endforeach
		  </ul>
		@endif

		@if (session('message'))
		  <div id="flash-message" class="">
		        {{ session('message') }}
		  </div>
		@endif
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
				Order Settings
			</div>
			<div class="card-body">
				<div class="card-header mt-4 bg-black">
				  <div class="row">
				  	<div class="col-md-1">#</div>
				  	<div class="col-md-7">Territory Name</div>
				  	<div class="col-md-2">Master Switch</div>
				  </div>
				</div>
				<div class="card-body pt-0">
					<div class="accordion" id="accordion_section">
					@forelse($countries as $country)

					<div class="row align-items-center bordered">
					  	<div class="col-md-1">{{ $loop->index + 1 }}</div>
					  	<div class="col-md-7">

						    <div class="card">
							    <div class="card-header pl-0" id="headingOne">
							      <h2 class="mb-0">
							        <button class="btn btn-bg pl-0" type="button" data-toggle="collapse" data-target="#{{$country->country_code}}" aria-expanded="true" aria-controls="collapseOne">
							          {{ $country->name }}
							        </button>
							      </h2>
							    </div>

							    <div id="{{$country->country_code}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion_section">
								    <div class="card-body">
							          	<form class="mt-4" action="{{ route('orderSettings.store') }}" method="post">
							          	@csrf
						          			<input type="hidden" name="country_id" value="{{$country->id}}">
						          			<div class="form-group">
						          				<label for="massy_card_required">Massy Card ( Required )</label>
						          				<div class="">
						          				    <select name="massy_card_required" id="massy_card_required" class="form-control" required="required">
						          				    	<option value="0" {{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->massy_card_required == 0 ?'selected':'':''}}>No</option>
						          				    	<option value="1" {{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->massy_card_required == 1 ?'selected':'':''}}>Yes</option>
						          				    </select>
						          				</div>
						          			</div>
						          			<div class="form-group">
								          	    <label for="primary_email">Primary Email (Only one)</label>
								          	    <input type="Text" class="form-control" id="primary_email" name="primary_email" value="{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->primary_email:''}}">
								          	</div>
							          		<div class="form-group">
								          	    <label for="cc_email_addresses">Add multiple CC Email Addresses (Separate with comma)</label>
								          	    <input type="Text" class="form-control" id="cc_email_addresses" name="cc_email_addresses" placeholder="Please Add Comma Separated Emails " value="{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->cc_email_addresses:''}}">
								          	</div>
								          	<div class="form-group">
								          	    <label for="minimum_order_price">Minimum Order Price</label>
								          	    <input type="number" class="form-control" id="minimum_order_price" name="minimum_order_price" value="{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->minimum_order_price:''}}">
								          	</div>
								          	<div class="form-group">
								          	    <label for="quantity_text">Quantity Text</label>
								          	    <textarea rows="4" cols="50" class="form-control" id="quantity_text" name="quantity_text">{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->quantity_text:''}}</textarea>
								          	</div>
							          	  <div class="form-group">
							          	    <label for="pickup_customer_notice_text">Pickup Customer Notice Text</label>
							          	    <textarea rows="4" cols="50" class="form-control" id="pickup_customer_notice_text" name="pickup_customer_notice_text">{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->pickup_customer_notice_text:''}}</textarea>
							          	  </div>
							          	  <div class="form-group">
							          	    <label for="delivery_customer_notice_text">Delivery Customer Notice Text</label>
							          	    <textarea rows="4" cols="50" class="form-control" id="delivery_customer_notice_text" name="delivery_customer_notice_text">{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->delivery_customer_notice_text:''}}</textarea>
							          	  </div>
							          	  <div class="form-group">
							          	    <label for="order_services_text">Order Services Text</label>
							          	    <textarea rows="4" cols="50" class="form-control" id="order_services_text" name="order_services_text">{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->order_services_text:''}}</textarea>
							          	  </div>
							          	  <div class="form-group">
							          	    <label for="welcome_text">Welcome Message</label>
							          	    <textarea rows="4" cols="50" class="form-control" id="welcome_text" name="welcome_text">{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->welcome_text:''}}</textarea>
							          	  </div>
							          	  <div class="form-group">
							          	    <label for="completion_text">Completion Text</label>
							          	    <textarea rows="4" cols="50" class="form-control" id="completion_text" name="completion_text">{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->completion_text:''}}</textarea>
							          	  </div>
							          	  <div class="form-group">
							          	    <label for="customer_email_text">Customer Email Text</label>
							          	    <textarea rows="10" cols="80" class="form-control" id="customer_email_text_{{$country->country_code}}" name="customer_email_text">{{(isset($country->orderSettings) && count($country->orderSettings) > 0)?$country->orderSettings->customer_email_text:''}}</textarea>
							          	  </div>
							          	  <button type="submit" class="btn btn-primary">Save</button>
							          	</form>
								    </div>
							    </div>
						    </div>
						</div>
					  	<div class="col-md-2">
					  		<div id="toggles">
				  				<input type="checkbox" name="{{$country->country_code}}_switch" onchange="handleClick(this)" id="{{$country->country_code}}_switch" class="ios-toggle" 
				  				@if(isset($country->orderSettings->primary_email))
				  					{{($country->order_service_status)?'checked':''}}
				  				@else
				  					disabled
				  				@endif
				  				/>
				  				<label for="{{$country->country_code}}_switch" class="checkbox-label" data-off="Off" data-on="On"></label>
					  		</div>
					  	</div>
				  	</div>
				  	@empty
				  		No Record Found!
				  	@endforelse
				  	</div>
				</div>
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
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: base_url+'/admin/orderSettings/service/status',
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
<script>
	$(document).ready(function(){

		$('#accordion_section').on('shown.bs.collapse', function (e) {
			console.log(e);
			ClassicEditor
			    .create( document.querySelector( '#customer_email_text_'+e.target.id ) )
			    .catch( error => {
			        console.error( error );
			    } );
		})

		$('#accordion_section').on('hidden.bs.collapse', function (e) {
			$('#customer_email_text_'+e.target.id).remove();
		})
	});
</script>

@endsection

