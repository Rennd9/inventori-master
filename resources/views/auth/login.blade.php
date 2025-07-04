<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Simple Neobrutalism</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        .brutal-shadow {
            box-shadow: 4px 4px 0px #000;
        }
        
        .brutal-hover:hover {
            box-shadow: 6px 6px 0px #000;
            transform: translate(-2px, -2px);
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Left Sidebar -->
        <div class="hidden lg:flex lg:w-1/2 bg-blue-500 border-r-4 border-black items-center justify-center p-12">
            <div class="text-center text-white">
                <div class="bg-white text-blue-500 p-6 border-4 border-black brutal-shadow mb-8 inline-block">
                    <h1 class="text-4xl font-bold">WELCOME</h1>
                </div>
                <p class="text-xl font-semibold mb-4">Masuk ke akun Anda</p>
                <p class="text-lg opacity-90">Manajement Barang Inventori untuk kebutuhan Anda</p>
            </div>
        </div>
        
        <!-- Right Content Area -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Login Card -->
                <div class="bg-white border-4 border-black brutal-shadow p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">LOGIN</h2>
                        <p class="text-gray-600">Masukkan Email Dan Password Anda</p>
    @if(session('error'))
 <div class=" bg-white text-blue-500 p-4 border-black brutal-shadow mb-8 inline-block " >
    <h1 class="font-bold"> {{ session('error') }}</h1>
    </div>
@endif
                    </div>
                    
                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email" 
                                autofocus
                                class="w-full px-4 py-3 border-3 border-black focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all duration-200 @error('email') border-red-500 bg-red-50 @enderror"
                                placeholder="nama@email.com"
                            >
                            @error('email')
                                <div class="mt-2 text-sm text-red-600 font-medium">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                class="w-full px-4 py-3 border-3 border-black focus:outline-none focus:border-blue-500 focus:bg-blue-50 transition-all duration-200 @error('password') border-red-500 bg-red-50 @enderror"
                                placeholder="Masukkan password"
                            >
                            @error('password')
                                <div class="mt-2 text-sm text-red-600 font-medium">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="remember" 
                                    id="remember" 
                                    class="w-4 h-4 border-2 border-black rounded-none" 
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <label for="remember" class="ml-2 text-sm font-medium text-gray-700">
                                    Ingat saya
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a 
                                    href="{{ route('password.request') }}" 
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline"
                                >
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        
                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white border-4 border-black py-3 px-6 font-bold text-lg brutal-hover transition-all duration-200"
                        >
                            MASUK
                        </button>
                    </form>
                    
                    <!-- Additional Links -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Header (visible on small screens) -->
    <div class="lg:hidden bg-blue-500 border-b-4 border-black p-4">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-white">WELCOME BACK</h1>
        </div>
    </div>
    
    <script>
        // Simple focus effects
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>