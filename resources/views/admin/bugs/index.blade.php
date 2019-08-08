@extends('admin.master')
{{--@section('breadcrumbs', Breadcrumbs::render('posts'))--}}
@section('title','گزارش اشکالات رسیده')
@push('styles')
@endpush
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
        @endif
   <ul class="bug">
       @forelse($estates as $estate)
           <li class="first-loop"> <h4 class="h-border">کد ملک : {{$estate->id}}</h4>
           <ul>
               @foreach($estate->bugs->groupBy('title') as $title=>$bugs)
                   <li><h5 style="display: inline-flex"> <form action="{{action('Admin\BugController@destroy',['estate'=>$estate,'bug_id'=>$bugs[0]->id])}}" method="POST">
                               {{ csrf_field() }}
                               {{ method_field('DELETE') }}
                               <button type="submit" style="border: none;background: none" class="text-danger" ><i class="fa fa-trash-o" style="font-size: 23px"></i></button>
                           </form>
                           {{$title}} </h5>
                   <ul>
                       @foreach($bugs as $bug)
                       <li>
                           <ul>
                               @forelse($bug->pivot->meta as $key=>$value)
                                   <li style="margin-right: 10px;">
                                       {{jdate($bug->pivot->created_at)->format('H:i Y-m-d ')}} - {{$value}}</li>
                               @empty
                                   {{jdate($bug->pivot->created_at)->format('H:i Y-m-d ')}}
                                   @endforelse
                           </ul>

                       </li>
                           @endforeach
                   </ul>
                   </li>
                   @endforeach
           </ul>
           </li>
           @empty
           <p>اشکالی گزارش نشده است.</p>
           @endforelse
   </ul>
@endsection
@push('scripts')

@endpush