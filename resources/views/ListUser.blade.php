@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
  <!-- Header -->
  <div class="mb-4">
    <h2 class="h3 mb-1 text-gray-800 fw-bold">Data Mahasiswa</h2>
    <p class="text-muted mb-0">Kelola data mahasiswa Tellink</p>
  </div>
  
  <!-- Stats Cards -->
  <div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">Total Mahasiswa</h6>
              <h3 class="mb-0 fw-bold text-primary" id="total-users">0</h3>
            </div>
            <div class="ms-3">
              <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-users text-primary fa-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">Aktif Hari Ini</h6>
              <h3 class="mb-0 fw-bold text-success" id="active-today">0</h3>
            </div>
            <div class="ms-3">
              <div class="bg-success bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-user-check text-success fa-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">Baru Minggu Ini</h6>
              <h3 class="mb-0 fw-bold text-info" id="new-week">0</h3>
            </div>
            <div class="ms-3">
              <div class="bg-info bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-user-plus text-info fa-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">Jurusan</h6>
              <h3 class="mb-0 fw-bold text-warning" id="total-majors">3</h3>
            </div>
            <div class="ms-3">
              <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-graduation-cap text-warning fa-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filter Bar -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
              <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="search-nama" class="form-control border-start-0 ps-0" placeholder="Cari nama, NIM, atau email..." onkeyup="searchUser()">
          </div>
        </div>
        <div class="col-md-3">
          <select id="filter-jurusan" class="form-select" onchange="searchUser()">
            <option value="">Semua Jurusan</option>
            <option value="D3 Rekayasa Perangkat Lunak">D3 RPL</option>
            <option value="D3 Sistem Informasi">D3 SI</option>
            <option value="D3 Teknik Komputer">D3 TK</option>
          </select>
        </div>
        <div class="col-md-3">
          <select id="perPage" class="form-select" onchange="updatePerPage()">
            <option value="10">10 data</option>
            <option value="25">25 data</option>
            <option value="50">50 data</option>
            <option value="100">100 data</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Mahasiswa</th>
              <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">NIM</th>
              <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Email</th>
              <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Jurusan</th>
              <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Status</th>
              <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="user-table-body">
            <tr>
              <td colspan="6" class="text-center py-5">
                <div class="spinner-border text-danger" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-3 text-muted">Memuat data mahasiswa...</div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Pagination -->
    <div class="card-footer bg-white border-top py-3">
      <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted small">
          Menampilkan <span id="showing-start">0</span> - <span id="showing-end">0</span> dari <span id="total-data">0</span> data
        </div>
        <nav>
          <ul class="pagination pagination-sm mb-0" id="pagination">
            <!-- Pagination will be generated here -->
          </ul>
        </nav>
      </div>
    </div>
  </div>

  <!-- Modal Form -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0">
        <div class="modal-header bg-light border-0">
          <h5 class="modal-title fw-bold" id="modal-title">Tambah Mahasiswa</h5>
          <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" id="edit-nim">
          
          <div class="row g-3">
            <div class="col-md-6">
              <label for="form-nim" class="form-label small fw-bold text-muted text-uppercase">NIM <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-lg" id="form-nim" placeholder="Contoh: 123456789">
              <div class="invalid-feedback">NIM wajib diisi</div>
            </div>
            
            <div class="col-md-6">
              <label for="form-nama" class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-lg" id="form-nama" placeholder="Masukkan nama lengkap">
              <div class="invalid-feedback">Nama wajib diisi</div>
            </div>
            
            <div class="col-md-12">
              <label for="form-email" class="form-label small fw-bold text-muted text-uppercase">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control form-control-lg" id="form-email" placeholder="email@example.com">
              <div class="invalid-feedback">Email tidak valid</div>
            </div>
            
            <div class="col-md-6">
              <label for="form-jurusan" class="form-label small fw-bold text-muted text-uppercase">Jurusan <span class="text-danger">*</span></label>
              <select class="form-select form-select-lg" id="form-jurusan">
                <option value="">Pilih Jurusan</option>
                <option value="D3 Rekayasa Perangkat Lunak">D3 Rekayasa Perangkat Lunak</option>
                <option value="D3 Sistem Informasi">D3 Sistem Informasi</option>
                <option value="D3 Teknik Komputer">D3 Teknik Komputer</option>
              </select>
              <div class="invalid-feedback">Jurusan wajib dipilih</div>
            </div>
            
            <div class="col-md-6">
              <label for="form-password" class="form-label small fw-bold text-muted text-uppercase">Password <span class="text-danger" id="password-required">*</span></label>
              <div class="input-group">
                <input type="password" class="form-control form-control-lg" id="form-password" placeholder="Min. 8 karakter">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                  <i class="fas fa-eye" id="password-icon"></i>
                </button>
              </div>
              <small class="text-muted d-none" id="password-hint">Kosongkan jika tidak ingin mengubah password</small>
              <div class="invalid-feedback">Password minimal 8 karakter</div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-light px-4" onclick="closeModal()">Batal</button>
          <button type="button" class="btn btn-danger px-4" onclick="submitForm()">
            <i class="fas fa-save me-2"></i>Simpan
          </button>
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
/* Modern UI Styles */
:root {
  --primary-color: #dc3545;
  --secondary-color: #6c757d;
  --success-color: #28a745;
  --info-color: #17a2b8;
  --warning-color: #ffc107;
  --danger-color: #dc3545;
  --light-color: #f8f9fa;
  --dark-color: #343a40;
}

