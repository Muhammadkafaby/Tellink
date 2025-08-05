<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Profile Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <x-layout>
    <div class="bg-white p-6 pt-2 rounded-lg shadow h-full flex flex-col items-center gap-4 relative">

      <!-- Profile Image -->
      <div class="h-[40%] w-full flex justify-center items-center">
        @if($user->avatar)
          <img class="h-full max-h-40 object-contain rounded-full" src="{{ asset($user->avatar) }}" alt="Profile Image">
        @else
          <img class="h-full max-h-40 object-contain" src="/asset/profile.svg" alt="Profile Image">
        @endif
      </div>

      <!-- Display Name -->
      <h1 id="profileName" class="text-3xl font-bold text-gray-800 text-center">
        {{ $user->name }}
      </h1>
      <p class="text-gray-600">{{ $user->email }}</p>

      <!-- Edit Profile Form -->
      <div class="w-full max-w-md mt-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Edit Profile</h2>
        
        @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
          </div>
        @endif
        
        @if($errors->any())
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif
        <form id="editForm" action="{{ url('/profile/update') }}" method="POST" class="space-y-4">
          @csrf
          <!-- Name Input -->
          <div>
            <label class="block mb-1 text-gray-600 font-medium">Name (optional)</label>
            <input name="name" id="nameInput" type="text" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter new name">
          </div>

          <!-- Password Input -->
          <div>
            <label class="block mb-1 text-gray-600 font-medium">Password</label>
            <input name="password" id="passwordInput" type="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>

          <!-- Confirm Password -->
          <div>
            <label class="block mb-1 text-gray-600 font-medium">Confirm Password</label>
            <input id="confirmInput" type="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
            <p id="errorMsg" class="text-red-500 text-sm mt-1 hidden">Passwords do not match</p>
          </div>

          <!-- Save Button -->
          <div class="text-right">
            <button
              id="saveBtn"
              type="submit"
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 disabled:opacity-50"
              disabled>
              Finish Editing
            </button>
          </div>
        </form>
      </div>
      
      <!-- Upload Avatar Form -->
      <div class="w-full max-w-md mt-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Upload Avatar</h2>
        <form action="{{ url('/profile/upload-avatar') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf
          <div>
            <label class="block mb-1 text-gray-600 font-medium">Choose Image</label>
            <input type="file" name="avatar" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2" required>
          </div>
          <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Upload Avatar
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const nameInput = document.getElementById('nameInput');
      const passwordInput = document.getElementById('passwordInput');
      const confirmInput = document.getElementById('confirmInput');
      const errorMsg = document.getElementById('errorMsg');
      const saveBtn = document.getElementById('saveBtn');
      const profileName = document.getElementById('profileName');

      // Validate passwords
      function validatePasswords() {
        const pw = passwordInput.value;
        const cpw = confirmInput.value;
        const match = pw && cpw && pw === cpw;
        errorMsg.classList.toggle('hidden', match);
        saveBtn.disabled = !match;
      }

      passwordInput.addEventListener('input', validatePasswords);
      confirmInput.addEventListener('input', validatePasswords);

      // Form will submit normally to backend
    </script>
  </x-layout>
</body>
</html>
