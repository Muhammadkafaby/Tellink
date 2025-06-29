<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

      <form id="loginFormElement">
        <div class="mb-4">
          <label for="loginUsername" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
          <input type="text" id="loginUsername" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Enter your username">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Username is required</p>
        </div>

        <div class="mb-6">
          <label for="loginPassword" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
          <input type="password" id="loginPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Enter your password">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Password is required</p>
        </div>

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

      <form id="registerFormElement">
        <div class="mb-4">
          <label for="registerEmail" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
          <input type="email" id="registerEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Enter your email">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Email is required</p>
        </div>

        <div class="mb-4">
          <label for="registerUsername" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
          <input type="text" id="registerUsername" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Choose a username">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Username is required</p>
        </div>

        <div class="mb-6">
          <label for="registerPassword" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
          <input type="password" id="registerPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2" placeholder="Create a password">
          <p class="text-red-500 text-sm mt-1 error-message hidden">Password is required</p>
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

      // Form Validasi
      function addValidation(formId, fields) {
        const form = document.getElementById(formId);
        form.addEventListener('submit', function (e) {
          e.preventDefault();
          let isValid = true;

          fields.forEach(fieldId => {
            const input = document.getElementById(fieldId);
            const error = input.nextElementSibling;

            if (input.value.trim() === "") {
              error.classList.remove('hidden');
              isValid = false;
            } else {
              error.classList.add('hidden');
            }
          });

          if (isValid) {
            alert(`${formId === 'loginFormElement' ? 'Login' : 'Register'} successful!`);
            window.location.href='/listuser'
            form.reset();
          }
        });
      }

      addValidation('loginFormElement', ['loginUsername', 'loginPassword']);
      addValidation('registerFormElement', ['registerEmail', 'registerUsername', 'registerPassword']);
    });
  </script>
</body>
</html>
