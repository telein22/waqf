/*
Template: SocialV - Responsive Bootstrap 4 Admin Dashboard Template
Author: iqonicthemes.in
Design and Developed by: iqonicthemes.in
NOTE: This file contains the styling for responsive Template.
*/

/*----------------------------------------------
Index Of Script
------------------------------------------------

:: Tooltip
:: Sidebar Widget
:: Magnific Popup
:: Ripple Effect
:: Page faq
:: Page Loader
:: Owl Carousel
:: Select input
:: Search input
:: Scrollbar
:: Counter
:: slick
:: Progress Bar
:: Page Menu
:: Page Loader
:: Wow Animation
:: Mail Inbox
:: Chat
:: Todo
:: Form Validation
:: Sidebar Widget
:: Flatpicker

------------------------------------------------
Index Of Script
----------------------------------------------*/

(function(jQuery) {



    "use strict";

    jQuery(document).ready(function() {

        /*---------------------------------------------------------------------
        Tooltip
        -----------------------------------------------------------------------*/
        jQuery('[data-toggle="popover"]').popover();
        jQuery('[data-toggle="tooltip"]').tooltip();

       

        /*---------------------------------------------------------------------
        Magnific Popup
        -----------------------------------------------------------------------*/
        jQuery('.popup-gallery').magnificPopup({
            delegate: 'a.popup-img',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(item) {
                    return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
                }
            }
        });
        jQuery('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });


        /*---------------------------------------------------------------------
        Ripple Effect
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', ".iq-waves-effect", function(e) {
            // Remove any old one
            jQuery('.ripple').remove();
            // Setup
            let posX = jQuery(this).offset().left,
                posY = jQuery(this).offset().top,
                buttonWidth = jQuery(this).width(),
                buttonHeight = jQuery(this).height();

            // Add the element
            jQuery(this).prepend("<span class='ripple'></span>");


            // Make it round!
            if (buttonWidth >= buttonHeight) {
                buttonHeight = buttonWidth;
            } else {
                buttonWidth = buttonHeight;
            }

            // Get the center of the element
            let x = e.pageX - posX - buttonWidth / 2;
            let y = e.pageY - posY - buttonHeight / 2;


            // Add the ripples CSS and start the animation
            jQuery(".ripple").css({
                width: buttonWidth,
                height: buttonHeight,
                top: y + 'px',
                left: x + 'px'
            }).addClass("rippleEffect");
        });

        /*---------------------------------------------------------------------
        Page faq
        -----------------------------------------------------------------------*/
        jQuery('.iq-accordion .iq-accordion-block .accordion-details').hide();
        jQuery('.iq-accordion .iq-accordion-block:first').addClass('accordion-active').children().slideDown('slow');
        jQuery(document).on("click", '.iq-accordion .iq-accordion-block', function() {
            if (jQuery(this).children('div.accordion-details ').is(':hidden')) {
                jQuery('.iq-accordion .iq-accordion-block').removeClass('accordion-active').children('div.accordion-details ').slideUp('slow');
                jQuery(this).toggleClass('accordion-active').children('div.accordion-details ').slideDown('slow');
            }
        });
        
        /*---------------------------------------------------------------------
        Page Loader
        -----------------------------------------------------------------------*/
        jQuery("#load").fadeOut();
        jQuery("#loading").delay().fadeOut("");

        

        /*---------------------------------------------------------------------
       Owl Carousel
       -----------------------------------------------------------------------*/
        jQuery('.owl-carousel').each(function() {
            let jQuerycarousel = jQuery(this);
            jQuerycarousel.owlCarousel({
                items: jQuerycarousel.data("items"),
                loop: jQuerycarousel.data("loop"),
                margin: jQuerycarousel.data("margin"),
                nav: jQuerycarousel.data("nav"),
                dots: jQuerycarousel.data("dots"),
                autoplay: jQuerycarousel.data("autoplay"),
                autoplayTimeout: jQuerycarousel.data("autoplay-timeout"),
                navText: ["<i class='fa fa-angle-left fa-2x'></i>", "<i class='fa fa-angle-right fa-2x'></i>"],
                responsiveClass: true,
                responsive: {
                    // breakpoint from 0 up
                    0: {
                        items: jQuerycarousel.data("items-mobile-sm"),
                        nav: false,
                        dots: true
                    },
                    // breakpoint from 480 up
                    480: {
                        items: jQuerycarousel.data("items-mobile"),
                        nav: false,
                        dots: true
                    },
                    // breakpoint from 786 up
                    786: {
                        items: jQuerycarousel.data("items-tab")
                    },
                    // breakpoint from 1023 up
                    1023: {
                        items: jQuerycarousel.data("items-laptop")
                    },
                    1199: {
                        items: jQuerycarousel.data("items")
                    }
                }
            });
        });

        /*---------------------------------------------------------------------
        Select input
        -----------------------------------------------------------------------*/
        jQuery('.select2jsMultiSelect').select2({
            tags: true
        });

        /*---------------------------------------------------------------------
        Search input
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', function(e) {
            let myTargetElement = e.target;
            let selector, mainElement;
            if (jQuery(myTargetElement).hasClass('search-toggle') || jQuery(myTargetElement).parent().hasClass('search-toggle') || jQuery(myTargetElement).parent().parent().hasClass('search-toggle')) {
                if (jQuery(myTargetElement).hasClass('search-toggle')) {
                    selector = jQuery(myTargetElement).parent();
                    mainElement = jQuery(myTargetElement);
                } else if (jQuery(myTargetElement).parent().hasClass('search-toggle')) {
                    selector = jQuery(myTargetElement).parent().parent();
                    mainElement = jQuery(myTargetElement).parent();
                } else if (jQuery(myTargetElement).parent().parent().hasClass('search-toggle')) {
                    selector = jQuery(myTargetElement).parent().parent().parent();
                    mainElement = jQuery(myTargetElement).parent().parent();
                }
                if (!mainElement.hasClass('active') && jQuery(".navbar-list li").find('.active')) {
                    jQuery('.navbar-list li').removeClass('iq-show');
                    jQuery('.navbar-list li .search-toggle').removeClass('active');
                }

                selector.toggleClass('iq-show');
                mainElement.toggleClass('active');

                e.preventDefault();
            } else if (jQuery(myTargetElement).is('.search-input')) {} else {
                jQuery('.navbar-list li').removeClass('iq-show');
                jQuery('.navbar-list li .search-toggle').removeClass('active');
            }
        });

        /*---------------------------------------------------------------------
        Scrollbar
        -----------------------------------------------------------------------*/
        let Scrollbar = window.Scrollbar;
        if (jQuery('#sidebar-scrollbar').length) {
            Scrollbar.init(document.querySelector('#sidebar-scrollbar'), options);
        }
        let Scrollbar1 = window.Scrollbar;
        if (jQuery('#right-sidebar-scrollbar').length) {
            Scrollbar1.init(document.querySelector('#right-sidebar-scrollbar'), options);
        }



        /*---------------------------------------------------------------------
        Counter
        -----------------------------------------------------------------------*/
        jQuery('.counter').counterUp({
            delay: 10,
            time: 1000
        });

        /*---------------------------------------------------------------------
        slick
        -----------------------------------------------------------------------*/
        jQuery('.slick-slider').slick({
            centerMode: true,
            centerPadding: '60px',
            slidesToShow: 9,
            slidesToScroll: 1,
            focusOnSelect: true,
            responsive: [{
                breakpoint: 992,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '30',
                    slidesToShow: 3
                }
            }, {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '15',
                    slidesToShow: 1
                }
            }],
            nextArrow: '<a href="#" class="ri-arrow-left-s-line left"></a>',
            prevArrow: '<a href="#" class="ri-arrow-right-s-line right"></a>',
        });

        jQuery('#new-music').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            focusOnSelect: true,
            arrows: false,
            responsive: [{
                breakpoint: 992,
                settings: {
                    arrows: false,
                    centerMode: true,
                    slidesToShow: 3
                }
            }, {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    slidesToShow: 1
                }
            }],
           
        });

         jQuery('#recent-music').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            focusOnSelect: true,
            arrows: false,
            responsive: [{
                breakpoint: 992,
                settings: {
                    arrows: false,
                    centerMode: true,
                    slidesToShow: 3
                }
            }, {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    slidesToShow: 1
                }
            }],
           
        });

          jQuery('#top-music').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            focusOnSelect: true,
            arrows: false,
            responsive: [{
                breakpoint: 992,
                settings: {
                    arrows: false,
                    centerMode: true,
                    slidesToShow: 3
                }
            }, {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    slidesToShow: 1
                }
            }],
           
        });



        /*---------------------------------------------------------------------
        Progress Bar
        -----------------------------------------------------------------------*/
        jQuery('.iq-progress-bar > span').each(function() {
            let progressBar = jQuery(this);
            let width = jQuery(this).data('percent');
            progressBar.css({
                'transition': 'width 2s'
            });

            setTimeout(function() {
                progressBar.appear(function() {
                    progressBar.css('width', width + '%');
                });
            }, 100);
        });


        /*---------------------------------------------------------------------
        Page Menu
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', '.wrapper-menu', function() {
            jQuery(this).toggleClass('open');
        });

        jQuery(document).on('click', ".wrapper-menu", function() {
            jQuery("body").toggleClass("sidebar-main");
        });
        


        /*---------------------------------------------------------------------
        Wow Animation
        -----------------------------------------------------------------------*/
        let wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 0,
            mobile: false,
            live: true
        });
        wow.init();


        /*---------------------------------------------------------------------
        Mailbox
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', 'ul.iq-email-sender-list li', function() {
            jQuery(this).next().addClass('show');
        });

        jQuery(document).on('click', '.email-app-details li h4', function() {
            jQuery('.email-app-details').removeClass('show');
        });


        /*---------------------------------------------------------------------
        chatuser
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', '.chat-head .chat-user-profile', function() {
            jQuery(this).parent().next().toggleClass('show');
        });
        jQuery(document).on('click', '.user-profile .close-popup', function() {
            jQuery(this).parent().parent().removeClass('show');
        });

        /*---------------------------------------------------------------------
        chatuser main
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', '.chat-search .chat-profile', function() {
            jQuery(this).parent().next().toggleClass('show');
        });
        jQuery(document).on('click', '.user-profile .close-popup', function() {
            jQuery(this).parent().parent().removeClass('show');
        });

        /*---------------------------------------------------------------------
        Chat start
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', '#chat-start', function() {
            jQuery('.chat-data-left').toggleClass('show');
        });
        jQuery(document).on('click', '.close-btn-res', function() {
            jQuery('.chat-data-left').removeClass('show');
        });
        jQuery(document).on('click', '.iq-chat-ui li', function() {
            jQuery('.chat-data-left').removeClass('show');
        });
        jQuery(document).on('click', '.sidebar-toggle', function() {
            jQuery('.chat-data-left').addClass('show');
        });

        /*---------------------------------------------------------------------
        todo Page
        -----------------------------------------------------------------------*/
        jQuery(document).on('click', '.todo-task-list > li > a', function() {
            jQuery('.todo-task-list li').removeClass('active');
            jQuery('.todo-task-list .sub-task').removeClass('show');
            jQuery(this).parent().toggleClass('active');
            jQuery(this).next().toggleClass('show');
        });
        jQuery(document).on('click', '.todo-task-list > li li > a', function() {
            jQuery('.todo-task-list li li').removeClass('active');
            jQuery(this).parent().toggleClass('active');
        });
        /*---------------------------------------------------------------------
        Form Validation
        -----------------------------------------------------------------------*/

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);

        /*---------------------------------------------------------------------
        Sidebar Widget
        -----------------------------------------------------------------------*/
        jQuery(document).ready(function() {
            jQuery().on('click', '.todo-task-lists li', function() {
                if (jQuery(this).find('input:checkbox[name=todo-check]').is(":checked")) {

                    jQuery(this).find('input:checkbox[name=todo-check]').attr("checked", false);
                    jQuery(this).removeClass('active-task');
                } else {
                    jQuery(this).find('input:checkbox[name=todo-check]').attr("checked", true);
                    jQuery(this).addClass('active-task');
                }
               
            });
        });

       

        /*------------------------------------------------------------------
        Flatpicker
        * -----------------------------------------------------------------*/
        if (typeof flatpickr !== 'undefined' && jQuery.isFunction(flatpickr)) {
            jQuery(".flatpicker").flatpickr({
                inline: true
            });
        }

        


    });

    $('.select2').select2();

})(jQuery);

