@extends('admin.layout.app')
@section('page_css')
<link rel="stylesheet" href="{{asset('assets')}}/admin/css/image_preview.css?{{time()}}">
<link rel="stylesheet" href="{{asset('assets')}}/admin/css/select2.min.css?{{time()}}" />
<link rel="stylesheet" href="{{asset('assets')}}/admin/css/select2_custom.css?{{time()}}" />
@endsection
@section('content')
<div class="container-fluid">

				<div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Add User</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>

                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Add Courier Man</a></li>
                        </ol>
                    </div>
                </div>

				<div class="row">
					<div class="col-xl-12 col-xxl-12 col-sm-12">
                        <div class="card">

							<div class="card-body">
                                <form action="{{route('add-user')}}" method="post" enctype="multipart/form-data">
                                @csrf
									<div class="row">


									 <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label">Name</label>
												<input type="text" class="form-control" name="name">
											</div>
									    </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label">Mobile</label>
												<input type="text" class="form-control" name="contact_no">
											</div>
									    </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label">Email</label>
												<input type="text" class="form-control" name="email">
											</div>
									    </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
													<label>Role</label>
													<select class="form-control select2" id="sel1" name="role">
                                                        <option selected disabled>Select Role</option>
														@foreach($datas as $data)
														<option value="{{$data->name}}">{{$data->name}}</option>
													   @endforeach
													</select>
											</div>
										</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label">Default Password</label>
												<input type="text" class="form-control" name="password">
											</div>
									    </div>















										<div class="col-lg-12 col-md-12 col-sm-12">
											<button type="submit" class="btn btn-primary">Submit</button>
											<button type="submit" class="btn btn-light">Cancel</button>
										</div>
									</div>
								</form>
                            </div>
                        </div>
                    </div>
				</div>

            </div>
@endsection

@section('page_js')
<script src="{{asset('assets')}}/admin/js/single_image_preview.js?{{time()}}"></script>
<script src="{{asset('assets')}}/admin/js/select2.full.js"></script>
<script src="{{asset('assets')}}/admin/js/advanced-form-element.js"></script>
@endsection
