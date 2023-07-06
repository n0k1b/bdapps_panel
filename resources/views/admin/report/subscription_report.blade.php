
@extends('admin.layout.app')
@section('page_css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    ::selection {
	color: black;
    background: none;
	
}
</style>
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
                            <h4>Subscription Report</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>

                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Subscription Report</a></li>
                        </ol>
                    </div>
                </div>

                
              <div class="row page-titles" style="margin-left:10px;margin-top:20px">
                <div class="col-md-3">
                    <div class="date_picker_pair mb-3">
                        <label for="inputSearchDate" class="form-label">Select Date</label>
                        <input type="text" class="form-control" name="daterange" id="inputSearchDate" value="01/01/2018 - 01/15/2018">
                        <input type="hidden" class='start_date' name='start_date'>
                        <input type="hidden" class='end_date' name="end_date">

                        <!-- <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" /> -->
                      </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-row align-items-center offer_select_option">
                        <label for="inlineFormCustomSelect" style="margin-bottom:14px">Select Type</label>

                        <select class="custom-select" id="type" name="type">
                          <option value="all">All</option>
                          <option value="Subscriber">Subscriber</option>
                          <option value="Unsubscriber">Unsubscriber</option>
                          <option value="Pending Charge">Pending Charge</option>
                          

                        </select>
                      </div>
                  </div>
                  <div class="col-md-3" style="margin-left:15px">
                    <div class="form-row align-items-center offer_select_option">
                        <label for="inlineFormCustomSelect" style="margin-bottom:14px">Choose Apps</label>

                        <select  data-placeholder="Select an Option"  class="custom-select" id="app_id" name="app_id">
                            <option></option>
                            <option value="all"> All</option>
                         @foreach ( $apps as $data )
                             <option value="{{ $data->id }}">{{ $data->app_name}}</option>
                         @endforeach

                        </select>
                      </div>
                  </div>
                 
                  <div class="col-md-2">
                    <input type="button"  onclick="filter()" value="Search" class="btn btn-success" style="margin-top:30px">
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
														<th>App Name</th>
														<th>Masking No</th>
														<th>Subscription Status</th>
                                                        <th>Date</th>

                                    
													</tr>
												</thead>
												<tbody>

												</tbody>
                                                <tfoot class="thead-dark" style="background-color: black" >
                                                    <tr>
                                                        <th></th>
                                                        <th scope="col"></th>
                                                        <th scope="col"></th>
                                                        <th scope="col"></th>
                                                        <th scope="col"></th>
                                                        
                            
                            
                                                      </tr>
                            
                                                </tfoot>
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
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.25/api/sum().js" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script>
    $("#example3").DataTable({
       ordering: false

   });
</script> --}}


<script type="text/javascript">

    $(function () {

        $('#app_id').select2({

placeholder: function(){
    $(this).data('placeholder');
}

});
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    //   var table = $('#order_report_table').DataTable({
    //       processing: true,
    //       serverSide: true,
    //       ajax: "{{ route('show_subscription_report') }}",

    //       columns: [
    //           {data: 'sl_no', name: 'sl_no'},

    //           {data:'app_name',name:'app_name'},

    //         {

    //             data: 'mask_no',
    //             name: 'mask_no',


    //         },
    //         {
    //             data:'subscription_status',
    //             name:'subscription_status',
    //         },


    //       ]
    //   });
var start = moment().subtract(29, 'days');
  var end = moment();
  $(".start_date").val(start.format('YYYY-MM-DD'));
  $(".end_date").val(end.format('YYYY-MM-DD'));
  fetch_table($(".start_date").val(),$(".end_date").val());

  $('input[name="daterange"]').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
  }, function(start, end, label) {
     // alert('hello')

     $(".start_date").val(start.format('YYYY-MM-DD'));
     $(".end_date").val(end.format('YYYY-MM-DD'));

    //fetch_table($(".start_date").val(),$(".end_date").val());


   // $('.invoice_table').DataTable().ajax.reload(null,false);
    // $("#start_date").val(start.format('YYYY-MM-DD'));
    // $("#end_date").val(end.format('YYYY-MM-DD'));
    //   var url = '/recharge/filebydate/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD');
      //window.location = url;
       //console.log('/filebydate/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
    //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });

    });

    function fetch_table(start_date,end_date)
{
//console.log('hello')
  //  var user_role = $("#user_role").val();

    var table = $('#order_report_table').DataTable();
    //console.log(table.column( 5 ).data().sum());
    table.destroy();

    var table = $('#order_report_table').DataTable({

        processing: true,
        serverSide: true,

        ordering:false,
        searchPanes: {
            orderable: false
        },
        dom: 'Plfrtip',
        language: {
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
        },
        columnDefs: [
    { "orderable": false, "targets": "_all" } // Applies the option to all columns
  ],
        ajax: {

            "url":'show_subscription_report',
            "type":'POST',
            dataSrc: function ( data ) {
                //console.log(data);
                subscriber = data.data[0].subscriber;
                unsubscriber = data.data[0].unsubscriber;
                pending_charge = data.data[0].pending_charge;
                 return data.data;
         } ,
            "data":{
                'start_date':$(".start_date").val(),
                'end_date':$(".end_date").val(),
                'app_id':$('#app_id option:selected').val(),
                'type':$('#type option:selected').val()

            }


            },
        deferRender: true,
        columns: [
            {data: 'sl_no'},
            {data:'app_name',name:'app_name'},
            {data:'mask_no',name:'mask_no'},
            {data:'subscription_status',name:'subscription_status'},
            {data:'date',name:'date'}
            

  ],


  drawCallback: function () {
            var api = this.api();

        $( api.column( 1 ).footer() ).html(
            'Subscriber = '+subscriber
            );
            $( api.column( 2 ).footer() ).html(
                'Unsubscriber = '+unsubscriber
            );
            $( api.column( 3 ).footer() ).html(
                'Pending Charge = '+pending_charge
            );
            //datatable_sum(api, false);
        }


    });
    // function datatable_sum(dt_selector, is_calling_first) {


    //     //col start from 0
    // 
    //     $( dt_selector.column(5).footer() ).html(dt_selector.column( 5, {page:'all'} ).data().sum().toFixed(2));
    //     $( dt_selector.column(6).footer() ).html(dt_selector.column( 6, {page:'all'} ).data().sum().toFixed(2));
    //  
    //     $( dt_selector.column(4).footer() ).html(dt_selector.column( 4, {page:'all'} ).data().sum().toFixed(2));
    //     $( dt_selector.column(5).footer() ).html(dt_selector.column( 5, {page:'all'} ).data().sum().toFixed(2));
    //    


    // }

}
function filter()
{

    fetch_table($(".start_date").val(),$(".end_date").val())
}
    
  </script>
<script src="{{asset('assets')}}/admin/js/admin.js?{{time()}}"></script>
@endsection
