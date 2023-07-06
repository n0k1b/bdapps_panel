@extends('admin.layout.app')
@section("page_css")
<link rel="stylesheet" href="{{asset('admin')}}/css/select2.min.css?{{time()}}" />
<link rel="stylesheet" href="{{asset('admin')}}/css/select2_custom.css?{{time()}}" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
       


          <div class="col-md-7 mt-4" style="top: 90px;left:18%">
            <div class="card line-chart-example" >
              <div class="card-header d-flex align-items-center">
                <h4 style="align: center">Select App</h4>
              </div>
           

              <form method="POST" action="{{route('app_type_submit_regular_content')}}">
                @csrf
                <div class="form-group" style="padding: 18px">
                  
                  <select class="form-control select2" id="sel1" name="app_id">
                  <option selected disabled>Select Option</option>
                    @foreach($datas as $data)
                    <option value="{{$data->id}}">{{$data->app_name.'('.$data->app_id.')'}}</option>
                     @endforeach
                  </select>
              </div>

              <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 10px; float: right;">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="submit" class="btn btn-light">Cancel</button>
              </div>
              </form>
             

             



            </div>
          </div>

    </div>

</div>
@endsection

@section('page_js')
<script src="{{asset('admin')}}/js/select2.full.js"></script>
<script src="{{asset('admin')}}/js/advanced-form-element.js"></script>
@endsection
