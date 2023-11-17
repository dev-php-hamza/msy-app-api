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
        <div class="card-header">Update Promotion</div>
        <div class="card-body">
          <form class="form-horizontal" action="{{ route('promotions.update', $promotion->id) }}" method="post" enctype="multipart/form-data">
          	@csrf
          	@method('PUT')
          	<div class="form-group">
          		<label class="control-label col-sm-2" for="promo_name">Name</label>
          		<div class="col-sm-10">
          		    <input type="text" class="form-control" id="promo_name" name="title" value="{{ $promotion->title }}" autofocus>
          		</div>
          	</div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="country">Choose country</label>
              <div class="col-sm-10">
                  <select name="country_id" class="form-control" required="required">
                    <option value="">Please choose country</option>
                    @foreach($countries as $key => $country)
                        <option value="{{ $country->id }}" {{($country->id == $promotion->country_id)?'selected':''}}>{{ $country->name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="country">Type</label>
              <div class="col-sm-10">
                  <select name="promotion_type" class="form-control" required="required">
                    <option value="{{$promotion->type}}">{{ucfirst($promotion->type)}}</option>
                  </select>
              </div>
            </div>
            <div class="row pl-3">
              <div class="col-sm-5">
                <label class="control-label" for="start_date_ux">Start Date</label>
                <div class="">
                  <!-- <input type="date" class="form-control" id="start_date_ux" name="start_date" placeholder="dd/mm/yyyy" value="{{date_format(date_create($promotion->start_date), 'd-m-Y')}}" required autofocus autocomplete="off"> -->
                  <!-- <input type="hidden" class="form-control" id="start_date" name="start_date" value="{{$promotion->start_date}}"> -->
                  <input type="text" class="form-control" id="start_date_ux" name="start_date_ux" placeholder="dd/mm/yyyy" value="{{date_format(date_create($promotion->start_date), 'd/m/Y')}}" required autofocus autocomplete="off">
                  <input type="hidden" class="form-control" id="start_date" name="start_date" value="{{date_format(date_create($promotion->start_date), 'Y-m-d')}}">
                </div>
              </div>
              <div class="col-sm-5">
                <label class="control-label" for="snday">Start Time:</label>
                <div class="">
                   <div class="row">
                       <div class="d-flex align-items-center">
                          <label class="control-label mr-2"></label>
                           <div class="form-group">
                              <input type="time" value="{{date_format(date_create($promotion->start_date), 'H:i')}}" name="start_time" class="form-control">
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
                    <!-- <input type="date" class="form-control" id="end_date_ux" name="end_date" placeholder="dd/mm/yyyy" value="{{date_format(date_create($promotion->end_date), 'd-m-Y')}}" required autofocus autocomplete="off"> -->
                    <!-- <input type="hidden" class="form-control" id="end_date" name="end_date" value="{{$promotion->end_date}}"> -->
                    <input type="text" class="form-control" id="end_date_ux" name="end_date_ux" placeholder="dd/mm/yyyy" value="{{date_format(date_create($promotion->end_date), 'd/m/Y')}}" required autofocus autocomplete="off">
                    <input type="hidden" class="form-control" id="end_date" name="end_date" value="{{date_format(date_create($promotion->end_date), 'Y-m-d')}}">
                </div>
              </div>
              <div class="col-sm-5">
                <label class="control-label" for="snday">End Time</label>
                <div class="">
                 <div class="row">
                     <div class="d-flex align-items-center">
                        <label class="control-label mr-2"></label>
                         <div class="form-group">
                            <input type="time" value="{{date_format(date_create($promotion->end_date), 'H:i')}}" name="end_time" class="form-control">
                        </div>
                     </div>
                 </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="description">Description</label>
              <div class="col-sm-10">
                <textarea rows="5" cols="97" name="description" placeholder="Enter Description here..." required>{{ $promotion->description }}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-12" for="promo_image">Promotion Banner Image: <strong style="font-weight: bold !important; display: inline-block;">(Recommended dimensions: 375px width and 187px height)</strong></label>
              <div class="col-sm-10">
                @if($promotion->image != null)
                  <img src="{{$promotion->getImage()}}" alt="promotionImage" width="100" height="100">
                @else
                  <h3>No file found!</h3>
                @endif
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-10">
                <input type="file" name="file" {{(isset($promotion->image) && !empty($promotion->image) && $promotion->image != '')?"":"required"}}>
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
      $("#start_date_ux").datepicker({ 
        dateFormat: "dd/mm/yy",
        altField: "#start_date",
        altFormat: "yy-mm-dd" 
      });
      $("#end_date_ux").datepicker({ 
        dateFormat: "dd/mm/yy",
        altField: "#end_date",
        altFormat: "yy-mm-dd" 
      });
    });
  </script>
@endsection

