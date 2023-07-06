$(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        fetch_category();


        $('.category').on('change', function() {

            var category_id = this.value;
            var formdata = new FormData();
            formdata.append('category_id', category_id);

            $.ajax({
                processData: false,
                contentType: false,
                type: 'post',
                data: formdata,
                url: 'get_sub_category',
                success: function(data) {


                    $('.sub_category').html(data);


                }
            })

        });

        $('.sub_category').on('change', function() {

            var sub_category_id = this.value;
            var formdata = new FormData();
            formdata.append('sub_category_id', sub_category_id);

            $.ajax({
                processData: false,
                contentType: false,
                type: 'post',
                data: formdata,
                url: 'get_brand',
                success: function(data) {

                    $('.brand').html(data);


                }
            })

        });




        $('#category').on('change', function() {

            var category_id = this.value;
            var formdata = new FormData();
            formdata.append('category_id', category_id);

            $.ajax({
                processData: false,
                contentType: false,
                type: 'post',
                data: formdata,
                url: 'get_sub_category',
                success: function(data) {

                    $('#sub_category').html(data);



                }
            })

        });

        $('#sub_category').on('change', function() {

            var sub_category_id = this.value;
            var formdata = new FormData();
            formdata.append('sub_category_id', sub_category_id);

            $.ajax({
                processData: false,
                contentType: false,
                type: 'post',
                data: formdata,
                url: 'get_brand',
                success: function(data) {

                    $('#brand').html(data);


                }
            })

        });


    })
    //domain start
function domain_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'domain_active_status_update/' + id,
        success: function(data) {


        }
    })
}



function domain_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'domain_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}
//domain end

//category start
function category_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'category_active_status_update/' + id,
        success: function(data) {


        }
    })
}

function category_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'category_content_delete/' + id,
            success: function(data) {
                var msg  = $.trim(data);
                if(msg=='sub_category_exist')
                {
                    alert('Sub category exist. Please delete sub category first')
                }
                else if(msg == 'product_exist')
                {
                    alert('Product Exist. Please delete product first first')
                }
                else{
                alert('Content Delete Successfully')
                location.reload();
                }

            }
        })
    }
}
//category end


//category start with domain
function category_wtih_domain_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'category_with_domain_active_status_update/' + id,
        success: function(data) {


        }
    })
}

function category_wtih_domain_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'category_with_domain_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}
//category end with domain


//sub category start

function sub_category_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'sub_category_active_status_update/' + id,
        success: function(data) {


        }
    })
}

function sub_category_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'sub_category_content_delete/' + id,
            success: function(data) {
                var msg  = $.trim(data);
                alert(msg);

                // if(msg == 'product_exist')
                // {
                //     alert('Product Exist. Please delete product first first')
                // }
                // else{
                // alert('Content Delete Successfully')
                // location.reload();
                // }

            }
        })
    }
}

//warehouse start

//warehouse end
//sub category end
function warehouse_product_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'warehouse_product_active_status/' + id,
        success: function(data) {


        }
    })
}

//product start

function fetch_product()
{
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'get_all_product',
        success: function(data) {

            $('#product_list').html(data);


        }
    })
}
function fetch_category() {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'get_category',
        success: function(data) {

            $('#category').html(data);

        }
    })
}

function product_field_mandatory_alert() {
    alert('This field is mandatory');
}

function product_field_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'product_required_field_active_status_update/' + id,
        success: function(data) {


        }
    })
}

function product_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'product_active_status_update/' + id,
        success: function(data) {


        }
    })
}

function product_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'product_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}


//product end

//brand start

function brand_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'brand_active_status_update/' + id,
        success: function(data) {


        }
    })
}

function brand_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'brand_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}

//brand end


//product modal start

function edit(product_id, column_name) {

    var formdata = new FormData();
    formdata.append('product_id', product_id);
    formdata.append('column_name', column_name);


    $.ajax({
        processData: false,
        contentType: false,
        type: 'post',
        data: formdata,
        url: 'get_product_update_modal',
        success: function(data) {

            $("#product_update_modal").modal('show');
            $('#product_content').html(data);


        }
    })
}

function  update_product_value()
{

   var column_name = $("#column_name").val();
   var input_value =  $("#value").val();
   var product_id = $("#product_id").val();
   var formdata = new FormData();
   formdata.append('product_id', product_id);
   formdata.append('column_name', column_name);
   formdata.append('input_value', input_value);

    if(column_name =='category')
    {

        formdata.append('category', $('.category').val());
        formdata.append('sub_category', $('.sub_category').val());

    }
    if(column_name == 'sub_category')
    {
        formdata.append('sub_category', $('.sub_category').val());

    }

    if(column_name == 'brand_category')
    {

        formdata.append('brand_id', $('.brand').val());
    }

    if(column_name == 'product_image')
    {
        formdata.append('thumbnail_image',$('#files')[0].files[0]);
    }
    if(column_name =='warehouse')
    {

        formdata.append('warehouse_id', $('.warehouse_id').val());

    }






   $.ajax({
    processData: false,
    contentType: false,
    type: 'post',
    data: formdata,
    url: 'update_product_value',
    success: function(data) {
       // $("#product_update_modal").modal('hide');

            $('#product_update_modal').modal('hide');
            $('#product').DataTable().ajax.reload(null,false);
            // $('#product').DataTable();
            // $('.dataTables_length').addClass('bs-select');
            // let pageItems = $('#product_wrapper').find('.page-item');
            // pageItems.last().prev().click();
            alert('Data Updated');


    }
})


}


//product modal end


function homepage_section_active_status(id)
{
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'homepage-section_active_status_update/' + id,
        success: function(data) {


        }
    })
}


function homepage_section_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'homepage-section_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}

//banner start

function banner_active_status(id)
{
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'banner_active_status_update/' + id,
        success: function(data) {


        }
    })
}


function banner_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'banner_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}


//banner end

//warehouse start
function warehouse_active_status(id)
{

    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'warehouse_active_status_update/' + id,
        success: function(data) {


        }
    })
}


function warehouse_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'warehouse_content_delete/' + id,
            success: function(data) {
                var msg = $.trim(data);
                if(msg == 'warehouse_exist')
                {
                    alert('Product exist in this warehouse. Please delete warehouse product first');
                }
                else{
                alert('Content Delete Successfully')
                location.reload();
                }

            }
        })
    }
}
//warehouse end

//Area start
function area_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'area_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}
//Area end

function deposit_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'deposit_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}


function accept_deposit_payment(id)
{
    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'accept_deposit_payment/' + id,
            success: function(data) {
                alert('Payment Received Successfully')
                location.reload();

            }
        })
    }
}

//couruer_man_start

function courier_man_active_status(id)
{

    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'courier_man_active_status/' + id,
        success: function(data) {


        }
    })
}

function access_alert()
{
    alert('You do not have access of this action');
}

function user_active_status(id)
{

    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'user_active_status/' + id,
        success: function(data) {


        }
    })
}

function courier_man_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'courier_man_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}


function user_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'user_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}


function role_content_delete(id) {

    var conf = confirm('Are you sure?');

    if (conf == true) {
        $.ajax({
            processData: false,
            contentType: false,
            type: 'GET',
            url: 'role_content_delete/' + id,
            success: function(data) {
                alert('Content Delete Successfully')
                location.reload();

            }
        })
    }
}
//couruer_man_end
