<!doctype html>
<html
        lang="{{$LOCALE}}"
        dir="{{$DIRECTION}}"
>
<head>
    @include('partials.head')
    <link
            href="{{ asset(manifest_directory("pdf-style/app-{$DIRECTION}.css")) }}"
            rel="stylesheet"
            type="text/css"
    >
    <link
            href="{{ asset(manifest_directory("fonts/fontawesome-free/css/all.css")) }}"
            rel="stylesheet"
            type="text/css"
    >

    <style>
        body {
            font-family: 'Kufi', 'Sans', monospace !important;
            background-color: white;
        }

        .pointer {
            cursor: pointer;
        }

        table.table-valign-middle th,
        table.table-valign-middle td {
            vertical-align: middle;
        }

        .page-break-inside-avoid {
            page-break-inside: avoid !important;
        }
    </style>
    @stack('styles')
</head>

<body>
<div class="d-print-none col-md-6 mr-auto p-5 top-print">
    <div
            class="pt-2 pl-2"
            style="position:fixed;top: 0;{{$ALIGN}}: 0;"
    >
        <a
                href="javascript:void(0);"
                class="btn btn-dark"
                onclick="printWindow()"
        >
            @lang( 'global.print' )
        </a>

        <a
                href="javascript:void(0)"
                onclick="window.opener ? window.close() : location.href = '{{ redirect()->back()->getTargetUrl() }}'"
                class='btn btn-danger'
        >
            @lang( 'global.back' )
        </a>
    </div>
</div>

@yield('print_content')

<script src="{{asset(manifest_directory('js/jquery/jquery.min.js'))}}"></script>

<script>
  window.arabicString = str => {
    try {
      if (!str.toString().trim())
        return str
      // console.log(str);
      let nStr =
        str.toString().replace(/9/g, '٩').replace(/8/g, '٨').replace(/7/g, '٧').replace(/6/g, '٦').replace(/5/g, '٥').replace(/4/g, '٤').replace(/3/g, '٣').replace(/2/g, '٢').replace(/1/g, '١').replace(/0/g, '٠')

      // Fix Hijri Date
      if (str.split('-').length === 3)
        nStr = nStr.replace(/-/g, '/').replace(/\/٠/g, '/')
      // console.log(nStr);
      return nStr
    } catch (e) {

    }
    return str
  }
  window.printWindow = () => {
    const inputClassName = 'd-print-none'
    const className = 'span-print'
    $(`.${className}`).remove()
    $(':input.' + inputClassName).each(function () {
      const elm = $(this)
      const span = $('<span></span>')
      span.attr('class', className)
      span.html(elm.is('select') ? elm.find('option:selected').text() : elm.val().toString().replace(/\n/g, '<br />'))
      span.insertAfter(elm)
    })

    // $(document).ready(function() {
    window.print()
    $(`.${className}`).remove()
    //   $('.arabic-string').each((i, v) => {
    //     let s = arabicString($(v).text());
    //   });
    // });
  }

  $(document).ready(function () {
    $('.arabic-string').each((i, v) => {

      let elm = $(v), e
      if (elm.is('input')) {
        elm.val(arabicString(elm.val()))
      } else {
        elm.text(arabicString(elm.text()))
      }
      // (e = elm.text()) &&
      // elm.text(arabicString(e));
    })
  })
</script>
@stack('scripts')
</body>
</html>