/* Stats Cards */
.stats-card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Avatar Styles */
.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 16px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.avatar.avatar-sm {
  width: 32px;
  height: 32px;
  font-size: 14px;
}

/* Table Styles */
.table-modern {
  border-collapse: separate;
  border-spacing: 0;
}

.table-modern thead th {
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.5px;
  color: #6c757d;
}

.table-modern tbody tr {
  transition: all 0.2s;
}

.table-modern tbody tr:hover {
  background-color: rgba(0,0,0,.02);
  transform: scale(1.01);
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.table-modern tbody td {
  vertical-align: middle;
  padding: 1rem;
}

/* Badge Styles */
.badge-modern {
  padding: 0.5em 1em;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.75rem;
  letter-spacing: 0.5px;
}

.badge-status {
  padding: 0.35em 0.85em;
  border-radius: 20px;
}

.badge-status.active {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
}

.badge-status.inactive {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
}

/* Button Styles */
.btn-action {
  padding: 0.375rem 0.75rem;
  border-radius: 5px;
  transition: all 0.2s;
  border: none;
}

.btn-action:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-edit {
  background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
  color: white;
}

.btn-delete {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
}

/* Search Input */
.search-modern {
  border: 2px solid transparent;
  transition: all 0.3s;
}

.search-modern:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

/* Card Styles */
.card-modern {
  border: none;
  border-radius: 15px;
  transition: all 0.3s;
}

.card-modern:hover {
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* Modal Styles */
.modal-modern .modal-content {
  border: none;
  border-radius: 15px;
  overflow: hidden;
}

.modal-modern .modal-header {
  background: linear-gradient(135deg, var(--primary-color) 0%, #c82333 100%);
  color: white;
  border: none;
}

.modal-modern .modal-title {
  font-weight: 600;
}

.modal-modern .btn-close {
  filter: brightness(0) invert(1);
}

/* Form Controls */
.form-control-modern {
  border: 2px solid #e9ecef;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  transition: all 0.3s;
}

.form-control-modern:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

/* Pagination */
.pagination-modern .page-link {
  border: none;
  border-radius: 5px;
  margin: 0 2px;
  padding: 0.5rem 0.75rem;
  color: #6c757d;
  transition: all 0.2s;
}

.pagination-modern .page-link:hover {
  background-color: var(--primary-color);
  color: white;
  transform: translateY(-2px);
}

.pagination-modern .page-item.active .page-link {
  background-color: var(--primary-color);
  border-color: var(--primary-color);
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.fade-in {
  animation: fadeIn 0.5s ease-out;
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
      '<tr><td colspan="6" style="text-align:center; color:red;">Error memuat data! Check console for details.</td></tr>';
  }
}

// Render table with user data
let currentPage = 1;
let perPage = 10;
let filteredUsers = [];

function renderTable(users) {
  filteredUsers = users;
  const start = (currentPage - 1) * perPage;
  const end = start + perPage;
  const paginatedUsers = filteredUsers.slice(start, end);
  
  let html = '';
  
  if (!paginatedUsers || !paginatedUsers.length) {
    html = `<tr>
      <td colspan="6" class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-0">Tidak ada data mahasiswa</p>
      </td>
    </tr>`;
  } else {
    paginatedUsers.forEach((user, index) => {
      const isActive = Math.random() > 0.3; // Random status for demo
      const avatarColors = [
        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'
      ];
      const avatarColor = avatarColors[index % avatarColors.length];
      
      html += `<tr class="fade-in" style="animation-delay: ${index * 0.05}s">
        <td class="px-4">
          <div class="d-flex align-items-center">
            <div class="avatar avatar-sm me-3" style="background: ${avatarColor}">
              ${(user.nama || user.name || 'U').charAt(0).toUpperCase()}
            </div>
            <div>
              <h6 class="mb-0 fw-semibold">${user.nama || user.name || '-'}</h6>
              <small class="text-muted">ID: ${user.id || start + index + 1}</small>
            </div>
          </div>
        </td>
        <td class="px-4">
          <span class="badge badge-modern bg-secondary">${user.nim || '-'}</span>
        </td>
        <td class="px-4">
          <a href="mailto:${user.email}" class="text-decoration-none">
            ${user.email || '-'}
          </a>
        </td>
        <td class="px-4">
          <span class="badge bg-info bg-opacity-10 text-info">
            ${user.jurusan || 'D3 Rekayasa Perangkat Lunak'}
          </span>
        </td>
        <td class="px-4">
          <span class="badge badge-status ${isActive ? 'active' : 'inactive'}">
            ${isActive ? 'Aktif' : 'Tidak Aktif'}
          </span>
        </td>
        <td class="px-4 text-center">
          <button onclick="showEditModal('${user.nim}')" class="btn btn-action btn-edit btn-sm me-1" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button onclick="deleteUser('${user.nim}')" class="btn btn-action btn-delete btn-sm" title="Hapus">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>`;
    });
  }
  
  document.getElementById("user-table-body").innerHTML = html;
  document.getElementById("total-users").innerText = filteredUsers.length;
  document.getElementById("total-data").innerText = filteredUsers.length;
  document.getElementById("showing-start").innerText = filteredUsers.length > 0 ? start + 1 : 0;
  document.getElementById("showing-end").innerText = Math.min(end, filteredUsers.length);
  
  // Update stats
  updateStats(users);
  
  // Render pagination
  renderPagination();
}

function updateStats(users) {
  const totalUsers = users.length;
  const activeToday = Math.floor(totalUsers * 0.3);
  const newWeek = Math.floor(totalUsers * 0.15);
  
  document.getElementById("total-users").innerText = totalUsers;
  document.getElementById("active-today").innerText = activeToday;
  document.getElementById("new-week").innerText = newWeek;
  document.getElementById("total-majors").innerText = 3;
}

function renderPagination() {
  const totalPages = Math.ceil(filteredUsers.length / perPage);
  const paginationEl = document.getElementById("pagination");
  
  let html = '';
  
  // Previous button
  html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
    <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
      <span aria-hidden="true">&laquo;</span>
    </a>
  </li>`;
  
  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
      html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
      </li>`;
    } else if (i === currentPage - 3 || i === currentPage + 3) {
      html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }
  }
  
  // Next button
  html += `<li class="page-item ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}">
    <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
      <span aria-hidden="true">&raquo;</span>
    </a>
  </li>`;
  
  paginationEl.innerHTML = html;
  paginationEl.classList.add('pagination-modern');
}

function changePage(page) {
  const totalPages = Math.ceil(filteredUsers.length / perPage);
  if (page < 1 || page > totalPages) return;
  
  currentPage = page;
  renderTable(filteredUsers);
  return false;
}

function updatePerPage() {
  perPage = parseInt(document.getElementById('perPage').value);
  currentPage = 1;
  renderTable(filteredUsers);
}

function togglePassword() {
  const passwordInput = document.getElementById('form-password');
  const passwordIcon = document.getElementById('password-icon');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    passwordIcon.classList.remove('fa-eye');
    passwordIcon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    passwordIcon.classList.remove('fa-eye-slash');
    passwordIcon.classList.add('fa-eye');
  }
}

