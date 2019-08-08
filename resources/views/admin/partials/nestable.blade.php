@push('styles')
    @if(LaravelLocalization::getCurrentLocaleDirection()=="rtl")
        <link href="{{ asset("theme/admin/components/nestable2/jquery.nestable-rtl.css") }}" rel="stylesheet"
              type="text/css">
    @else
        <link href="{{ asset("theme/admin/components/nestable2/jquery.nestable.css") }}" rel="stylesheet"
              type="text/css">
    @endif
@endpush
@push('scripts')
    @if(LaravelLocalization::getCurrentLocaleDirection()=="rtl")
        <script src="{{ asset("theme/admin/components/nestable2/jquery.nestable-rtl.js") }}"></script>
    @else
        <script src="{{ asset("theme/admin/components/nestable2/jquery.nestable.js") }}"></script>
    @endif
@endpush