<label class=" control-label">آپلود عکس های
    ملک:</label>
<div class="row">
    <div class="col-xs-12">
        <div class="clsbox-1" runat="server"  >
            <div class="dropzone clsbox" id="mydropzone"
                 data-url="{{ action('ImageController@uploadPhotos') }}"
                 data-id="{{ action('ImageController@getEstateImages') }}">
            </div>
        </div>

    </div>
</div>

{{--Dropzone Preview Template--}}
<div id="preview" style="display: none;">

    <div class="dz-preview dz-file-preview">
        <div class="dz-image"><img data-dz-thumbnail/></div>

        <div class="dz-details">
            <div class="dz-size"><span data-dz-size></span></div>
            <div class="dz-filename"><span data-dz-name></span></div>
        </div>
        <div class="dz-progress"><span class="dz-upload"
                                       data-dz-uploadprogress></span></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <a href="#" class="dz-hidden link">مخفی</a>

        <div class="dz-success-mark">

            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                 xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink"
                 xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                <title>Check</title>
                <desc>Created with Sketch.</desc>
                <defs></defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                   fill-rule="evenodd" sketch:type="MSPage">
                    <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                          id="Oval-2" stroke-opacity="0.198794158"
                          stroke="#747474" fill-opacity="0.816519475"
                          fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
                </g>
            </svg>

        </div>
        <div class="dz-error-mark">

            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1"
                 xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink"
                 xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                <title>error</title>
                <desc>Created with Sketch.</desc>
                <defs></defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                   fill-rule="evenodd" sketch:type="MSPage">
                    <g id="Check-+-Oval-2" sketch:type="MSLayerGroup"
                       stroke="#747474" stroke-opacity="0.198794158"
                       fill="#FFFFFF" fill-opacity="0.816519475">
                        <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"
                              id="Oval-2" sketch:type="MSShapeGroup"></path>
                    </g>
                </g>
            </svg>
        </div>
    </div>
</div>
<script src="{{asset('theme/admin/dropzone/js/dropzone.js')}}"></script>
{{--    <script src="{{asset('theme/admin/dropzone/js/dropzone-config.js')}}"></script>--}}
<script>
    $(document).ready()
    {
        var total_photos_counter = 0;
        var photo_counter = 0;
        var url = $('#mydropzone').data('url');

        Dropzone.autoDiscover = false;
        $("div#mydropzone").dropzone({
            url: url,
            headers: {
                'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
            },
            uploadMultiple: false,
            parallelUploads: 2,
            maxFilesize: 10,
            previewTemplate: document.querySelector('#preview').innerHTML,
            addRemoveLinks: true,
            dictRemoveFile: 'حذف فایل',
            dictFileTooBig: 'حجم تصویر انتخاب شده بیشتر از 10 مگابایت میباشد.',
            timeout: 10000,
            acceptedMimeTypes: 'image/*',
            init: function () {
                console.log('inja drop');
                var myDropzone = this;
                var url = $('#mydropzone').data('id');
                console.log('url', url);
                $.post(url, function (data) {
                    $.each(data.images, function (key, value) {
                        var file = {id: value.id, name: value.name, size: value.size};
                        myDropzone.options.addedfile.call(myDropzone, file);
                        myDropzone.options.thumbnail.call(myDropzone, file, value.thumb);
                        myDropzone.emit("complete", file);
                        photo_counter++;
                        $("#photoCounter").text("(" + photo_counter + ")");
                    });
                });

                this.on("removedfile", function (file) {
                    console.log(file.xhr);
                    var id;
                    if (file.xhr == undefined) {
                        id = file.id;
                    } else {
                        id = JSON.parse(file.xhr.response);
                    }
                    console.log('id', id);
                    $.ajax({
                        url: '/photos',
                        dataType: 'JSON',
                        type: 'DELETE',
                        data: {id: id},
                        success: function (data) {
                            total_photos_counter--;
                            $("#counter").text("# " + total_photos_counter);
                        },
                        error: function () {

                        }
                    });
                });
            },
            success: function (file, done) {
                console.log(done);
                total_photos_counter++;
                $("#counter").text("# " + total_photos_counter);
                $(".dz-hidden").attr('data-id', done);
                $(".dz-preview:last-child").attr('data-id', "done");
                var input = document.createElement("input");
                input.type = "hidden";
                input.value = done;
                input.name = "images[]";
                input.className = "dz-hidden-file";
                file.previewTemplate.appendChild(input);


            }
        });
    }
