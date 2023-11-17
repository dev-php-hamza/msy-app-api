@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
  @if(count($errors) > 0)
    <ul>
      @foreach($errors->all() as $error)
        <li class="alert alert-danger">{{$error}}</li>
      @endforeach
    </ul>
  @endif
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Edit Coupon</div>
        <div class="card-body">
          <form class="form-horizontal" action="{{ route('coupons.update', $coupon->id) }}" method="post" enctype="multipart/form-data">
          	@csrf
          	@method('PUT')
            <div class="col-sm-10 d-flex justify-content-between">
              <div id="toggles" style="margin-left: 20px;">
                <label class="pb-2">Status</label>
                <input type="checkbox" name="" id="on" onchange="switch_on_off(this,{{$coupon->id}})" class="ios-toggle"/ {{($coupon->active)?'checked':''}}>
                <label for="on" class="checkbox-label" data-off="Off" data-on="On" ></label>
              </div>
              <div id="toggles" style="margin-left: 20px;">
                <label class="pb-2">Featured</label>
                <input type="checkbox" name="" id="on-featured" onchange="featured_switch_on_off(this,{{$coupon->id}})" class="ios-toggle"/ {{($coupon->is_featured)?'checked':''}} />
                <label for="on-featured" class="checkbox-label" data-off="Off" data-on="On"></label>
              </div>
            </div>
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="coupon_name">Name</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="coupon_name" name="title" value="{{ $coupon->title }}" autofocus>
          		</div>
          	</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="country">Choose country</label>
              <div class="col-sm-10">
                  <select name="country_id" class="form-control" required="required">
                    <option value="">Please choose country</option>
                    @foreach($countries as $key => $country)
                        <option value="{{ $country->id }}" {{ ($country->id == $coupon->country_id)?'selected':'' }}>{{ $country->name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="country">Coupon type</label>
              <div class="col-sm-10">
                  <select name="coupon_type" class="form-control" id="coupon-select">
                    <option value="{{$coupon->coupon_type}}">{{ ($coupon->coupon_type == 'std_bundle') ? 'Standard Bundle' : (($coupon->coupon_type == 'mix_and_match') ? 'Mix & Match' : 'Barcode') }}</option>
                  </select>
              </div>
            </div>
            <!-- <div class="row pl-3">
              <div class="col-sm-5">
                <label class="control-label" for="start_date_ux">Start Date</label>
                <div class="">
                  <input type="text" class="form-control" id="start_date_ux" name="start_date_ux" placeholder="dd/mm/yyyy" value="{{date_format(date_create($coupon->start_date), 'd/m/Y')}}" required autofocus autocomplete="off">
                  <input type="hidden" class="form-control" id="start_date" name="start_date" value="{{$coupon->start_date}}">
                </div>
              </div>
              <div class="col-sm-5">
                <label class="control-label" for="snday">Start Time:</label>
                <div class="">
                   <div class="row">
                       <div class="d-flex align-items-center">
                          <label class="control-label mr-2"></label>
                           <div class="form-group">
                              <input type="time" value="{{date_format(date_create($coupon->start_time), 'H:i')}}" name="start_time" class="form-control">
                          </div>
                       </div>
                   </div>
                </div>
              </div>
            </div>
            <div class="row pl-3">
              <div class="col-sm-5">
                <label class="control-label" for="end_date_ux">End Date</label>
                <div class="">
                    <input type="text" class="form-control" id="end_date_ux" name="end_date_ux" placeholder="dd/mm/yyyy" value="{{date_format(date_create($coupon->end_date), 'd/m/Y')}}" required autofocus autocomplete="off">
                    <input type="hidden" class="form-control" id="end_date" name="end_date" value="{{$coupon->end_date}}">
                </div>
              </div>
              <div class="col-sm-5">
                <label class="control-label" for="snday">End Time</label>
                <div class="">
                 <div class="row">
                     <div class="d-flex align-items-center">
                        <label class="control-label mr-2"></label>
                         <div class="form-group">
                            <input type="time" value="{{date_format(date_create($coupon->end_time), 'H:i')}}" name="end_time" class="form-control">
                        </div>
                     </div>
                 </div>
                </div>
              </div>
            </div> -->
            <div class="form-group" id="barcode-div">
              <label class="control-label col-sm-12" for="barcode">Barcode <strong>(Only 11 or 13 digits allowed)</strong></label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="barcode" name="barcode" value="{{$coupon->barcode}}" placeholder="Enter UPC barcode number" required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="short-description">Short Description</label>
              <div class="col-sm-10">
                <textarea rows="2" cols="97" class="form-control" name="short_description" placeholder="Enter Short Description here..." required>{{ $coupon->short_description }}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="description">Description</label>
              <div class="col-sm-10">
                <textarea rows="5" cols="97" class="form-control" name="description" placeholder="Enter Description here..." required>{{ $coupon->description }}</textarea>
              </div>
            </div>
<!--             <div class="form-group">
              <label class="control-label col-sm-12" for="promo_image">Coupon Barcode Image: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 242px width and 184px height)</strong></label>
              <div class="col-sm-10">
                @if($coupon->barcode_image != null)
                  <img src="{{-- asset('coupon/images/'.$coupon->barcode_image) --}}" alt="barcodeImage" width="100" height="100">
                @else
                  <h3>No file found!</h3>
                @endif
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-10">
                <input type="file" name="barcode_image" id="barcode_image">
              </div>
            </div> -->
            <div class="form-group">
              <label class="control-label col-sm-12" for="promo_image">Coupon Image: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 218px width and 218px height)</strong></label>
              <div class="col-sm-10">
                @if($coupon->image != null)
                  <img src="{{$coupon->getImage()}}" alt="couponImage" width="100" height="100">
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
@section('scripts')
  <script type="text/javascript">
    $(document).ready(function(){
      var coupon = $('#coupon-select').val();
      if (coupon == 'std_bundle' || coupon == 'mix_and_match') {
        $('#barcode-div').hide();
        $("#barcode").removeAttr("required");
      }
      else{
        $('#barcode-div').show();
        $("#barcode").attr("required", "true");
      }
      // $("#start_date_ux").datepicker({ 
      //   dateFormat: "dd/mm/yy",
      //   altField: "#start_date",
      //   altFormat: "yy-mm-dd" 
      // });
      // $("#end_date_ux").datepicker({ 
      //   dateFormat: "dd/mm/yy",
      //   altField: "#end_date",
      //   altFormat: "yy-mm-dd" 
      // });
    });

    function switch_on_off(elem, id) {
      let base_url = window.location.origin;
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: base_url+'/admin/coupon/status',
        data:{
          'id': id,
        },
        dataType: 'json',
        beforeSend: function(){
        },
        success: function(res){
          
        }
      });
    }

    function featured_switch_on_off(elem, id) {
      let base_url = window.location.origin;
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'POST',
        url: base_url+'/admin/coupon/featured',
        data:{
          'id': id,
        },
        dataType: 'json',
        beforeSend: function(){
        },
        success: function(res){
          
        }
      });
    }
  </script>
@endsection
