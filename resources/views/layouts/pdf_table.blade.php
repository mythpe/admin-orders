<!doctype html>
<html
        lang="{{$LOCALE}}"
        dir="{{$DIRECTION}}"
>
<head>
    @include('partials.head')
    <link
            href="{{ asset(manifest_directory("/pdf-style/app-{$DIRECTION}.css")) }}"
            rel="stylesheet"
            type="text/css"
    >
    <style>
        body {
            font-family: 'Kufi', 'Sans', monospace !important;
        }
    </style>
</head>
@section('title',$pageTitle)
<body>
<table class="table table-bordered table-condensed table-striped">
    <tr class="text-center">
        <th colspan="{{count($headerItems)}}">{{$pageTitle}}</th>
    </tr>
    <tr>
        @foreach($headerItems as $k => $headerItem)
            <th>{!! ($headerItem['text'] ?? $k)  !!}</th>
        @endforeach
    </tr>

    @foreach($items as $itemKey => $item)
        <tr>
            @foreach($headerItems as $k => $headerItem)
                <td>{!! ($item[ ($headerItem['value'] ?? $k) ] ?? $k) !!}</td>
            @endforeach
        </tr>
    @endforeach
</table>
</body>
</html>
