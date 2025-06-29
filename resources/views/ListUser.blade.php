<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        /* Additional custom styles */
        .table-container {
            overflow-x: auto;
            margin: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .table-row:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }
        
        .header-cell {
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <x-layout>
        <?php
          $data = [];
          for ($i = 1; $i <= 1000; $i++) {
            $data[] = [
              'id' => $i,
              'nama' => "User $i",
              'nim' => "NIM00$i",
              'jurusan' => "Jurusan $i",
              'password' => '••••••••',
              'post' => rand(1, 10),
              'totalPost' => rand(1, 5),
            ];
          }
      
          $perPage = $_GET['perPage'] ?? 8;
          $currentPage = $_GET['page'] ?? 1;
          $totalPages = ceil(count($data) / $perPage);
          $offset = ($currentPage - 1) * $perPage;
          $pagedData = array_slice($data, $offset, $perPage);
        ?>
      
        <div class="bg-white p-6 rounded-lg shadow h-full flex flex-col">
            {{-- section 1  --}}
            <div class="flex items-center justify-between px-9 py-2">
                <!-- Tampilkan data -->
                <form method="GET" class="flex items-center gap-2">
                    <label for="pagination">Tampilkan</label>
                    <input id="pagination" type="number" max="8" name="perPage" value="<?= $perPage ?>" class="w-16 border border-gray-300 rounded px-2 py-1" />
                    <span>data</span>
                    <button type="submit" class="px-2 py-1 bg-red-500 hover:bg-red-400 text-white rounded">Terapkan</button>
                  </form>
              
                <!-- Search -->
                <div class="flex items-center gap-2">
                    <div class="border-gray-300 border-2 rounded">
                        <input type="text" placeholder="Search here..." class="w-64 focus:border-none outline-0 px-3 py-1 text-sm " />
                        <i class="fas fa-search text-gray-500 px-1"></i>
                    </div>
                    <button type="button" class="bg-red-500 text-white p-1 px-2 rounded-lg font-semibold">
                        Buat baru
                    </button>
                </div>
            </div>
            {{-- <-- Section 2 --> --}}
            <div class="container w-full px-4 py-0">
                <div class="table-container">
                  <table class="min-w-full border-2 border-gray-200 rounded-lg overflow-hidden">
                    <thead>
                      <tr class="bg-red-500 text-white">
                        <th class="border-2 py-2 px-4 w-[5%] header-cell">ID</th>
                        <th class="border-2 py-2 px-4 w-[20%] header-cell">Nama</th>
                        <th class="border-2 py-2 px-4 w-[10%] header-cell">NIM</th>
                        <th class="border-2 py-2 px-4 w-[20%] header-cell">Jurusan</th>
                        <th class="border-2 py-2 px-4 w-[15%] header-cell">Password</th>
                        <th class="border-2 py-2 px-4 w-[10%] header-cell">Post</th>
                        <th class="border-2 py-2 px-4 w-[10%] header-cell">Total Post</th>
                        <th class="border-2 py-2 px-4 w-[10%] header-cell">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($pagedData as $row)
                        <tr class="table-row even:bg-gray-50">
                          <td class="border-2 py-2 px-4 text-center">{{ $row['id'] }}</td>
                          <td class="border-2 py-2 px-4">{{ $row['nama'] }}</td>
                          <td class="border-2 py-2 px-4">{{ $row['nim'] }}</td>
                          <td class="border-2 py-2 px-4">{{ $row['jurusan'] }}</td>
                          <td class="border-2 py-2 px-4 font-mono">{{ $row['password'] }}</td>
                          <td class="border-2 py-2 px-4 text-center">{{ $row['post'] }}</td>
                          <td class="border-2 py-2 px-4 text-center">{{ $row['totalPost'] }}</td>
                          <td class="border-2 py-2 px-4 text-center">
                            <div class="flex gap-2 w-full text-white">
                              <button class="edit-btn w-[50%] rounded py-1 bg-[#259ee0] hover:bg-[#2eb6ff]">
                                <i class="fa-solid fa-pencil"></i>
                              </button>
                              <button class="dlt-btn w-[50%] rounded py-1 bg-red-600 hover:bg-red-500">
                                <i class="fa-solid fa-trash"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </x-layout>

    <!-- edit modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white w-[90%] max-w-md rounded-lg p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4">Edit Data</h2>
            <form id="editForm">
                <input type="hidden" id="edit-id" />
    
                <div class="mb-2">
                    <label for="edit-nama" class="block text-sm">Nama</label>
                    <input type="text" id="edit-nama" class="w-full border px-3 py-1 rounded" />
                </div>
    
                <div class="mb-2">
                    <label for="edit-nim" class="block text-sm">NIM</label>
                    <input type="text" id="edit-nim" class="w-full border px-3 py-1 rounded" />
                </div>
    
                <div class="mb-2">
                    <label for="edit-jurusan" class="block text-sm">Jurusan</label>
                    <textarea id="edit-jurusan" class="w-full border px-3 py-1 rounded"></textarea>
                </div>
    
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="closeModal" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- delete modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white w-[90%] max-w-md rounded-lg p-2 shadow-lg mx-4">
            <h2 class="text-xl font-bold mb-2">Hapus Data</h2>
            <div class="p-4">
                <p class="mb-4">Apakah anda yakin ingin menghapus data ini?</p>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="closeDeleteModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Cancel</button>
                    <button type="button" id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
     
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const row = this.closest('tr');
                const cells = row.querySelectorAll('td');
                document.getElementById('edit-nama').value = cells[1].innerText;
                document.getElementById('edit-nim').value = cells[2].innerText;
                document.getElementById('edit-jurusan').value = cells[3].innerText;

                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
            });
        });

    
        document.querySelectorAll('.dlt-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            });
        });

        // Close edit modal
        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('editModal').classList.remove('flex');
            document.getElementById('editModal').classList.add('hidden');
        });

        // Close delete modal
        document.getElementById('closeDeleteModal').addEventListener('click', function () {
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteModal').classList.add('hidden');
        });

        // Form submission
        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Form submitted!'); // panggil logic edit disini
            document.getElementById('editModal').classList.add('hidden');
        });

        // Confirm delete
        document.getElementById('confirmDelete').addEventListener('click', function () {
            alert('Data deleted!'); // panggil logic delete disini
            document.getElementById('deleteModal').classList.add('hidden');
        });
    </script>
</body>
</html>