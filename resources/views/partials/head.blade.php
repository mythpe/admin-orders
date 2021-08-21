<meta charset="utf-8">
<meta
        http-equiv="X-UA-Compatible"
        content="IE=edge"
>
<meta
        name="author"
        content="{!! $META_AUTHOR !!}"
>
<meta
        name="description"
        content="{!! $META_DESCRIPTION !!}"
>
<meta
        name="keywords"
        content="{!! $META_KEYWORDS !!}"
>
<meta
        name="viewport"
        content="width=device-width, initial-scale=1"
>
<meta
        name="csrf-token"
        content="{!! csrf_token() !!}"
>

<link
        rel="shortcut icon"
        href="{{asset(manifest_directory("/favicon.ico"))}}"
>
<link
        rel="apple-touch-icon-precomposed"
        href="{{asset("images/logo.png")}}"
>
<link
        rel="apple-touch-icon-precomposed"
        sizes="72x72"
        href="{{asset("images/logo.png")}}"
>
<link
        rel="apple-touch-icon-precomposed"
        sizes="114x114"
        href="{{asset("images/logo.png")}}"
>
<link
        rel="apple-touch-icon-precomposed"
        sizes="144x144"
        href="{{asset("images/logo.png")}}"
>

<title>{!! $APP_NAME !!} @hasSection("title") - @yield("title")@endif @hasSection("extra_title")
        - @yield("extra_title")@endif</title>
