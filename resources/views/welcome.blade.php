<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hi There!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .wave-animation {
            animation: wave 2s ease-in-out infinite;
            transform-origin: 70% 70%;
        }
        
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
            20%, 40%, 60%, 80% { transform: rotate(10deg); }
        }
        
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">
    <div class="text-center text-white fade-in">
        <!-- Main Greeting -->
        <div class="mb-8">
            <h1 class="text-6xl md:text-8xl font-bold mb-4">
                Hi There! 
                <span class="wave-animation inline-block">👋</span>
            </h1>
           
        </div>
        <!-- Content Card -->
        <div class=" backdrop-blur-lg rounded-3xl p-8 md:p-12 max-w-2xl mx-auto border border-white/20 shadow-2xl">
            <div class="space-y-6">
                <div class="text-lg md:text-xl">
                    <p class="mb-4">
                        🎉 Thanks for stopping by! We're excited to have you here.
                    </p>
                    <p class="opacity-80">
                        Feel free to explore and discover what we have to offer.
                    </p>
                </div>
                
                <!-- Action Buttons -->
              @if (Route::has('login'))
    @auth
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
            <button onclick="location.href='{{ url('/home') }}'" class="bg-white text-purple-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transform hover:scale-105 transition-all duration-200 shadow-lg">
                Home
            </button>
        </div>
    @else
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
            <button onclick="location.href='{{ route('login') }}'" class="bg-white text-purple-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transform hover:scale-105 transition-all duration-200 shadow-lg">
                Masuk
            </button>

           
        </div>
    @endauth
@endif
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-12 opacity-60">
            <p class="text-sm">
                Made with ❤️ for you
            </p>
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-4 h-4 bg-white/30 rounded-full animate-bounce"></div>
    <div class="absolute top-1/4 right-20 w-6 h-6 bg-yellow-300/40 rounded-full animate-pulse"></div>
    <div class="absolute bottom-20 left-20 w-5 h-5 bg-pink-300/40 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-1/4 right-10 w-3 h-3 bg-blue-300/40 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
    
    <script>
        // Add some interactive sparkle on click
        document.addEventListener('click', function(e) {
            createSparkle(e.clientX, e.clientY);
        });
        
        function createSparkle(x, y) {
            const sparkle = document.createElement('div');
            sparkle.innerHTML = '✨';
            sparkle.style.position = 'fixed';
            sparkle.style.left = x + 'px';
            sparkle.style.top = y + 'px';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.fontSize = '20px';
            sparkle.style.zIndex = '1000';
            sparkle.style.animation = 'sparkleAnimation 1s ease-out forwards';
            
            document.body.appendChild(sparkle);
            
            setTimeout(() => {
                sparkle.remove();
            }, 1000);
        }
        
        // Add sparkle animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes sparkleAnimation {
                0% {
                    opacity: 1;
                    transform: scale(0) rotate(0deg);
                }
                50% {
                    opacity: 1;
                    transform: scale(1) rotate(180deg);
                }
                100% {
                    opacity: 0;
                    transform: scale(0) rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>


