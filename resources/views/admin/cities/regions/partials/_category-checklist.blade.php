
<li><lable><input name="categories[]"  type="checkbox" value="{{ $category->id }}"
                {{old('categories')?in_array($category->id,old('categories')) ?'checked' :isset($post)? in_array($category->id,$post->categories->pluck('id')->toArray()) ? 'checked': '':'':''}} > {{ $category->name }}</lable>

</li>
@if ($category->children->count() > 0)
    <ul >
        @foreach($category->children as $child)
            @include('admin.posts.categories.partials._category-checklist', ['category' => $child])
        @endforeach
    </ul>
@endif