function toText( string ) {
    var elm = document.createElement('div');
    elm.innerText = string;
    return elm.innerText;
}

(function(_scope) {

    // $('.toast').toast({
    //     delay: 500000000
    // });

    var lastType;
    
    function toast( type, title, text )
    {

        var icon = 'info';
        switch( type )
        {
            case 'primary':
                icon = 'info';
                break;
            case 'danger':
                icon = 'warning';
                break;
        }

        $.toast({
            heading: title,
            text: text,
            showHideTransition: 'slide',
            hideAfter: 5000,
            icon: icon,
            position: 'top-center'
        });
        // type = 'bg-' + (type || 'primary');

        // if ( lastType )
        // {
        //     $('#toaster').removeClass(lastType);
        // }
        
        // lastType = type;
        // $('#toaster').addClass(type);
        // $('#toaster .title').text(title);
        // $('#toaster .toast-body').html(text);
    
        // $('#toaster').toast('show');
    }

    _scope.toast = toast;
    
    // $('#toaster').on('hidden.bs.toast', function () {
        
    // })

})(window);

(function(_scope) {
    function cConfirm( msg, success, declined ) {
        bootbox.confirm({
            message: msg,
            buttons: {
                confirm: {
                    label: _scope.cConfirm.labels.yes,
                    className: 'btn-primary'
                },
                cancel: {
                    label: _scope.cConfirm.labels.no,
                    className: 'btn-danger'
                }
            },
            centerVertical: true,
            callback: function( result ) {

                if ( !result ) {
                    declined && declined();
                    return;
                }

                success && success();
            }
        });
    }

    cConfirm.labels = {
        yes: "yes",
        no: "no"
    };

    _scope.cConfirm = _scope.cConfirm || cConfirm;
})(window);

