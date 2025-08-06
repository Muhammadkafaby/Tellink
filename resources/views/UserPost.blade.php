@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="h3 mb-1 text-gray-800 fw-bold">Project Posts</h2>
      <p class="text-muted mb-0">Kelola postingan dan project mahasiswa</p>
    </div>
    <button onclick="showCreateModal()" class="btn btn-danger shadow-sm">
      <i class="fas fa-plus-circle me-2"></i>Buat Postingan
    </button>
  </div>
  
  <!-- Stats Cards -->
  <div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100 stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">Total Posts</h6>
              <h3 class="mb-0 fw-bold text-primary" id="total-posts">0</h3>
            </div>
            <div class="ms-3">
              <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-clipboard-list text-primary fa-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100 stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">Total Likes</h6>
              <h3 class="mb-0 fw-bold text-danger" id="total-likes">0</h3>
            </div>
            <div class="ms-3">
              <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-heart text-danger fa-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100 stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">Posts Today</h6>
              <h3 class="mb-0 fw-bold text-success" id="posts-today">0</h3>
            </div>
            <div class="ms-3">
              <div class="bg-success bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-calendar-day text-success fa-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card border-0 shadow-sm h-100 stats-card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="flex-grow-1">
              <h6 class="text-uppercase text-muted mb-2 small fw-bold">With Images</h6>
              <h3 class="mb-0 fw-bold text-info" id="with-images">0</h3>
            </div>
            <div class="ms-3">
              <div class="bg-info bg-opacity-10 rounded-circle p-3">
                <i class="fas fa-image text-info fa-lg"></i>
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
        <div class="col-md-5">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
              <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="search-post" class="form-control border-start-0 ps-0" placeholder="Cari judul, deskripsi, atau NIM..." onkeyup="searchPosts()">
          </div>
        </div>
        <div class="col-md-2">
          <select id="filter-kategori" class="form-select" onchange="searchPosts()">
            <option value="">Semua Kategori</option>
            <option value="Web Development">Web Development</option>
            <option value="Mobile App">Mobile App</option>
            <option value="Data Science">Data Science</option>
            <option value="UI/UX Design">UI/UX Design</option>
            <option value="Machine Learning">Machine Learning</option>
            <option value="IoT Project">IoT Project</option>
            <option value="Game Development">Game Development</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="date" id="filter-date" class="form-control" onchange="searchPosts()" placeholder="Filter tanggal">
        </div>
        <div class="col-md-2">
          <select id="perPage" class="form-select" onchange="updatePerPage()">
            <option value="10">10 data</option>
            <option value="25">25 data</option>
            <option value="50">50 data</option>
            <option value="100">100 data</option>
          </select>
        </div>
        <div class="col-md-1 text-end">
          <button class="btn btn-outline-secondary" onclick="exportPosts()" title="Export Data">
            <i class="fas fa-download me-2"></i>Export
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Posts Grid View -->
  <div class="row g-4" id="posts-grid-container">
    <!-- Posts will be rendered here -->
    <div class="col-12 text-center py-5">
      <div class="spinner-border text-danger" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div class="mt-3 text-muted">Memuat postingan...</div>
    </div>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted small">
      Menampilkan <span id="showing-start">0</span> - <span id="showing-end">0</span> dari <span id="total-data">0</span> postingan
    </div>
    <nav>
      <ul class="pagination pagination-sm mb-0" id="pagination">
        <!-- Pagination will be generated here -->
      </ul>
    </nav>
  </div>

  <!-- Create/Edit Modal -->
  <div class="modal modal-modern fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0">
        <div class="modal-header bg-gradient border-0">
          <h5 class="modal-title fw-bold" id="modal-title">Tambah Postingan</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <form id="postForm">
            <input type="hidden" id="edit-id">
            
            <div class="row g-3">
              <div class="col-md-6">
                <label for="form-nim" class="form-label small fw-bold text-muted text-uppercase">NIM <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg" id="form-nim" placeholder="Masukkan NIM" required>
                <div class="invalid-feedback">NIM wajib diisi</div>
              </div>
              
              <div class="col-md-6">
                <label for="form-date" class="form-label small fw-bold text-muted text-uppercase">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control form-control-lg" id="form-date" required>
                <div class="invalid-feedback">Tanggal wajib diisi</div>
              </div>
              
              <div class="col-12">
                <label for="form-title" class="form-label small fw-bold text-muted text-uppercase">Judul Project <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg" id="form-title" placeholder="Masukkan judul project" required>
                <div class="invalid-feedback">Judul wajib diisi</div>
              </div>
              
              <div class="col-12">
                <label for="form-desc" class="form-label small fw-bold text-muted text-uppercase">Deskripsi <span class="text-danger">*</span></label>
                <textarea class="form-control form-control-lg" id="form-desc" rows="4" placeholder="Deskripsikan project Anda..." required></textarea>
                <div class="invalid-feedback">Deskripsi wajib diisi</div>
              </div>
              
              <div class="col-12">
                <label for="form-image" class="form-label small fw-bold text-muted text-uppercase">URL Gambar</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-image"></i></span>
                  <input type="url" class="form-control form-control-lg" id="form-image" placeholder="https://example.com/image.jpg">
                </div>
                <div id="image-preview" class="mt-3"></div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger px-4" onclick="submitForm()">
            <i class="fas fa-save me-2"></i>Simpan
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Font Awesome and SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Modern Card Styles */
.post-card {
  border: none;
  border-radius: 15px;
  overflow: hidden;
  transition: all 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.post-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.post-card-image {
  height: 200px;
  overflow: hidden;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
}

.post-card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.post-card:hover .post-card-image img {
  transform: scale(1.1);
}

.post-card-image.no-image {
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 3rem;
}

.post-card-body {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.post-card-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #2d3748;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.post-card-description {
  color: #718096;
  font-size: 0.95rem;
  line-height: 1.6;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  flex: 1;
}

.post-card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  margin-top: auto;
  border-top: 1px solid #e2e8f0;
}

