<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
                <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
        <link id="googleFonts" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800%7CShadows+Into+Light%7CPlayfair+Display:400&display=swap" rel="stylesheet" type="text/css">
        <!-- Vendor CSS -->
        <link rel="stylesheet" href="{{ asset('theme/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('theme/vendor/fontawesome-free/css/all.min.css') }}">
        <!-- Theme CSS -->
         <link rel="stylesheet" href="{{ asset('theme/css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('theme/css/theme.css') }}">
        <link rel="stylesheet" href="{{ asset('theme/css/theme-elements.css') }}">
        <link rel="stylesheet" href="{{ asset('theme/css/theme-blog.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('theme/css/style.css') }}" />
        <link rel="icon" href="{{ asset('theme/img/logo.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('theme/img/logo.png') }}" sizes="192x192" />
          <!-- Skin CSS -->
        <link id="skinCSS" rel="stylesheet" href="{{ asset('theme/css/skins/default.css') }}">

        <!-- Theme Custom CSS -->
       

        <!-- Head Libs -->
        <script src="{{ asset('theme/vendor/modernizr/modernizr.min.js') }}"></script>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased" data-plugin-page-transition>
        <div class="body">
        
              @inertia
        </div>
      

        @env ('local')
            <script src="http://localhost:8000/browser-sync/browser-sync-client.js"></script>
        @endenv
       <!-- Vendor -->

    </body>
</html>