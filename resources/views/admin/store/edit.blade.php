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
        <div class="card-header">Update Store</div>
        <div class="card-body">
        	<form class="form-horizontal" action="{{ route('stores.update', $store->id)}}" method="post" enctype="multipart/form-data">
        		@csrf
            @method('PUT')
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="name">Business Name:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="name" name="name" placeholder="Enter business name" value="{{ $store->name }}" required autofocus autocomplete="off">
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="addresslnOne">Address Line 1:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="addresslnOne" name="addresslnOne" placeholder="Enter address line 1" value="{{ $store->address_line_one }}" required>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="addresslnTwo">Address Line 2:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="addresslnTwo" name="addresslnTwo" placeholder="Enter address line 2" value="{{ $store->address_line_two }}">
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="name">Choose Country/Region</label>
        		  <div class="col-sm-10">
        		    <select name="country_id" id="countries" class="form-control" onchange="getLocations(this)" required>
        		    	<option value="">Please Choose Country</option>
        		    	@foreach($countries as $key => $country)
                            @if($country->id == $store->country_id)
                                <option value="{{ $country->id }}" selected>{{ $country->name }} ( {{$country->country_code}} )</option>
                            @else
        		              <option value="{{ $country->id }}">{{ $country->name }} ( {{$country->country_code}} )</option>
                            @endif
        		    	@endforeach
        		    </select>
        		  </div>
        		</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="location">Choose Locality</label>
              <div class="col-sm-10">
                <select name="location_id" id="locations" class="form-control" required>
                  <option value="">Please Choose Locaility</option>
                    @foreach($countryLocations as $key => $location)
                        @if($location->id == $store->location_id)
                            <option value="{{ $location->id }}" selected>{{ $location->name }}</option>
                        @else
                          <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endif
                    @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="storecode">Store Code:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="storecode" name="storecode" placeholder="Enter store code" value="{{ $store->storecode }}" onkeyup="validateStoreCode(this, {{ $store->id }})" required autofocus autocomplete="off">
              </div>
              <div class="col-sm-8" id="stcodeval" style="display: none; padding-top: 15px;"><span class="alert alert-danger" id="stcodeval-msg"></span></div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="lat">Latitude</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="lat" name="lat" placeholder="Enter store latitude" value="{{ $store->lat }}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="lon">Longitude</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="lon" name="lon" placeholder="Enter store longitude" value="{{ $store->lon }}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="email">Email Address:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email address" value="{{ $store->email }}">
              </div>
            </div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="phone">Phone:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="phone" name="phone_number" placeholder="Enter phone number" value="{{ $storeInfo->phone_number }}" required>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="website">Website:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="website" name="website" placeholder="Enter Website Url" value="{{ $storeInfo->website }}">
        		  </div>
        		</div>
        		<div class="form-group md-4">
        		  <label class="control-label col-sm-2" for="category">Category</label>
        		  <div class="col-sm-10">
        		    <select name="category" id="category" class="form-control" required>
        		    	<option value="">Please Choose Category</option>
        		    	@foreach($storeCategories as $key => $category)
                    @if($category === $storeInfo->primary_category)
                        <option value="{{ $category }}" selected>{{ $category }}</option>
                    @else
                      <option value="{{ $category }}">{{ $category }}</option>
                    @endif
                  @endforeach
        		    </select>
        		  </div>
        		</div>
            <div class="form-group">
              <label class="control-label col-sm-12" for="delivery_company_name">Delivery Company Name:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_company_name" name="delivery_company_name" placeholder="Enter Delivery Company Name" value="{{ $store->delivery_company_name }}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-12" for="delivery_company_email">Delivery Company Email:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_company_email" name="delivery_company_email" placeholder="Enter Delivery Company Email" value="{{ $store->delivery_company_email }}">
              </div>
            </div>
            <div class="form-group md-4">
              <label class="control-label col-sm-2" for="delivery">Delivery</label>
              <div class="col-sm-10">
                <select name="delivery" id="delivery" class="form-control" required>
                  <option value="off" {{ ($store->delivery === 'off')?'selected':'' }}>Off</option>
                  <option value="on" {{ ($store->delivery  === 'on')?'selected':'' }}>On</option>
                  <option value="full" {{ ($store->delivery  === 'full')?'selected':'' }}>Full</option>
                </select>
              </div>
            </div>
            <div class="form-group md-4">
              <label class="control-label col-sm-2" for="curbside">Curbside</label>
              <div class="col-sm-10">
                <select name="curbside" id="curbside" class="form-control" required>
                  <option value="off" {{ ($store->curbside === 'off')?'selected':'' }}>Off</option>
                  <option value="on" {{ ($store->curbside  === 'on')?'selected':'' }}>On</option>
                  <option value="full" {{ ($store->curbside  === 'full')?'selected':'' }}>Full</option>
                </select>
              </div>
            </div>
            <hr>
        		<div class="form-group hlight-section">
        		  <label class="control-label col-sm-4" for="phone">Working Hours:</label>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="snday">Sunday Hours:</label>
        		  <div class="col-sm-10">
        		    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">From:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="sndayFrom" value="{{ $storeInfo->sunday_hours_from }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="sndayTo" value="{{ $storeInfo->sunday_hours_to }}">
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
                               <input type="time" class="form-control" name="mndayFrom" value="{{ $storeInfo->monday_hours_from }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="mndayTo" value="{{ $storeInfo->monday_hours_to }}">
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
                               <input type="time" class="form-control" name="tsdayFrom" value="{{ $storeInfo->tuesday_hours_from }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="tsdayTo" value="{{ $storeInfo->tuesday_hours_to }}">
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
                               <input type="time" class="form-control" name="wddayFrom" value="{{ $storeInfo->wednesday_hours_from }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="wddayTo" value="{{ $storeInfo->wednesday_hours_to }}">
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
                               <input type="time" class="form-control" name="thrsdayFrom" value="{{ $storeInfo->thursday_hours_from }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="thrsdayTo" value="{{ $storeInfo->thursday_hours_to }}">
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
                               <input type="time" class="form-control" name="frdayFrom" value="{{ $storeInfo->friday_hours_from }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="frdayTo" value="{{ $storeInfo->friday_hours_to }}">
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
                               <input type="time" class="form-control" name="satrdayFrom" value="{{ $storeInfo->saturday_hours_from }}">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                           <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                               <input type="time" class="form-control" name="satrdayTo" value="{{ $storeInfo->saturday_hours_to }}">
                           </div>
                        </div>
                    </div>
        		  </div>
            </div>
            <div class="form-group" style="padding-left: 14px;">
              <label class="control-label" for="store_image"><b>Store Image</b></label>
               <div class="col-sm-10">
                   @if($store->image != null)
                     <img src="{{$store->getImage()}}" alt="storeImage" width="100" height="100">
                   @else
                     <h3>No file found!</h3>
                   @endif
               </div>
            </div>
            <div class="form-group">
              <div class="col-sm-10">
                <input type="file" name="file">
              </div>
            </div>
        		<div class="form-group"> 
        		  <div class="col-sm-offset-2 col-sm-10">
        		    <button type="submit" class="btn btn-primary">Update</button>
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