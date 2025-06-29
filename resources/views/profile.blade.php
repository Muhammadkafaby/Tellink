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
        <img class="h-full max-h-40 object-contain" src="/asset/profile.svg" alt="Profile Image">
      </div>

      <!-- Display Name -->
      <h1 id="profileName" class="text-3xl font-bold text-gray-800 text-center">
        Dive Kopling 2069
      </h1>

      <!-- Edit Profile Form -->
      <div class="w-full max-w-md mt-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Edit Profile</h2>
        <form id="editForm" class="space-y-4">
          <!-- Name Input -->
          <div>
            <label class="block mb-1 text-gray-600 font-medium">Name (optional)</label>
            <input id="nameInput" type="text" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter new name">
          </div>

          <!-- Password Input -->
          <div>
            <label class="block mb-1 text-gray-600 font-medium">Password</label>
            <input id="passwordInput" type="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
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

      // Handle form submit
      document.getElementById('editForm').addEventListener('submit', (e) => {
        e.preventDefault();
        if (nameInput.value.trim()) {
          profileName.textContent = nameInput.value.trim();
        }
        alert("Profile updated successfully!");
        passwordInput.value = '';
        confirmInput.value = '';
        nameInput.value = '';
        saveBtn.disabled = true;
      });
    </script>
  </x-layout>
</body>
</html>
