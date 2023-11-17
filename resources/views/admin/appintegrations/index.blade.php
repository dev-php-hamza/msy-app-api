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
					App Integration List
					<a href="{{ route('apps.create') }}" class="btn btn-primary" style="float: right;">Add new</a>
				</div>
				<div class="card-body">
					<div class="countires-table">
						<table>
							<thead>
								<tr>
									<th>#</th>
									<th>App Name</th>
									<th>Auth Token</th>
									<th class="action-last" style="min-width: 57px;width: 80px;">Action</th>
								</tr>
							</thead>
							<tbody>
								@forelse($apps as $app)
									<tr>
										<td>{{ $loop->index + $apps->firstItem() }}</td>
										<td>{{ $app->app_name }}</td>
										<td> <input type="password" id="auth_token" value="{{ $app->auth_token }}" class="custom_token" readonly=""> </td>
										<td>
											<button class="btn show_pass">
												<i class="far fa-eye"></i>
												<!-- <i class="far fa-eye"></i> -->
											</button>
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
					{{ $apps->appends(\Request::except('_token'))->render() }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	$('.show_pass').click(function(){
		const input = $(this).parent().siblings().find('input')
		const attribute= input.attr('type')
		console.log(input)
		if (attribute === "password") {
			input.attr('type','text')
			// this.parent().parent().attr('type', 'text')
		    // x.attr('type', 'text');
		    $(this).children('.fa-eye').addClass('fa-eye-slash').removeClass('fa-eye');


		} else {
			// this.parent().parent().attr('type', 'password')
		    input.attr('type','password')
		    $(this).children('.fa-eye-slash').addClass('fa-eye').removeClass('fa-eye-slash');
		}
	})
</script>
@endsection