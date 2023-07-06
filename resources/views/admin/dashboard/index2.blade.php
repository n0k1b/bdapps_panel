@extends('admin.layout.app')

@section('content')

      <div class="container-fluid">

                <div class="row">
					<div class="col-xl-12 col-xxl-12 col-sm-12">
						<div class="row">
							<div class="col-xl-3 col-xxl-3 col-sm-3">
								<div class="widget-stat card">
									<div class="card-body">
										<h4 class="card-title">Total Cateogry</h4>
										<h3>{{$total_category}}</h3>
										<!--<div class="progress mb-2">-->
										<!--	<div class="progress-bar progress-animated bg-primary" style="width: 80%"></div>-->
										<!--</div>-->
										<!--<small>80% Increase in 20 Days</small>-->
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-xxl-3 col-sm-3">
								<div class="widget-stat card">
									<div class="card-body">
										<h4 class="card-title">Total Sub Cateogry</h4>
										<h3>{{$total_sub_category}}</h3>
										<!--<div class="progress mb-2">-->
										<!--	<div class="progress-bar progress-animated bg-warning" style="width: 50%"></div>-->
										<!--</div>-->
										<!--<small>50% Increase in 25 Days</small>-->
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-xxl-3 col-sm-3">
								<div class="widget-stat card">
									<div class="card-body">
										<h4 class="card-title">Total Products</h4>
										<h3>{{$total_product}}</h3>
										<!--<div class="progress mb-2">-->
										<!--	<div class="progress-bar progress-animated bg-red" style="width: 76%"></div>-->
										<!--</div>-->
										<!--<small>76% Increase in 20 Days</small>-->
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-xxl-3 col-sm-3">
								<div class="widget-stat card">
									<div class="card-body">
										<h4 class="card-title">Total Order</h4>
										<h3>{{$total_order}}</h3>
										<!--<div class="progress mb-2">-->
										<!--	<div class="progress-bar progress-animated bg-success" style="width: 30%"></div>-->
										<!--</div>-->
										<!--<small>30% Increase in 30 Days</small>-->
									</div>
								</div>
							</div>
						</div>
                    </div>







				</div>
            </div>

@endsection
@section('page_js')

@endsection
