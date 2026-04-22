<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-start justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="text-3xl font-bold text-blue-600">
                    {{ config('app.name') }}
                </a>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Create an Account</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-indigo-500">
                        Sign in
                    </a>
                </p>
            </div>
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
                <!-- Registration Form -->
                <form action="{{ route('register') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input 
                         type="text" 
                         name="name" 
                         id="name" 
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                         required
                         value="{{ old('name') }}"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div> 
                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input
                         id="email"
                         type="email"
                         name="email"
                         value="{{ old('email') }}"
                         required
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input
                         id="phone"
                         type="tel"
                         name="phone"
                         value="{{ old('phone') }}"
                         required
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input 
                         id="password"
                         type="password"
                         name="password"
                         required
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input 
                         id="password_confirmation"
                         type="password"
                         name="password_confirmation"
                         required
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-center gap-2">
                            <input 
                             type="checkbox"
                             required
                             class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-600">
                                I agree to the 
                                <a href="#" class="text-blue-600 hover:text-indigo-500">Terms and Conditions</a>
                                and 
                                <a href="#" class="text-blue-600 hover:text-indigo-500">Privacy Policy</a>
                            </span>
                        </label>
                    </div>
                    <!-- Submit Button -->
                    <button type="submit"class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition font-semibold">
                        Create Account 
                    </button>
                </form>
                <!-- Benefits -->
                <div class="mt-6 p-4 bg-indigo-50 rounded-lg">
                    <p class="text-sm font-medium text-indigo-900 mb-2">Why join us?</p>
                    <ul class="text-sm text-indigo-700 space-y-1">
                        <li>✓ Track your orders easily</li>
                        <li>✓ Save multiple addresses</li>
                        <li>✓ Get exclusive member offers</li>
                        <li>✓ Faster checkout process</li>
                    </ul>
                </div>
                <!-- Back to Home -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="font-medium text-blue-600 hover:text-indigo-500">← Back to Home</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>