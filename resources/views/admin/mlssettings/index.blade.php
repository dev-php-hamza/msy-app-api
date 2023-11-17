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
				All MLS
			</div>
			<div class="card-body">
				<div class="card-header mt-4 bg-black">
				  <div class="row">
				  	<div class="col-md-1">#</div>
				  	<div class="col-md-7">Territory Name</div>
				  	<div class="col-md-2">Master Switch</div>
				  	<div class="col-md-2">Instances</div>
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
							    	@if($country->name == 'Pakistan')
							    		<h5>Only for development!</h5>
							    	@else
								    <div class="card-body">
								        <ul class="nav nav-tabs" id="myTab" role="tablist">
								          <li class="nav-item">
								            <a class="nav-link active" id="{{$country->country_code}}_tab_live" data-toggle="tab" href="#{{$country->country_code}}_live" role="tab" aria-controls="{{$country->country_code}}_live" aria-selected="true">Live</a>
								          </li>
								          <li class="nav-item">
								            <a class="nav-link" id="{{$country->country_code}}_tab_beta" data-toggle="tab" href="#{{$country->country_code}}_beta" role="tab" aria-controls="{{$country->country_code}}_beta" aria-selected="false">Beta</a>
								          </li>
								          <li class="nav-item">
								            <a class="nav-link" id="{{$country->country_code}}_tab_choose_instance" data-toggle="tab" href="#{{$country->country_code}}_choose_instance" role="tab" aria-controls="{{$country->country_code}}_choose_instance" aria-selected="false">Choose instance</a>
								          </li>
								        </ul>
								        @php
								        	$countryMlsSettings = $country->mlsSettings;
								        	$cmlsCount    = count($countryMlsSettings);
								        	$liveInstance = '';
								        	$betaInstance = '';
								        	if(count($countryMlsSettings) > 0){
								        		foreach($countryMlsSettings as $key => $countryInstance){
								        			if($countryInstance->type === 'live'){
								        				$liveInstance = $countryInstance;
								        			}

								        			if($countryInstance->type === 'beta'){
								        				$betaInstance = $countryInstance;
								        			}
								        		}
								        	}
								        @endphp
								        <div class="tab-content" id="myTabContent">
								          <div class="tab-pane fade show active" id="{{$country->country_code}}_live" role="tabpanel" aria-labelledby="{{$country->country_code}}_tab_live">
								          	<form class="mt-4" action="{{ route('mlsSettings.store') }}" method="post">
								          	@csrf
							          			<input type="hidden" name="country_id" value="{{$country->id}}">
									          	<div class="form-group">
									          	    <label for="base_url">Base URL</label>
									          	    <input type="text" class="form-control" id="base_url" aria-describedby="base_url" name="base_url" value="{{(isset($liveInstance) && $liveInstance != '' )?$liveInstance->base_url:''}}" required>
									          	</div>
								          	  <div class="form-group">
								          	    <label for="mlid">MLID</label>
								          	    <input type="text" class="form-control" id="mlid" name="mlid" value="{{(isset($liveInstance) && $liveInstance != '' )?$liveInstance->mlid:''}}" required>
								          	  </div>
								          	  <div class="form-group">
								          	    <label for="secretKey">Secret Key</label>
								          	    <input type="text" class="form-control" id="secretKey" name="secretKey" value="{{(isset($liveInstance) && $liveInstance != '' )?$liveInstance->secret_key:''}}" required>
								          	  </div>
								          	  <input type="hidden" name="type" value="live">
								          	  <button type="submit" class="btn btn-primary">Save</button>
								          	  @if(isset($liveInstance) && $liveInstance != '')
								          	  		<a href="{{ route('mlssetting_test_mls',['countryId'=>$country->id,'type'=>'live']) }}" class="btn btn-success">Test</a>
								          	  @endif
								          	</form>
								          </div>
								          <div class="tab-pane fade" id="{{$country->country_code}}_beta" role="tabpanel" aria-labelledby="{{$country->country_code}}_tab_beta">
								          	<form class="mt-4" action="{{ route('mlsSettings.store') }}" method="post">
								          		@csrf
								          		<input type="hidden" name="country_id" value="{{$country->id}}">
										          	<div class="form-group">
										          	    <label for="base_url">Base URL</label>
										          	    <input type="text" class="form-control" id="base_url" aria-describedby="base_url" name="base_url" value="{{(isset($betaInstance) && $betaInstance != '' )?$betaInstance->base_url:''}}" required>
										          	</div>
										          	<div class="form-group">
										          	    <label for="mlid">MLID</label>
										          	    <input type="text" class="form-control" id="mlid" name="mlid" value="{{(isset($betaInstance) && $betaInstance != '' )?$betaInstance->mlid:''}}" required>
										          	</div>
										          	<div class="form-group">
										          	    <label for="secretKey">Secret Key</label>
										          	    <input type="text" class="form-control" id="secretKey" name="secretKey" value="{{(isset($betaInstance) && $betaInstance != '' )?$betaInstance->secret_key:''}}" required>
										          	</div>
									          	<input type="hidden" name="type" value="beta">
									          	<button type="submit" class="btn btn-primary">Save</button>
									          	@if(isset($betaInstance) && $betaInstance != '')
									          		<a href="{{ route('mlssetting_test_mls',['countryId'=>$country->id,'type'=>'beta']) }}" class="btn btn-success">Test</a>
									          	@endif
								          	</form>
								          </div>
								          <div class="tab-pane fade" id="{{$country->country_code}}_choose_instance" role="tabpanel" aria-labelledby="{{$country->country_code}}_tab_choose_instance">
								          	<form>
								          		<div class="d-flex justify-content-between mt-3">
									          		<p>please choose one MLS Instance</p>
									          		<div>
									          			@if(count($countryMlsSettings) == 1)
									          				@foreach($countryMlsSettings as $countryMlsSetting)
									          					@if($countryMlsSetting->type == 'live')
									          						<label class="mr-2">
									          							<input type="radio" name="instance" value="{{$country->country_code}}_live" checked> Live
									          						</label>
									          						<label>
									          							<input type="radio" name="instance" value="{{$country->country_code}}_beta" disabled> Beta
									          						</label>
									          					@else
									          						<label class="mr-2">
									          							<input type="radio" name="instance" value="{{$country->country_code}}_live" disabled> Live
									          						</label>
									          						<label>
									          							<input type="radio" name="instance" value="{{$country->country_code}}_beta" checked> Beta
									          						</label>
									          					@endif
									          				@endforeach
									          			@elseif(count($countryMlsSettings) == 2)
									          				@foreach($countryMlsSettings as $countryMlsSetting)
									          					@if($countryMlsSetting->type === 'live' && $countryMlsSetting->switch === 1)
									          						<label class="mr-2">
									          							<input type="radio" name="instance" value="{{$country->country_code}}_live" checked onchange="chooseInstance(this)"> Live
									          						</label>
									          						<label>
									          							<input type="radio" name="instance" value="{{$country->country_code}}_beta" onchange="chooseInstance(this)"> Beta
									          						</label>
									          					@endif

									          					@if($countryMlsSetting->type === 'beta' && $countryMlsSetting->switch === 1)
									          						<label class="mr-2">
									          							<input type="radio" name="instance" value="{{$country->country_code}}_live" onchange="chooseInstance(this)"> Live
									          						</label>
									          						<label>
									          							<input type="radio" name="instance" value="{{$country->country_code}}_beta" checked onchange="chooseInstance(this)"> Beta
									          						</label>
									          					@endif
									          				@endforeach
									          			@else
									          				<label class="mr-2">
									          					<input type="radio" name="instance" value="{{$country->country_code}}_live" disabled> Live
									          				</label>
									          				<label>
									          					<input type="radio" name="instance" value="{{$country->country_code}}_beta" disabled> Beta
									          				</label>
									          			@endif
									          		</div>
								          		</div>
								          	</form>
								          </div>
								        </div>
								    </div>
								    @endif
							    </div>
						    </div>
						</div>
					  	<div class="col-md-2">
					  		<div id="toggles">
					  			@if(count($countryMlsSettings) > 0)
					  				<input type="checkbox" name="{{$country->country_code}}_switch" onchange="handleClick(this)" id="{{$country->country_code}}_switch" class="ios-toggle" {{($country->mls_service_status)?'checked':''}}/>
					  				<label for="{{$country->country_code}}_switch" class="checkbox-label" data-off="Off" data-on="On"></label>
					  			@elseif($country->name == 'Pakistan')
					  				<input type="checkbox" name="{{$country->country_code}}_switch" onchange="handleClick(this)" id="{{$country->country_code}}_switch" class="ios-toggle" {{($country->mls_service_status)?'checked':''}} />
					  				<label for="{{$country->country_code}}_switch" class="checkbox-label" data-off="Off" data-on="On"></label>
					  			@else
					  				<input type="checkbox" name="{{$country->country_code}}_switch" onchange="handleClick(this)" id="{{$country->country_code}}_switch" class="ios-toggle" disabled />
					  				<label for="{{$country->country_code}}_switch" class="checkbox-label" data-off="Off" data-on="On"></label>
					  			@endif
					  		</div>
					  	</div>
					  	<div class="col-md-2">
					  		@forelse($countryMlsSettings as $mlsSetting)
					  			@if($mlsSetting->type === 'live' && $mlsSetting->switch == 1 )
					  				<span class="badge badge-success">Live</span>
					  			@endif

					  			@if($mlsSetting->type === 'beta' && $mlsSetting->switch == 1)
					  				<span class="badge badge-primary">Beta</span>
					  			@endif
					  		@empty
					  			<span></span>
					  		@endforelse
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
<script>
function handleClick(elem){
	let base_url = window.location.origin;
	console.log(base_url);
	let countryCode = elem.id;
	countryCode = countryCode.split('_');

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: base_url+'/admin/mlsSettings/service/status',
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

function chooseInstance(elem) {
	let base_url = window.location.origin;
	let elemData = elem.value;
	elemData = elemData.split('_');
	let countryCode = elemData[0];
	let type = elemData[1];

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: base_url+'/admin/mlssetting/choose-instance',
		data:{
			'country_code': countryCode,
			'type': type,
			 },
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(res){
			if (res['status']) {
				$('#flash-message')
			}
		}
	});
}
</script>
@endsection