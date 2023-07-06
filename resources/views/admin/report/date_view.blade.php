@extends('admin.layout.app')
@section("page_css")
<link rel="stylesheet" href="{{ asset('admin') }}/vendor/pickadate/themes/default.css">
<link rel="stylesheet" href="{{ asset('admin') }}/vendor/pickadate/themes/default.date.css">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7 mt-4" style="top: 90px;left:18%">
            <div class="card line-chart-example" >
              <div class="card-header d-flex align-items-center">
                <h4 style="align: center">Select Date</h4>
              </div>
           

              <form method="GET" action="">
                @csrf
              <div class="card-body">
                <p class="mb-1">From Date</p>
                <input name="from_date" class="datepicker-default form-control" id="datepicker">

                <br>
                <p class="mb-1">To Date</p>
                <input name="to_date" class="datepicker-default form-control" id="datepicker">
                <br>
                <button type="submit" class="btn btn-primary" style="float: right">Submit</button>
              </div>
              </form>
             

             



            </div>
          </div>

    </div>

</div>
@endsection

@section('page_js')
    <script src="{{ asset('admin') }}/vendor/pickadate/picker.js"></script>
    <script src="{{ asset('admin') }}/vendor/pickadate/picker.time.js"></script>
	<script src="{{ asset('admin') }}/vendor/pickadate/picker.date.js"></script>
    <script src="{{ asset('admin') }}/js/plugins-init/pickadate-init.js"></script>
    <script>
     $('.datepicker').pickadate({
  // Escape any “rule” characters with an exclamation mark (!).
  format: 'You selecte!d: dddd, dd mmm, yyyy',
  formatSubmit: 'yyyy-mm-dd',
  hiddenPrefix: 'prefix__',
  hiddenSuffix: '__suffix'
})
    </script>
@endsection
