@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
  <div class="row justify-content-center">
    @if (session('message'))
      <div id="flash-message" class="">
            {{ session('message') }}
      </div>
    @endif
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
        All Offers
        <a href="{{ route('offers.create') }}" class="btn btn-primary" style="float: right;">Add new</a>
      </div>
        <div class="card-body">
          <table class="offers_table">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Country Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Created On</th>
              <th>Action</th>
            </tr>
            <tbody>
              @forelse($offers as $offer)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $offer->name }}</td>
                  <td>{{ $offer->country->name }}</td>
                  <td>{{ $offer->start_date }}</td>
                  <td>{{ $offer->end_date }}</td>
                  <td>{{ $offer->created_at }}</td>
                  <td>
                    <form action="{{route('offers.destroy',$offer->id)}}" method="post">
                      @csrf
                      @method('DELETE')
                      
                      <a href="{{route('offers.show',$offer->id)}}" class="btn btn-info disabled">Details</a> |
                      <a href="{{route('offers.edit',$offer->id)}}" class="btn btn-success disabled">Edit</a> |
                      <button type="submit" class="btn btn-danger disabled">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td>No Record Found!</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection