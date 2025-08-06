@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Data Mahasiswa</h2>
    <button onclick="showCreateModal()" class="btn btn-success">
      <i class="fas fa-plus"></i> Tambah Mahasiswa
    </button>
  </div>
  
  <!-- Search Bar -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" id="search-nama" class="form-control" placeholder="Cari berdasarkan nama...">
            <button class="btn btn-primary" onclick="searchUser()">
              <i class="fas fa-search"></i> Cari
            </button>
          </div>
        </div>
        <div class="col-md-6 text-end">
          <span class="text-muted">Total: <strong id="total-users">0</strong> mahasiswa</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-danger">
            <tr>
              <th scope="col" width="5%">ID</th>
              <th scope="col" width="20%">NAMA</th>
              <th scope="col" width="15%">NIM</th>
              <th scope="col" width="25%">EMAIL</th>
              <th scope="col" width="15%">JURUSAN</th>
              <th scope="col" width="10%">PASSWORD</th>
              <th scope="col" width="10%" class="text-center">ACTION</th>
            </tr>
          </thead>
          <tbody id="user-table-body">
            <tr>
              <td colspan="7" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2">Loading data...</div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Form -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Tambah Mahasiswa</h5>
          <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit-nim">
          
          <div class="mb-3">
            <label for="form-nim" class="form-label">NIM <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="form-nim" placeholder="Masukkan NIM">
          </div>
          
          <div class="mb-3">
            <label for="form-nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="form-nama" placeholder="Masukkan nama lengkap">
          </div>
          
          <div class="mb-3">
            <label for="form-email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="form-email" placeholder="Masukkan email">
          </div>
          
          <div class="mb-3">
            <label for="form-password" class="form-label">Password <span class="text-danger" id="password-required">*</span></label>
            <input type="password" class="form-control" id="form-password" placeholder="Masukkan password">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password (untuk edit)</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
          <button type="button" class="btn btn-primary" onclick="submitForm()">Simpan</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Custom Avatar Styles */
.avatar-sm {
  width: 40px;
  height: 40px;
  display: inline-flex;
}

.avatar-title {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 16px;
}

.bg-soft-primary {
  background-color: rgba(13, 110, 253, 0.1);
}

/* Table Hover Effect */
.table-hover tbody tr:hover {
  background-color: rgba(0,0,0,.02);
  cursor: pointer;
}

/* Search input on focus */
#search-nama:focus {
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Card hover effect */
.card {
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Button group styling */
.btn-group-sm .btn {
  padding: 0.25rem 0.5rem;
}

/* Modal styling */
.modal-content {
  border: none;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Badge styling */
.badge {
  font-weight: 500;
  padding: 0.35em 0.65em;
}
</style>

<script>
let allUsers = [];

// Load users on page load
async function loadUsers() {
  try {
    // Use Laravel proxy route to avoid CORS
    const response = await fetch('/api/tellink/users');
    if (!response.ok) throw new Error('Network response was not ok');
    
    const resData = await response.json();
    console.log('API Response:', resData); // Debug log
    
    // Handle different response formats
    if (resData.data && Array.isArray(resData.data)) {
      allUsers = resData.data;
    } else if (Array.isArray(resData)) {
      allUsers = resData;
    } else {
      allUsers = [];
    }
    
    renderTable(allUsers);
  } catch (err) {
    console.error('Error loading users:', err);
    document.getElementById("user-table-body").innerHTML = 
      '<tr><td colspan="7" style="text-align:center; color:red;">Error memuat data! Check console for details.</td></tr>';
  }
}

// Render table with user data
function renderTable(users) {
  let html = '';
  
  if (!users || !users.length) {
    html = `<tr>
      <td colspan="7" class="text-center py-4">
        <i class="fas fa-inbox fa-3x text-muted"></i>
        <p class="mt-2 text-muted">Data mahasiswa kosong</p>
      </td>
    </tr>`;
  } else {
    users.forEach((user, index) => {
      html += `<tr>
        <td>${user.id || index + 1}</td>
        <td>
          <div class="d-flex align-items-center">
            <div class="avatar-sm me-2">
              <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                ${(user.nama || user.name || 'U').charAt(0).toUpperCase()}
              </div>
            </div>
            <div>
              <h6 class="mb-0">${user.nama || user.name || '-'}</h6>
            </div>
          </div>
        </td>
        <td><span class="badge bg-secondary">${user.nim || '-'}</span></td>
        <td>${user.email || '-'}</td>
        <td>${user.jurusan || 'D3 Rekayasa Perangkat Lunak'}</td>
        <td><span class="text-muted">••••••••</span></td>
        <td class="text-center">
          <div class="btn-group btn-group-sm" role="group">
            <button onclick="showEditModal('${user.nim}')" class="btn btn-warning" title="Edit">
              <i class="fas fa-edit"></i>
            </button>
            <button onclick="deleteUser('${user.nim}')" class="btn btn-danger" title="Hapus">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>`;
    });
  }
  
  document.getElementById("user-table-body").innerHTML = html;
  document.getElementById("total-users").innerText = users.length;
}

// Search functionality
function searchUser() {
  const query = document.getElementById('search-nama').value.toLowerCase();
  const filtered = allUsers.filter(u => 
    (u.nama && u.nama.toLowerCase().includes(query)) || 
    (u.name && u.name.toLowerCase().includes(query)) ||
    (u.nim && u.nim.toLowerCase().includes(query)) ||
    (u.email && u.email.toLowerCase().includes(query))
  );
  renderTable(filtered);
}

// Enable search on Enter key
document.getElementById('search-nama').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    searchUser();
  }
});

