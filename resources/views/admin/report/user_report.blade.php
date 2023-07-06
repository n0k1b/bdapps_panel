<?php
  $with_domain_status = 0;
  $user_id = Auth::guard('admin')->user()->id;
  $user_role = Auth::guard('admin')->user()->role;
  $role_id = DB::table('roles')->where('name',$user_role)->first()->id;
  $role_permission = DB::table('role_permisiions')->where('role_id',$role_id)->pluck('content_name')->toArray();
 // file_put_contents('role.txt',json_encode($role_permission));


?>
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
                            <h4>Order Report</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>

                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Order Report</a></li>
                        </ol>
                    </div>
                </div>

				<div class="row">

					<div class="col-lg-12">
						<div class="row tab-content">
							<div id="list-view" class="tab-pane fade active show col-lg-12">
								<div class="card">

									<div class="card-body">
										<div class="table-responsive">
											<table  id="order_report_table" class="display table table-bordered table-striped" style="min-width: 845px">
												<thead class="thead-dark">
													<tr>
														<th>#</th>
                                                        <th>User Name</th>
                                                        <th>Contact No</th>
														<th>Date</th>

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
  <script src="https://cdn.datatables.net/plug-ins/1.10.25/api/sum().js" type="text/javascript"></script>
{{-- <script>
    $("#example3").DataTable({
       ordering: false

   });
</script> --}}

<script type="text/javascript">
       $(function () {

var table = $('#order_report_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('show_user_report') }}",


    columns: [
        {data: 'sl_no', name: 'sl_no'},

        {data: 'name', name: 'name'},

        {data:'contact_no',name:'contact_no'},

        {data:'date' ,name:'date'}



    ]

});



});
  </script>
<script src="{{asset('assets')}}/admin/js/admin.js?{{time()}}"></script>
@endsection
