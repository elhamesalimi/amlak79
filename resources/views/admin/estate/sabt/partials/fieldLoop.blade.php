{{--@if(count($fields) > 0 )--}}
@foreach($fields as $field)
    @switch($field->component)
        @case('input')
        @if($field->type==='checkbox')
            <div class="col-sm-1 col-xs-12 ">
                <div class="form-group" style="padding-top: 30px; white-space: nowrap">
                    <label for="{{$field->name}}">
                        <input class="" id="{{$field->name}}"
                               {{isset($fields_value[$field->name]) ? $fields_value[$field->name] ? 'checked' : '' : ''}} name="{{$field->name}}"
                               type="{{$field->type}}"
                        >
                        {{$field->label}}</label>
                </div>
            </div>
        @else
            <div class="{{$field->attribute==='disabled'?'col-sm-2 ':'col-sm-3 '}}col-xs-12 ">
                <div class="form-group">
                    <label for="{{$field->name}}">{{$field->label}}</label>
                    <input class="form-control"
                           value="{{isset($fields_value[$field->name])  ?$fields_value[$field->name]: ''}}"
                           {{ isset($fields_value[$field->name]) && $field->attribute==='disabled' ? '' : $field->attribute}}  id="{{$field->name}}"
                           name="{{$field->name}}" type="{{$field->type}}"
                           data-category="quantity" placeholder="{{$field->placeholder}}"
                    />
                </div>
            </div>
        @endif
        @break
        @case('select')
        @if($field->name==='delivery')
            <div class="{{$field->attribute==='disabled'?'col-sm-2':'col-sm-3'}} col-xs-12 ">
                <div class="form-group">
                    <label for="{{$field->name}}">{{$field->label}}</label>
                    <select id="{{$field->name}}" class="label form-control"
                            {{ isset($fields_value[$field->name]) && $field->attribute==='disabled' ? '' : $field->attribute}}  name="{{$field->name}}"
                            data-rule-required="true"
                            data-rule-required="true"
                            data-msg-required="این فیلد الزامی است">
                        <option value="" hidden>انتخاب کنید</option>
                        @if(isset($fields_value[$field->name]) &&$fields_value[$field->name] <0)
                            <option value="-1" selected> گذشته</option>
                        @endif
                        @if(is_array($field->options) > 0 )
                            @foreach($field->options as $key=>$value)
                                <option {{isset($fields_value[$field->name]) ?  $fields_value[$field->name]==$key ? 'selected' : '' : ''}} value="{{$key}}">{{$value}}</option>
                            @endforeach
                        @endif
                    </select>

                </div>
            </div>
        @else
            <div class="{{$field->attribute==='disabled'?'col-sm-2':'col-sm-3'}} col-xs-12 ">
                <div class="form-group">
                    <label for="{{$field->name}}">{{$field->label}}</label>
                    <select id="{{$field->name}}" class="label form-control"
                            {{ isset($fields_value[$field->name]) && $field->attribute==='disabled' ? '' : $field->attribute}}  name="{{$field->name}}"
                            data-rule-required="true"
                            data-rule-required="true"
                            data-msg-required="این فیلد الزامی است">
                        <option value="" hidden>انتخاب کنید</option>
                        @if(is_array($field->options) > 0 )
                            @foreach($field->options as $key=>$value)
                                <option {{isset($fields_value[$field->name]) ?  $fields_value[$field->name]==$key ? 'selected' : '' : ''}} value="{{$key}}">{{$value}}</option>
                            @endforeach
                        @endif
                    </select>

                </div>
            </div>
        @endif
        @break
        @case('select2')
        <div class="{{$field->attribute==='disabled'?'col-sm-2':'col-sm-3'}} col-xs-12 ">
            <div class="form-group">
                <label for="{{$field->name}}">{{$field->label}}</label>
                <select id="{{$field->name}}" class="label form-control" multiple="multiple"
                        {{ isset($fields_value[$field->name]) && $field->attribute==='disabled' ? '' : $field->attribute}}  name="{{$field->name}}[]"
                        data-rule-required="true"
                        data-rule-required="true"
                        data-msg-required="این فیلد الزامی است">
                    <option value="" hidden>انتخاب کنید</option>
                    @if(is_array($field->options) > 0 )
                        @foreach($field->options as $key=>$value)
                            <option {{isset($fields_value[$field->name]) ? in_array($key,$fields_value[$field->name])?'selected':'' : ''}} value="{{$key}}">{{$value}}</option>
                        @endforeach
                    @endif
                </select>

            </div>

        </div>
        @break
    @endswitch
@endforeach
{{--@dd($fields_value['delivery']==3)--}}
<div class="col-sm-9 col-xs-12 mb20">
    <div class="form-group">
        <label for="address" class="active">آدرس</label>
        <textarea class="form-control" name="address" id="address" data-rule-required="true"
                  data-msg-required="این فیلد الزامی است">{{$fields_value['address'] ?? ''}}</textarea>
    </div>
</div>
<div class="clearfix"></div>
@if(count($facilities) > 0)
    <div class="facilities mb20">
        <h4 class="ff-title"> امکانات</h4>
        @foreach($facilities as $facility)
            <div class="col-md-2 form-group col-xs-6 mb20">
                <label for="{{$facility->slug}}" class="Parking">
                    <input class="" id="{{$facility->slug}}" name="{{$facility->slug}}"
                           {{isset($facilities_value) ? in_array($facility->slug,$facilities_value) ? 'checked' : '':''}} type="checkbox">
                    {{$facility->name}}
                </label>
            </div>
        @endforeach
        <div class="col-sm-4 form-group form-inline col-xs-6 field placeholder mb20">
            <label for="type_cabinet" class="active"> نوع کابینت </label>
            @php($cabinetType = ['فلزی','mdf','چوب','طرح mdf','های گلاس','ممبران','سایر'])
            <select id="type_cabinet" class=" form-control label" name="type_cabinet"
                    {{isset($fields_value['type_cabinet']) ? '': 'disabled'}}>
                <option className="light-gray" value="" hidden>انتخاب کنید</option>
                @foreach($cabinetType as $index=>$value)
                    <option className="light-gray"
                            {{isset($fields_value['type_cabinet']) && $fields_value['type_cabinet']=== $value ? 'selected':''}}  value="{{$value}}">
                        {{$value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif
<div class="col-md-12 col-xs-12">
    <div class="form-group">
        <label for="description">توضیحات </label>
        <textarea name="description" id="description"
                  class="form-control" cols="30" rows="6">{{ $fields_value['description'] ?? ''}}</textarea>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
        <label for="more">اطلاعات بیشتر</label>
        <textarea name="more" id="more"
                  class="form-control" cols="30" rows="6">{{ $fields_value['more'] ?? ''}}</textarea>
    </div>
</div>

