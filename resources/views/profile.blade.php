@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1 text-gray-800 fw-bold">Profile Settings</h2>
            <p class="text-muted mb-0">Kelola informasi profile dan keamanan akun Anda</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">

                    <!-- Profile Image -->
                    <div class="position-relative mb-4">
                        <div class="avatar-container mx-auto" style="width: 150px; height: 150px;">
                            @if($user->avatar && file_exists(public_path($user->avatar)))
                                <img class="rounded-circle w-100 h-100 object-fit-cover border border-4 border-light shadow" 
                                     src="{{ asset($user->avatar) }}" 
                                     alt="Profile Image"
                                     id="currentAvatar">
                            @else
                                <div class="rounded-circle w-100 h-100 d-flex align-items-center justify-content-center bg-gradient" 
                                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" 
                                     id="currentAvatar">
                                    <span class="text-white" style="font-size: 3rem; font-weight: 600;">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-danger rounded-circle position-absolute" 
                                style="bottom: 10px; right: 35%; transform: translateX(50%);" 
                                onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>

                    <!-- Display Name -->
                    <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <!-- Admin Info -->
                    <div class="mb-4">
                        <span class="badge bg-danger px-3 py-2">
                            <i class="fas fa-user-shield me-2"></i>Administrator
                        </span>
                    </div>
                    
                    <!-- System Info Button -->
                    <div class="d-grid">
                        <button class="btn btn-outline-secondary btn-sm" onclick="viewSystemInfo()">
                            <i class="fas fa-info-circle me-2"></i>System Information
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Upload Avatar Form (Hidden) -->
            <form id="avatarForm" action="{{ url('/profile/upload-avatar') }}" method="POST" enctype="multipart/form-data" class="d-none">
                @csrf
                <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="previewAndUploadAvatar(this)">
            </form>
        </div>

        <!-- Forms Column -->
        <div class="col-lg-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    @foreach($errors->all() as $error)
                        <p class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</p>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Edit Profile Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2 text-danger"></i>Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form id="editForm" action="{{ url('/profile/update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                                <input name="name" type="text" class="form-control form-control-lg" 
                                       value="{{ $user->name }}" placeholder="Masukkan nama lengkap">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                                <input name="email" type="email" class="form-control form-control-lg" 
                                       value="{{ $user->email }}" placeholder="email@example.com">
                            </div>
                            
                            <div class="col-12">
                                <hr class="my-4">
                                <p class="text-muted mb-3"><i class="fas fa-lock me-2"></i>Masukkan password untuk verifikasi</p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Password Saat Ini <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input name="current_password" type="password" 
                                           class="form-control form-control-lg" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-light px-4 me-2" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Admin Settings -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-cogs me-2 text-primary"></i>Admin Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-1">Ubah Password Admin</h6>
                                    <small class="text-muted">Ganti password administrator</small>
                                </div>
                                <button class="btn btn-sm btn-outline-danger" onclick="showChangePasswordModal()">
                                    <i class="fas fa-key me-2"></i>Ubah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Modern Profile Styles */
.avatar-container img {
    transition: all 0.3s ease;
}

.avatar-container:hover img {
    transform: scale(1.05);
}

.card {
    transition: all 0.3s ease;
    border-radius: 15px !important;
}

.card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<script>
// Form elements - check if they exist before using them
const editForm = document.getElementById('editForm');
if (editForm) {
    // No longer need password confirmation for profile update
}

// Toggle password visibility
function togglePassword(button) {
    const input = button.parentElement.querySelector('input[type="password"], input[type="text"]');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Preview and upload avatar
function previewAndUploadAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.match('image.*')) {
            Swal.fire('Error', 'Please select an image file', 'error');
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Error', 'Image size must be less than 2MB', 'error');
            return;
        }
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            // Show loading
            Swal.fire({
                title: 'Uploading Avatar',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            document.getElementById('avatarForm').submit();
        };
        reader.readAsDataURL(file);
    }
}

// Reset form
function resetForm() {
    document.getElementById('editForm').reset();
}

// View system info
function viewSystemInfo() {
    Swal.fire({
        title: 'System Information',
        html: `
            <div class="text-start">
                <div class="mb-3 p-3 bg-light rounded">
                    <small class="text-muted fw-bold">Laravel Version</small>
                    <p class="mb-0 mt-1">Laravel 11.x</p>
                </div>
                <div class="mb-3 p-3 bg-light rounded">
                    <small class="text-muted fw-bold">PHP Version</small>
                    <p class="mb-0 mt-1">PHP 8.2</p>
                </div>
                <div class="mb-3 p-3 bg-light rounded">
                    <small class="text-muted fw-bold">Database</small>
                    <p class="mb-0 mt-1">MySQL 8.0</p>
                </div>
                <div class="p-3 bg-light rounded">
                    <small class="text-muted fw-bold">Server</small>
                    <p class="mb-0 mt-1">Apache/Nginx</p>
                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        width: '500px'
    });
}

// Change password modal
function showChangePasswordModal() {
    Swal.fire({
        title: 'Ubah Password',
        html: `
            <form id="changePasswordForm" action="{{ url('/profile/change-password') }}" method="POST">
                @csrf
                <div class="mb-3 text-start">
                    <label class="form-label">Password Lama</label>
                    <input type="password" class="form-control" name="current_password" id="currentPassword" required>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Password Baru (min 8 karakter)</label>
                    <input type="password" class="form-control" name="new_password" id="newPassword" required>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="new_password_confirmation" id="confirmNewPassword" required>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ubah Password',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const current = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmNewPassword').value;
            
            if (!current) {
                Swal.showValidationMessage('Password lama harus diisi');
                return false;
            }
            
            if (newPass !== confirm) {
                Swal.showValidationMessage('Password baru tidak cocok');
                return false;
            }
            
            if (newPass.length < 8) {
                Swal.showValidationMessage('Password minimal 8 karakter');
                return false;
            }
            
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form
            document.getElementById('changePasswordForm').submit();
        }
    });
}

</script>
@endsection
