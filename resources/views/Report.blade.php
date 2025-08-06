@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="mb-4">
        <h2 class="h3 mb-1 text-gray-800 fw-bold">Project Reports</h2>
        <p class="text-muted mb-0">Kelola dan review laporan project mahasiswa</p>
    </div>

    <style>
        /* Modern UI Styles */
        .stats-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 15px;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .status-badge {
            padding: 0.35em 0.85em;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
        }
        
        .status-resolved {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-investigating {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .table-modern thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding: 1rem;
        }
        
        .table-modern tbody tr {
            transition: all 0.2s;
            border-bottom: 1px solid #f1f3f5;
        }
        
        .table-modern tbody tr:hover {
            background-color: rgba(0,0,0,.02);
            transform: scale(1.01);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
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
        
.pagination .page-item.active .page-link {
  background-color: #dc3545;
  border-color: #dc3545;
  color: white !important;
}
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Total Reports</h6>
                            <h3 class="mb-0 fw-bold text-dark" id="totalReports">0</h3>
                        </div>
                        <div class="ms-3">
                            <div class="bg-secondary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-flag text-secondary fa-lg"></i>
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
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Pending</h6>
                            <h3 class="mb-0 fw-bold text-warning" id="pendingReports">0</h3>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-warning fa-lg"></i>
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
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Investigating</h6>
                            <h3 class="mb-0 fw-bold text-info" id="investigatingReports">0</h3>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-search text-info fa-lg"></i>
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
                            <h6 class="text-uppercase text-muted mb-2 small fw-bold">Resolved</h6>
                            <h3 class="mb-0 fw-bold text-success" id="resolvedReports">0</h3>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
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
                        <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari project ID, NIM, atau alasan..." onkeyup="applyFilters()">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select" onchange="applyFilters()">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="investigating">Investigating</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="perPageSelect" class="form-select" onchange="applyFilters()">
                        <option value="10" selected>10 data</option>
                        <option value="25">25 data</option>
                        <option value="50">50 data</option>
                        <option value="100">100 data</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
            
    <!-- Table Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <!-- Loading state -->
            <div id="loadingState" class="text-center py-5">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-3 text-muted">Memuat laporan...</div>
            </div>
            
            <!-- Table Container -->
            <div id="tableContainer" class="table-responsive d-none">
                <table class="table table-hover mb-0 table-modern">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">No</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Report ID</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Project ID</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Project Title</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Reported By</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted">Reason</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted text-center">Status</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted text-center">Date</th>
                            <th scope="col" class="px-4 py-3 text-uppercase small fw-bold text-muted text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Empty state -->
            <div id="emptyState" class="text-center py-5 d-none">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">Tidak ada laporan ditemukan</p>
                <p class="text-muted small">Semua project dalam kondisi baik!</p>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div id="paginationInfo" class="text-muted small"></div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="paginationControls">
                        <!-- Pagination will be generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold" id="detailsModalLabel">Report Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="detailsContent">
                        <!-- Details will be populated here -->
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold" id="statusModalLabel">Update Report Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="newStatus" class="form-label small fw-bold text-muted text-uppercase">New Status</label>
                            <select id="newStatus" class="form-select form-select-lg">
                                <option value="pending">Pending</option>
                                <option value="investigating">Investigating</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="statusNote" class="form-label small fw-bold text-muted text-uppercase">Note (Optional)</label>
                            <textarea id="statusNote" rows="3" class="form-control" placeholder="Add a note about this status change..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome and SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Global variables
let allData = [];
let filteredData = [];
let currentPage = 1;
let perPage = 10;
let currentReportId = null;

// CSRF Token setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Refresh data
function refreshData() {
    fetchData();
}

// Export reports
function exportReports() {
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
            Swal.fire('Export Excel', 'Data laporan berhasil diexport ke Excel', 'success');
        } else if (result.isDenied) {
            Swal.fire('Export PDF', 'Data laporan berhasil diexport ke PDF', 'success');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Export CSV', 'Data laporan berhasil diexport ke CSV', 'success');
        }
    });
}

