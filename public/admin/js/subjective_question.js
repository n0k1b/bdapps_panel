$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    fetch_chapter();

    $('.chapter').on('change', function() {

        var chapter_id = this.value;
        var formdata = new FormData();
        formdata.append('chapter_id',chapter_id);
    
    $.ajax({
        processData: false,
        contentType: false,
        type: 'post',
        data:formdata,
        url: 'get_topic',
        success: function (data) {
          
           $('.topic').html(data);
      
            
        }
    })
        
     });
    
})

function fetch_chapter()
{
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'get_chapter',
        success: function (data) {
           
           

            $('.chapter').html(data);
    
        }
    })
}

