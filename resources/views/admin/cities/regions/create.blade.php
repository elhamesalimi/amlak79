<style>
    .error{
        color: #a94442;
    }
</style>
<div class="panel panel-warning ">
    <div class="panel-heading ">{{isset($region) ? 'ویرایش منطقه  ' : 'افزودن منطقه'}}</div>
    <div class="panel-body" style=" padding: 15px;">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form   method="POST" id="frm"
                action="{{isset($region)?action('Admin\RegionController@update', ['region'=>$region]):action('Admin\RegionController@store')}}">
            @csrf
            @isset($region)
                {{ method_field('PUT')}}
            @endisset
            <div class="form-group">
                <label class="control-label">نام شهر :</label>
                <select name="city_id" class="form-control" id="city_id">
                    @foreach($cities as $city)
                        <option value="{{$city->id}}" {{isset($region)?$region->city_id === $city->id ?'selected':'' :''}}  >{{$city->name}}</option>
                    @endforeach
                </select>

            </div>
            <div class="form-group">
                <label class="control-label">نام منطقه : </label>
                <input type="text" class="form-control" name="name" id="name"
                       value="{{old('name',isset($region)?$region->name:'')}}" required>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label class="control-label">ترتیب چینش  : </label>
                <input type="number" class="form-control" name="arrange" id="arrange"
                       value="{{old('arrange',isset($region)?$region->arrange:0)}}" required>
                @if ($errors->has('arrange'))
                    <span class="help-block">
                        <strong>{{ $errors->first('arrange') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block " value="{{isset($region) ? 'ویرایش منطقه' : 'ایجاد منطقه'}}">
            </div>
        </form>
    </div>
</div>