// feed js

(function(_scope) {
    function Feed( id, commentIds )
    {
        if ( this.constructor !== Feed ) throw new Error("Please use new");

        var $feedBox = $('#feed_' + id);
        var $commentBox = $feedBox.find('.post-comments');
        var $delete = $feedBox.find('.delete_btn');
        var $likeBtn = $feedBox.find('.total-like-block .like-btn');
        var $floatImage = $feedBox.find('.float-image');

        $floatImage.on('click', function(e) {
            e.preventDefault();
            _createImageViewer($floatImage.attr('href'));
        });

        // var isViewed = false;

        // // var myElement = document.getElementById('my-element');
        // $(window).on('scroll', function() {
        //     var bounding = $feedBox[0].getBoundingClientRect();

        //     if (bounding.top >= 0 && bounding.left >= 0 && bounding.right <= window.innerWidth && bounding.bottom <= window.innerHeight) {

        //         _view();
        //     } else {
        //         _view();
        //     }
        // });
    

        // On comment
        _initComments(id, commentIds, $feedBox);

        // On click delete button
        $delete.on('click', function(e) {
            e.preventDefault();

            // TODO: Translation
            bootbox.confirm({
                message: Feed.labels.delete_sure,
                buttons: {
                    confirm: {
                        label: Feed.labels.yes,
                        className: 'btn-success'
                    },
                    cancel: {
                        label: Feed.labels.no,
                        className: 'btn-danger'
                    }
                },
                centerVertical: true,
                callback: function( result ) {

                    if ( !result ) return;

                    // now submit
                    $.ajax({
                        url: URLS.delete_feed,
                        data: {
                            id: id
                        },
                        dataType: 'JSON',
                        accepts: 'JSON',
                        type: 'POST',
                        beforeSubmit: function() {

                        },
                        success: function (data) {
                        },
                        complete: function() {
                            $feedBox.remove();
                        }
                    });

                }
            });
        });

        // on click like btn toggle the button
        $likeBtn.on('click', function() {
            // also send the ajax.
            $(this).toggleClass('liked')

             // now submit
             $.ajax({
                url: URLS.toggle_expression,
                data: {
                    entityType: 'feed',
                    entityId: id,
                    type: 'like'
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSubmit: function() {

                },
                success: function (data) {
                    // if ( data.info !== 'success' )
                    // {
                    //     toast('danger', data.payload[0], data.payload[1]);
                    //     return;
                    // }

                    // // else fetch comment and show it to comment box.

                    // _loadComment(feedId, data.payload, $commentBox);
                },
                complete: function() {
                    // $commentForm.find('input').val('');
                }
            });
        });
        
    }

    function _initComments( feedId, commentIds, $feedBox )
    {
        var $commentForm = $feedBox.find('.comment-text');
        var $loadMore = $feedBox.find('.comment-load-more a');
        var $commentBox = $feedBox.find('.post-comments');

        var isBusy = false;

        commentIds.forEach(function(v, i){
            new Comment(v);
        });

        var lastCommentId = commentIds.length > 0 ? commentIds[0] : 0;

        $loadMore.on('click', function(e) {
            e.preventDefault();

            _loadMore(feedId, lastCommentId);
        });
        
        

        $commentForm.on('submit', function(e) {
            e.preventDefault();  
            console.log(isBusy);
            if ( isBusy ) return;

            var comment = $commentForm.find('input').val().trim();            

            

            // now submit
            $.ajax({
                url: URLS.post_comment,
                data: {
                    comment: comment,
                    feedId: feedId
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSend: function() {
                    
                },
                success: function (data) {
                    if ( data.info !== 'success' )
                    {
                        toast('danger', data.payload[0], data.payload[1]);
                        return;
                    }

                    // else fetch comment and show it to comment box.
                    var str = $feedBox.find('.total-comments-count span').text();                    
                    var number = parseInt(str, 10);                    
                    var newNumber = number + 1;
                    str1 = str.replace(number.toString(), newNumber.toString());                    
                    $feedBox.find('.total-comments-count span').text(str1);

                    _loadComment(feedId, data.payload, $commentBox);
                },
                complete: function() {
                    $commentForm.find('input').val('');
                    isBusy = false;
                }
            });
        });

        function _loadComment( feedId, id, commentBox ) {
            $.ajax({
                url: URLS.get_comment,
                data: {
                    feedId: feedId,
                    id: id,
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSubmit: function() {

                },
                success: function (data) {
                    if ( data.info !== 'success' )
                    {
                        toast('danger', data.payload[0], data.payload[1]);
                        return;
                    }

                    // else fetch comment and show it to comment box.
                    commentBox.append('<li>' + data.payload.comment + '<li>');
                    new Comment(data.payload.id);
                },
                complete: function() {
                    $commentForm.find('input').val('');
                }
            });
        }

        function _loadMore( feedId, lastId )
        {

            $.ajax({
                url: URLS.load_comment,
                data: {
                    feedId: feedId,
                    lastId: lastCommentId
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSubmit: function() {

                },
                success: function (data) {
                    if ( data.info !== 'success' )
                    {
                        toast('danger', data.payload[0], data.payload[1]);
                        return;
                    }

                    data.payload.comments.forEach(function(v, i) {
                        var commentId = data.payload.ids[i];
                        $loadMore.parent().after('<li>' + v + '</li>');

                        lastCommentId = commentId;
                        new Comment(commentId);
                    });

                    if ( !data.payload.dataAvailable ) {
                        $loadMore.remove();
                    }

                    // console.log(data.payload);

                    // else fetch comment and show it to comment box.
                },
                complete: function() {
                    $commentForm.find('input').val('');
                }
            });
        }
    }

    function _createImageViewer( src )
    {
        var overlay = document.createElement('div');
        overlay.className = 'viewer-overlay';
        var float = document.createElement('div');
        float.className = 'viewer-float';

        var close = document.createElement('i');
        close.className = 'ri-close-line';
        overlay.appendChild(close);

        $(close).on('click', function() {
            $(overlay).remove();
        });

        var image = new Image();
        image.src = src;
        float.appendChild(image);

        overlay.appendChild(float);

        document.body.appendChild(overlay);
    }

    Feed.labels = {
        delete_sure: 'Sure?',
        yes: 'Yes',
        no: 'no'
    }

    _scope.Feed = Feed;
})(window);

(function(_scope) {
    function Comment( id )
    {
        var $comment = $('#comment_' + id);
        var $deleteBtn = $comment.find('.delete-btn');

        $deleteBtn.on('click', function(e) {
            e.preventDefault();

            
            $.ajax({
                url: URLS.delete_comment,
                data: {
                    id: id,
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSubmit: function() {

                },
                success: function (data) {
                    if ( data.info !== 'success' )
                    {
                        toast('danger', data.payload[0], data.payload[1]);
                        return;
                    }

                    $comment.remove();
                },
                complete: function() {
                    
                }
            });
        });
    }

    _scope.Comment = Comment;
})(window);


(function(_scope){

    function Workshop( id ) {

        var $item = $('#workshop_' + id);
        var $startBtn = $item.find('.start-btn');
        var $deleteBtn = $item.find('.delete-btn');
        var $cancelBtn = $item.find('.cancel-btn');
        var $markCompletedBtn = $item.find('.mark-completed-btn');
        var $completedBtn = $item.find('.completed-btn');
        var $canceledBtn = $item.find('.canceled-btn');
        var $joinBtn = $item.find('.join-btn');
        var $advisorJoinBtn = $item.find('.advisor-join-btn');

        var isDeleted = false;
        var isBusy = false;

        $deleteBtn.on('click', function(e){
            e.preventDefault();            

            if(isDeleted) return false;

            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Workshop.labels.loading);
            var $btn = $(this);
            isBusy = true;

            cConfirm(Workshop.labels.delete_title, function(){
                $.ajax({
                    url: URLS.delete_workshop,
                    data: {
                        id: id
                    },
                    beforeSend: function() {
    
                    },
                    success: function( data ) {
                        if ( data.info !== 'success' ) {                        
                            toast('danger', Workshop.labels.error_title, data.payload);
                            return;
                        }

                        $startBtn.addClass('d-none');
                        $cancelBtn.addClass('d-none');
                        $deleteBtn.text(Workshop.labels.deleted);
                        isDeleted = true;
                        location.reload();
                    },
                    complete: function() {
                        $btn.text(oldText);
                        isBusy = false;
                    }
                });
            })

        });

        $startBtn.on('click', function(e){
            e.preventDefault();
            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Workshop.labels.loading);
            var $btn = $(this);
            isBusy = true;

            $.ajax({
                url: URLS.start_workshop,
                data: {
                    id: id
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Workshop.labels.error_title, data.payload);
                        return;
                    }

                    $deleteBtn.addClass('d-none');
                    $startBtn.addClass('d-none');
                    $cancelBtn.addClass('d-none');
                    $markCompletedBtn.removeClass('d-none');
                    $advisorJoinBtn.removeClass('d-none');
                },
                complete: function() {
                    $btn.text(oldText);
                    isBusy = false;
                }
            });

        });

        $cancelBtn.on('click', function(e){
            e.preventDefault();

            var dialog = bootbox.prompt({ 
                size: "small",
                title: Workshop.labels.cancel_title,
                inputType: 'textarea',
                placeholder: Workshop.labels.cancel_placeholder,
                buttons: {
                    confirm: {
                        label: Workshop.labels.yes,
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: Workshop.labels.no,
                        className: 'btn-danger'
                    }
                },
                centerVertical: true,
                callback: function(result){ 
                    if ( result === null ) return true;

                    if ( result.trim() === '' ) {
                        toast(
                            'danger',
                            Workshop.labels.error_title,
                            Workshop.labels.error_cancel_confirm
                        );
                        return false;
                    }


                    // else  run ajax.
                    $.ajax({
                        url: URLS.cancel_workshop,
                        data: {
                            id: id,
                            coupon: result,
                        },
                        beforeSend: function() {
        
                        },
                        success: function( data ) {
                            if ( data.info !== 'success' ) {                        
                                toast('danger', Workshop.labels.error_title, data.payload);
                                return;
                            }
        
                            $startBtn.addClass('d-none');
                            $cancelBtn.addClass('d-none');
                            $canceledBtn.removeClass('d-none');

                            dialog.modal('hide');
                        },
                        complete: function() {
        
                        }
                    });
                    
                    // else run the ajax.
                    
                    
                    return false;
                }
            });

        });

        $advisorJoinBtn.on('click', function(e){
            e.preventDefault();
            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Workshop.labels.loading);
            var $btn = $(this);
            isBusy = true;

            $.ajax({
                url: URLS.join_workshop,
                data: {
                    id: id,
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Workshop.labels.error_title, data.payload.msg);
                        return;
                    }

                    setTimeout(function() {
                        window.open(data.payload.Advisor_url, '_blank');
                    });
                },
                complete: function() {
                    $btn.text(oldText);
                    isBusy = false;
                }
            });

        });

        $joinBtn.on('click', function(e){
            e.preventDefault();
            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Workshop.labels.loading);
            var $btn = $(this);
            isBusy = true;

            $.ajax({
                url: URLS.join_workshop,
                data: {
                    id: id
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Workshop.labels.error_title, data.payload.msg);
                        return;
                    }


                    setTimeout(function() {
                        window.open(data.payload.JoinMeetingURL, '_blank');
                    });                    
                },
                complete: function() {
                    $btn.text(oldText);
                    isBusy = false;
                }
            });

        });

        $markCompletedBtn.on('click', function(){
            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Workshop.labels.loading);
            var $btn = $(this);
            isBusy = true;

            $.ajax({
                url: URLS.complete_workshop,
                data: {
                    id: id
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Workshop.labels.error_title, data.payload);
                        return;
                    }

                    $markCompletedBtn.addClass('d-none');
                    $completedBtn.removeClass('d-none');
                    $advisorJoinBtn.addClass('d-none');
                    $joinBtn.addClass('d-none');
                },
                complete: function() {
                    $btn.text(oldText);
                    isBusy = false;
                }
            });
        });


    }

    Workshop.labels = {
        error_title: 'error',
        window_cant_open: "window_cant_open"
    };

    _scope.Workshop = _scope.Workshop || Workshop;

})(window);

