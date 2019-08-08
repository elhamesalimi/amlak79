
"use strict";

function ajaxSetup() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("table").on("click", ".delete", function () {
        var url = $(this).data('url');
        var thisElement = $(this);

        const  parent=$(this).parents("table:first");

        const alert_title =parent.data('alert_title');
        const alert_text = parent.data('alert_body');

        const deleted_sucsses_text = parent.data('success_delete');
        const deleted_error_text =parent.data('error_delete');

        const confirmButtonText =parent.data('alert_title');
        const cancelButtonText =parent.data('cancel_text');

        const deleted_title =parent.data('deleted');
        const not_deleted_title = parent.data('not_deleted');

        swal({
                title: alert_title,
                text: alert_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            },
            function () {
                $.ajax({
                    url: url,
                    dataType: 'JSON',
                    type: 'DELETE',
                    success: function (response) {
                        if (response === 'deleted') {
                            thisElement.parents("tr:first").fadeOut(500, function () {
                                thisElement.parents("tr:first").remove();
                            });
                            swal(deleted_title, deleted_sucsses_text, "success");
                        } else {
                            swal(not_deleted_title, response, "error");
                        }
                    },
                    error: function () {
                        swal(not_deleted_title, deleted_error_text, "error");
                    }
                });
            });
    });


    $('.nestable-delete').on('click', function () {
        var url = $(this).attr("data-id");
        var thisElement = $(this);

        const  parent=$(this).parents(".dd:first");

        const alert_title = parent.data('alert_title');
        const alert_body = parent.data('alert_body');

        const deleted_success_text = parent.data('success_delete');
        const deleted_error_text = parent.data('error_delete');

        const confirmButtonText = parent.data('confirm_text');
        const cancelButtonText = parent.data('cancel_text');

        const deleted_title = parent.data('deleted');
        const not_deleted_title = parent.data('not_deleted');

        swal({
                title: alert_title,
                text: alert_body,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            },
            function () {
                $.ajax({
                    url: url,
                    dataType: 'JSON',
                    type: 'DELETE',
                    success: function (response) {
                        if (response === 'deleted') {
                            thisElement.parents("li:first").fadeOut(500, function () {
                                thisElement.parents("li:first").remove();
                            });
                            swal(deleted_title, deleted_success_text, "success");
                        } else {
                            swal(not_deleted_title, response, "error");
                        }
                    },
                    error: function () {
                        swal(not_deleted_title, deleted_error_text, "error");
                    }
                });
            });
    });
}

/*****Ready function start*****/
$(document).ready(function () {
    philbert();
    var $preloader = $(".preloader-it > .la-anim-1");
    $preloader.addClass('la-animate');
    ajaxSetup();
});
/*****Ready function end*****/

/*****Load function start*****/
$(window).load(function () {
    $(".preloader-it").delay(500).fadeOut("slow");
    /*Progress Bar Animation*/
    var progressAnim = $('.progress-anim');
    if (progressAnim.length > 0) {
        for (var i = 0; i < progressAnim.length; i++) {
            var $this = $(progressAnim[i]);
            $this.waypoint(function () {
                var progressBar = $(".progress-anim .progress-bar");
                for (var i = 0; i < progressBar.length; i++) {
                    $this = $(progressBar[i]);
                    $this.css("width", $this.attr("aria-valuenow") + "%");
                }
            }, {
                triggerOnce: true,
                offset: 'bottom-in-view'
            });
        }
    }
});
/*****Load function* end*****/

/***** Full height function start *****/
var setHeightWidth = function () {
    var height = $(window).height();
    var width = $(window).width();
    $('.full-height').css('height', (height));
    $('.page-wrapper').css('min-height', (height));

    /*Right Sidebar Scroll Start*/
    if (width <= 1007) {
        $('#chat_list_scroll').css('height', (height - 270));
        $('.fixed-sidebar-right .chat-content').css('height', (height - 279));
        $('.fixed-sidebar-right .set-height-wrap').css('height', (height - 219));

    }
    else {
        $('#chat_list_scroll').css('height', (height - 204));
        $('.fixed-sidebar-right .chat-content').css('height', (height - 213));
        $('.fixed-sidebar-right .set-height-wrap').css('height', (height - 153));
    }
    /*Right Sidebar Scroll End*/

    /*Vertical Tab Height Cal Start*/
    var verticalTab = $(".vertical-tab");
    if (verticalTab.length > 0) {
        for (var i = 0; i < verticalTab.length; i++) {
            var $this = $(verticalTab[i]);
            $this.find('ul.nav').css(
                'min-height', ''
            );
            $this.find('.tab-content').css(
                'min-height', ''
            );
            height = $this.find('ul.ver-nav-tab').height();
            $this.find('ul.nav').css(
                'min-height', height + 40
            );
            $this.find('.tab-content').css(
                'min-height', height + 40
            );
        }
    }
    /*Vertical Tab Height Cal End*/
};
/***** Full height function end *****/

