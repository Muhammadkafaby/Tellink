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
                    
                    <!-- Stats -->
                    <div class="row g-3 text-center mb-4">
                        <div class="col-4">
                            <div class="p-2">
                                <h5 class="fw-bold mb-0 text-danger">{{ $user->posts_count ?? 0 }}</h5>
                                <small class="text-muted">Posts</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h5 class="fw-bold mb-0 text-info">{{ $user->likes_count ?? 0 }}</h5>
                                <small class="text-muted">Likes</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h5 class="fw-bold mb-0 text-success">{{ $user->reports_count ?? 0 }}</h5>
                                <small class="text-muted">Reports</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger btn-sm" onclick="viewActivity()">
                            <i class="fas fa-chart-line me-2"></i>View Activity
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
                                <p class="text-muted mb-3"><i class="fas fa-lock me-2"></i>Verifikasi password untuk menyimpan perubahan</p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input name="password" id="passwordInput" type="password" 
                                           class="form-control form-control-lg" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordInput')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="confirmInput" type="password" 
                                           class="form-control form-control-lg" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmInput')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="errorMsg" class="invalid-feedback d-none">Password tidak cocok!</div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-light px-4 me-2" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button id="saveBtn" type="submit" class="btn btn-danger px-4" disabled>
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Security Settings -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shield-alt me-2 text-success"></i>Keamanan Akun</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-1">Ubah Password</h6>
                                    <small class="text-muted">Perbarui password akun Anda secara berkala</small>
                                </div>
                                <button class="btn btn-sm btn-outline-danger" onclick="showChangePasswordModal()">
                                    <i class="fas fa-key me-2"></i>Ubah
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-1">Two-Factor Authentication</h6>
                                    <small class="text-muted">Tambahkan lapisan keamanan ekstra</small>
                                </div>
                                <button class="btn btn-sm btn-outline-success" onclick="enable2FA()">
                                    <i class="fas fa-mobile-alt me-2"></i>Aktifkan
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="mb-1">Login History</h6>
                                    <small class="text-muted">Lihat riwayat login akun Anda</small>
                                </div>
                                <button class="btn btn-sm btn-outline-info" onclick="viewLoginHistory()">
                                    <i class="fas fa-history me-2"></i>Lihat
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
// Password validation
const passwordInput = document.getElementById('passwordInput');
const confirmInput = document.getElementById('confirmInput');
const errorMsg = document.getElementById('errorMsg');
const saveBtn = document.getElementById('saveBtn');

function validatePasswords() {
    const pw = passwordInput.value;
    const cpw = confirmInput.value;
    const match = pw && cpw && pw === cpw;
    
    if (pw && cpw) {
        if (match) {
            errorMsg.classList.add('d-none');
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        } else {
            errorMsg.classList.remove('d-none');
            errorMsg.classList.add('d-block');
            confirmInput.classList.add('is-invalid');
            confirmInput.classList.remove('is-valid');
        }
    }
    
    saveBtn.disabled = !match;
}

passwordInput.addEventListener('input', validatePasswords);
confirmInput.addEventListener('input', validatePasswords);

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = event.currentTarget.querySelector('i');
    
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
    validatePasswords();
}

// View activity
function viewActivity() {
    Swal.fire({
        title: 'Activity History',
        html: `
            <div class="text-start">
                <div class="mb-3 p-3 bg-light rounded">
                    <small class="text-muted">Today</small>
                    <p class="mb-0 mt-1">Login at 09:00 AM</p>
                </div>
                <div class="mb-3 p-3 bg-light rounded">
                    <small class="text-muted">Yesterday</small>
                    <p class="mb-0 mt-1">Posted new project</p>
                </div>
                <div class="p-3 bg-light rounded">
                    <small class="text-muted">2 days ago</small>
                    <p class="mb-0 mt-1">Updated profile</p>
                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false
    });
}

// Change password modal
function showChangePasswordModal() {
    Swal.fire({
        title: 'Change Password',
        html: `
            <form id="changePasswordForm">
                <div class="mb-3 text-start">
                    <label class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="currentPassword" required>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newPassword" required>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirmNewPassword" required>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Change Password',
        confirmButtonColor: '#dc3545',
        preConfirm: () => {
            const current = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmNewPassword').value;
            
            if (newPass !== confirm) {
                Swal.showValidationMessage('Passwords do not match');
                return false;
            }
            
            if (newPass.length < 8) {
                Swal.showValidationMessage('Password must be at least 8 characters');
                return false;
            }
            
            return { current, newPass };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Success!', 'Password changed successfully', 'success');
        }
    });
}

// Enable 2FA
function enable2FA() {
    Swal.fire({
        title: 'Enable Two-Factor Authentication',
        text: 'This will add an extra layer of security to your account',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Enable',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Enabled!', '2FA has been enabled for your account', 'success');
        }
    });
}

// View login history
function viewLoginHistory() {
    Swal.fire({
        title: 'Login History',
        html: `
            <div class="text-start" style="max-height: 300px; overflow-y: auto;">
                <div class="mb-2 p-2 border-bottom">
                    <div class="d-flex justify-content-between">
                        <small class="fw-bold">Windows - Chrome</small>
                        <small class="text-muted">Today, 09:00 AM</small>
                    </div>
                    <small class="text-muted">IP: 192.168.1.1</small>
                </div>
                <div class="mb-2 p-2 border-bottom">
                    <div class="d-flex justify-content-between">
                        <small class="fw-bold">Android - App</small>
                        <small class="text-muted">Yesterday, 02:30 PM</small>
                    </div>
                    <small class="text-muted">IP: 192.168.1.2</small>
                </div>
                <div class="p-2">
                    <div class="d-flex justify-content-between">
                        <small class="fw-bold">iOS - Safari</small>
                        <small class="text-muted">3 days ago</small>
                    </div>
                    <small class="text-muted">IP: 192.168.1.3</small>
                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        width: '600px'
    });
}
</script>
@endsection
