@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1 text-gray-800 fw-bold">Project Reports</h2>
            <p class="text-muted mb-0">Kelola dan review laporan project mahasiswa</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="refreshData()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <button class="btn btn-danger shadow-sm" onclick="exportReports()">
                <i class="fas fa-download me-2"></i>Export Reports
            </button>
        </div>
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
                <div class="col-md-4">
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
                <div class="col-md-3 text-end">
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-secondary" onclick="toggleView()">
                            <i class="fas fa-th-large me-2" id="viewIcon"></i>
                            <span id="viewText">Grid View</span>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="exportReports()">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
            <!-- Table Section -->
            <div class="px-6 pb-6">
                <!-- Loading state -->
                <div id="loadingState" class="flex justify-center items-center py-12">
                    <div class="text-center">
                        <div class="loading-spinner mx-auto mb-4" style="width: 40px; height: 40px; border-color: #dc2626;"></div>
                        <p class="text-gray-600">Loading reports...</p>
                    </div>
                </div>
                
                <!-- Table Container -->
                <div id="tableContainer" class="overflow-x-auto hidden">
                    <table id="dataTable" class="min-w-full">
                        <thead>
                            <tr class="bg-red-600 text-white">
                                <th class="py-3 px-4 text-left header-cell">No</th>
                                <th class="py-3 px-4 text-left header-cell">Report ID</th>
                                <th class="py-3 px-4 text-left header-cell">Project ID</th>
                                <th class="py-3 px-4 text-left header-cell">Project Title</th>
                                <th class="py-3 px-4 text-left header-cell">Reported By</th>
                                <th class="py-3 px-4 text-left header-cell">Reason</th>
                                <th class="py-3 px-4 text-center header-cell">Status</th>
                                <th class="py-3 px-4 text-center header-cell">Date</th>
                                <th class="py-3 px-4 text-center header-cell">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty state -->
                <div id="emptyState" class="text-center py-12 hidden">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No reports found</p>
                    <p class="text-gray-400 text-sm mt-2">All projects are in good standing!</p>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="border-t border-gray-200 px-6 py-4">
                <div id="paginationContainer" class="flex justify-between items-center">
                    <div id="paginationInfo" class="text-sm text-gray-700"></div>
                    <div id="paginationControls" class="flex gap-2"></div>
                </div>
            </div>

    <!-- View Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-[999]">
        <div class="bg-white w-[90%] max-w-2xl rounded-lg p-6 shadow-lg mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-bold">Report Details</h2>
                <button id="closeDetailsModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detailsContent" class="space-y-4">
                <!-- Details will be populated here -->
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-[999]">
        <div class="bg-white w-[90%] max-w-md rounded-lg p-6 shadow-lg mx-4">
            <h2 class="text-xl font-bold mb-4">Update Report Status</h2>
            <form id="statusForm">
                <div class="mb-4">
                    <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select id="newStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="pending">Pending</option>
                        <option value="investigating">Investigating</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="statusNote" class="block text-sm font-medium text-gray-700 mb-2">Note (Optional)</label>
                    <textarea id="statusNote" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Add a note about this status change..."></textarea>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="closeStatusModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span id="toastMessage"></span>
    </div>
</div>

<script>
// Global variables
let allData = [];
let filteredData = [];
let currentPage = 1;
let perPage = 10;
let currentReportId = null;

// CSRF Token setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
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
        document.getElementById('loadingState').classList.add('hidden');
        if (allData.length > 0) {
            document.getElementById('tableContainer').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
        } else {
            document.getElementById('tableContainer').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error fetching data:', error);
        showToast('Failed to load reports: ' + error.message, 'error');
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('emptyState').classList.remove('hidden');
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
        row.className = 'hover:bg-gray-50 transition-colors';
        row.innerHTML = `
            <td class="py-4 px-4 text-sm">${start + index + 1}</td>
            <td class="py-4 px-4 text-xs font-mono text-gray-600">${item.id ? item.id.substring(0, 8) + '...' : '-'}</td>
            <td class="py-4 px-4 text-xs font-mono text-gray-600">${item.projectId ? item.projectId.substring(0, 8) + '...' : '-'}</td>
            <td class="py-4 px-4 text-sm font-medium">${item.projectTitle || 'Unknown Project'}</td>
            <td class="py-4 px-4 text-sm">${item.reportedBy || '-'}</td>
            <td class="py-4 px-4">
                <div class="text-sm text-gray-600 max-w-xs truncate" title="${item.reason || ''}">
                    ${item.reason || '-'}
                </div>
            </td>
            <td class="py-4 px-4 text-center">${getStatusBadge(item.status || 'pending')}</td>
            <td class="py-4 px-4 text-center text-sm text-gray-600">${formatDate(item.createdAt)}</td>
            <td class="py-4 px-4 text-center">
                <div class="flex gap-2 justify-center">
                    <button onclick="viewDetails('${item.id}')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="updateStatus('${item.id}')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors" title="Update Status">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });
    
    // Show table container after rendering
    if (pageData.length > 0) {
        document.getElementById('tableContainer').classList.remove('hidden');
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
    paginationInfo.textContent = `Showing ${start} - ${end} of ${filteredData.length} reports`;
    
    // Update controls
    paginationControls.innerHTML = '';
    
    // Previous button
    if (currentPage > 1) {
        const prevBtn = createPaginationButton('Previous', () => {
            currentPage--;
            renderTable();
            updatePagination();
        });
        paginationControls.appendChild(prevBtn);
    }
    
    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        const pageBtn = createPaginationButton(i, () => {
            currentPage = i;
            renderTable();
            updatePagination();
        }, isActive);
        paginationControls.appendChild(pageBtn);
    }
    
    // Next button
    if (currentPage < totalPages) {
        const nextBtn = createPaginationButton('Next', () => {
            currentPage++;
            renderTable();
            updatePagination();
        });
        paginationControls.appendChild(nextBtn);
    }
}

// Create pagination button
function createPaginationButton(text, onClick, isActive = false) {
    const button = document.createElement('button');
    button.textContent = text;
    button.onclick = onClick;
    button.className = `px-3 py-1 rounded ${isActive ? 'bg-red-500 text-white' : 'bg-gray-100 hover:bg-gray-200'}`;
    return button;
}

// View details function
function viewDetails(id) {
    const item = allData.find(d => d.id === id);
    if (!item) return;
    
    const detailsContent = document.getElementById('detailsContent');
    detailsContent.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Report ID</h3>
                <p class="mt-1 text-sm text-gray-900 font-mono">${item.id}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Status</h3>
                <p class="mt-1">${getStatusBadge(item.status || 'pending')}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Project ID</h3>
                <p class="mt-1 text-sm text-gray-900 font-mono">${item.projectId}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Project Title</h3>
                <p class="mt-1 text-sm text-gray-900">${item.projectTitle || 'Unknown Project'}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Reported By</h3>
                <p class="mt-1 text-sm text-gray-900">${item.reportedBy}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Report Date</h3>
                <p class="mt-1 text-sm text-gray-900">${formatDate(item.createdAt)}</p>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500">Reason for Report</h3>
            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded">${item.reason}</p>
        </div>
        ${item.statusHistory ? `
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Status History</h3>
            <div class="space-y-2">
                ${item.statusHistory.map(h => `
                    <div class="bg-gray-50 p-3 rounded text-sm">
                        <span class="font-medium">${h.status}</span> - ${formatDate(h.date)}
                        ${h.note ? `<p class="text-gray-600 mt-1">${h.note}</p>` : ''}
                    </div>
                `).join('')}
            </div>
        </div>
        ` : ''}
    `;
    
    document.getElementById('detailsModal').classList.remove('hidden');
    document.getElementById('detailsModal').classList.add('flex');
}

// Update status function
function updateStatus(id) {
    const item = allData.find(d => d.id === id);
    if (!item) return;
    
    currentReportId = id;
    document.getElementById('newStatus').value = item.status || 'pending';
    document.getElementById('statusNote').value = '';
    
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
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

// Close modals
document.getElementById('closeDetailsModal').addEventListener('click', function() {
    document.getElementById('detailsModal').classList.remove('flex');
    document.getElementById('detailsModal').classList.add('hidden');
});

document.getElementById('closeStatusModal').addEventListener('click', function() {
    document.getElementById('statusModal').classList.remove('flex');
    document.getElementById('statusModal').classList.add('hidden');
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
        
        showToast('Report status updated successfully', 'success');
        
        // Close modal
        document.getElementById('statusModal').classList.remove('flex');
        document.getElementById('statusModal').classList.add('hidden');
        
        // Refresh display
        updateStats();
        applyFilters();
        
    } catch (error) {
        console.error('Error updating status:', error);
        showToast('Failed to update status', 'error');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    fetchData();
});
</script>
@endsection