</script>

<script src="{{asset('theme/admin/dropzone/js/dropzone.js')}}"></script>
<script>
    var total_photos_counter = 0;
    var photo_counter = 0;
    var url = $('#mydropzone').data('url');

    Dropzone.autoDiscover = false;
    $("div#mydropzone").dropzone({
        url: url,
        headers: {
            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
        },
        uploadMultiple: false,
        parallelUploads: 2,
        maxFilesize: 10,
        previewTemplate: document.querySelector('#preview').innerHTML,
        addRemoveLinks: true,
        dictRemoveFile: 'حذف فایل',
        dictFileTooBig: 'حجم تصویر انتخاب شده بیشتر از 10 مگابایت میباشد.',
        timeout: 10000,
        acceptedMimeTypes: 'image/*',
        init: function () {
            console.log('load preview');
            var myDropzone = this;
            var url = $('#mydropzone').data('id');
            console.log('url', url);
            $.post(url, function (data) {
                $.each(data.images, function (key, value) {
                    var file = {id: value.id, name: value.name, size: value.size};
                    myDropzone.options.addedfile.call(myDropzone, file);
                    myDropzone.options.thumbnail.call(myDropzone, file, value.thumb);
                    myDropzone.emit("complete", file);
                    photo_counter++;
                    $("#photoCounter").text("(" + photo_counter + ")");
                });
            });
            this.on("removedfile", function (file) {
                console.log(file.xhr);
                var id;
                if (file.xhr == undefined) {
                    id = file.id;
                } else {
                    id = JSON.parse(file.xhr.response);
                }
                console.log('id', id);
                $.ajax({
                    url: '/photos',
                    dataType: 'JSON',
                    type: 'DELETE',
                    data: {id: id},
                    success: function (data) {
                        total_photos_counter--;
                        $("#counter").text("# " + total_photos_counter);
                    },
                    error: function () {

                    }
                });
            });
        },
        success: function (file, done) {
            console.log(done);
            total_photos_counter++;
            $("#counter").text("# " + total_photos_counter);
            var input = document.createElement("input");
            input.type = "hidden";
            input.value = done;
            input.name = "images[]";
            input.className = "dz-hidden-file";
            file.previewTemplate.appendChild(input);
            var a = document.createElement("a");
            a.innerHTML = 'مخفی کن';
            a.href = "javascript:undefined;";
            a.setAttribute('data-id', done);
            a.className = "dz-hidden-link dz-remove text-danger";
            file.previewTemplate.appendChild(a);


        }
    });

    $(document).on('click', '.dz-hidden-link', function () {
        event.preventDefault();
        var id = $(this).data('id');
        $('.loading').show();
        var element = $(this);
        $.ajax({
            type: "GET",
            url: "{{action('ImageController@hidePhoto')}}",
            data: {id},
            contentType: false,
            success: function (data) {
                element.text(null);
                if (data.status) {
                    element.text('مخغی کن');
                    element.removeClass('text-success');
                    element.addClass('text-danger');
                } else {
                    element.text('ظاهر کن');
                    element.removeClass('text-danger');
                    element.addClass('text-success');
                }

                $('.loading').hide();
                // $(".dz-hidden-link").text('salam');
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    });

    $('.social_btn').click(function () {
        $(this).closest('.social-wrapper').children('.form-group').slideToggle();
    });


    $().ready(function () {
        // validate the comment form when it is submitted
        $("#commentForm").validate();

    });

</script>