/***** philbert function start *****/
var $wrapper = $(".wrapper");

function toast(title, text, type, delay) {

    $.toast().reset('all');
    $("body").removeAttr('class').addClass("bottom-center-fullwidth");
    $.toast({
        heading: title,
        text: text,
        position: 'bottom-center',
        loaderBg: '#878787',
        icon: type === 1 ? 'success' : 'error',
        hideAfter: delay
    });
}

var philbert = function () {

    /*Counter Animation*/
    var counterAnim = $('.counter-anim');
    if (counterAnim.length > 0) {
        counterAnim.counterUp({
            delay: 10,
            time: 1000
        });
    }

    /*Tooltip*/
    if ($('[data-toggle="tooltip"]').length > 0)
        $('[data-toggle="tooltip"]').tooltip();

    /*Popover*/
    if ($('[data-toggle="popover"]').length > 0)
        $('[data-toggle="popover"]').popover()


    /*Sidebar Collapse Animation*/
    var sidebarNavCollapse = $('.fixed-sidebar-left .side-nav  li .collapse');
    var sidebarNavAnchor = '.fixed-sidebar-left .side-nav  li a';
    $(document).on("click", sidebarNavAnchor, function (e) {
        if ($(this).attr('aria-expanded') === "false")
            $(this).blur();
        $(sidebarNavCollapse).not($(this).parent().parent()).collapse('hide');
    });

    /*Panel Remove*/
    $(document).on('click', '.close-panel', function (e) {
        var effect = $(this).data('effect');
        $(this).closest('.panel')[effect]();
        return false;
    });

    /*Accordion js*/
    $(document).on('show.bs.collapse', '.panel-collapse', function (e) {
        $(this).siblings('.panel-heading').addClass('activestate');
    });

    $(document).on('hide.bs.collapse', '.panel-collapse', function (e) {
        $(this).siblings('.panel-heading').removeClass('activestate');
    });

    /*Sidebar Navigation*/
    $(document).on('click', '#toggle_nav_btn,#open_right_sidebar,#setting_panel_btn', function (e) {
        $(".dropdown.open > .dropdown-toggle").dropdown("toggle");
        return false;
    });
    $(document).on('click', '#toggle_nav_btn', function (e) {
        $wrapper.removeClass('open-right-sidebar open-setting-panel').toggleClass('slide-nav-toggle');
        return false;
    });

    $(document).on('click', '#open_right_sidebar', function (e) {
        $wrapper.toggleClass('open-right-sidebar').removeClass('open-setting-panel');
        return false;

    });

    $(document).on('click', '.product-carousel .owl-nav', function (e) {
        return false;
    });

    $(document).on('click', 'body', function (e) {
        if ($(e.target).closest('.fixed-sidebar-right,.setting-panel').length > 0) {
            return;
        }
        $('body > .wrapper').removeClass('open-right-sidebar open-setting-panel');
        return;
    });

    $(document).on('show.bs.dropdown', '.nav.navbar-right.top-nav .dropdown', function (e) {
        $wrapper.removeClass('open-right-sidebar open-setting-panel');
        return;
    });

    $(document).on('click', '#setting_panel_btn', function (e) {
        $wrapper.toggleClass('open-setting-panel').removeClass('open-right-sidebar');
        return false;
    });
    $(document).on('click', '#toggle_mobile_nav', function (e) {
        $wrapper.toggleClass('mobile-nav-open').removeClass('open-right-sidebar');
        return;
    });


    $(document).on("mouseenter mouseleave", ".wrapper > .fixed-sidebar-left", function (e) {
        if (e.type == "mouseenter") {
            $wrapper.addClass("sidebar-hover");
        }
        else {
            $wrapper.removeClass("sidebar-hover");
        }
        return false;
    });

    $(document).on("mouseenter mouseleave", ".wrapper > .setting-panel", function (e) {
        if (e.type == "mouseenter") {
            $wrapper.addClass("no-transition");
        }
        else {
            $wrapper.removeClass("no-transition");
        }
        return false;
    });

    /*Horizontal Nav*/
    $(document).on("show.bs.collapse", ".top-fixed-nav .fixed-sidebar-left .side-nav > li > ul", function (e) {
        e.preventDefault();
    });

    /*Refresh Init Js*/
    var refreshMe = '.refresh';
    $(document).on("click", refreshMe, function (e) {
        var panelToRefresh = $(this).closest('.panel').find('.refresh-container');
        var dataToRefresh = $(this).closest('.panel').find('.panel-wrapper');
        var loadingAnim = panelToRefresh.find('.la-anim-1');
        panelToRefresh.show();
        setTimeout(function () {
            loadingAnim.addClass('la-animate');
        }, 100);

        function started() {
        } //function before timeout
        setTimeout(function () {
            function completed() {
            } //function after timeout
            panelToRefresh.fadeOut(800);
            setTimeout(function () {
                loadingAnim.removeClass('la-animate');
            }, 800);
        }, 1500);
        return false;
    });

    /*Fullscreen Init Js*/
    $(document).on("click", ".full-screen", function (e) {
        $(this).parents('.panel').toggleClass('fullscreen');
        $(window).trigger('resize');
        return false;
    });

    /*Nav Tab Responsive Js*/
    $(document).on('show.bs.tab', '.nav-tabs-responsive [data-toggle="tab"]', function (e) {
        var $target = $(e.target);
        var $tabs = $target.closest('.nav-tabs-responsive');
        var $current = $target.closest('li');
        var $parent = $current.closest('li.dropdown');
        $current = $parent.length > 0 ? $parent : $current;
        var $next = $current.next();
        var $prev = $current.prev();
        $tabs.find('>li').removeClass('next prev');
        $prev.addClass('prev');
        $next.addClass('next');
        return;
    });
};
/***** philbert function end *****/

