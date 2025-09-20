<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Football Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* ============= FOOTBALL ANIMATION BACKGROUND ============= */
        .ball-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }
        .ball {
            position: absolute;
            width: 50px;
            height: 50px;
            background-image: url('{{ asset("ball.png") }}'); /* Transparent football PNG */
            background-size: cover;
            opacity: 0.08;
            animation: float 20s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-30px) rotate(90deg); }
            50% { transform: translateY(0px) rotate(180deg); }
            75% { transform: translateY(30px) rotate(270deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center min-h-screen p-4">

    <!-- Animated Football Background -->
    <div class="ball-bg">
        <div class="ball" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="ball" style="top: 60%; left: 80%; animation-delay: -4s;"></div>
        <div class="ball" style="top: 40%; left: 30%; animation-delay: -8s;"></div>
        <div class="ball" style="top: 80%; left: 60%; animation-delay: -12s;"></div>
        <div class="ball" style="top: 15%; left: 75%; animation-delay: -16s;"></div>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md z-10 mx-4">
        <img src="{{ asset('WhatsApp Image 2025-09-16 at 19.31.12_62e45b1b.jpg') }}" alt="" class="h-40 w-full">

        <h2 class="text-2xl font-bold text-center mb-2 text-gray-800">JOIN THE TEAM</h2>
        <p class="text-center text-gray-600 mb-6">Create your account to get in the game</p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Full Name
                    </div>
                </label>
                <input type="text" id="name" name="name" 
                       class="form-input shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight 
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                       value="{{ old('name') }}" required autofocus
                       placeholder="Enter your full name">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        Email Address
                    </div>
                </label>
                <input type="email" id="email" name="email" 
                       class="form-input shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight 
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                       value="{{ old('email') }}" required
                       placeholder="your@email.com">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Password
                    </div>
                </label>
                <input type="password" id="password" name="password" 
                       class="form-input shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight 
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                       required
                       placeholder="Create a strong password">
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 5-5v0a5 5 0 0 1 5 5v4"></path>
                        </svg>
                        Confirm Password
                    </div>
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="form-input shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight 
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                       required
                       placeholder="Confirm your password">
            </div>
            <div class="flex items-center justify-between mb-4">
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none 
                        focus:shadow-outline transform hover:scale-105 transition-all duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Register
                </button>
                <a href="{{ route('login') }}" class="inline-block align-baseline font-bold text-sm text-green-600 hover:text-green-800">
                    Already registered?
                </a>
            </div>
        </form>
        
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-center text-gray-600 text-sm">By registering, you agree to our 
                <a href="#" class="text-green-600 hover:text-green-800">Terms of Service</a> and 
                <a href="#" class="text-green-600 hover:text-green-800">Privacy Policy</a>
            </p>
        </div>
    </div>

    <!-- Animated balls in the background -->
    <div class="absolute bottom-5 left-1/4 floating opacity-25">
        <svg width="40" height="40" viewBox="0 0 40 40">
            <circle cx="20" cy="20" r="15" fill="#FFFFFF" />
            <path d="M20,5 C23,5 26,6 28,8 C30,10 31,13 31,16 C31,19 30,22 28,24 C26,26 23,27 20,27 C17,27 14,26 12,24 C10,22 9,19 9,16 C9,13 10,10 12,8 C14,6 17,5 20,5 Z" fill="#000000" />
        </svg>
    </div>
    
    <div class="absolute top-20 right-1/4 floating opacity-25" style="animation-delay: 1s;">
        <svg width="40" height="40" viewBox="0 0 40 40">
            <circle cx="20" cy="20" r="15" fill="#FFFFFF" />
            <path d="M20,5 C23,5 26,6 28,8 C30,10 31,13 31,16 C31,19 30,22 28,24 C26,26 23,27 20,27 C17,27 14,26 12,24 C10,22 9,19 9,16 C9,13 10,10 12,8 C14,6 17,5 20,5 Z" fill="#000000" />
        </svg>
    </div>

    <script>
        // Simple animation for the running player
        document.addEventListener('DOMContentLoaded', function() {
            const player = document.querySelector('.player-running');
            if (player) {
                // Reset animation when it completes
                player.addEventListener('animationiteration', () => {
                    player.style.animation = 'none';
                    void player.offsetWidth; // Trigger reflow
                    player.style.animation = 'run 15s linear infinite';
                });
            }
        });
    </script>
</body>
</html>
