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
              <div class="card-body scroll_card" style="max-height: 220px;overflow-y: scroll;">
                <div class="col-sm-12 scrollTable">
                  @forelse($assignedStores as $key => $assignedStore)
                   <div class="d-flex align-items-center justify-content-between">
                      <p class="mb-0">{{ $assignedStore->name }}</p>
                      <a href="{{ route('delivery-companies_unassign_store',['deliveryCompanyId' => $deliveryCompany->id, 'storeId' => $assignedStore->id]) }}" class="unassign ml-5 btn btn-danger">Unassign</a>
                   </div>
                  @empty
                    <p>No Store is currently assinged!</p><br>
                  @endforelse
                </div>
              </div>
          </div>
          <br>
          <form class="form-horizontal" method="post" action="{{ route('delivery-companies_assign_store_save') }}">
            @csrf
            <div class="card">
              <div class="card-header">Assign New Stores</div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <input type="hidden" name="dCompany_id" value="{{ $deliveryCompany->id }}">
                        <label class="control-label col-sm-12 font-weight-bold pl-0" for="storeList">Stores</label>
                        <div class="storeList">
                          <select style="display:none" id="storeList"  name="storeIds[]" multiple required autofocus>
                            @foreach($stores as $store)
                              <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="text-center mt-3">
                      <input type="submit" value="Assign" class="btn btn-success">
                    </div>
                  </div>
                </div>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $('.storeList').dropdown({
    multipleMode: 'label'
  });
</script>
@endsection