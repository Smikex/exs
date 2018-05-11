// validation photo
(function($) {
    $.fn.checkFileType = function(options) {
        var defaults = {
            allowedExtensions: [],
            selector: $(this),
            success: function() {},
            error: function() {}
        };
        options = $.extend(defaults, options);

        return this.each(function() {

            $(this).on('change', function() {
                var value = $(this).val(),
                    file = value.toLowerCase(),
                    extension = file.substring(file.lastIndexOf('.') + 1);

                if ($.inArray(extension, options.allowedExtensions) == -1) {
                    options.error();
                    $(this).focus();
                } else {
                    options.success();

                }

            });

        });
    };

})(jQuery);
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	if($('.selectpicker').length > 0) {
		$('.selectpicker').selectpicker();
	}
    // ModalPhoto
	$('#photo').checkFileType({
        allowedExtensions: ['jpg', 'jpeg', 'png'],
        success: function() {
        	$('#verification .photo-upload .status .progress-wrap').css('display','block');
	  		$('#verification .photo-upload .status .finish').css('display','none');
		  	var width = 1;
		  	var id = setInterval(frame, 8);
		  	function frame() {
		    	if (width >= 100) {
		      		clearInterval(id);
		      		setTimeout(finish, 500);
			    } else {
					width++; 
					$('#verification .photo-upload .status .line').css('width',width+'%');
					$('#verification .photo-upload .status .counter').text(width);
			    }
		  	}
		  	function finish() {
		  		$('#verification .photo-upload .status .progress-wrap').css('display','none');
		  		$('#verification .photo-upload .status #progressModal').css('width','0');
		  		$('#verification .photo-upload .status .counter').text(0);
		  		$('#verification .photo-upload .status .finish').css('display','block');
		  	}
        },
        error: function() {
        	$(this).selector.val('');
        	alert('Неправильный тип файла');
        }
    });
    // lkPhoto
    $('#photoCard').checkFileType({
        allowedExtensions: ['jpg', 'jpeg', 'png'],
        success: function() {
            $('#photoWrap .status .progress-wrap').css('display','block');
            $('#photoWrap .status .finish').css('display','none');
            var width = 1;
            var id = setInterval(frame, 8);
            function frame() {
                if (width >= 100) {
                    clearInterval(id);
                    setTimeout(finish, 500);
                } else {
                    width++; 
                    $('#photoWrap .status .line').css('width',width+'%');
                    $('#photoWrap .status .counter').text(width);
                }
            }
            function finish() {
                $('#photoWrap .status .progress-wrap').css('display','none');
                $('#photoWrap .status #progressModal').css('width','0');
                $('#photoWrap .status .counter').text(0);
                $('#photoWrap .status .finish').css('display','block');
            }
        },
        error: function() {
            $(this).selector.val('');
            alert('Неправильный тип файла');
        }
    });
    
});
$(document).on('show.bs.modal', function (event) {
    if (!event.relatedTarget) {
        $('.modal').not(event.target).modal('hide');
    };
    if ($(event.relatedTarget).parents('.modal').length > 0) {
        $(event.relatedTarget).parents('.modal').modal('hide');
    };
});

$(document).on('shown.bs.modal', function (event) {
    if ($('body').hasClass('modal-open') == false) {
        $('body').addClass('modal-open');
    };
});
// FAQ acordion menu
$(document).on("click",".btns-wrap button.title-h1",function() {
    $(this).stop(true,true).toggleClass("close-caret");
    $(this).next().stop(true,true).slideToggle('fast');
});
$(document).on("click",".btns-wrap .list-titles button",function() {
    $('.btns-wrap .list-titles button').removeClass('active');
    $(this).addClass('active');
    if($(window).scrollTop() > 0) {
        $('html, body').animate({scrollTop:0},100);
    }
});
// $(window ).resize(function() {

// })