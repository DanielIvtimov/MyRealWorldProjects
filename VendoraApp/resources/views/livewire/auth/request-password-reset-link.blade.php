<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-gray-100 px-4 py-10 text-gray-900">
        <div class="mx-auto max-w-md rounded-xl bg-white p-6 shadow">
            <h1 class="text-2xl font-semibold">Forgot Password</h1>
            <p class="mt-3 text-sm text-gray-600">Enter your email and we will send you a password reset link.</p>

            @if (session('status'))
                <div class="mt-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-gray-900"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                >
                    Send reset link
                </button>
            </form>
        </div>
    </body>
</html>
