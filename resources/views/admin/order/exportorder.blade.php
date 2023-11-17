@extends('layouts.admin.dashboard')

@section('admin-content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Export Order CSV</div>
        <div class="card-body">
          <form class="form-horizontal" action="{{ route('orders_export_csv') }}" method="post">
            @csrf
            <div class="row mb-3">
                <div class="col-md-5 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label" for="fromDate">From Date</label>
                    <div class="">
                      <input type="text" id="from" name="fromDate" class="form-control" placeholder="Select Order From Date" autocomplete="off" required />
                    </div>
                  </div>
                </div>
                <div class="col-md-5 pr-0">
                  <div class="form-group mb-0">
                    <label class="control-label " for="toDate">To Date</label>
                    <div class="">
                      <input type="text" id="to" name="toDate" class="form-control" placeholder="Select Order To Date" autocomplete="off" required />
                    </div>
                  </div>
                </div>
                <div class="col-md-1 d-flex align-items-end pl-3">
                  <div class="form-group mb-0">        
                    <div class="col-sm-offset-2 ">
                      <button type="submit" class="btn btn-primary" id="btnFormSubmit">Export</button>
                    </div>
                  </div>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
       $("#to").datepicker({ dateFormat: 'yy-mm-dd' });
       $("#from").datepicker({ dateFormat: 'yy-mm-dd' }).bind("change",function(){
           var minValue = $(this).val();
           minValue = $.datepicker.parseDate("yy-mm-dd", minValue);
           minValue.setDate(minValue.getDate()+1);
           $("#to").datepicker( "option", "minDate", minValue );
       })
   });
</script>
@endsection