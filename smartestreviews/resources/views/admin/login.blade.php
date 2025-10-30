@extends('layouts.app')

@section('title', 'Admin Login - Smartest Reviews')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <!-- Form -->
        <form method="POST" action="{{ route('admin.login') }}" class="bg-white p-8 rounded-lg shadow-md">
            @csrf
            
            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Đăng nhập admin</h2>

            <!-- Alerts -->
            @if($errors->any())
                <div class="mb-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                           placeholder="email" value="{{ old('email') }}">
                </div>

                <!-- Password -->
                <div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                           placeholder="Mật khẩu">
                </div>

                <!-- Remember me -->
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ghi nhớ đăng nhập</label>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="mt-6 w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Đăng nhập
            </button>
        </form>
    </div>
</div>
@endsection