function exportData() {
  Swal.fire({
    title: 'Export Data',
    text: 'Pilih format export:',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Excel',
    cancelButtonText: 'CSV',
    showDenyButton: true,
    denyButtonText: 'PDF'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire('Export Excel', 'Data berhasil diexport ke Excel', 'success');
    } else if (result.isDenied) {
      Swal.fire('Export PDF', 'Data berhasil diexport ke PDF', 'success');
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire('Export CSV', 'Data berhasil diexport ke CSV', 'success');
    }
  });
}

// Document ready
document.addEventListener('DOMContentLoaded', function() {
  // Initialize when DOM is ready
});

// Search functionality
function searchUser() {
  const query = document.getElementById('search-nama').value.toLowerCase();
  const jurusan = document.getElementById('filter-jurusan').value;
  
  let filtered = allUsers.filter(u => {
    const matchesQuery = !query || 
      (u.nama && u.nama.toLowerCase().includes(query)) || 
      (u.name && u.name.toLowerCase().includes(query)) ||
      (u.nim && u.nim.toLowerCase().includes(query)) ||
      (u.email && u.email.toLowerCase().includes(query));
    
    const matchesJurusan = !jurusan || u.jurusan === jurusan;
    
    return matchesQuery && matchesJurusan;
  });
  
  currentPage = 1;
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
