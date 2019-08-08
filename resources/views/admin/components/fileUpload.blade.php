<div class="form-group mb-30">
                    <label class="control-label mb-10 text-left">{{ trans('admin.word_list') }}</label>
<div class="fileinput fileinput-new input-group" data-provides="fileinput">
    <div class="form-control" data-trigger="fileinput">
        <i class="glyphicon glyphicon-file fileinput-exists"></i>
        <span class="fileinput-filename"></span>
    </div>
    <span class="input-group-addon fileupload btn btn-info btn-anim btn-file">
                            <i class="fa fa-upload"></i>
                            <span class="fileinput-new btn-text">{{ trans('admin.select_file') }}</span>
                            <span class="fileinput-exists btn-text">{{ trans('admin.change') }}</span>
                            <input type="file" name="{{ $name }}">
														</span>
    <a href="#" class="input-group-addon btn btn-danger btn-anim fileinput-exists"
       data-dismiss="fileinput">
        <i class="fa fa-trash"></i>
        <span class="btn-text"> {{ trans('admin.delete') }}</span>
    </a>
</div>
</div>