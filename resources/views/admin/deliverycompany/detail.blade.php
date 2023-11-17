@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container" style="max-width: 100%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Delivery Company Detail</div>
              <div class="card-body">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label col-sm-12 font-weight-bold" for="dCompany_name">Name</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="dCompany_name" value="{{ucwords($deliveryCompany->name)}}" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-12 font-weight-bold" for="dCompany_email">Email</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="dCompany_email" value="{{$deliveryCompany->email}}" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-12 font-weight-bold" for="dCompany_country">Country</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="dCompany_country" value="{{ $countryName }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <br>
          <div class="card">
            <div class="card-header">Assigned Stores List</div>
              <div class="card-body scroll_card">
                <div class="col-sm-12 scrollTable">
                  @forelse($assignedStores as $key => $assignedStore)
                   <div class="d-flex align-items-center justify-content-between">
                      <p class="mb-0">{{ $assignedStore->name }}</p>
                   </div>
                  @empty
                    <p>No Store is currently assinged!</p><br>
                  @endforelse
                </div>
              </div>
          </div>
        </div>
    </div>
</div>
@endsection