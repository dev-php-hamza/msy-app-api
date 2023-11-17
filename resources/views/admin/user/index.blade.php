@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container container-body">
    <div class="row justify-content-center">
      @if(session('error'))
        <ul style="list-style: none;">
          <li class="alert alert-danger">{{session('error')}}</li>
        </ul>
      @endif
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">All Users
                  <a href="{{ route('users_export') }}" class="btn btn-primary" style="float: right;">Export CSV</a>
                </div>
                <div class="card-body pr-5 pl-4">
                  <form class="form-horizontal" action="{{ route('users_search') }}" method="GET">
                    <div class="row mb-3 justify-content-center">
                      <div class="col-md-4 pr-0">
                        <div class="form-group mb-0">
                          <label class="control-label" for="searchTerm">Search</label>
                          <div class="">
                            <input type="text" class="form-control" id="searchTerm" name="term" placeholder="Search..." value="{{ old('term') }}" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 pr-0">
                        <div class="form-group mb-0">
                          <label class="control-label" for="filters">Filters</label>
                          <div class="">
                            <select name="type" id="filters" class="form-control" required>
                              <option value="">Please Choose</option>
                              <option value="name">Name</option>
                              <option value="email">Email</option>
                              <option value="phone">Phone</option>
                            </select>
                          </div>
                          
                        </div>
                      </div>
                      <div class="col-md-1 d-flex align-items-end pl-4">
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
                          <strong>Error!</strong> Entered or Name or Email or Phone is not found. Try different UPC.
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="user_table">
                    <table>
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile</th>
                          <th>Role</th>
                          <th>City</th>
                          <th>Country</th>
                          <th>created at</th>
                          <th class="action-last">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($users as $user)
                          <tr>
                            <td>{{ $loop->index + $users->firstItem() }}</td>
                            <td>{{ $user->fullName() }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ (isset($user->userInfo->phone_number) && $user->userInfo->phone_number !='' ) ? $user->userInfo->phone_number:'-' }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>{{ isset($user->userInfo->city) ? $user->userInfo->city :'-' }}</td>
                            <td>{{ isset($user->userInfo->country)?$user->userInfo->country : '-' }}</td>
                            <td>{{ date('d-M-y', strtotime($user->created_at)) }}</td>
                            <td> 
                              <form action="{{ route('users.destroy',['userId'=>$user->id]) }}" method="POST">
                                <a href="{{route('users.show',$user->id)}}" class="btn btn-info"><i class="fa fa-info"></i></a> |
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash-alt"></i></button>
                              </form>
                            </td>
                          </tr>
                        @empty
                          <tr><td>No Record found!</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="pull-right ml-3">
                  {{ $users->appends(\Request::except('_token'))->render() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