(function(_scope){

    function Call( id ) {

        var $item = $('#call_' + id);
        var $startBtn = $item.find('.start-btn');
        var $cancelBtn = $item.find('.cancel-btn');
        var $bCancelBtn = $item.find('.b-cancel-btn');
        var $markCompletedBtn = $item.find('.mark-completed-btn');
        var $completedBtn = $item.find('.completed-btn');
        var $canceledBtn = $item.find('.canceled-btn');
        var $joinBtn = $item.find('.join-btn');
        var $advisorJoinBtn = $item.find('.advisor-join-btn');

        var isBusy = false;

        $startBtn.on('click', function(e){
            e.preventDefault();
            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Call.labels.loading);
            var $btn = $(this);
            isBusy = true;

            $.ajax({
                url: URLS.start_call,
                data: {
                    id: id
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Call.labels.error_title, data.payload);
                        return;
                    }

                    $startBtn.addClass('d-none');
                    $cancelBtn.addClass('d-none');
                    $markCompletedBtn.removeClass('d-none');
                    $advisorJoinBtn.removeClass('d-none');
                },
                complete: function() {
                    $btn.text(oldText);
                    isBusy = false;
                }
            });

        });

        $cancelBtn.on('click', function(e){
            e.preventDefault();

            var dialog = bootbox.prompt({ 
                size: "small",
                title: Call.labels.cancel_title,
                inputType: 'textarea',
                placeholder: Call.labels.cancel_placeholder,
                buttons: {
                    confirm: {
                        label: Call.labels.yes,
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: Call.labels.no,
                        className: 'btn-danger'
                    }
                },
                centerVertical: true,
                callback: function(result){ 
                    if ( result === null ) return true;

                    if ( result.trim() === '' ) {
                        toast(
                            'danger',
                            Call.labels.error_title,
                            Call.labels.error_cancel_confirm
                        );
                        return false;
                    }


                    // else  run ajax.
                    $.ajax({
                        url: URLS.cancel_call,
                        data: {
                            id: id,
                            coupon: result,
                        },
                        beforeSend: function() {
        
                        },
                        success: function( data ) {
                            if ( data.info !== 'success' ) {                        
                                toast('danger', Call.labels.error_title, data.payload);
                                return;
                            }
        
                            $joinBtn.addClass('d-none');
                            $startBtn.addClass('d-none');
                            $cancelBtn.addClass('d-none');
                            $canceledBtn.removeClass('d-none');

                            dialog.modal('hide');
                        },
                        complete: function() {

                        }
                    });
                    
                    // else run the ajax.
                    
                    
                    return false;
                }
            });

        });

        $bCancelBtn.on('click', function(){
            var dialog = bootbox.confirm({ 
                size: "small",
                message: Call.labels.cancel_title,                
                buttons: {
                    confirm: {
                        label: Call.labels.yes,
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: Call.labels.no,
                        className: 'btn-danger'
                    }
                },
                centerVertical: true,
                callback: function(result){ 
                    if ( !result ) return true;

                    // else  run ajax.
                    $.ajax({
                        url: URLS.cancel_call,
                        data: {
                            id: id
                        },
                        beforeSend: function() {
        
                        },
                        success: function( data ) {
                            if ( data.info !== 'success' ) {                        
                                toast('danger', Call.labels.error_title, data.payload);
                                return;
                            }
        
                            $joinBtn.addClass('d-none');
                            $startBtn.addClass('d-none');
                            $bCancelBtn.addClass('d-none');
                            $canceledBtn.removeClass('d-none');

                            dialog.modal('hide');
                        },
                        complete: function() {
        
                        }
                    });
                    
                    // else run the ajax.
                    
                    
                    return false;
                }
            });
        });

        $advisorJoinBtn.on('click', function(e){
            e.preventDefault();

            if ( isBusy ) return;

            var oldText = $(this).text();
            $(this).text(Call.labels.loading);
            var $btn = $(this);
            isBusy = true;

            $.ajax({
                url: URLS.join_call,
                data: {
                    id: id
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Call.labels.error_title, data.payload.msg);
                        return;
                    }

                    setTimeout(function() {
                        window.open(data.payload.Advisor_url, '_blank');
                    });
                },
                complete: function() {
                    $btn.text(oldText);
                    isBusy = false;
                }
            });

        });

        $joinBtn.on('click', function(e){
            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Call.labels.loading);
            var $btn = $(this);
            isBusy = true;

            e.preventDefault();

            $.ajax({
                url: URLS.join_call,
                data: {
                    id: id
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Call.labels.error_title, data.payload.msg);
                        return;
                    }

                    setTimeout(function() {
                        window.open(data.payload.JoinMeetingURL, '_blank');
                    });                    
                },
                complete: function() {
                    $btn.text(oldText);
                    isBusy = false;
                }
            });

        });

        $markCompletedBtn.on('click', function(){
            if ( isBusy ) return;
            var oldText = $(this).text();
            $(this).text(Call.labels.loading);
            var $btn = $(this);
            isBusy = true;

            $.ajax({
                url: URLS.complete_call,
                data: {
                    id: id
                },
                beforeSend: function() {

                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {                        
                        toast('danger', Call.labels.error_title, data.payload);
                        return;
                    }

                    $markCompletedBtn.addClass('d-none');
                    $completedBtn.removeClass('d-none');
                    $advisorJoinBtn.addClass('d-none');
                },
                complete: function() {                    
                    $btn.text(oldText);
                    isBusy = false;
                }
            });
        });


    }

    Call.labels = {
        error_title: 'error',
        window_cant_open: "window_open_not_supported",
        loading: '...',
    };

    _scope.Call = _scope.Call || Call;

})(window);

