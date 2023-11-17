@extends('layouts.admin.dashboard')

@section('admin-content')
<div id="main" role="main">
  <div class="container">
    @if (session('message'))
      <div id="flash-message" class="">
        {{ session('message') }}
      </div>
    @endif
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Massy Dashboard</div>

            <div class="card-body">
                <!-- @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in! -->
                <!-- <a href="{{-- route('testMLSB') --}}" class="btn btn-primary">Test MLSB</a>
                <a href="{{-- route('testCRONProduct') --}}" class="btn btn-warning">Test CRON Product</a> -->
                It is a beta version Massy CRM !! 
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
