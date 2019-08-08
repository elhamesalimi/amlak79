@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <script>
            toast('خطا' , '{{$error}}',0,2000);
        </script>
    @endforeach
@endif