.post-card-author {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.post-card-author-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.875rem;
}

.post-card-stats {
  display: flex;
  gap: 1rem;
}

.post-card-stat {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #718096;
  font-size: 0.875rem;
}

.post-card-actions {
  padding: 1rem 1.5rem;
  background: #f7fafc;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.post-date-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(255,255,255,0.95);
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  color: #4a5568;
  backdrop-filter: blur(10px);
}

/* Stats Cards */
.stats-card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Modal Styles */
.modal-modern .modal-content {
  border: none;
  border-radius: 15px;
  overflow: hidden;
}

.modal-modern .modal-header {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  border: none;
  padding: 1.5rem;
}

.modal-modern .btn-close-white {
  filter: brightness(0) invert(1);
}

.bg-gradient {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

/* Image Preview */
#image-preview {
  border: 2px dashed #e2e8f0;
  border-radius: 10px;
  padding: 1rem;
  text-align: center;
  min-height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
}

#image-preview img {
  max-height: 200px;
  max-width: 100%;
  object-fit: contain;
  border-radius: 8px;
}

/* Pagination Modern */
.pagination-modern .page-link {
  border: none;
  border-radius: 5px;
  margin: 0 2px;
  padding: 0.5rem 0.75rem;
  color: #6c757d;
  transition: all 0.2s;
}

.pagination-modern .page-link:hover {
  background-color: #dc3545;
  color: white;
  transform: translateY(-2px);
}

.pagination-modern .page-item.active .page-link {
  background-color: #dc3545;
  border-color: #dc3545;
}

/* Like Button Animation */
@keyframes likeAnimation {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}

.btn-like {
  transition: all 0.2s;
}

.btn-like:hover {
  animation: likeAnimation 0.3s ease;
}

.btn-like.liked {
  color: #dc3545;
  background: rgba(220, 53, 69, 0.1);
}

/* Animations */
@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(20px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}

.fade-in {
  animation: fadeIn 0.5s ease-out;
}
</style>

<script>
let allPosts = [];
let currentPage = 1;
let perPage = 10;
let deletePostId = null;

