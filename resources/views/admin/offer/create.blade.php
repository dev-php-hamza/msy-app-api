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
        <div class="card-header">Create new offers</div>
        <div class="card-body">
          <form class="form-horizontal" action="{{ route('offers.store') }}" method="post">
            @csrf
            <div class="form-group">
              <label class="control-label col-sm-2" for="offer_name">Offer Name</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="offer_name" name="name" placeholder="Enter Offer Name" required autofocus>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="country">Choose country</label>
              <div class="col-sm-10">
                  <select name="country_id" class="form-control" required="required">
                    <option value="">Please choose country</option>
                    @foreach($countries as $key => $country)
                      @if($country->id == 2)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                      @else
                        <option value="{{ $country->id }}" disabled>{{ $country->name }}</option>
                      @endif
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="start_date">Start Date</label>
              <div class="col-sm-10">
                  <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Enter Promotion name" required autofocus>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="end_date">End Date</label>
              <div class="col-sm-10">
                  <input type="date" class="form-control" id="end_date" name="end_date" placeholder="Enter Promotion name" required autofocus>
              </div>
            </div>
            <div class="form-group"> 
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection