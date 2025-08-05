<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    <!-- Toggle Buttons -->
    <div class="flex mb-6 rounded-lg overflow-hidden shadow">
      <button id="loginTab" class="flex-1 py-3 px-4 font-medium text-white bg-red-600 focus:outline-none">Login</button>
      <button id="registerTab" class="flex-1 py-3 px-4 font-medium text-black bg-white focus:outline-none">Register</button>
    </div>

    <!-- Login Form -->
    <div id="loginForm" class="bg-white px-8 pb-8 rounded-lg shadow-lg">
      <div class="w-full flex justify-center items-center">
        <img class="h-48" src="/asset/logo.png" alt="">
      </div>
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Welcome back</h2>

      <form id="loginFormElement" action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="mb-4">
          <label for="loginEmail" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" id="loginEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Enter your email">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Email is required</p>
        </div>

        <div class="mb-6">
          <label for="loginPassword" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
          <input type="password" name="password" id="loginPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Enter your password">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Password is required</p>
        </div>
        
        @if($errors->any())
          <div class="mb-4">
            <p class="text-red-500 text-sm">{{ $errors->first() }}</p>
          </div>
        @endif
        
        @if(session('success'))
          <div class="mb-4">
            <p class="text-green-500 text-sm">{{ session('success') }}</p>
          </div>
        @endif

        <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-300 transition duration-200">Login</button>

        <div class="mt-4 text-center">
          <p class="text-gray-600 text-sm">Don't have an account? 
            <button type="button" id="switchToRegister" class="text-primary font-medium hover:underline">Register here</button>
          </p>
        </div>
      </form>
    </div>

    <!-- Register Form -->
    <div id="registerForm" class="bg-white px-8 pb-8 rounded-lg shadow-lg hidden">
      <div class="w-full flex justify-center items-center">
        <img class="h-48" src="/asset/logo.png" alt="">
      </div>
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Create an account</h2>

      <form id="registerFormElement" action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="mb-4">
          <label for="registerName" class="block text-gray-700 text-sm font-medium mb-2">Name</label>
          <input type="text" name="name" id="registerName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Enter your name">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Name is required</p>
        </div>
        
        <div class="mb-4">
          <label for="registerEmail" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" id="registerEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Enter your email">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Email is required</p>
        </div>

        <div class="mb-4">
          <label for="registerPassword" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
          <input type="password" name="password" id="registerPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Create a password">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Password is required</p>
        </div>
        
        <div class="mb-6">
          <label for="registerPasswordConfirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
          <input type="password" name="password_confirmation" id="registerPasswordConfirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Confirm your password">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Password confirmation is required</p>
        </div>

        <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-300 transition duration-200">Register</button>

        <div class="mt-4 text-center">
          <p class="text-gray-600 text-sm">Already have an account? 
            <button type="button" id="switchToLogin" class="text-primary font-medium hover:underline">Login here</button>
          </p>
        </div>
      </form>
    </div>
  </div>


  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const switchToRegister = document.getElementById('switchToRegister');
            const switchToLogin = document.getElementById('switchToLogin');

            function showLogin() {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                loginTab.classList.add('text-white', 'bg-red-600');
                loginTab.classList.remove('text-gray-700', 'bg-white');
                registerTab.classList.add('text-gray-700', 'bg-white');
                registerTab.classList.remove('text-white', 'bg-gray-200');
            }

            function showRegister() {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                registerTab.classList.add('text-white', 'bg-red-600');
                registerTab.classList.remove('text-gray-700', 'bg-white');
                loginTab.classList.add('text-gray-700', 'bg-white');
                loginTab.classList.remove('text-white', 'bg-gray-200');
            }

            // Event listeners
            loginTab.addEventListener('click', showLogin);
            registerTab.addEventListener('click', showRegister);
            switchToRegister.addEventListener('click', showRegister);
            switchToLogin.addEventListener('click', showLogin);

      // Form Validasi - removed to allow server-side validation
    });
  </script>
</body>
</html>