/***** Chat App function Start *****/
var chatAppTarget = $('.chat-for-widgets-1.chat-cmplt-wrap');
var chatApp = function () {
    $(document).on("click", ".chat-for-widgets-1.chat-cmplt-wrap .chat-data", function (e) {
        var width = $(window).width();
        if (width <= 1007) {
            chatAppTarget.addClass('chat-box-slide');
        }
        return false;
    });
    $(document).on("click", "#goto_back_widget_1", function (e) {
        var width = $(window).width();
        if (width <= 1007) {
            chatAppTarget.removeClass('chat-box-slide');
        }
        return false;
    });
};
/***** Chat App function End *****/

var boxLayout = function () {
    if ((!$wrapper.hasClass("rtl-layout")) && ($wrapper.hasClass("box-layout")))
        $(".box-layout .fixed-sidebar-right").css({right: $wrapper.offset().left + 300});
    else if ($wrapper.hasClass("box-layout rtl-layout"))
        $(".box-layout .fixed-sidebar-right").css({left: $wrapper.offset().left});
}
boxLayout();

/***** Resize function start *****/
$(window).on("resize", function () {
    setHeightWidth();
    boxLayout();
    chatApp();
}).resize();





$(document).ready(function () {

    $('.remove-row').on('click', function () {
        var url = $(this).attr("data-id");
        var thisElement = $(this);
        swal({
                title: "شما مطمئن هستید؟",
                text: "شما نمی خواهید قادر به بازیابی این رکورد!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "بله، آن را حذف کنید!",
                cancelButtonText: "لغو کردن",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            },
            function () {
                $.ajax({
                    url: url,
                    dataType: 'JSON',
                    type: 'DELETE',
                    success: function (response) {
                        if (response == 'deleted') {
                            thisElement.parents(".box:first").fadeOut(500, function () {
                                thisElement.parents(".box:first").remove();
                            });
                            swal("حذف شد!", "ركورد شما حذف شده است.", "success");
                        } else {
                            swal("حذف نمی شود!", response, "error");
                        }
                    },
                    error: function () {
                        swal("حذف نمی شود!", "به خطا مواجه شد!", "error");
                    }
                });
            });
    });

});


/***** Resize function end *****/

