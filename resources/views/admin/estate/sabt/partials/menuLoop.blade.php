<ul class="categories-select">
    @foreach($categories as $category)
        <li data-selected="{{ $category->id }}" value="{{ $category->id }}" data-dropdown-text="{{ $category->name }}">
                {{--<input class="cate" type="hidden" value="{{ $category->id }}">--}}
                {!! \App\Http\Controllers\admin\PostController::getParentCategory($category->id) !!}
        </li>
    @endforeach
</ul>