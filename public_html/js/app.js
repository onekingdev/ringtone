$(document).ready(function () {

	$(".btn-select").click(function () {
	    $(".input-file").click();
	})
	$(".img-selector").change(function(){
	    readURL(this,"#img-preview");
	});
  $(".alert-dashborad .close").click(function() {
    $(".alert-dashborad").fadeOut();
  })

  $("#Slide_type").change(function() {
      if ($("#Slide_type").val()==1) {
        $("#Slide_url").parent().hide();
        $("#Slide_category").parent().show();
        $("#Slide_ringtone").parent().hide();
      }
      if ($("#Slide_type").val()==2) {
        $("#Slide_url").parent().show();
        $("#Slide_category").parent().hide();
        $("#Slide_ringtone").parent().hide();
      }
      if ($("#Slide_type").val()==3) {
        $("#Slide_url").parent().hide();
        $("#Slide_category").parent().hide();
        $("#Slide_ringtone").parent().show();
      }
  });
  $(".btn-select-1").click(function () {
      $(".input-file-1").click();
  })
  $(".img-selector-1").change(function(){
      readURL(this,"#img-preview-1");
  });

  $(".input-btn-3").click(function () {
      $(".input-file-3").click();
  })
});
function readURL(input,pr) {
    if (input.files && input.files[0]) {
    var countFiles = input.files.length;
     var imgPath = input.value;
     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

     if (extn == "png" || extn == "jpg" || extn == "jpeg") {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(pr).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }else{
          alert("the image file is not valid please select a valid image file : png,jpg or jpeg");
      }
    }
}


$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img style="width:210px;height:auto;display:inline-block;margin:15px"  class="thumbnail thumbnail-2" />')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#product_files').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
    
});
jQuery(function() {
  $(".img-thum-product").click(function() {
    $("#image_product_view").attr({"src":$(this).attr("data")});
    $(".img-thum-product").removeClass("img-thum-product-active");
    $(this).addClass("img-thum-product-active");
  });
    $(".button-file").change(function(){
        readURL(this);
    });
});
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.image-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img style="width:230px;height:230px;margin:10px;display:inline-block"  class="thumbnail thumbnail-2" >')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#Wallpaper_files').on('change', function() {
        $("div.gallery").html("");
        imagesPreview(this, 'div.gallery');
    });
    
});
$(document).ready(function () {


  $(".img-selector").change(function(){
      readURLL(this,"#img-preview");
  });
});
function play(element){
  if ( element.innerHTML=="play_circle_outline") {
      var audio = document.getElementById("audioplayer");
      audio.src=element.getAttribute("rel")
      audio.onloadstart = function() {
         audio.play();

      };
      audio.onplay =  function () {
         $(".player-control").html("play_circle_outline")
         element.innerHTML="pause_circle_outline";
      }
      audio.onpause = function() {
         $(".player-control").html("play_circle_outline")
         element.innerHTML="play_circle_outline";
      };

      element.innerHTML="watch_later";

  }else{
    var audio = document.getElementById("audioplayer");
      audio.pause();
      $(".player-control").html("play_circle_outline")
      element.innerHTML="play_circle_outline";
  }
}
function readURLL(input,pr) {
    if (input.files && input.files[0]) {
    var countFiles = input.files.length;
     var imgPath = input.value;
     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

     if (extn == "png" || extn == "jpg" || extn == "jpeg") {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(pr).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }else{
          alert("the image file is not valid please select a valid image file : png,jpg or jpeg");
      }
    }
}
