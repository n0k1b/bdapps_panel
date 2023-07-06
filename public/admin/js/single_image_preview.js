$(document).ready(function() {

    if (window.File && window.FileList && window.FileReader) {
        $("#files").on("change", function(e) {


            var files = e.target.files,
                filesLength = files.length;

            var f = files[0];
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var file = e.target;
                if (!$(".pip").length) {
                    $("<span class=\"pip\">" +
                        "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                        "<br/><span class=\"remove\">Remove image</span>" +
                        "</span>").insertAfter("#files");
                }
                $(".remove").click(function() {
                    $(this).parent(".pip").remove();
                    $("#files").val(null);
                });

                // Old code here
                /*$("<img></img>", {
                  class: "imageThumb",
                  src: e.target.result,
                  title: file.name + " | Click to remove"
                }).insertAfter("#files").click(function(){$(this).remove();});*/

            });
            fileReader.readAsDataURL(f);

        });

        $("#files2").on("change", function(e) {


            var files = e.target.files,
                filesLength = files.length;

            var f = files[0];
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var file = e.target;
                if (!$(".pip2").length) {
                    $("<span class=\"pip2\">" +
                        "<img class=\"imageThumb2\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                        "<br/><span class=\"remove2\">Remove image</span>" +
                        "</span>").insertAfter("#files2");
                }
                $(".remove2").click(function() {
                    $(this).parent(".pip2").remove();
                    $("#files2").val(null);
                });

                // Old code here
                /*$("<img></img>", {
                  class: "imageThumb",
                  src: e.target.result,
                  title: file.name + " | Click to remove"
                }).insertAfter("#files").click(function(){$(this).remove();});*/

            });
            fileReader.readAsDataURL(f);

        });


        $("#files3").on("change", function(e) {


            var files = e.target.files,
                filesLength = files.length;

            var f = files[0];
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var file = e.target;
                if (!$(".pip3").length) {
                    $("<span class=\"pip3\">" +
                        "<img class=\"imageThumb3\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                        "<br/><span class=\"remove3\">Remove image</span>" +
                        "</span>").insertAfter("#files3");
                }
                $(".remove3").click(function() {
                    $(this).parent(".pip3").remove();
                    $("#files3").val(null);
                });

                // Old code here
                /*$("<img></img>", {
                  class: "imageThumb",
                  src: e.target.result,
                  title: file.name + " | Click to remove"
                }).insertAfter("#files").click(function(){$(this).remove();});*/

            });
            fileReader.readAsDataURL(f);

        });

    } else {
        alert("Your browser doesn't support to File API")
    }
});
