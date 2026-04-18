<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - JUSTRIX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }
        .animate-slideLeft {
            animation: slideInLeft 0.6s ease-out;
        }
        .animate-slideRight {
            animation: slideInRight 0.6s ease-out;
        }
        .register-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
            outline: none;
        }
    </style>
</head>
<body class="register-container min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-6xl w-full flex flex-col md:flex-row rounded-2xl overflow-hidden shadow-2xl">
            
            <!-- Left Side - Welcome Section -->
            <div class="md:w-1/2 bg-gradient-to-br from-purple-800 to-indigo-900 p-8 md:p-12 flex flex-col justify-center animate-slideLeft">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ url('/') }}" class="inline-flex items-center text-purple-200 hover:text-white transition group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Join JUSTRIX Today!</h1>
                    <p class="text-purple-200 mb-8 leading-relaxed">
                        Create your account and start shopping with us today!
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-purple-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Easy and secure shopping</span>
                        </div>
                        <div class="flex items-center space-x-3 text-purple-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Wide selection of products</span>
                        </div>
                        <div class="flex items-center space-x-3 text-purple-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Fast and reliable delivery</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Registration Form -->
            <div class="md:w-1/2 bg-white p-8 md:p-12 animate-slideRight">
                <div class="max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Create Account</h2>
                        <p class="text-gray-500 text-sm">Fill in your details to get started</p>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf
                        
                        <!-- Name Field -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition"
                                    placeholder="John Doe">
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email Field -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition"
                                    placeholder="you@example.com">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" name="password" required
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition"
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" name="password_confirmation" required
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition"
                                    placeholder="••••••••">
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md mt-6">
                            Create Account
                        </button>
                        
                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Already have an account?</span>
                            </div>
                        </div>
                        
                        <!-- Login Link -->
                        <p class="text-center">
                            <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-semibold hover:underline">
                                Sign in to your account
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="text-center py-4">
        <p class="text-purple-200 text-sm">&copy; 2024 JUSTRIX. All rights reserved.</p>
    </div>
    
</body>
</html>