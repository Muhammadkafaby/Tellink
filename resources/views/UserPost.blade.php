@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Data Postingan</h2>
    <button onclick="showCreateModal()" class="btn btn-danger">
      <i class="fas fa-plus"></i> Buat baru
    </button>
  </div>
  
  <!-- Search and Filter Bar -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="d-flex align-items-center">
            <label for="perPage" class="me-2">Tampilkan</label>
            <select id="perPage" class="form-select form-select-sm w-auto me-2" onchange="updatePerPage()">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span>data</span>
          </div>
        </div>
        <div class="col-md-9">
          <div class="input-group">
            <input type="text" id="search-post" class="form-control" placeholder="Search here..." onkeyup="searchPosts()">
            <button class="btn btn-outline-secondary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
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
              <th scope="col" width="10%">NIM</th>
              <th scope="col" width="10%">DATE</th>
              <th scope="col" width="25%">DESC</th>
              <th scope="col" width="15%">IMAGE</th>
              <th scope="col" width="10%">LIKES</th>
              <th scope="col" width="15%">TITLE</th>
              <th scope="col" width="10%" class="text-center">ACTION</th>
            </tr>
          </thead>
          <tbody id="posts-container">
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2">Loading posts...</div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Tambah Postingan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="postForm">
            <input type="hidden" id="edit-id">
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="form-nim" class="form-label">NIM <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="form-nim" required>
              </div>
              
              <div class="col-md-6 mb-3">
                <label for="form-date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="form-date" required>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="form-title" class="form-label">Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="form-title" required>
            </div>
            
            <div class="mb-3">
              <label for="form-desc" class="form-label">Description <span class="text-danger">*</span></label>
              <textarea class="form-control" id="form-desc" rows="3" required></textarea>
            </div>
            
            <div class="mb-3">
              <label for="form-image" class="form-label">Image URL</label>
              <input type="url" class="form-control" id="form-image" placeholder="https://example.com/image.jpg">
              <div id="image-preview" class="mt-2"></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="submitForm()">Simpan</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Font Awesome and SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Image preview */
#image-preview img {
  max-height: 200px;
  max-width: 100%;
  object-fit: contain;
}

/* Table image styling */
.post-image {
  max-height: 100px;
  max-width: 150px;
  object-fit: cover;
  cursor: pointer;
}

/* Badge styling */
.badge {
  font-weight: 500;
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

// Render table with post data
function renderTable() {
  const filteredPosts = getFilteredPosts();
  const startIndex = (currentPage - 1) * perPage;
  const endIndex = startIndex + perPage;
  const paginatedPosts = filteredPosts.slice(startIndex, endIndex);
  
  let html = '';
  
  if (!paginatedPosts.length) {
    html = `<tr>
      <td colspan="8" class="text-center py-4">
        <i class="fas fa-inbox fa-3x text-muted"></i>
        <p class="mt-2 text-muted">Tidak ada postingan</p>
      </td>
    </tr>`;
  } else {
    paginatedPosts.forEach((post) => {
      html += `<tr>
        <td>${post.id}</td>
        <td><span class="badge bg-secondary">${post.nim || '-'}</span></td>
        <td>${formatDate(post.date)}</td>
        <td>
          <div class="text-truncate" style="max-width: 300px;" title="${escapeHtml(post.desc || '')}">
            ${escapeHtml(post.desc || '-')}
          </div>
        </td>
        <td class="text-center">
          ${post.images ? `<img src="${post.images}" class="post-image" onclick="showImage('${post.images}')" alt="Post image">` : '<span class="text-muted">No image</span>'}
        </td>
        <td class="text-center">
          <span class="badge bg-info">
            <i class="fas fa-heart"></i> ${post.likes || 0}
          </span>
        </td>
        <td>${escapeHtml(post.title || '-')}</td>
        <td class="text-center">
          <div class="btn-group btn-group-sm" role="group">
            <button onclick="showEditModal(${post.id})" class="btn btn-warning" title="Edit">
              <i class="fas fa-edit"></i>
            </button>
            <button onclick="deletePost(${post.id})" class="btn btn-danger" title="Hapus">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>`;
    });
  }
  
  document.getElementById("posts-container").innerHTML = html;
}

// Search functionality
function searchPosts() {
  currentPage = 1;
  renderTable();
}

function getFilteredPosts() {
  const query = document.getElementById('search-post').value.toLowerCase();
  if (!query) return allPosts;
  
  return allPosts.filter(post => 
    (post.nim && post.nim.toLowerCase().includes(query)) ||
    (post.title && post.title.toLowerCase().includes(query)) ||
    (post.desc && post.desc.toLowerCase().includes(query))
  );
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
