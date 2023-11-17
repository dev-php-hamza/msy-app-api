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
    <p class="alert alert-danger" id="leastOne-error">Please enter UPC or Product name</p>
    <p class="alert alert-danger" id="country-error">Country is required</p>
    <div class="row justify-content-center">
      <div class="w-100">
        <div class="card">
          <div class="card-header">Search Product</div>
          <div class="card-body pr-5 pl-4">
            <form class="form-horizontal" action="{{ route('search_by_upc') }}" method="GET">
              <div class="row mb-3">
                <div class="col-md-6 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="upc">Upc:</label>
                    <div class="">
                      <input type="text" class="form-control" id="upc" name="upc" placeholder="Enter UPC" value="{{ (request()->get('upc') !== null)?request()->get('upc'): old('upc') }}">
                    </div>
                  </div>
                </div>
                <div class="col-md-6 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="prodName">Name</label>
                    <div class="">
                      <input type="text" class="form-control" id="prodName" name="prodName" placeholder="Enter Product Name" value="{{ (request()->get('prodName') !== null)?request()->get('prodName'): '' }}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="name">Country</label>
                    <div class="">
                      <select name="countryId" id="countries" class="form-control">
                        <option value="">Please Choose Country</option>
                        @foreach($countries as $key => $country)
                          <option value="{{ $country->id }}" {{ ($country->id == request()->get('countryId'))?'selected':''}}>{{ $country->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="image">Image</label>
                    <div class="">
                      <select name="image" id="image" class="form-control">
                          <option value="">Please Choose</option>
                          <option value="1" {{(isset($image) &&$image == '1')?'selected':''}}>Yes</option>
                          <option value="0" {{(isset($image) &&$image == '0')?'selected':''}}>No</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="searchable">Searchable</label>
                    <div class="">
                      <select name="searchable" id="searchable" class="form-control">
                          <option value="">Please Choose</option>
                          <option value="1" {{(isset($searchable) &&$searchable == '1')?'selected':''}}>Yes</option>
                          <option value="0" {{(isset($searchable) &&$searchable == '0')?'selected':''}}>No</option>
                      </select>
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
            <div class="found__product">
            </div>
            <div class="row error">
              <div class="col-md-12">
                <div class="alert alert-danger append-error-div fade show not__found" >
                    <strong>Error!</strong> Entered Upc or Name is not found in selected country. Try different UPC.
                </div>
              </div>
            </div>
          </div>
          <div class="products_table mr-2 ml-2">
            <table>
              <tr>
                <th>#</th>
                <th>Image</th>
                <th>upc</th>
                <th>Desc</th>
                <th>Size</th>
                <th>Item Packing</th>
                <th>Unit Retail</th>
                <th>Searchable</th>
                <th>Action</th>
              </tr>
              <tbody id="table-body">
                  @forelse($products as $key => $product)
                    <tr>
                      <td>{{ $loop->index + $products->firstItem() }}</td>
                      <td>
                          @if(request()->get('image') !== null && request()->get('image') == 1)
                            <div class="zoom">
                              @foreach($product->images as $key => $productImage)
                                    <img src="{{ $productImage->getImage($product->upc) }}" width="50" height="50" alt="productImage">
                                  @break
                              @endforeach
                            </div>
                          @else
                            <img src="{{asset('assets/images/no-image.png')}}" width="50" height="50" alt="productImage">
                          @endif
                      </td>
                      <td>{{ $product['upc'] }}</td>
                      <td>{{ $product['desc'] }}</td>
                      <td>{{ $product['size'] }}</td>
                      <td>{{ $product['item_packing'] }}</td>
                      <td>{{ $product['unit_retail'] }}</td>
                      <td>{{ ($product['is_searchable'] == 1)?'Yes':'No' }}</td>
                      <td>
                        <a href="{{ route('products.show', $product['id']) }}" class="btn btn-info"><i class="fa fa-info"></i></a> |
                        <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td>No record found!</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <br>
          <div class="pull-right" class="pagination">
            {{ $products->appends(\Request::except('_token'))->render() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
