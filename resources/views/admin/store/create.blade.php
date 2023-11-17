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
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Create New Store</div>
        <div class="card-body">
        	<form class="form-horizontal" action="{{ route('stores.store')}}" method="post" enctype="multipart/form-data">
        		@csrf
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="name">Business Name:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="name" name="name" placeholder="Enter business name" value="{{ old('name') }}" required autofocus autocomplete="off">
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="addresslnOne">Address Line 1:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="addresslnOne" name="addresslnOne" placeholder="Enter address line 1" value="{{ old('addresslnOne') }}" required>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="addresslnTwo">Address Line 2:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="addresslnTwo" name="addresslnTwo" placeholder="Enter address line 2" value="{{ old('addresslnTwo') }}">
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="name">Choose Country/Region</label>
        		  <div class="col-sm-10">
        		    <select name="country_id" id="countries" class="form-control" onchange="getLocations(this)" required>
        		    	<option value="">Please Choose Country</option>
        		    	@foreach($countries as $key => $country)
        		          <option value="{{ $country->id }}">{{ $country->name }} ( {{$country->country_code}} )</option>
        		    	@endforeach
        		    </select>
        		  </div>
        		</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="location">Choose Locality</label>
              <div class="col-sm-10">
                <select name="location_id" id="locations" class="form-control" required>
                  <option value="">Please Choose Locaility</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="storecode">Store Code:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="storecode" name="storecode" placeholder="Enter Store Code" value="{{ old('storecode') }}" onkeyup="validateStoreCode(this, false)" required autofocus autocomplete="off">
              </div>
              <div class="col-sm-8" id="stcodeval" style="display: none; padding-top: 15px;"><span class="alert alert-danger" id="stcodeval-msg"></span></div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="lat">Latitude</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="lat" name="lat" placeholder="Enter store latitude" value="{{ old('lat') }}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="lon">Longitude</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="lon" name="lon" placeholder="Enter store longitude" value="{{ old('lon') }}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="email">Email Address:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email address" value="{{ old('email') }}" >
              </div>
            </div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="phone">Phone:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="phone" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number') }}" required>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="website">Website:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="website" name="website" placeholder="Enter Website Url" value="{{ old('web') }}">
        		  </div>
        		</div>
        		<div class="form-group mb-4">
        		  <label class="control-label col-sm-2" for="category">Category</label>
        		  <div class="col-sm-10">
        		    <select name="category" id="category" class="form-control" required>
        		    	<option value="">Please Choose Category</option>
                  <option value="Pharmacy">Pharmacy</option>
        		    	<option value="Supermarket">Supermarket</option>
        		    	<option value="Hypermarket">Hypermarket</option>
        		    	<option value="Department store">Department store</option>
        		    	<option value="Corporate office">Corporate office</option>
        		    </select>
        		  </div>
        		</div>
            <div class="form-group">
              <label class="control-label col-sm-12" for="delivery_company_name">Delivery Company Name:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_company_name" name="delivery_company_name" placeholder="Enter Delivery Company Name" value="{{ old('delivery_company_name') }}" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-12" for="delivery_company_email">Delivery Company Email:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_company_email" name="delivery_company_email" placeholder="Enter Delivery Company Email" value="{{ old('delivery_company_email') }}" >
              </div>
            </div>
            <div class="form-group mb-4">
              <label class="control-label col-sm-2" for="delivery">Delivery</label>
              <div class="col-sm-10">
                <select name="delivery" id="delivery" class="form-control" required>
                  <option value="off">Off</option>
                  <option value="on">On</option>
                  <option value="full">Full</option>
                </select>
              </div>
            </div>
            <div class="form-group mb-4">
              <label class="control-label col-sm-2" for="curbside">Curbside</label>
              <div class="col-sm-10">
                <select name="curbside" id="curbside" class="form-control" required>
                  <option value="off">Off</option>
                  <option value="on">On</option>
                  <option value="full">Full</option>
                </select>
              </div>
            </div>
            <hr>
        		<div class="form-group hlight-section">
        		  <label class="control-label col-sm-4" for="wHours"><b>Working Hours</b></label>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="snday">Sunday Hours:</label>
        		  <div class="col-sm-10">
                     <div class="row">
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">From:</label>
                             <div class="form-group">
                                <input type="time" class="form-control" name="sndayFrom">
                            </div>
                         </div>
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">To:</label>
                             <div class="form-group">
                                <input type="time" class="form-control" name="sndayTo">
                            </div>
                         </div>
                     </div>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="mnday">Monday Hours:</label>
        		  <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">From:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="mndayFrom" value="{{ old('mndayFrom') }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="mndayTo" value="{{ old('mndayTo') }}">
                           </div>
                        </div>
                    </div>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="tsday">Tuesday Hours:</label>
        		  <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">From:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="tsdayFrom" value="{{ old('tsdayFrom') }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="tsdayTo" value="{{ old('tsdayTo') }}">
                           </div>
                        </div>
                    </div>
        		  </div>
        		</div>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="wdday">WednessDay Hours:</label>
                  <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">From:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="wddayFrom" value="{{ old('wddayFrom') }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="wddayTo" value="{{ old('wddayTo') }}">
                           </div>
                        </div>
                    </div>
                  </div>
                </div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="thrsday">Thursday Hours:</label>
        		  <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">From:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="thrsdayFrom" value="{{ old('thrsdayFrom') }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="thrsdayTo" value="{{ old('thrsdayTo') }}">
                           </div>
                        </div>
                    </div>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="frday">Friday Hours:</label>
        		  <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">From:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="frdayFrom" value="{{ old('frdayFrom') }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="frdayTo" value="{{ old('frdayTo') }}">
                           </div>
                        </div>
                    </div>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="satrday">Saturday Hours:</label>
        		  <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">From:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="satrdayFrom" value="{{ old('satrdayFrom') }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="satrdayTo" value="{{ old('satrdayTo') }}">
                           </div>
                        </div>
                    </div>
        		  </div>
        		</div>
            <div class="form-group">
              <div class="col-sm-10">
                <input type="file" name="file">
              </div>
            </div>
        		<div class="form-group"> 
        		  <div class="col-sm-offset-2 col-sm-10">
        		    <button type="submit" class="btn btn-primary">Add</button>
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
  <script src="{{ asset('js/stores.js') }}"></script>
@endsection