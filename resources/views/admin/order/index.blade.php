@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container container-body">
  <div class="row justify-content-center">
    @if (session('message'))
      <div id="flash-message" class="">
            {{ session('message') }}
      </div>
    @endif
    <div class="col-md-12">
      <!-- <div>
        <a href="{{ route('show_import_stores_form') }}" class="btn btn-primary">Import Massy stores</a>
      </div> -->
      <div class="card">
        <div class="card-header">
          Orders
          <a href="{{ route('orders_export_form') }}" class="btn btn-primary" style="float: right;">Export Orders</a>
        </div>
        <div class="card-body">
          <form class="form-horizontal" action="{{ route('orders.index') }}" method="GET">
            <div class="row mb-3">
                <div class="col-md-3 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="orderNum">Order #</label>
                    <div class="">
                      <input type="text" class="form-control" id="orderNum" name="orderNum" placeholder="Enter Order Number" value="{{ (request()->get('orderNum') !== null)?request()->get('orderNum'): '' }}">
                    </div>
                  </div>
                </div>
                <div class="col-md-3 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label " for="custName">Customer Name</label>
                    <div class="">
                      <input type="text" class="form-control" id="custName" name="custName" placeholder="Enter Customer Name" value="{{ (request()->get('custName') !== null)?request()->get('custName'): '' }}">
                    </div>
                  </div>
                </div>
                <div class="col-md-3 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="email">Email</label>
                    <div class="">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email Address" value="{{ (request()->get('email') !== null)?request()->get('email'): '' }}">
                    </div>
                  </div>
                </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-3 pr-0">
                <div class="form-group">
                  <label class="control-label" for="country">Country (Only to get store options)</label>
                  <div class="">
                    <select name="country" id="country" class="form-control" >
                      <option value="">Please choose country</option>
                        @foreach($countries as $key => $country)
                          <option value="{{$country->id}}" {{ (request()->get('country') == null && $country->id == 1)?'selected':(request()->get('country') !== null && $country->id == request()->get('country'))?'selected':'' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3 pr-0">
                <div class="form-group mb-0">
                  <label class="control-label" for="stores">Stores By Country</label>
                  <div class="">
                    <select name="store" id="stores" class="form-control" onchange="changeFocus(this)">
                      <option value="">Please choose store</option>
                      @foreach($stores as $key => $store)
                        <option value="{{$store->id}}" {{ (request()->get('store') !== null && request()->get('store') == $store->id)?'selected': '' }}>{{ $store->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3 pr-0">
                <div class="form-group mb-0">
                  <label class="control-label" for="created_at">Placed At</label>
                  <div class="">
                    <input type="date" name="created_at" id="created_at" class="form-control" value="{{ (request()->get('created_at') !== null)?request()->get('created_at'): '' }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-1 d-flex align-items-end pl-3">
                <div class="form-group mb-0">        
                  <div class="col-sm-offset-2 ">
                    <button type="submit" class="btn btn-primary" id="btnFormSubmit">Search</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="orders-table">
            <table>
              <thead>
                <tr>
                  <th style="width: 80px;">Customer Name</th>
                  <th style="width: 80px;">Order #</th>
                  <th style="width: 230px;">Store Name</th>
                  <th style="width: 80px;">Total Products</th>
                  <th style="width: 80px;">Estimated Bill</th>
                  <th style="width: 80px;">Store Email</th>
                  <th style="width: 80px;">Customer Email</th>
                  <!-- <th style="width: 80px;">Status</th> -->
                  <th style="width: 80px;">Placed At</th>
                  <th class="action-last">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($orders as $order)
                  <tr>
                    <td>{{ $order->deliveryDetail->fullName() }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->store->name }}</td>
                    <td>{{ count($order->orderProducts) }}</td>
                    <td>{{ number_format($order->total_price,2) }}</td>
                    <!-- <td>{{-- $order->order_status --}}</td> -->
                    @if($order->sent_to_store == 0)
                      <td>NOT SENT <br><a href="{{ route('orders_resend_email_store', $order->id) }}" style="font-size: 10px;">(Resend)</a></td>
                    @else
                      <td>SENT <br><a href="{{ route('orders_resend_email_store', $order->id) }}" style="font-size: 10px;">(Resend)</a></td>
                    @endif

                    @if($order->sent_to_customer == 0)
                      <td>NOT SENT</td>
                    @else
                      <td>SENT</td>
                    @endif
                    <td>{{ $order->placeAt() }}</td>
                    <td>
                      <form action="{{ route('orders.destroy',$order->id) }}" method="POST">
                        <a href="{{route('orders.show',$order->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
                        <!-- <a href="{{-- route('orders.edit',$order->id) --}}" class="btn btn-success"><i class="fa fa-edit"></i></a> | -->
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
                      </form>
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
          {{ $orders->appends(\Request::except('_token'))->render() }}
        </div>
      </div>
    </div> 
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function(){
    $('#country').change(function(elem){
      let countryId = $(this).val();
      $("#stores").empty();
      $("input[type=date]").val("")
      if (countryId != '') {
        let base_url = window.location.origin;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'get',
            url: base_url+"/admin/stores/country/"+countryId,
            dataType: "json",
            beforeSend: function(){
            },
            success: function(data){
                let newOpt = '';
                newOpt += "<option value=''>Please Choose Store</option>"
                $.each(data['stores'], function(index,value){
                    newOpt += "<option value='"+value['id']+"'>"+value['name']+"</option>"

                });

                $("#stores").append(newOpt);
            }
        });
      }
    });
  });

  function changeFocus(elem) {
    if (elem.value != '') {
      $('#created_at').focus();
    }
  }
</script>
@endsection