(function(_scope){

    var _redirectHandler = function(url) {
        window.location.href = url
    };

    var actions = {
        'follow': _redirectHandler,
        'feed.like': _redirectHandler,
        'feed.comment': _redirectHandler,
        'message.completed': _redirectHandler,
        'message.reminder': _redirectHandler,
        'message.canceled': _redirectHandler,
        'message.accepted': _redirectHandler,
        'message.rejected': _redirectHandler,
        'message.cancelled': _redirectHandler,
        'workshop.completed': _redirectHandler,
        'workshop.canceled': _redirectHandler,
        'workshop.accepted': _redirectHandler,
        'workshop.rejected': _redirectHandler,
        'call.completed': _redirectHandler,
        'call.reminder': _redirectHandler,
        'call.canceled': _redirectHandler,
        'call.accepted': _redirectHandler,
        'call.rejected': _redirectHandler,
        'workshop.pending': _redirectHandler,
        'workshop.reminder': _redirectHandler,
        'workshop.invited': _redirectHandler,
        'workshop_auto.canceled': _redirectHandler,
        'message.pending': _redirectHandler,
        'call.pending': _redirectHandler,
        'call.request': _redirectHandler,
        'call.request.resolved': _redirectHandler,
    };

    function Notification() {
        if ( this.constructor !== Notification ) throw new Error("Please use `new` key");

        this.open = function() {            
            var args = arguments;

            var action = args[0];

            if ( typeof actions[action] == 'function' ) {
                args = [].slice.call(args, 1);
                actions[action].apply({}, args);
            }
        }
    }

    _scope.notification =  _scope.notification || new Notification();

})(window);

