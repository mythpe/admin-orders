<!doctype html>
<html
        lang="{{ $LOCALE }}"
        dir="{{$DIRECTION}}"
        class=""
>
<head>
    @include('partials.head')
    <link
            href="{{ mix('css/app.css',manifest_directory()) }}"
            rel="stylesheet"
    >
    <script
            src="{{ mix('js/app.js',manifest_directory()) }}"
            defer
    ></script>
    <title></title>
</head>
<body>
<div id="app"></div>
</body>
</html>
