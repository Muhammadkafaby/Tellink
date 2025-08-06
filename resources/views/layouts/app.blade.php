<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Tellink - Platform Kolaborasi Mahasiswa')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --tellink-red: #dc3545;
            --tellink-dark-red: #c82333;
        }
        
        .navbar-tellink {
            background-color: var(--tellink-red) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .navbar-tellink .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-tellink .nav-link {
            color: rgba(255,255,255,.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-tellink .nav-link:hover,
        .navbar-tellink .nav-link.active {
            color: #fff !important;
            background-color: rgba(255,255,255,.1);
        }
        
        .navbar-tellink .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #fff;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: rgba(255,255,255,.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
        }
    </style>
    
    <!-- Additional CSS -->
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-tellink">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-link"></i>
                Tellink
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('listuser') ? 'active' : '' }}" href="{{ url('/listuser') }}">
                            <i class="fas fa-users me-1"></i> Mahasiswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('userpost') ? 'active' : '' }}" href="{{ url('/userpost') }}">
                            <i class="fas fa-project-diagram me-1"></i> Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('report') ? 'active' : '' }}" href="{{ url('/report') }}">
                            <i class="fas fa-chart-bar me-1"></i> Reports
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="user-avatar">
                                <i class="fas fa-user"></i>
                            </span>
                            User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ url('/profile') }}">
                                    <i class="fas fa-user-circle me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/settings') }}">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ url('/logout') }}">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Tellink. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">Powered by Laravel</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    @vite(['resources/js/app.js'])
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
