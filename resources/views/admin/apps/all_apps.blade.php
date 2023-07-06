
@extends('admin.layout.app')
@section('page_css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')



<div class="container-fluid">
@if(Session::has('success'))
    <div class="col-md-10 col-sm-10 col-10 offset-md-1 offset-sm-10 alert alert-success" >

        {{Session::get('success')}}

        </div>
    @endif

    @if ($errors->any())
            <div class="col-md-10 col-sm-10 col-10 offset-md-1 offset-sm-10 alert alert-danger" >
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
     @endif
				<div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>All Apps</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>

                            <li class="breadcrumb-item active"><a href="javascript:void(0);">App List</a></li>
                        </ol>
                    </div>
                </div>

				<div class="row">

					<div class="col-lg-12">
						<div class="row tab-content">
							<div id="list-view" class="tab-pane fade active show col-lg-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title"></h4>
                                        <p>Send Otp Parameter: mobile (mobile number format: 0181***)</p>
                                        <p>Verify Otp Parameter:otp,reference_no</p>
										<a href="{{ route('add-apps') }}" class="btn btn-primary">+ Add new</a>
                                       
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table id="apps_table" class="display" style="min-width: 845px">
												<thead>
													<tr>
														<th>#</th>
														<th>App Name</th>


														<th>App Id</th>

														<th>App Password</th>

                                                        <th>App Type</th>
                                                        <th>Sms Connection URL</th>
                                                        <th>USSD Connection URL</th>
                                                        <th>Subscription Notification URL</th>
                                                        <th>Send Otp Endpoint</th>
                                                        <th>Verify Otp Endpoint</th>
                                                        <th>Action</th>
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
										</div>
									</div>
                                </div>
                            </div>

						</div>
					</div>
				</div>

            </div>

@endsection
@section('page_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
{{-- <script>
    $("#example3").DataTable({
       ordering: false

   });
</script> --}}

<script type="text/javascript">
    $(function () {

      var table = $('#apps_table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('show-all-apps') }}",
          columns: [
              {data: 'sl_no', name: 'sl_no'},

              {data:'app_name',name:'app_name'},
            
              {data:'app_id',name:'app_id'},

              {data:'app_password',name:'app_password'},
              {data:'app_type',name:'app_type'},
              {data:'sms_url',name:'sms_url'},
              {data:'ussd_url',name:'ussd_url'},
              {data:'subscription_notification_url',name:'subscription_notification_url'},
              {data:'send_otp_endpoint',name:'send_otp_endpoint'},
              {data:'verify_otp_endpoint',name:'verify_otp_endpoint'},
              {data:'action',name:'action'},

           
          ]
      });

    });
  </script>
  <script>
    function app_delete(id) {

var conf = confirm('Are you sure?');

if (conf == true) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'app_delete/' + id,
        success: function(data) {
            alert('Apps Delete Successfully')
            location.reload();

        }
    })
}
}
  </script>
<script src="{{asset('assets')}}/admin/js/admin.js?{{time()}}"></script?>
@endsection
