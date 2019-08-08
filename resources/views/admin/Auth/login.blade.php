<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <meta name="csrf-token" content="CsPKksDeApODmb0I1Bsf9eDZ9PEF8Ah3zQGJdtAa">
    <title> املاک 79 - </title>
    <script src="/asset/js/jquery.min.js"></script>
    <script src="/asset/js/bootstrap-multiselect.min.js"></script> <!--only for index-->


    <link rel="stylesheet" href="/asset/css/bootstrap.rtl.full.min.css">

    <link rel="stylesheet" id="responsive-css-css" href="/asset/css/responsive.css" type="text/css" media="all">
    <link rel="stylesheet" id="custom-responsive-css-css" href="/asset/css/custom-responsive.css" type="text/css"
          media="all">
    <link rel="stylesheet" id="rtl-custom-responsive-css-css" href="/asset/css/rtl-custom-responsive.css"
          type="text/css" media="all">
    <link href="/asset/css/singleshow.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" id="main-css-css" href="/asset/css/main.css" type="text/css" media="all">


    <link rel="stylesheet" id="rtl-main-css-css" href="/asset/css/rtl-main.css" type="text/css" media="all">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css">
    <!--only for index-->


    <meta name="theme-color" content="#ffffff">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="CsPKksDeApODmb0I1Bsf9eDZ9PEF8Ah3zQGJdtAa">
</head>
<body>
<div id="site">
    <div>
        <div class="minimum-height container" style="padding:50px">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3"><h3 class="text-center">ورود به پنل
                        ادمین</h3>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="frm" method="POST" action="{{action('Admin\Auth\LoginController@login')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="formControlsUsername" class="control-label"> نام کاربری
                            </label>
                            <input placeholder="نام کاربری را وارد نمایید" type="text" id="username" name="username"
                                   value="{{old('username','')}}" id="formControlsUsername" class="form-control">
                                <span style="position: inherit;margin-top: 0; right: 13px;" id="error" class="error help-block">  </span>
                        </div>
                        <div class="form-group"><label for="formControlsPassword" class="control-label">رمز عبور</label><input
                                    placeholder="رمز عبور خود را وارد نمایید" name="password" type="password"
                                    id="formControlsPassword"
                                    class="form-control"></div>
                        <div class="form-group col-xs-12">
                            <label class="form-inline checkbox" for="remember_me">
                                <input id="remember_me" type="checkbox"
                                       name="remember" {{old('remember') ? 'checked' : ''}} />
                                مرا به خاطر بسپار
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn btn-default btn-block">ورود</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/app.js"></script>
<script src="/asset/js/custom.js"></script>
<script src="/asset/js/bootstrap.min.js"></script>
<script src="/asset/js/jquery.validate.min.js"></script><!--only for this page-->
<script>
    $(document).on('submit', 'form#frm', function (event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                window.localStorage.setItem('access_token', response.token);
                window.localStorage.setItem('refresh_token', response.token);
                $('.is-invalid').removeClass('is-invalid');
                $('.has-error').removeClass('has-error');
                $('.error').text('');
                location.href = '/admin';

                // location.reload('/admin')
                // if (data.fail) {
                //     if (data.code){
                //         $('#error-code').html(data.code);
                //     }
                //     for (control in data.errors) {
                //         $('#' + control).addClass('is-invalid');
                //         $('#error-' + control).html(data.errors[control]);
                //
                //     }
                // } else {
                //     window.location.href = '/admin'
                // }

            },
            error: function (error) {
                console.log(error)
                var key='username';
                    $('#' + key).addClass('is-invalid');
                    $('#' + key).parent().addClass('has-error');
                    $('.error').text(error.responseJSON.error );

                $('.is-invalid:first').focus()

                // alert("Error: " + errorThrown);
            }
        });
        return false;
    });

</script>
</body>
</html>