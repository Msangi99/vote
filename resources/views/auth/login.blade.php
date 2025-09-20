<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Football Portal</title>
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
        <!-- Football field header -->
        <div class="" style="justify-content: center;">
           <img src="{{ asset('WhatsApp Image 2025-09-16 at 19.31.12_62e45b1b.jpg') }}" class="h-40 w-8/12" alt="" style="margin-left: 16%;">
        </div>

        {{-- <h2 class="text-2xl font-bold text-center mb-2 text-gray-800">GOAL ACCESS</h2> --}}
        {{-- <p class="text-center text-gray-600 mb-6">Enter your credentials to access the field</p> --}}

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" class="pt-10" method="POST">
            @csrf
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
                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight 
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                       value="{{ old('email') }}" required autofocus
                       placeholder="your@email.com">
            </div>
            <div class="mb-6">
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
                       class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight 
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                       required
                       placeholder="••••••••">
            </div>
            <div class="flex items-center justify-center mb-4">
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none 
                        focus:shadow-outline transform hover:scale-105 transition-all duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10,17 15,12 10,7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Login
                </button>
                {{-- <a href="#" class="inline-block align-baseline font-bold text-sm text-green-600 hover:text-green-800">
                    Forgot Password?
                </a> --}}
            </div>
            {{-- <div class="text-center mt-6 pt-4 border-t border-gray-200">
                <p class="text-gray-600">Don't have an account?</p>
                <a href="{{ route('register') }}" 
                   class="inline-block mt-2 font-bold text-sm text-green-600 hover:text-green-800 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Register Now
                </a>
            </div> --}}
        </form>
    </div>

    <!-- Animated balls in the background -->
    

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
