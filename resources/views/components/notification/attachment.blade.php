		
<div class="row" style="padding-left: 14px;">
	@if(isset($attachment) && !empty($attachment))
		<div class="col-md-6 mb-4">
			<div class="row">
				<div class="col-md-4">
					@if(isset($attachment->image) && !empty($attachment->image))
						@if($type == 'promotion')
							<img class="image" src="{{ $attachment->image }}" alt="Promotion Image">
						@endif
						@if($type == 'coupon')
							<img class="image" src="{{ $attachment->image }}" alt="Coupon Image">
						@endif
					@else
						<img class="image" src="{{ asset('assets/images/no-image.png') }}">
					@endif
				</div>
				<div class="col-md-8 pl-0">
					<div class="mb-2">
						@if($type == 'promotion')
							<a href="{{ route('promotions.show', $attachment->id ) }}"><strong>{{$attachment->title}}</strong></a>
						@endif
						@if($type == 'coupon')
							<a href="{{ route('coupons.show', $attachment->id ) }}"><strong>{{$attachment->title}}</strong></a>
						@endif
					</div>
				</div>
			</div>
		</div>
	@else
		<h5>No Attachment Found</h5>
	@endif
</div>
