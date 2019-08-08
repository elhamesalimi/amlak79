<div class="panel panel-info">
    <div class="panel-heading ">{{isset($category) ? 'ویرایش لغت  ' : 'افزودن لغت'}}</div>
    <div class="panel-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" id="frm"
              action="{{isset($category)?action('Admin\VocabularyController@update', ['vocabulary'=>$category]):action('Admin\VocabularyController@store')}}">
            @csrf
            @isset($category)
                {{ method_field('PUT') }}
            @endisset
            <div class="form-group">
                <label class="control-label">دسته مادر :</label>
                <select name="vocat" class="form-control" required>
                    <option value="">مادر</option>
                    @foreach($categories as $key=>$value)
                        <option value="{{$key}}" {{isset($category)?$category->vocat_id === $key ?'selected':'' :''}} >{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="control-label"> لغت : </label>
                <input type="text" class="form-control" name="vocab" id="vocab"
                       value="{{old('vocab',isset($category)?$category->vocab:'')}}" required>
                @if ($errors->has('vocab'))
                    <span class="help-block">
                        <strong>{{ $errors->first('vocab') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label class="control-label">ترجمه لغت : </label>
                <input type="text" class="form-control" name="trans" id="trans"
                       value="{{old('trans',isset($category)?$category->trans:'')}}" required>
                @if ($errors->has('trans'))
                    <span class="help-block">
                        <strong>{{ $errors->first('trans') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block "
                       value="{{isset($category) ? 'ویرایش لغت' : 'ایجاد لغت'}}">
            </div>
        </form>
    </div>
</div>