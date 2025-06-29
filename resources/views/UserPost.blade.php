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
        .hide-scrollbar::-webkit-scrollbar {
          display: none;
        }
        .hide-scrollbar {
          -ms-overflow-style: none;  /* IE and Edge */
          scrollbar-width: none;     /* Firefox */
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
              'nim' => "NIM00$i",
              'date' => "08/03/25",
              'desc' => 'consectetur adipisicing elit. Velit in incidunt corporis...',
              'images' => '/asset/logo.png',
              'likes' => rand(1, 100),
              'title' => "post ke-$i"
            ];
          }
      
          $perPage = $_GET['perPage'] ?? 3;
          $currentPage = $_GET['page'] ?? 1;
          $totalPages = ceil(count($data) / $perPage);
          $offset = ($currentPage - 1) * $perPage;
          $pagedData = array_slice($data, $offset, $perPage);
        ?>
        <div class="bg-white p-6 rounded-lg shadow h-full flex flex-col overflow-y-scroll hide-scrollbar">
            <div>
                {{-- section 1  --}}
                <div class="flex items-center justify-between px-9 py-2">
                    <!-- Tampilkan data -->
                    <form method="GET" class="flex items-center gap-2">
                        <label for="pagination">Tampilkan</label>
                        <input id="pagination" type="number" max="3" name="perPage" value="<?= $perPage ?>" class="w-16 border border-gray-300 rounded px-2 py-1" />
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
                {{-- section 2 --}}
                <div class="container mx-auto px-4 py-0 h-full overflow-hidden">
                    <div class="table-container w-full overflow-x-auto">
                      <table class="w-full border-2 border-gray-200 rounded-lg">
                        <thead>
                          <tr class="bg-red-500 text-white">
                            <th class="border-2 py-2 px-4 w-[5%] header-cell">ID</th>
                            <th class="border-2 py-2 px-4 w-[10%] header-cell">NIM</th>
                            <th class="border-2 py-2 px-4 w-[10%] header-cell">Date</th>
                            <th class="border-2 py-2 px-4 w-[15%] header-cell">Desc</th>
                            <th class="border-2 py-2 px-4 w-[15%] header-cell">Image</th>
                            <th class="border-2 py-2 px-4 w-[10%] header-cell">Likes</th>
                            <th class="border-2 py-2 px-4 w-[10%] header-cell">Title</th>
                            <th class="border-2 py-2 px-4 w-[10%] header-cell">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagedData as $row)
                            <tr class="table-row even:bg-gray-50">
                                <td class="border-2 py-2 px-4 text-center">{{$row['id']}}</td>
                                <td class="border-2 py-2 px-4">{{$row['nim']}}</td>
                                <td class="border-2 py-2 px-4">{{$row['date']}}</td>
                                <td class="border-2 py-2 px-4 break-words max-w-[250px]">
                                  {{$row["desc"]}}
                                </td>
                                <td class="border-2 py-2 px-4 font-mono">
                                  <img class="w-full  max-h-[100px] object-contain" src="{{$row['images']}}" alt="">
                                </td>
                                <td class="border-2 py-2 px-4 text-center">{{$row['likes']}}</td>
                                <td class="border-2 py-2 px-4 text-center">{{$row['title']}}</td>
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
            <div>
            <!-- Pagination Controls -->
            <div class="flex justify-end mt-4 gap-2">
                @php
                  $start = max(1, $currentPage - 2);
                  $end = min($totalPages, $currentPage + 2);
                @endphp
        
                @if ($currentPage > 1)
                  <a href="?page={{ $currentPage - 1 }}&perPage={{ $perPage }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">Previous</a>
                @endif
        
                @for ($i = $start; $i <= $end; $i++)
                  <a href="?page={{ $i }}&perPage={{ $perPage }}" class="px-3 py-1 rounded {{ $i == $currentPage ? 'bg-red-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">{{ $i }}</a>
                @endfor
        
                @if ($currentPage < $totalPages)
                  <a href="?page={{ $currentPage + 1 }}&perPage={{ $perPage }}" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">Next</a>
                @endif
            </div>
            </div>
        </div>
    </x-layout>

        <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white w-[90%] max-w-md rounded-lg p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4">Edit Data</h2>
            <form id="editForm">
                <input type="hidden" id="edit-id" />

                <div class="mb-2">
                    <label for="edit-nim" class="block text-sm">NIM</label>
                    <input type="text" id="edit-nim" class="w-full border px-3 py-1 rounded" />
                </div>

                <div class="mb-2">
                    <label for="edit-date" class="block text-sm">Date</label>
                    <input type="date" id="edit-date" class="w-full border px-3 py-1 rounded" />
                </div>

                <div class="mb-2">
                    <label for="edit-desc" class="block text-sm">Description</label>
                    <textarea id="edit-desc" class="w-full border px-3 py-1 rounded"></textarea>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="closeEditModal" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-[999]">
    <div class="bg-white w-[90%] max-w-md rounded-lg p-6 shadow-lg mx-4">
        <h2 class="text-xl font-bold mb-4">Hapus Data</h2>
        <div class="p-4">
            <p class="mb-4">Apakah anda yakin ingin menghapus data ini?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeDeleteModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Cancel</button>
                <button type="button" id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Delete</button>
            </div>
        </div>
    </div>
</div>

</body>

<script>
    // Open Edit Modal
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('tr');
            const cells = row.querySelectorAll('td');

            document.getElementById('edit-id').value = cells[0].innerText;
            document.getElementById('edit-nim').value = cells[1].innerText;
            document.getElementById('edit-date').value = cells[2].innerText;
            document.getElementById('edit-desc').value = cells[3].innerText;

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        });
    });

   // Open Delete Modal
    document.querySelectorAll('.dlt-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        });
    });


    // Close modals
    document.getElementById('closeEditModal').addEventListener('click', function () {
        document.getElementById('editModal').classList.remove('flex');
        document.getElementById('editModal').classList.add('hidden');
    });

   
    document.getElementById('closeDeleteModal').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
    });

    // Submit Edit Form
    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Form submitted!'); // Ganti dengan logika update data
        document.getElementById('editModal').classList.add('hidden');
    });
</script>

</html>