@foreach($tags as $tag)
    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
@endforeach