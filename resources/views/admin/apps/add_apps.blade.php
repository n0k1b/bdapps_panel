@extends('admin.layout.app')
@section('page_css')
<link rel="stylesheet" href="{{asset('assets')}}/admin/css/image_preview.css?{{time()}}">
@endsection
@section('content')
<div class="container-fluid">
    @if (count($errors)>0)
    <div class="col-md-10 col-sm-10 col-10 offset-md-1 offset-sm-10 alert alert-danger" >
        <ul>
            @foreach($errors->all() as $error)
                <li style="display: list-item;list-style-type:disc">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

				<div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Add Apps</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>

                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Apps</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Add</a></li>
                        </ol>
                    </div>
                </div>

				<div class="row">
					<div class="col-xl-12 col-xxl-12 col-sm-12">
                        <div class="card">

							<div class="card-body">
                                <form action="{{route('add-apps')}}" method="post" enctype="multipart/form-data">
                                @csrf
									<div class="row">


									    <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label">App Name</label>
												<input type="text" class="form-control" name="app_name" >
											</div>
										</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label">App Type</label>
												<input type="text" class="form-control" name="app_type" >
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
@endsection
