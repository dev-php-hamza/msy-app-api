@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
	<div class="row justify-content-center">
		@if (session('message'))
		  <div id="flash-message" class="">
		        {{ session('message') }}
		  </div>
		@endif
		<div class="col-md-12">
			<section class="card">
				<div class="card-header" style="border-bottom: 1px solid #f7f7f7">
				    <h4 class="mb-0">API Parameters ({{(isset($countryMlsSetting) && $countryMlsSetting != '')?$countryMlsSetting->type:''}})</h4>
				 </div>
				<div class="card-body">
					<div class="form-group">
						<label>BaseURL</label>
						<input type="text" class="form-control" id="baseUrl" value="{{ (isset($countryMlsSetting) && $countryMlsSetting != '')?$countryMlsSetting->base_url:'' }}" readonly>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<label>Endpoint</label>
								<input type="text" class="form-control" id="endPoint" value="cardLookup" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Card Number</label>
								<input type="text" class="form-control" id="card" placeholder="Please Enter Massy Loyalty Number">
							</div>
						</div>
						<div class="col-md-1 pl-0 pt-4 text-right">
							<button class="btn btn-primary" id="{{(isset($countryMlsSetting) && $countryMlsSetting != '')?$countryMlsSetting->id:0}}" onclick="testInstance(this)">Query</button>
						</div>
					</div>
				</div>
			</section>

			<!-- details -->
			<section class="card mt-5">
				<div class="card-header" style="border-bottom: 1px solid #f7f7f7">
				    <h4 class="mb-0">API Response</h4>
				 </div>
				<div class="card-body">
					<p>
						<div id="response"></div>
					</p>
				</div>
			</section>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script>
function testInstance(elem) {
	$('#response').empty();
	let instanceId = elem.id;
	let card = $('#card').val();

    if (instanceId != '' && card != '') {
    	let base_url = window.location.origin;    
    	$.ajaxSetup({
    		headers: {
    			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		}
    	});
    	$.ajax({
    		type: 'GET',
    		url: base_url+'/admin/test-mslb/'+instanceId+'/'+card,
    		dataType: 'json',
    		success: function(res){
    			if (res['status']) {
    				let HTML = '{'+'<br>';
	                $.each(res['mls']['response'], function(index,cardData){
	                    HTML += index+': "'+res['mls']['response'][index]+'"'+'<br>';
	                });
	                HTML += '}';
    				$('#response').append(HTML);
    			}else{
    				alert("something happend..!! Try again");
    			}
    		}
    	});
    }
}
</script>
@endsection