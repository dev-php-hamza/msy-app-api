@extends('layouts.admin.dashboard')

@section('admin-content')
  <div class="container container-body">
    <div class="row justify-content-center">
      @if (session('message'))
        <div id="flash-message" class="mb-3">
          {{ session('message') }}
        </div>
      @endif
      
      @if( session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif
      <div class="">
        <div class="mb-3">
          {{-- <a href="{{ route('export_products_excel') }}" class="btn btn-primary">Export</a>
          <a href="{{ route('show_import_daily_products_form') }}" class="btn btn-primary">Import Daily Products</a>
          <a href="{{ route('show_import_products_form_via_sftp') }}" class="btn btn-primary">Import Daily Products Via SFTP</a> --}}
          <a href="{{ route('search_form') }}" class="btn btn-primary">Search Product</a>
        </div>
        <div class="card">
          <div class="card-header">
            All Products
            <a href="{{ route('products.create') }}" class="btn btn-primary" style="float: right;">Add New</a>
          </div>
          <div class="card-body">
            <div class="products_table">
              <table>
                <tr>
                  <th>#</th>
                  <th>Image</th>
                  <th>upc</th>
                  <th style="width: 200px;">Desc</th>
                  <th>Size</th>
                  <th>Item Packing</th>
                  <th>Unit Retail</th>
                  <th>Country</th>
                  <th>Searchable</th>
                  <th class="action-last">Action</th>
                </tr>
                <tbody id="table-body">
                    @forelse($products as $key => $product)
                      <tr>
                        <td>{{ $loop->index + $products->firstItem() }}</td>

                        <td>
                            @forelse($product->images as $key => $productImage)
                              <div class="zoom">
                                <img src="{{ $productImage->getImage($product->upc) }}" width="50" height="50" alt="productImage">
                              </div>
                              @break
                            @empty
                              <img src="{{asset('assets/images/no-image.png')}}" width="50" height="50" alt="productImage">
                            @endforelse
                        </td>
                        <td>{{ $product['upc'] }}</td>
                        <td>{{ $product['desc'] }}</td>
                        <td>{{ $product['size'] }}</td>
                        <td>{{ $product['item_packing'] }}</td>
                        <td>{{ $product['unit_retail'] }}</td>
                        <td>{{ $product->country->name }}</td>
                        <td>{{ ($product['is_searchable'] == 1)?'Yes':'No' }}</td>
                        <td>
                          <form action="{{ route('products.destroy', $product['id']) }}" method="POST">
                            <a href="{{ route('products.show', $product['id']) }}" class="btn btn-info"><i class="fa fa-info"></i></a> |
                            <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-success"><i class="fa fa-edit"></i></a> |
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
                          </form>
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
          </div>
          <div class="pull-right ml-3">
            {{ $products->appends(\Request::except('_token'))->render() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
