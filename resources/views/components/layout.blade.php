<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tellink - Connect & Collaborate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-jQG9xK5oY6n3zZnUjs2Y5Ty2RCZt+qJHK0RPiRA1uWvHJk+96LUm6A1Owz+CcfAOZBRWhYaV6Q5aZMjYUIc8fg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex flex-col h-screen overflow-hidden">
        <!-- Navbar -->
        <header class="bg-red-600 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-3">
                    <!-- Left: Logo and Navigation -->
                    <div class="flex items-center space-x-8">
                        <!-- Logo -->
                        <a href="/" class="flex items-center space-x-2">
                            <i class="fas fa-link text-white text-2xl"></i>
                            <span class="text-white text-xl font-bold">Tellink</span>
                        </a>
                        
                        <!-- Navigation Links -->
                        <nav class="hidden md:flex space-x-6">
                            <a href="/listuser" class="text-white hover:text-gray-200 font-medium transition flex items-center space-x-2">
                                <i class="fas fa-users"></i>
                                <span>Mahasiswa</span>
                            </a>
                            <a href="/userpost" class="text-white hover:text-gray-200 font-medium transition flex items-center space-x-2">
                                <i class="fas fa-project-diagram"></i>
                                <span>Projects</span>
                            </a>
                            <a href="/report" class="text-white hover:text-gray-200 font-medium transition flex items-center space-x-2">
                                <i class="fas fa-flag"></i>
                                <span>Reports</span>
                            </a>
                        </nav>
                    </div>
                    
                    <!-- Right: User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Mobile menu button -->
                        <button id="mobileMenuBtn" class="md:hidden text-white hover:text-gray-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- User dropdown -->
                        <div class="relative">
                            <button id="userMenuBtn" class="flex items-center space-x-2 text-white hover:text-gray-200 focus:outline-none">
                                @if(auth()->user() && auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-red-600"></i>
                                    </div>
                                @endif
                                <span class="hidden md:block font-medium">{{ auth()->user()->name ?? 'User' }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> Profile
                                </a>
                                <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <hr class="my-1">
                                <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Navigation -->
                <nav id="mobileNav" class="md:hidden hidden pb-3">
                    <a href="/listuser" class="block text-white hover:text-gray-200 font-medium py-2">
                        <i class="fas fa-users mr-2"></i> Mahasiswa
                    </a>
                    <a href="/userpost" class="block text-white hover:text-gray-200 font-medium py-2">
                        <i class="fas fa-project-diagram mr-2"></i> Projects
                    </a>
                    <a href="/report" class="block text-white hover:text-gray-200 font-medium py-2">
                        <i class="fas fa-flag mr-2"></i> Reports
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // User dropdown
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        
        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        
        mobileMenuBtn.addEventListener('click', function() {
            mobileNav.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userDropdown.classList.add('hidden');
        });
        
        // Add active state to current page
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('nav a');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('bg-red-700', 'bg-opacity-50', 'rounded', 'px-3', 'py-1');
            }
        });
    </script>
</body>
</html>
