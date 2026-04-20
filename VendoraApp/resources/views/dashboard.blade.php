<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-gray-100 px-4 py-10 text-gray-900">
        <div class="mx-auto max-w-3xl rounded-xl bg-white p-6 shadow">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        You are logged in as <span class="font-medium">{{ auth()->user()->email }}</span>.
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                    >
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
