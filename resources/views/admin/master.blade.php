<!DOCTYPE html>
<html>
@include('admin.partials.head')

<body>

{{--<div class="preloader-it">--}}
    {{--<div class="la-anim-1"></div>--}}
{{--</div>--}}
<div class="wrapper theme-1-active pimary-color-green">

    <!-- Top Menu Items -->
@include('admin.partials.navbar')
<!-- /Top Menu Items -->

    <!-- Sidebar Menu -->
@include('admin.partials.sidebar')
<!-- Sidebar Menu -->


    <!-- Right Sidebar Backdrop -->
    <div class="right-sidebar-backdrop"></div>
    <!-- /Right Sidebar Backdrop -->

    <!-- Main Content -->
    <div class="page-wrapper" style="min-height: 100vh;">
        <div class="container-fluid">


            <div class="row heading-bg">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4>
                        @yield('title')
                    </h4>
                </div>
                <!-- Breadcrumb -->
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    @yield('breadcrumbs')
                </div>
                <!-- /Breadcrumb -->
            </div>

            <!-- /Title -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default card-view">
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->

        @include('admin.partials.footer')

        <!-- /Footer -->
        </div>
    </div>
    <!-- /Main Content -->

</div>
<!-- /#wrapper -->


@include('admin.partials.errors')
{{--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>--}}
<script src="{{ asset("theme/admin/components/jquery/dist/jquery.min.js") }}"></script>
<script src="{{asset('/theme/admin/js/bootstrap.min.js')}}"></script>
<script src="{{ asset("theme/admin/js/jquery.slimscroll.js") }}"></script>
{{--<script src="{{ asset("theme/admin/components/jquery-toast-plugin/dist/jquery.toast.min.js") }}"></script>--}}
{{--<script src="{{ asset("theme/admin/components/sweetalert/dist/sweetalert.min.js") }}"></script>--}}

<script src="{{ asset("theme/admin/js/init.js") }}"></script>

{{--<script src="{{asset('theme/admin/js/jquery.tooltipster.min.js')}}"></script>--}}
{{--<script src="{{asset('theme/admin/js/jquery.validate.min.js')}}"></script>--}}
<script src="{{asset('theme/admin/js/moment-with-locales.min.js')}}"></script>
{{--<script src="{{asset('theme/admin/js/bootstrap-datetimepicker.min.js')}}"></script>--}}

{{--<script src="{{asset('theme/admin/js/parsley.min.js')}}"></script>--}}

<script>
    // validation form wizard
    $(document).ready(function () {

        //validation
        // $('input, select').tooltipster({
        //     trigger: 'custom',
        //     onlyOne: false,
        //     position: 'bottom',
        //     theme: 'tooltipster-light'
        // });

        // $("#form").validate({
        //     errorPlacement: function (error, element) {
        //         var lastError = $(element).data('lastError'),
        //             newError = $(error).text();
        //
        //         $(element).data('lastError', newError);
        //
        //         if (newError !== '' && newError !== lastError) {
        //             $(element).tooltipster('content', newError);
        //             $(element).tooltipster('show');
        //         }
        //     },
        //     success: function (label, element) {
        //         $(element).tooltipster('hide');
        //     }
        // });
        /* This code handles all of the navigation stuff.
        ** Probably leave it. Credit to https://bootsnipp.com/snippets/featured/form-wizard-and-validation
        */
        var navListItems = $('ul.setup-panel li a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');
        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                $('input, select').tooltipster("hide");
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        /* Handles validating using jQuery validate.
        */
        allNextBtn.click(function () {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('ul.setup-panel li a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input"),
                isValid = true;

            //Loop through all inputs in this form group and validate them.
            for (var i = 0; i < curInputs.length; i++) {
                if (!$(curInputs[i]).valid()) {
                    isValid = false;
                }
            }

            if (isValid) {
                //Progress to the next page.
                nextStepWizard.removeClass('disabled').trigger('click');
                // # # # AJAX REQUEST HERE # # #
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);

            }

        });

        $('ul.setup-panel li a.btn-primary').trigger('click');

    });
</script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<script>
    // Add and Remove Phone & Sans
    // $('body').on('click', '.remove', function () {
    //     $(this).parents(".box:first").remove();
    // });


    if ($(".social-check").is(":checked")) {
        // checkbox is checked
        const url = $(this).data('url');
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                $(".add-social").append(response);
            }
        });
    } else {
    }
</script>

<script>
    // uplod image
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function (e) {
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
        $('.file-upload-content').remove();
        $('.image-upload-wrap').show();
    }

    $('.image-upload-wrap').bind('dragover', function () {
        $('.image-upload-wrap').addClass('image-dropping');
    });
    $('.image-upload-wrap').bind('dragleave', function () {
        $('.image-upload-wrap').removeClass('image-dropping');
    });

</script>



@stack('scripts')


</body>
</html>