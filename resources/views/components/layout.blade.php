<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-jQG9xK5oY6n3zZnUjs2Y5Ty2RCZt+qJHK0RPiRA1uWvHJk+96LUm6A1Owz+CcfAOZBRWhYaV6Q5aZMjYUIc8fg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed z-30 inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out">
            @include('components.sidebar')
        </div>
        <div id="profile" class="fixed z-30 inset-y-0 right-0 w-64 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out">
            @include('components.profile')
        </div>

        <!-- Overlay for mobile -->
        <div id="overlay" class="fixed inset-0 bg-black opacity-50 z-20 hidden"></div>

        <!-- Main content wrapper -->
        <div class="flex-1 flex flex-col ml-0">
            <!-- Navbar -->
            <header class="bg-red-600 shadow-sm flex items-center justify-between px-6 py-2">
                <button id="toggleSidebar" class="text-white focus:outline-none">
                    <!-- Hamburger icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            
                <!-- Profile image -->
                <button id="toggleProfile" class="">
                    <img src="/asset/profile3.svg" alt="Profile" class="w-10 h-10 rounded-full p-0 invert-0 bg-white">
                </button>
                
            </header>
            

            <!-- Main Content -->
            <main class="flex-1 overflow-y-hidden p-6 bg-gray-100">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const toggleProfile = document.getElementById('toggleProfile')
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        toggleBtn.addEventListener('click', () => {
            const isOpen = !sidebar.classList.contains('-translate-x-full');

            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
        });
        toggleProfile.addEventListener('click', () => {
            const isOpen = !profile.classList.contains('translate-x-full');

            if (isOpen) {
                profile.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            } else {
                profile.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
            }
        });

        // Optional: Click outside to close
        overlay.addEventListener('click', () => {
            profile.classList.add('translate-x-full');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>
</body>
</html>
