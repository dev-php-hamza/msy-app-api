@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
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
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">Create new notification</div>
				<div class="card-body">
					<form class="form-horizontal" action="{{ route('notifications.next')}}" method="post">
						@csrf
						<div class="form-group">
							<label class="control-label col-sm-2" for="choose-one">Choose Country</label>
							<div class="col-sm-10">
								<select name="country_id" id="countryId" class="form-control" onchange="clearNotificationObj(this)" required>
									<option value="">Please choose country</option>
									@foreach($countries as $key => $country)
						    		<option value="{{$country->id}}">{{ $country->name }}</option>
						    	@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							 <label class="control-label col-sm-2" for="choose-one">Choose Type</label>
							<div class="col-sm-10">
								<select name="selection" id="selection" class="form-control" onchange="getNotificationType(this)" required disabled>
									<option value="">Choose</option>
									<option value="promotion">Promotion</option>
									<option value="coupon">Coupon</option>
									<option value="text">Text</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="select-type">
						  <label class="control-label col-sm-2" for="choose-one">Choose Object</label>
						  <div class="col-sm-10">
						    <select id="choose-one" class="form-control" name="object_id" disabled>
						    </select>
						  </div>
						</div>
						<div class="form-group" id="text-title">
							<label class="control-label col-sm-4" for="text-title">Please Choose Title</label>
							<div class="col-sm-10">
								<input type="text" name="title" class="form-control" placeholder="Enter the notfication title..." data-emojiable="true">
							</div>
						</div>
						<div class="form-group">
						  <label class="control-label col-sm-3" for="choose-one">Notfication Message</label>
						  <div class="col-sm-10">
						    <textarea id="simple-text" rows="5" cols="97" name="description" id="description" required placeholder="Enter the notification message..." data-emojiable="true"></textarea>
						  </div>
						</div>
						<div class="form-group"> 
						  <div class="col-sm-offset-2 col-sm-10">
						    <button type="submit" id="btnSubmit" class="btn btn-primary" disabled>Next</button>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('js/notification.js') }}"></script>

  <script>
    $(function() {
      // Initializes and creates emoji set from sprite sheet
      window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: "{{ asset('plugins/emoji-picker/lib/img/') }}",
        popupButtonClasses: 'far fa-smile'
      });
      // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
      // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
      // It can be called as many times as necessary; previously converted input fields will not be converted again
      window.emojiPicker.discover();
    });
  </script>
@endsection