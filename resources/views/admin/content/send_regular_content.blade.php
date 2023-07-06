@extends('admin.layout.app') @section('page_css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
@endsection @section('content')

<div class="container-fluid">
	@if(Session::has('success'))
	<div class="col-md-10 col-sm-10 col-10 offset-md-1 offset-sm-10 alert alert-success">
		{{Session::get('success')}}
	</div>
	@endif @if ($errors->any())
	<div class="col-md-10 col-sm-10 col-10 offset-md-1 offset-sm-10 alert alert-danger">
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
				<h4>Instant Content</h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>

				<li class="breadcrumb-item active"><a href="javascript:void(0);">Content</a></li>
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
								<section class="content">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-2">
													<input type="hidden" id="app_id" value="{{$app_id}}">
													{{-- <select class="form-control select2" style="width: 100%;" id="content_type" name="class">
														<option selected="selected">Content Type</option>
														@foreach($content_types as $content_type)

														<option value="{{$content_type->app_type}}" name="{{$content_type->app_type}}">{{$content_type->app_type}}</option>
														@endforeach
													</select> --}}
												</div>
											</div>
											<br />

											<!-- /.box-header -->

											<div class="row">
												<div class="col-md-12">
													<!--<textarea  class="form-control" id="content"  rows="6" cols="8" placeholder="Enter Content"></textarea>-->

													<table class="table table-bordered table-striped" id="tb">
														<tr>
															<th>Content</th>

															
															
															<!--<th><a href="javascript:void(0);" style="font-size:18px;" id="addMore" title="Add More Person"><span class="glyphicon glyphicon-plus"></span></a></th>-->
														</tr>

														<tr>
															<td width="70%"><textarea class="form-control cont" id="content" rows="6" cols="8" placeholder="Enter Content"></textarea></td>



															
															
														</tr>

														
													</table>
													<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
													<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
													

													<br />

													<br />
													<div class="row">
														<div class="col-md-12" style="text-align:center;">
															<button type="button" onClick="submit()" class="btn btn-primary">Send</button>
														</div>
													</div>
												</div>
											</div>

											<!-- /.box -->
										</div>
										<!-- /.col-->
									</div>

									<!-- ./row -->
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection @section('page_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
{{--
<script>
	 $("#example3").DataTable({
	    ordering: false

	});
</script>
--}}
<script type="text/javascript">
       
    $('#datepicker').datepicker({
 autoclose: true,
 todayHighlight: true
}).datepicker('update', new Date())
  </script>
  <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script>
    
    function readURL(input) {
if (input.files && input.files[0]) {

 var reader = new FileReader();

 reader.onload = function(e) {
   $('.image-upload-wrap').hide();

   $('.file-upload-image').attr('src', e.target.result);
   $('.file-upload-content').show();

   $('.image-title').html(input.files[0].name);
 };

 reader.readAsDataURL(input.files[0]);

} else {
 removeUpload();
}
}

function removeUpload() {
$('.file-upload-input').replaceWith($('.file-upload-input').clone());
$('.file-upload-content').hide();
$('.image-upload-wrap').show();
}
$('.image-upload-wrap').bind('dragover', function () {
 $('.image-upload-wrap').addClass('image-dropping');
});
$('.image-upload-wrap').bind('dragleave', function () {
 $('.image-upload-wrap').removeClass('image-dropping');
});


 function submit()
 {
         var content = [];            
     $('.cont').each(function(){
         var a = $(this).val().replace(new RegExp(',','g'),"");
         a = a.trim();
         // a = a.replace(',','-');
       
         content.push(a);
     });
     //content.replace(',','-');
     
     
      var date = [];
      $('input[name^=date]').each(function(){
         date.push($(this).val());
     });


     
     
        
        // alert(content);
         
         
         var formData= new FormData();
         formData.append("content",content);


         formData.append('app_id',$("#app_id").val());
        
         
         
         
         
                   $.ajax({
             processData: false,
             contentType: false,
             url:"{{url('save_content_regular')}}",
             type:'POST',
             data: formData,
             success:function(data, status){
         
                 alert("Content Added Successfully");
         
               location.reload();
         
             },
         
         });


 }


        function send_notice()
{


  var content = document.getElementById("content").value.trim();
 var title = $("#title").val();
 var date = $("#date").val();
 var time = $("#time").val();

  

 

 
  
 
 var formData= new FormData();
 formData.append("title",title);
 formData.append("content",content);
formData.append('file',$('#upload')[0].files[0]);
formData.append("date",date);
formData.append("time",time);

 





 $.ajax({
   processData: false,
   contentType: false,
   url:"{{url('submit_international_news')}}",
   type:'POST',
   data: formData,
   success:function(data, status){

  alert("Content Create Successfully");
  location.reload();
},



 });

// $('#present').css('color', 'green');

}
    </script>



    
    
    <script>
     $(function () {



$.ajaxSetup({

     headers: {

         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

     }

 });




$('#class_name').on('change',function(){
     var class_name = $(this).val();

var formData= new FormData();
formData.append('class',class_name);



     if(class_name){
         $.ajax({
           processData: false,
contentType: false,
             type:'POST',
             url:'{{url('getSection')}}',
            data: formData,
             success:function(data,status){
                  $('#section').html(data);

                // alert(data);


                 

                 
             }
         }); 
     }
 });
         

         
         
       // Replace the <textarea id="editor1"> with a CKEditor
       // instance, using default configuration.
       CKEDITOR.replace('editor1')
       //bootstrap WYSIHTML5 - text editor
       $('.textarea').wysihtml5()
     })
   </script>

<script src="{{asset('assets')}}/admin/js/admin.js?{{time()}}"></script>
@endsection
