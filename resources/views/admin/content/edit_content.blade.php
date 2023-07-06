@extends('admin.layout.app')
@section('content')
<div class="container-fluid">

				<div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Edit Apps</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Content</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit</a></li>
                        </ol>
                    </div>
                </div>

				<div class="row">
					<div class="col-xl-12 col-xxl-12 col-sm-12">
                        <div class="card">

							<div class="card-body">
                                <form action="{{route('update_content')}}" method="post" enctype="multipart/form-data">
                                @csrf
									<div class="row">
										

                                        <div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label">Edit Content</label>
                                                <input type="hidden" name='id'  value="{{ $data->id }}">
                                                <textarea class="form-control cont" id="content" rows="6" cols="8" placeholder="Enter Content" name="content">{{$data->content}}</textarea>
                                               
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
