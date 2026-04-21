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
    <body class="min-h-screen bg-gray-100 text-gray-900">
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <p class="text-sm font-medium text-blue-600">Laravel Starter Style</p>
                    <h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        You are logged in as <span class="font-medium">{{ auth()->user()->email }}</span>.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('home') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Home
                    </a>

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
        </header>

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 lg:col-span-2">
                    <h2 class="text-lg font-semibold">Starter Resources</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        The problem was not with login itself. The issue was that your `/dashboard` route was pointing
                        to a very small custom Blade file, so after authentication you only saw a message and a logout
                        button. `Fortify` gives you authentication logic, but it does not generate a rich starter-kit
                        dashboard screen on its own.
                    </p>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <a
                            href="https://laravel.com/docs"
                            target="_blank"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-blue-300 hover:bg-blue-50"
                        >
                            <h3 class="font-medium text-gray-900">Documentation</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Read the official Laravel documentation and explore the framework features.
                            </p>
                        </a>

                        <a
                            href="https://github.com/laravel/laravel"
                            target="_blank"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-blue-300 hover:bg-blue-50"
                        >
                            <h3 class="font-medium text-gray-900">Repository</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Browse the Laravel application skeleton and compare it with your project.
                            </p>
                        </a>

                        <a
                            href="https://laracasts.com"
                            target="_blank"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-blue-300 hover:bg-blue-50"
                        >
                            <h3 class="font-medium text-gray-900">Laracasts</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Learn authentication, Blade, Eloquent, and full Laravel workflows through videos.
                            </p>
                        </a>

                        <a
                            href="https://livewire.laravel.com"
                            target="_blank"
                            class="rounded-xl border border-gray-200 p-4 transition hover:border-blue-300 hover:bg-blue-50"
                        >
                            <h3 class="font-medium text-gray-900">Livewire</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Explore interactive Laravel frontend development with Livewire, Volt, and Flux.
                            </p>
                        </a>
                    </div>
                </section>

                <aside class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-lg font-semibold">Quick Info</h2>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        <li class="rounded-xl border border-gray-200 p-4">
                            <span class="block font-medium text-gray-900">Why this happened</span>
                            <span class="mt-1 block">
                                Your project was using a custom minimal dashboard view instead of a starter-style page.
                            </span>
                        </li>
                        <li class="rounded-xl border border-gray-200 p-4">
                            <span class="block font-medium text-gray-900">What was needed</span>
                            <span class="mt-1 block">
                                A richer `dashboard.blade.php` so the authenticated redirect lands on a full screen.
                            </span>
                        </li>
                        <li class="rounded-xl border border-gray-200 p-4">
                            <span class="block font-medium text-gray-900">Current state</span>
                            <span class="mt-1 block">
                                Login still uses your Fortify setup, but the post-login dashboard now looks like a
                                proper starter screen.
                            </span>
                        </li>
                    </ul>
                </aside>
            </div>
        </main>
    </body>
</html>