// Load posts on page load
async function loadPosts() {
  try {
    const response = await fetch('/api/tellink/messages', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      credentials: 'same-origin'
    });
    
    if (response.status === 401) {
      window.location.href = '/login';
      return;
    }
    
    if (!response.ok) throw new Error('Network response was not ok');
    
    const resData = await response.json();
    console.log('API Response:', resData);
    
    // Handle different response formats
    if (resData.data && Array.isArray(resData.data)) {
      allPosts = resData.data;
    } else if (Array.isArray(resData)) {
      allPosts = resData;
    } else {
      allPosts = [];
    }
    
    renderTable();
  } catch (err) {
    console.error('Error loading posts:', err);
    document.getElementById("posts-container").innerHTML = 
      `<tr><td colspan="8" class="text-center py-4">
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i> 
          <strong>Perhatian:</strong> API Tellink sedang tidak dapat diakses. Menampilkan data contoh.
          <br><small class="text-muted">Error: ${err.message}</small>
        </div>
      </td></tr>`;
  }
}

// Render grid view with post data
function renderTable() {
  const filteredPosts = getFilteredPosts();
  const startIndex = (currentPage - 1) * perPage;
  const endIndex = startIndex + perPage;
  const paginatedPosts = filteredPosts.slice(startIndex, endIndex);
  
  const container = document.getElementById("posts-grid-container");
  
  if (!paginatedPosts.length) {
    container.innerHTML = `
      <div class="col-12 text-center py-5">
        <i class="fas fa-inbox fa-5x text-muted mb-3"></i>
        <h4 class="text-muted">Belum ada postingan</h4>
        <p class="text-muted">Mulai buat postingan pertama Anda!</p>
        <button onclick="showCreateModal()" class="btn btn-danger mt-3">
          <i class="fas fa-plus-circle me-2"></i>Buat Postingan
        </button>
      </div>
    `;
  } else {
    let html = '';
    paginatedPosts.forEach((post, index) => {
      const authorInitial = post.nim ? post.nim.charAt(0).toUpperCase() : 'U';
      const hasImage = post.images && post.images !== '';
      
      html += `
        <div class="col-xl-4 col-lg-6 col-md-6 fade-in" style="animation-delay: ${index * 0.1}s">
          <div class="card post-card shadow-sm">
            <div class="post-card-image ${!hasImage ? 'no-image' : ''}">
              ${hasImage ? 
                `<img src="${post.images}" alt="${escapeHtml(post.title)}" onclick="showImage('${post.images}')" style="cursor: pointer;">` : 
                '<i class="fas fa-image"></i>'
              }
              <span class="post-date-badge">
                <i class="fas fa-calendar me-1"></i>${formatDate(post.date)}
              </span>
            </div>
            
            <div class="post-card-body">
              <h5 class="post-card-title">${escapeHtml(post.title || 'Untitled')}</h5>
              <p class="post-card-description">${escapeHtml(post.desc || 'No description')}</p>
              
              <div class="post-card-meta">
                <div class="post-card-author">
                  <div class="post-card-author-avatar">${authorInitial}</div>
                  <div>
                    <div class="fw-semibold small">${post.nim || 'Unknown'}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Mahasiswa</div>
                  </div>
                </div>
                
                <div class="post-card-stats">
                  <button class="btn btn-sm btn-like ${post.liked ? 'liked' : ''}" onclick="likePost(${post.id})">
                    <i class="fas fa-heart"></i> ${post.likes || 0}
                  </button>
                </div>
              </div>
            </div>
            
            <div class="post-card-actions">
              <button onclick="viewPost(${post.id})" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye me-1"></i>View
              </button>
              <div>
                <button onclick="showEditModal(${post.id})" class="btn btn-sm btn-warning me-1">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="deletePost(${post.id})" class="btn btn-sm btn-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
    });
    container.innerHTML = html;
  }
  
  // Update stats
  updateStats(filteredPosts);
  
  // Update pagination info
  document.getElementById("showing-start").innerText = filteredPosts.length > 0 ? startIndex + 1 : 0;
  document.getElementById("showing-end").innerText = Math.min(endIndex, filteredPosts.length);
  document.getElementById("total-data").innerText = filteredPosts.length;
  
  // Render pagination
  renderPagination(filteredPosts.length);
}

// Update statistics
function updateStats(posts) {
  const totalPosts = posts.length;
  const totalLikes = posts.reduce((sum, post) => sum + (post.likes || 0), 0);
  const today = new Date().toDateString();
  const postsToday = posts.filter(post => {
    const postDate = new Date(post.date).toDateString();
    return postDate === today;
  }).length;
  const withImages = posts.filter(post => post.images && post.images !== '').length;
  
  document.getElementById("total-posts").innerText = totalPosts;
  document.getElementById("total-likes").innerText = totalLikes;
  document.getElementById("posts-today").innerText = postsToday;
  document.getElementById("with-images").innerText = withImages;
}

// Render pagination
function renderPagination(totalItems) {
  const totalPages = Math.ceil(totalItems / perPage);
  const paginationEl = document.getElementById("pagination");
  
  let html = '';
  
  // Previous button
  html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
    <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
      <span aria-hidden="true">&laquo;</span>
    </a>
  </li>`;
  
  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
      html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
      </li>`;
    } else if (i === currentPage - 3 || i === currentPage + 3) {
      html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }
  }
  
  // Next button
  html += `<li class="page-item ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}">
    <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
      <span aria-hidden="true">&raquo;</span>
    </a>
  </li>`;
  
  paginationEl.innerHTML = html;
  paginationEl.classList.add('pagination-modern');
}

// Change page
function changePage(page) {
  const filteredPosts = getFilteredPosts();
  const totalPages = Math.ceil(filteredPosts.length / perPage);
  
  if (page < 1 || page > totalPages) return;
  
  currentPage = page;
  renderTable();
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// View post details
function viewPost(id) {
  const post = allPosts.find(p => p.id === id);
  if (!post) return;
  
  Swal.fire({
    title: escapeHtml(post.title || 'Untitled'),
    html: `
      <div class="text-start">
        ${post.images ? `<img src="${post.images}" class="img-fluid rounded mb-3" alt="Post image">` : ''}
        <p class="text-muted"><i class="fas fa-user me-2"></i>NIM: ${post.nim || 'Unknown'}</p>
        <p class="text-muted"><i class="fas fa-calendar me-2"></i>${formatDate(post.date)}</p>
        <hr>
        <p>${escapeHtml(post.desc || 'No description')}</p>
        <hr>
        <div class="d-flex justify-content-between align-items-center">
          <span class="badge bg-danger"><i class="fas fa-heart me-1"></i>${post.likes || 0} Likes</span>
        </div>
      </div>
    `,
    showCloseButton: true,
    showConfirmButton: false,
    width: '600px'
  });
}

// Like post
function likePost(id) {
  const post = allPosts.find(p => p.id === id);
  if (!post) return;
  
  // Toggle like
  post.liked = !post.liked;
  post.likes = (post.likes || 0) + (post.liked ? 1 : -1);
  
  // Re-render
  renderTable();
  
  // You could also make an API call here to persist the like
}

// Export posts
function exportPosts() {
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
      Swal.fire('Export Excel', 'Data postingan berhasil diexport ke Excel', 'success');
    } else if (result.isDenied) {
      Swal.fire('Export PDF', 'Data postingan berhasil diexport ke PDF', 'success');
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire('Export CSV', 'Data postingan berhasil diexport ke CSV', 'success');
    }
  });
}

// Search functionality
function searchPosts() {
  currentPage = 1;
  renderTable();
}

function getFilteredPosts() {
  const query = document.getElementById('search-post').value.toLowerCase();
  const dateFilter = document.getElementById('filter-date').value;
  const kategoriFilter = document.getElementById('filter-kategori').value;
  
  return allPosts.filter(post => {
    const matchesQuery = !query || 
      (post.nim && post.nim.toLowerCase().includes(query)) ||
      (post.title && post.title.toLowerCase().includes(query)) ||
      (post.desc && post.desc.toLowerCase().includes(query));
    
    const matchesDate = !dateFilter || post.date === dateFilter;
    
    const matchesKategori = !kategoriFilter || post.kategori === kategoriFilter;
    
    return matchesQuery && matchesDate && matchesKategori;
  });
}

// Update per page
function updatePerPage() {
  perPage = parseInt(document.getElementById('perPage').value);
  currentPage = 1;
  renderTable();
}

// Show create modal
function showCreateModal() {
  document.getElementById('modal-title').innerText = 'Tambah Postingan Baru';
  document.getElementById('postForm').reset();
  document.getElementById('edit-id').value = '';
  document.getElementById('image-preview').innerHTML = '';
  
  const modal = new bootstrap.Modal(document.getElementById('postModal'));
  modal.show();
}

// Show edit modal
function showEditModal(id) {
  const post = allPosts.find(p => p.id === id);
  if (!post) {
    Swal.fire('Error', 'Post tidak ditemukan', 'error');
    return;
  }
  
  document.getElementById('modal-title').innerText = 'Edit Postingan';
  document.getElementById('edit-id').value = id;
  document.getElementById('form-nim').value = post.nim || '';
  document.getElementById('form-date').value = post.date || '';
  document.getElementById('form-title').value = post.title || '';
  document.getElementById('form-desc').value = post.desc || '';
  document.getElementById('form-image').value = post.images || '';
  
  if (post.images) {
    document.getElementById('image-preview').innerHTML = `<img src="${post.images}" class="img-thumbnail">`;
  }
  
  const modal = new bootstrap.Modal(document.getElementById('postModal'));
  modal.show();
}

// Submit form
async function submitForm() {
  const editId = document.getElementById('edit-id').value;
  const data = {
    nim: document.getElementById('form-nim').value,
    date: document.getElementById('form-date').value,
    title: document.getElementById('form-title').value,
    desc: document.getElementById('form-desc').value,
    images: document.getElementById('form-image').value || null,
    likes: 0
  };
  
  if (!data.nim || !data.date || !data.title || !data.desc) {
    Swal.fire('Error', 'Semua field wajib diisi!', 'error');
    return;
  }
  
  try {
    let response;
    if (editId) {
      // Update existing post
      response = await fetch(`/api/tellink/messages/${editId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
      });
    } else {
      // Create new post
      response = await fetch('/api/tellink/messages', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
      });
    }
    
    if (!response.ok) throw new Error('Request failed');
    
    Swal.fire('Berhasil!', editId ? 'Post berhasil diupdate!' : 'Post berhasil ditambahkan!', 'success');
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('postModal')).hide();
    
    // Reload posts
    loadPosts();
  } catch(e) {
    console.error('Error saving post:', e);
    Swal.fire('Error!', 'Gagal menyimpan post. Silakan coba lagi.', 'error');
  }
}

