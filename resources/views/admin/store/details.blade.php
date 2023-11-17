@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Store Details</div>
        <div class="card-body">
        	<form class="form-horizontal">
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="name">Business Name:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="name" value="{{ $store->name }}" readonly>
        		  </div>
        		</div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="storecode">Store Code:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="storecode" value="{{ $store->storecode }}" readonly>
                  </div>
                </div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="addresslnOne">Address Line 1:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="addresslnOne" value="{{ $store->address_line_one }}" readonly>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="addresslnTwo">Address Line 2:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="addresslnTwo" value="{{ $store->address_line_two }}"readonly>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-4" for="name">Country/Region</label>
        		  <div class="col-sm-10">
                    <input type="text" class="form-control" id="location" value="{{ $countryName }}"readonly>
        		  </div>
        		</div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="location">Locality</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="location" value="{{ $locationName }}"readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="latitude">Latitude</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="latitude" value="{{ $store->lat }}"readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="longitude">Longitude</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="longitude" value="{{ $store->lon }}"readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="email">Email:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" value="{{ $store->email }}" readonly>
                  </div>
                </div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="phone">Phone:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="phone" value="{{ $storeInfo->phone_number }}" readonly>
        		  </div>
        		</div>
        		<div class="form-group">
        		  <label class="control-label col-sm-2" for="web">Website:</label>
        		  <div class="col-sm-10">
        		    <input type="text" class="form-control" id="web" value="{{ $storeInfo->website }}" readonly>
        		  </div>
        		</div>
        		<div class="form-group mb-4">
        		  <label class="control-label col-sm-2" for="category">Category</label>
        		  <div class="col-sm-10">
        		     <input type="text" class="form-control" id="web" value="{{ $storeInfo->primary_category }}" readonly>
        		  </div>
        		</div>
            <div class="form-group">
              <label class="control-label col-sm-12" for="delivery_company_name">Delivery Company Name:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_company_name" value="{{ $store->delivery_company_name }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-12" for="delivery_company_email">Delivery Company Email:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="delivery_company_email" value="{{ $store->delivery_company_email }}" readonly>
              </div>
            </div>
                <div class="form-group mb-4">
                  <label class="control-label col-sm-2" for="delivery">Delivery</label>
                  <div class="col-sm-10">
                     <input type="text" class="form-control" id="web" value="{{ ucfirst($store->delivery) }}" readonly>
                  </div>
                </div>
                <div class="form-group mb-4">
                  <label class="control-label col-sm-2" for="curbside">Curbside</label>
                  <div class="col-sm-10">
                     <input type="text" class="form-control" id="web" value="{{ ucfirst($store->curbside) }}" readonly>
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
                                    <input type="time" class="form-control" id="snday" value="{{ $storeInfo->sunday_hours_from }}" readonly>
                                </div>
                             </div>
                             <div class="col-md-6 d-flex align-items-center">
                                <label class="control-label mr-2">To:</label>
                                <div class="form-group">
                                    <input type="time" class="form-control" id="snday" value="{{ $storeInfo->sunday_hours_to }}" readonly>
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
                                <input type="time" class="form-control" id="mnday" value="{{ $storeInfo->monday_hours_from }}" readonly>
                            </div>
                         </div>
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                                <input type="time" class="form-control" id="mnday" value="{{ $storeInfo->monday_hours_to }}" readonly>
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
                                <input type="time" class="form-control" id="tsday" value="{{ $storeInfo->tuesday_hours_from }}" readonly>
                            </div>
                         </div>
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                                <input type="time" class="form-control" id="tsday" value="{{ $storeInfo->tuesday_hours_to }}" readonly>
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
                                <input type="time" class="form-control" id="wdday" value="{{ $storeInfo->wednesday_hours_from }}" readonly>
                            </div>
                         </div>
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                                <input type="time" class="form-control" id="wdday" value="{{ $storeInfo->wednesday_hours_to }}" readonly>
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
                                <input type="time" class="form-control" id="thrsday" value="{{ $storeInfo->thursday_hours_from }}" readonly>
                            </div>
                         </div>
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                                <input type="time" class="form-control" id="thrsday" value="{{ $storeInfo->thursday_hours_to }}" readonly>
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
                                <input type="time" class="form-control" id="frday" value="{{ $storeInfo->friday_hours_from }}" readonly>
                            </div>
                         </div>
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                                <input type="time" class="form-control" id="frday" value="{{ $storeInfo->friday_hours_to }}" readonly>
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
                                <input type="time" class="form-control" id="satrday" value="{{ $storeInfo->saturday_hours_from }}" readonly>
                            </div>
                         </div>
                         <div class="col-md-6 d-flex align-items-center">
                            <label class="control-label mr-2">To:</label>
                            <div class="form-group">
                                <input type="time" class="form-control" id="satrday" value="{{ $storeInfo->saturday_hours_to }}" readonly>
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
        	</form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection