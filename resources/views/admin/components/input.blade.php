<div class="form-group">
    <label class="control-label mb-10">{{ $lable }}</label>
    <input name="{{$name}}" @if(isset($value)) value="{{$value}}" @endif type="{{  isset($type)?$type:'text' }}" class="form-control {{ isset($class)?$class:'' }}"
           title="{{ $lable }}" @if(isset($placeholder)) placeholder="{{$placeholder}}" @endif @if(isset($required)) required @endif >
</div>