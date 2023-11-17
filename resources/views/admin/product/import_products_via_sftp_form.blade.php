@extends('layouts.admin.dashboard')

@section('admin-content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        @if(count($errors) > 0)
          <ul>
            @foreach($errors->all() as $error)
              <li class="alert alert-danger">{{$error}}</li>
            @endforeach
          </ul>
        @endif
        @if(session('excepMessage'))
          <div class="excepMessage">
            <p>{{ session('excepMessage') }}</p>
          </div>
        @endif

        @if(session('message'))
          <div class="message">
            <p>{{ session('message') }}</p>
          </div>
        @endif
        <div class="card">
          <div class="card-header">Import products Via SFTP</div>
          <div class="card-body">
            <form action="{{ route('import_products_via_sftp') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label class="control-label col-sm-2" for="name">Country</label>
                <div class="col-sm-10">
                  <select name="country_id" class="form-control" required>
                    <option value="">Please Choose Country</option>
                    @foreach($countries as $key => $country)
                      <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-primary" style="margin-left: 15px;">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
