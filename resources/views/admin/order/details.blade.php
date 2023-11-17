@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container" style="max-width: 100%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form class="form-horizontal">
                <div class="form-group d-flex">
                  <label class="control-label col-sm-6" for="name">Order #: <strong>{{ $order->order_number }}</strong></label>
                  <label class="control-label col-sm-6" for="name" style="text-align: right;">Order Status: <b>{{ strtoupper($order->order_status) }}</b></label>
                </div>
                <div class="card">
                    <div class="card-header">Mobile App User</div>
                    <div class="card-body">
                        <div class="col-sm-12">
                            <div class="form-group">
                              <label class="control-label col-sm-12 font-weight-bold" for="name">Name</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" value="{{ucwords($order->user->fullName())}}" readonly>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-sm-12 font-weight-bold" for="name">Email</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" value="{{$order->user->email}}" readonly>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-sm-12 font-weight-bold" for="name">Phone</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" value="{{$order->user->userInfo->phone_number}}" readonly>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Delivery Details</div>
                                <div class="card-body">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Customer Name</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ ucwords($order->deliveryDetail->fullName()) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Email</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ $order->deliveryDetail->email }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Phone</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ $order->deliveryDetail->phone }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Address Line 1</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ ucwords($order->deliveryDetail->address_line_1) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Address Line 2</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ ucwords($order->deliveryDetail->address_line_2) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Area</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ $order->deliveryDetail->area??null }}" readonly>
                                            </div>
                                        </div>                         
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Store Info</div>
                                <div class="card-body">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Business Name</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ ucwords($order->store->name) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Phone</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ $order->store->storeInfo->phone_number }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Email</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ $order->store->email }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Address line 1</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ ucwords($order->store->address_line_one) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-12 font-weight-bold" for="name">Territory</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" value="{{ ucwords($order->store->country->name) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
            <div style="margin-top: 1%;">
              <table style="width: 100%;margin-left: auto;margin-bottom: 20px;">
                <thead>
                   <tr style="text-align: left;">
                        <th
                          style="font-family: Poppins;width: 200px;height: 40px;padding-bottom: 0px;padding-top: 0px;padding-left: 10px;color:#c4ceda;font-weight: 500;"
                        >
                          ITEM
                        </th>
                        <th
                          style="font-family: Poppins;width: 100px;text-align: center !important;color: #c4ceda;font-weight: 500;"
                        >
                          QTY
                        </th>
                        <th
                          style="font-family: Poppins;width: 100px;text-align: center;color: #c4ceda;font-weight: 500;"
                        >
                          COST
                        </th>
                   </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $product)
                      <tr style="vertical-align: middle !important;text-align: center;">
                        <td style="text-align: left;width: 200px; font-weight: 700;">
                          <p style="font-family: Poppins;margin-bottom: 0;">
                            {{$product->desc}}<small> ({{$product->messuringUnit()}})</small>
                          </p>
                          <p
                            style="color: #00b3ca; font-family: Poppins;font-weight:400;margin: 0;"
                          >
                            ${{number_format($product->subPrice(), 2)}}
                          </p>
                        </td>
                        <td style="font-family: Poppins;font-weight: 700;text-align: center !important;">{{$product->pivot->product_quantity}}</td>
                        <td style="font-family: Poppins;font-weight: 700;text-align: center !important;">
                            {{number_format($product->subPrice() * $product->pivot->product_quantity, 2)}}
                        </td>
                      </tr>
                    @endforeach

                    <!-- Order coupon products -->
                    @foreach($orderCoupons as $coupon)
                      <tr><td style="text-align: left;font-weight: bold; color:#015289; font-family:helvetica neue,helvetica,arial,verdana,sans-serif;padding-top: 10px;">{{ucwords(trim($coupon['title']))}}</td></tr>
                      @foreach($coupon['ordered_coupon_products'] as $product)
                        <tr style="vertical-align: middle !important;text-align: center;">
                          <td style="text-align: left;width: 200px; font-weight: 700;">
                            <p style="font-family: Poppins;margin-bottom: 0;">
                              {{$product['desc']}}<small> ({{$product['size']}})</small>
                            </p>
                            <p
                              style="color: #00b3ca; font-family: Poppins;font-weight:400;margin: 0;"
                            >
                              ${{$product['unit_retail']}}
                            </p>
                          </td>
                          <td style="font-family: Poppins;font-weight: 700;text-align: center !important;">{{$product['quantity']}}</td>
                          <td style="font-family: Poppins;font-weight: 700;text-align: center !important;">
                              {{$product['price_text']}}
                          </td>
                        </tr>
                      @endforeach
                    @endforeach
                </tbody>
              </table>
            </div>
            <hr style="border:1px solid #eee" />
            <div style="display: block;width: 60%;margin-left: auto;">
              <table style="padding-right: 20px;width: 100%;">
                <tr>
                  <td
                    style="font-family: Poppins;font-size: 16px;font-weight: 500;margin:0 0 3px"
                  >
                    Estimated Bill
                  </td>
                  <td
                    style="font-family: Poppins;font-size: 16px;font-weight: 500;margin:0 0 3px;text-align: center;text-align: center;"
                  >
                    ${{ number_format($order->total_price,2) }}
                  </td>
                </tr>
                <tr>
                  <td
                    style="font-family: Poppins;font-size: 16px;font-weight: 500;margin:0 0 3px"
                  >
                    Order Date
                  </td>
                  <td
                    style="font-family: Poppins;font-size: 16px;font-weight: 500;margin:0 0 3px;text-align: center;text-align: center;"
                  >
                    {{$order->placeAt()}}
                  </td>
                </tr>
              </table>
            </div>
        </div>
    </div>
</div>
@endsection