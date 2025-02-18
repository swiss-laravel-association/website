<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! seo() !!}

    <meta name="fediverse:creator" content="@swiss_laravel_association@phpc.social" />

    <link rel="me" href="https://x.com/swisslaravel">
    <link rel="me" href="https://github.com/swiss-laravel-association">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {!! \App\Helpers\SchemaHelper::render() !!}
</head>


<body class="flex flex-col min-h-screen font-sans antialiased bg-gray-100">
    <x-header />


    <x-nav />

    {{ $slot }}

    <x-footer/>

    @livewireScripts
</body>
</html>