// Toggle view (placeholder function)
function toggleView() {
    Swal.fire('Info', 'Grid view toggle belum tersedia', 'info');
}

// Fetch data from API
async function fetchData() {
    try {
        const response = await fetch('/api/tellink/reports', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Failed to fetch data');
        
        const result = await response.json();
        
        // Handle the response structure based on API documentation
        if (result.success !== undefined && !result.success) {
            throw new Error(result.message || 'Failed to fetch reports');
        }
        
        // Extract data array from response
        allData = Array.isArray(result) ? result : (result.data || []);
        
        filteredData = [...allData];
        
        updateStats();
        applyFilters();
        renderTable();
        updatePagination();
        
        // Hide loading, show table
        document.getElementById('loadingState').classList.add('d-none');
        if (allData.length > 0) {
            document.getElementById('tableContainer').classList.remove('d-none');
            document.getElementById('emptyState').classList.add('d-none');
        } else {
            document.getElementById('tableContainer').classList.add('d-none');
            document.getElementById('emptyState').classList.remove('d-none');
        }
    } catch (error) {
        console.error('Error fetching data:', error);
        Swal.fire('Error!', 'Failed to load reports: ' + error.message, 'error');
        document.getElementById('loadingState').classList.add('d-none');
        document.getElementById('emptyState').classList.remove('d-none');
    }
}

// Update statistics
function updateStats() {
    const total = allData.length;
    const pending = allData.filter(r => r.status === 'pending').length;
    const investigating = allData.filter(r => r.status === 'investigating').length;
    const resolved = allData.filter(r => r.status === 'resolved').length;
    
    document.getElementById('totalReports').textContent = total;
    document.getElementById('pendingReports').textContent = pending;
    document.getElementById('investigatingReports').textContent = investigating;
    document.getElementById('resolvedReports').textContent = resolved;
}

// Format date
function formatDate(dateString) {
    if (!dateString) return '-';
    
    // Handle Firebase timestamp
    if (dateString.seconds) {
        const date = new Date(dateString.seconds * 1000);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    // Handle ISO string
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Get status badge HTML
function getStatusBadge(status) {
    const statusClasses = {
        'pending': 'status-pending',
        'investigating': 'status-investigating',
        'resolved': 'status-resolved'
    };
    
    const statusText = {
        'pending': 'Pending',
        'investigating': 'Investigating',
        'resolved': 'Resolved'
    };
    
    const className = statusClasses[status] || 'status-pending';
    const text = statusText[status] || status;
    
    return `<span class="status-badge ${className}">${text}</span>`;
}

// Render table
function renderTable() {
    const tableBody = document.getElementById('tableBody');
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const pageData = filteredData.slice(start, end);
    
    tableBody.innerHTML = '';
    
    pageData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4">${start + index + 1}</td>
            <td class="px-4"><span class="badge bg-secondary font-monospace">${item.id ? item.id.substring(0, 8) + '...' : '-'}</span></td>
            <td class="px-4"><span class="badge bg-secondary font-monospace">${item.projectId ? item.projectId.substring(0, 8) + '...' : '-'}</span></td>
            <td class="px-4 fw-semibold">${item.projectTitle || 'Unknown Project'}</td>
            <td class="px-4">${item.reportedBy || '-'}</td>
            <td class="px-4">
                <div class="text-truncate" style="max-width: 200px;" title="${item.reason || ''}">
                    ${item.reason || '-'}
                </div>
            </td>
            <td class="px-4 text-center">${getStatusBadge(item.status || 'pending')}</td>
            <td class="px-4 text-center"><small class="text-muted">${formatDate(item.createdAt)}</small></td>
            <td class="px-4 text-center">
                <button onclick="viewDetails('${item.id}')" class="btn btn-sm btn-info me-1" title="View Details">
                    <i class="fas fa-eye"></i>
                </button>
                <button onclick="updateStatus('${item.id}')" class="btn btn-sm btn-warning" title="Update Status">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
    
    // Show table container after rendering
    if (pageData.length > 0) {
        document.getElementById('tableContainer').classList.remove('d-none');
    }
}

// Update pagination
function updatePagination() {
    const totalPages = Math.ceil(filteredData.length / perPage);
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationControls = document.getElementById('paginationControls');
    
    // Update info
    const start = filteredData.length === 0 ? 0 : (currentPage - 1) * perPage + 1;
    const end = Math.min(currentPage * perPage, filteredData.length);
    paginationInfo.textContent = `Menampilkan ${start} - ${end} dari ${filteredData.length} laporan`;
    
    // Update controls
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
    
    paginationControls.innerHTML = html;
    paginationControls.classList.add('pagination-modern');
}

// Change page
function changePage(page) {
    const totalPages = Math.ceil(filteredData.length / perPage);
    if (page < 1 || page > totalPages) return;
    
    currentPage = page;
    renderTable();
    updatePagination();
}

// View details function
function viewDetails(id) {
    const item = allData.find(d => d.id === id);
    if (!item) return;
    
    const detailsContent = document.getElementById('detailsContent');
    detailsContent.innerHTML = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Report ID</label>
                <p class="font-monospace">${item.id}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Status</label>
                <p>${getStatusBadge(item.status || 'pending')}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Project ID</label>
                <p class="font-monospace">${item.projectId}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Project Title</label>
                <p>${item.projectTitle || 'Unknown Project'}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Reported By</label>
                <p>${item.reportedBy}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Report Date</label>
                <p>${formatDate(item.createdAt)}</p>
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold text-muted">Reason for Report</label>
                <div class="bg-light p-3 rounded">${item.reason}</div>
            </div>
        </div>
        ${item.statusHistory ? `
        <div class="mt-4">
            <label class="form-label small fw-bold text-muted">Status History</label>
            ${item.statusHistory.map(h => `
                <div class="bg-light p-3 rounded mb-2">
                    <span class="fw-semibold">${h.status}</span> - ${formatDate(h.date)}
                    ${h.note ? `<p class="text-muted mt-1 mb-0">${h.note}</p>` : ''}
                </div>
            `).join('')}
        </div>
        ` : ''}
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
}

// Update status function
function updateStatus(id) {
    const item = allData.find(d => d.id === id);
    if (!item) return;
    
    currentReportId = id;
    document.getElementById('newStatus').value = item.status || 'pending';
    document.getElementById('statusNote').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Apply filters
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    
    filteredData = allData.filter(item => {
        // Search filter
        const matchesSearch = searchTerm === '' || 
            (item.projectId && item.projectId.toLowerCase().includes(searchTerm)) ||
            (item.reportedBy && item.reportedBy.toLowerCase().includes(searchTerm)) ||
            (item.reason && item.reason.toLowerCase().includes(searchTerm)) ||
            (item.projectTitle && item.projectTitle.toLowerCase().includes(searchTerm));
        
        // Status filter
        const matchesStatus = statusFilter === '' || item.status === statusFilter;
        
        return matchesSearch && matchesStatus;
    });
    
    currentPage = 1;
    renderTable();
    updatePagination();
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', applyFilters);

// Status filter
document.getElementById('statusFilter').addEventListener('change', applyFilters);

// Per page change
document.getElementById('perPageSelect').addEventListener('change', function(e) {
    perPage = parseInt(e.target.value);
    currentPage = 1;
    renderTable();
    updatePagination();
});


// Handle status form submission
document.getElementById('statusForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!currentReportId) return;
    
    const newStatus = document.getElementById('newStatus').value;
    const note = document.getElementById('statusNote').value;
    
    try {
        // Since there's no specific endpoint for updating report status in the API docs,
        // we'll simulate the update locally
        const report = allData.find(d => d.id === currentReportId);
        if (report) {
            report.status = newStatus;
            if (!report.statusHistory) report.statusHistory = [];
            report.statusHistory.push({
                status: newStatus,
                date: new Date().toISOString(),
                note: note
            });
        }
        
        Swal.fire('Berhasil!', 'Status laporan berhasil diupdate', 'success');
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
        
        // Refresh display
        updateStats();
        applyFilters();
        
    } catch (error) {
        console.error('Error updating status:', error);
        Swal.fire('Error!', 'Gagal mengupdate status', 'error');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchData();
});
</script>
@endsection
