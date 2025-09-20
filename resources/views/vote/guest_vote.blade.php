<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Voting</title>
    @livewireStyles()
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
            background-image: url('{{ asset('ball.png') }}');
            /* Transparent football PNG */
            background-size: cover;
            opacity: 0.08;
            animation: float 20s infinite ease-in-out;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            25% {
                transform: translateY(-30px) rotate(90deg);
            }

            50% {
                transform: translateY(0px) rotate(180deg);
            }

            75% {
                transform: translateY(30px) rotate(270deg);
            }
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 items-center justify-center min-h-screen p-4">

    <!-- Animated Football Background -->
    <div class="ball-bg">
        <div class="ball" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="ball" style="top: 60%; left: 80%; animation-delay: -4s;"></div>
        <div class="ball" style="top: 40%; left: 30%; animation-delay: -8s;"></div>
        <div class="ball" style="top: 80%; left: 60%; animation-delay: -12s;"></div>
        <div class="ball" style="top: 15%; left: 75%; animation-delay: -16s;"></div>
    </div>

    <!-- Card -->
    @livewire('guest-vote')
    <!-- JavaScript (unchanged) -->

</body>
@livewireScripts()

</html>
