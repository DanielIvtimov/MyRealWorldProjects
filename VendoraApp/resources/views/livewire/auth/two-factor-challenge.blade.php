<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Two Factor Challenge</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-gray-100 px-4 py-10 text-gray-900">
        <div class="mx-auto max-w-md rounded-xl bg-white p-6 shadow">
            <h1 class="text-2xl font-semibold">Two Factor Challenge</h1>
            <p class="mt-3 text-sm text-gray-600">
                This page is ready for the two-factor challenge flow when you enable that Fortify feature.
            </p>
        </div>
    </body>
</html>