// Show create modal
function showCreateModal() {
  document.getElementById('modal-title').innerText = 'Tambah Mahasiswa Baru';
  document.getElementById('edit-nim').value = '';
  document.getElementById('form-nim').value = '';
  document.getElementById('form-nama').value = '';
  document.getElementById('form-email').value = '';
  document.getElementById('form-password').value = '';
  document.getElementById('password-required').style.display = 'inline';
  
  // Show modal using Bootstrap
  const modal = new bootstrap.Modal(document.getElementById('userModal'));
  modal.show();
}

// Show edit modal
function showEditModal(nim) {
  const user = allUsers.find(u => u.nim === nim);
  if (!user) {
    Swal.fire('Error', 'User tidak ditemukan', 'error');
    return;
  }
  
  document.getElementById('modal-title').innerText = 'Edit Data Mahasiswa';
  document.getElementById('edit-nim').value = nim;
  document.getElementById('form-nim').value = user.nim;
  document.getElementById('form-nama').value = user.nama || user.name || '';
  document.getElementById('form-email').value = user.email || '';
  document.getElementById('form-password').value = '';
  document.getElementById('password-required').style.display = 'none';
  
  // Show modal using Bootstrap
  const modal = new bootstrap.Modal(document.getElementById('userModal'));
  modal.show();
}

// Close modal
function closeModal() {
  const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
  if (modal) {
    modal.hide();
  }
}

// Submit form (create or update)
async function submitForm() {
  const nim = document.getElementById('form-nim').value;
  const nama = document.getElementById('form-nama').value;
  const email = document.getElementById('form-email').value;
  const password = document.getElementById('form-password').value;
  const editNim = document.getElementById('edit-nim').value;
  
  if (!nim || !nama || !email) {
    alert("NIM, Nama, dan Email wajib diisi!");
    return;
  }
  
  if (!editNim && !password) {
    alert("Password wajib diisi untuk user baru!");
    return;
  }
  
  try {
    let response;
    
    if (editNim) {
      // Update existing user - for now just alert since API endpoint not available
      alert("Fitur edit belum tersedia di API");
      closeModal();
      return;
    } else {
      // Create new user via Laravel proxy
      response = await fetch('/api/tellink/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nim, nama, email, password })
      });
    }
    
    if (!response.ok) throw new Error('Request failed');
    
    alert(editNim ? "Update berhasil!" : "User baru berhasil ditambahkan!");
    closeModal();
    loadUsers();
  } catch(e) {
    console.error('Error saving data:', e);
    alert('Gagal menyimpan data! Check console for details.');
  }
}

// Delete user
async function deleteUser(nim) {
  const result = await Swal.fire({
    title: 'Konfirmasi Hapus',
    text: `Yakin ingin menghapus mahasiswa dengan NIM: ${nim}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  });
  
  if (!result.isConfirmed) return;
  
  try {
    const response = await fetch('/api/tellink/delete-user', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nim })
    });
    
    if (!response.ok) throw new Error('Delete failed');
    
    Swal.fire('Terhapus!', 'Data mahasiswa berhasil dihapus.', 'success');
    loadUsers();
  } catch(e) {
    console.error('Error deleting user:', e);
    Swal.fire('Error!', 'Gagal menghapus data. Silakan coba lagi.', 'error');
  }
}

// Load data when page loads
window.onload = function() {
  console.log('Page loaded, fetching users...');
  loadUsers();
};
</script>
@endsection