// Delete post
async function deletePost(id) {
  const post = allPosts.find(p => p.id === id);
  if (!post) {
    Swal.fire('Error', 'Post tidak ditemukan', 'error');
    return;
  }
  
  const result = await Swal.fire({
    title: 'Konfirmasi Hapus',
    text: 'Yakin ingin menghapus postingan ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  });
  
  if (!result.isConfirmed) return;
  
  try {
    const response = await fetch(`/api/tellink/messages/${id}?nim=${post.nim}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      credentials: 'same-origin'
    });
    
    if (!response.ok) throw new Error('Delete failed');
    
    Swal.fire('Terhapus!', 'Postingan berhasil dihapus.', 'success');
    loadPosts();
  } catch(e) {
    console.error('Error deleting post:', e);
    Swal.fire('Error!', 'Gagal menghapus postingan. Silakan coba lagi.', 'error');
  }
}

// Show image in modal
function showImage(imageUrl) {
  Swal.fire({
    imageUrl: imageUrl,
    imageAlt: 'Post image',
    showConfirmButton: false,
    showCloseButton: true,
    width: 'auto'
  });
}

// Utility functions
function formatDate(dateString) {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

// Preview image when URL is entered
document.getElementById('form-image').addEventListener('input', function() {
  const url = this.value;
  const preview = document.getElementById('image-preview');
  
  if (url) {
    preview.innerHTML = `<img src="${url}" class="img-thumbnail" onerror="this.style.display='none'">`;
  } else {
    preview.innerHTML = '';
  }
});

// Load data when page loads
window.onload = function() {
  console.log('Page loaded, fetching posts...');
  loadPosts();
};
</script>
@endsection
