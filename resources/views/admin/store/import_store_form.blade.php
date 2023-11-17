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
          <div class="card-header">Import stores from Excel file</div>
          <div class="card-body">
            <form action="{{ route('import_stores_excel') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label class="control-label col-sm-2" for="import_file">Please Choose:</label>
                <div class="col-sm-10">
                  <input type="file" class="form-control" name="import_file" required>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
