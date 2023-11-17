@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">User Details</div>
        <div class="card-body">
          <form class="form-horizontal">
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="user_name">Name</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="user_name" value="{{$user->fullName()}}" readonly>
          		</div>
          	</div>
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="email">Email</label>
          		<div class="col-sm-10">
          			<input type="text" class="form-control" id="email" value="{{$user->email}}" readonly>
          		</div>
          	</div>
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="role">Role</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="role" value="{{$user->role->name}}" readonly>
          		</div>
          	</div>
            <br>
            <hr>
          	<div class="form-group hlight-section">
          		<label class="control-label col-sm-4" for="products"><b>User Info</b></label>
          	</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="city">City</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="city" value="{{isset($user->userInfo->city)?$user->userInfo->city:''}}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="country">Country</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="country" value="{{isset($user->userInfo->country)?$user->userInfo->country:''}}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="dob">Date of Birth</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="dob" value="{{ isset($user->userInfo->date_of_birth)?date('d-M-Y', strtotime($user->userInfo->date_of_birth)):'' }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="phone">Phone</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="phone" value="{{isset($user->userInfo->phone_number)?$user->userInfo->phone_number:'' }}" readonly>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection