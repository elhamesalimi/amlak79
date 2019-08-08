var total_photos_counter = 0;
var photo_counter = 0;

Dropzone.options.myDropzone = {
    uploadMultiple: false,
    parallelUploads: 2,
    maxFilesize: 1,
    previewTemplate: document.querySelector('#preview').innerHTML,
    addRemoveLinks: true,
    dictRemoveFile: 'حذف فایل',
    dictFileTooBig: 'حجم تصویر انتخاب شده بیشتر از 1 مگابایت میباشد.',
    timeout: 10000,

    init: function () {

        var myDropzone = this;
        $.get('/server-images', function (data) {
            $.each(data.images, function (key, value) {
                var file = {id: value.id,name: value.name, size: value.size};
                myDropzone.options.addedfile.call(myDropzone, file);
                myDropzone.options.thumbnail.call(myDropzone, file, value.thumb);
                myDropzone.emit("complete", file);
                photo_counter++;
                $("#photoCounter").text("(" + photo_counter + ")");
            });
        });

        this.on("removedfile", function (file) {
                var id;

                if(file.xhr==undefined){
                    id=file.id;
                }else {
                    id=JSON.parse(file.xhr.response).id;
                }
            $.ajax({
                url: '/images-delete',
                dataType: 'JSON',
                type: 'post',
                data: {id:id},
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
        total_photos_counter++;
        $("#counter").text("# " + total_photos_counter);


    }
};