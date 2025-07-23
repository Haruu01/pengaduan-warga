// Sistem Pengaduan Warga - JavaScript Application
// Simulasi database menggunakan localStorage

class PengaduanApp {
    constructor() {
        this.currentUser = null;
        this.currentRole = null;
        this.githubStorage = new GitHubStorage();
        this.useGitHubStorage = false;
        this.initializeData();
        this.checkGitHubIntegration();
    }

    // Check if GitHub integration is available
    checkGitHubIntegration() {
        this.useGitHubStorage = this.githubStorage.isAvailable();
        if (this.useGitHubStorage) {
            console.log('GitHub integration enabled');
        }
    }

    // Initialize sample data
    initializeData() {
        if (!localStorage.getItem('pengaduan_users')) {
            const users = [
                {
                    id: 1,
                    name: 'Admin System',
                    email: 'admin@demo.com',
                    password: 'password123',
                    role: 'admin'
                },
                {
                    id: 2,
                    name: 'Ahmad Rizki',
                    email: 'user@demo.com',
                    password: 'password123',
                    role: 'user'
                },
                {
                    id: 3,
                    name: 'Siti Nurhaliza',
                    email: 'siti@demo.com',
                    password: 'password123',
                    role: 'user'
                }
            ];
            localStorage.setItem('pengaduan_users', JSON.stringify(users));
        }

        if (!localStorage.getItem('pengaduan_categories')) {
            const categories = [
                { id: 1, name: 'Infrastruktur', description: 'Masalah jalan, jembatan, dan fasilitas umum' },
                { id: 2, name: 'Kebersihan', description: 'Masalah sampah dan kebersihan lingkungan' },
                { id: 3, name: 'Keamanan', description: 'Masalah keamanan dan ketertiban' },
                { id: 4, name: 'Pelayanan Publik', description: 'Masalah pelayanan administrasi dan publik' }
            ];
            localStorage.setItem('pengaduan_categories', JSON.stringify(categories));
        }

        if (!localStorage.getItem('pengaduan_complaints')) {
            const complaints = [
                {
                    id: 1,
                    user_id: 2,
                    category_id: 1,
                    title: 'Jalan Rusak di Depan Sekolah',
                    description: 'Jalan di depan SD Negeri 1 mengalami kerusakan parah dengan banyak lubang.',
                    location: 'Jl. Pendidikan No. 123, Kelurahan Sukamaju',
                    photo: 'sample1.jpg',
                    status: 'process',
                    admin_response: 'Sedang dalam proses perbaikan oleh Dinas PU',
                    created_at: new Date('2024-07-20').toISOString(),
                    updated_at: new Date('2024-07-21').toISOString()
                },
                {
                    id: 2,
                    user_id: 3,
                    category_id: 1,
                    title: 'Lampu Jalan Mati Total',
                    description: 'Lampu penerangan jalan sudah mati selama 2 minggu.',
                    location: 'Jl. Merdeka Raya, RT 05/RW 02',
                    photo: 'sample2.jpg',
                    status: 'pending',
                    admin_response: null,
                    created_at: new Date('2024-07-21').toISOString(),
                    updated_at: new Date('2024-07-21').toISOString()
                },
                {
                    id: 3,
                    user_id: 2,
                    category_id: 2,
                    title: 'Saluran Air Tersumbat',
                    description: 'Saluran air mengalami penyumbatan sehingga sering banjir saat hujan.',
                    location: 'Perumahan Griya Asri Blok C',
                    photo: 'sample3.jpg',
                    status: 'completed',
                    admin_response: 'Saluran air telah dibersihkan dan diperbaiki',
                    created_at: new Date('2024-07-19').toISOString(),
                    updated_at: new Date('2024-07-22').toISOString()
                }
            ];
            localStorage.setItem('pengaduan_complaints', JSON.stringify(complaints));
        }
    }

    // Get data from localStorage
    getUsers() {
        return JSON.parse(localStorage.getItem('pengaduan_users') || '[]');
    }

    getCategories() {
        return JSON.parse(localStorage.getItem('pengaduan_categories') || '[]');
    }

    getComplaints() {
        return JSON.parse(localStorage.getItem('pengaduan_complaints') || '[]');
    }

    // Save data to localStorage
    saveUsers(users) {
        localStorage.setItem('pengaduan_users', JSON.stringify(users));
    }

    saveComplaints(complaints) {
        localStorage.setItem('pengaduan_complaints', JSON.stringify(complaints));
    }

    // Authentication
    login(email, password, role) {
        const users = this.getUsers();
        const user = users.find(u => u.email === email && u.password === password && u.role === role);
        
        if (user) {
            this.currentUser = user;
            this.currentRole = role;
            return true;
        }
        return false;
    }

    logout() {
        this.currentUser = null;
        this.currentRole = null;
    }

    // Get user complaints
    getUserComplaints(userId) {
        const complaints = this.getComplaints();
        const categories = this.getCategories();
        
        return complaints
            .filter(c => c.user_id === userId)
            .map(c => ({
                ...c,
                category_name: categories.find(cat => cat.id === c.category_id)?.name || 'Unknown'
            }))
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }

    // Get all complaints with user info (for admin)
    getAllComplaints() {
        const complaints = this.getComplaints();
        const users = this.getUsers();
        const categories = this.getCategories();
        
        return complaints.map(c => ({
            ...c,
            user_name: users.find(u => u.id === c.user_id)?.name || 'Unknown',
            user_email: users.find(u => u.id === c.user_id)?.email || 'Unknown',
            category_name: categories.find(cat => cat.id === c.category_id)?.name || 'Unknown'
        })).sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }

    // Add new complaint
    async addComplaint(complaintData) {
        // Prepare complaint data with user info
        const categories = this.getCategories();
        const category = categories.find(c => c.id === parseInt(complaintData.category_id));

        const complaintWithUserInfo = {
            ...complaintData,
            user_id: this.currentUser.id,
            user_name: this.currentUser.name,
            user_email: this.currentUser.email,
            category_name: category ? category.name : 'Unknown'
        };

        // Try GitHub storage first
        if (this.useGitHubStorage) {
            try {
                const githubResult = await this.githubStorage.createComplaint(complaintWithUserInfo);

                // Also save to localStorage as backup
                const complaints = this.getComplaints();
                const newComplaint = {
                    id: githubResult.id,
                    user_id: this.currentUser.id,
                    category_id: parseInt(complaintData.category_id),
                    title: complaintData.title,
                    description: complaintData.description,
                    location: complaintData.location,
                    photo: complaintData.photo || null,
                    status: 'pending',
                    admin_response: null,
                    created_at: githubResult.created_at,
                    updated_at: githubResult.created_at,
                    github_url: githubResult.github_url
                };

                complaints.push(newComplaint);
                this.saveComplaints(complaints);

                return newComplaint;
            } catch (error) {
                console.error('GitHub storage failed, using localStorage:', error);
                // Fall back to localStorage
            }
        }

        // localStorage fallback
        const complaints = this.getComplaints();
        const newId = Math.max(...complaints.map(c => c.id), 0) + 1;

        const newComplaint = {
            id: newId,
            user_id: this.currentUser.id,
            category_id: parseInt(complaintData.category_id),
            title: complaintData.title,
            description: complaintData.description,
            location: complaintData.location,
            photo: complaintData.photo || null,
            status: 'pending',
            admin_response: null,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
        };

        complaints.push(newComplaint);
        this.saveComplaints(complaints);
        return newComplaint;
    }

    // Update complaint status (admin only)
    updateComplaintStatus(complaintId, status, response = null) {
        const complaints = this.getComplaints();
        const complaint = complaints.find(c => c.id === complaintId);
        
        if (complaint) {
            complaint.status = status;
            complaint.admin_response = response;
            complaint.updated_at = new Date().toISOString();
            this.saveComplaints(complaints);
            return true;
        }
        return false;
    }

    // Get statistics
    getStatistics() {
        const complaints = this.currentRole === 'admin' 
            ? this.getAllComplaints() 
            : this.getUserComplaints(this.currentUser.id);
        
        return {
            total: complaints.length,
            pending: complaints.filter(c => c.status === 'pending').length,
            process: complaints.filter(c => c.status === 'process').length,
            completed: complaints.filter(c => c.status === 'completed').length,
            rejected: complaints.filter(c => c.status === 'rejected').length
        };
    }

    // Register new user
    register(userData) {
        const users = this.getUsers();
        const existingUser = users.find(u => u.email === userData.email);
        
        if (existingUser) {
            return { success: false, message: 'Email sudah terdaftar' };
        }
        
        const newId = Math.max(...users.map(u => u.id), 0) + 1;
        const newUser = {
            id: newId,
            name: userData.name,
            email: userData.email,
            password: userData.password,
            role: 'user'
        };
        
        users.push(newUser);
        this.saveUsers(users);
        return { success: true, message: 'Registrasi berhasil' };
    }
}

// Global app instance
const app = new PengaduanApp();

// UI Functions
function login() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const role = document.getElementById('loginRole').value;
    
    if (!email || !password) {
        alert('Mohon isi email dan password');
        return;
    }
    
    if (app.login(email, password, role)) {
        document.getElementById('loginScreen').style.display = 'none';
        document.getElementById('mainApp').style.display = 'block';
        document.getElementById('currentUser').textContent = app.currentUser.name;
        
        setupSidebar();
        showDashboard();
    } else {
        alert('Email, password, atau role tidak valid');
    }
}

function logout() {
    app.logout();
    document.getElementById('loginScreen').style.display = 'flex';
    document.getElementById('mainApp').style.display = 'none';
    
    // Reset form
    document.getElementById('loginEmail').value = '';
    document.getElementById('loginPassword').value = '';
    document.getElementById('loginRole').value = 'user';
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    sidebar.classList.toggle('show');
    mainContent.classList.toggle('shifted');
}

function setupSidebar() {
    const sidebarNav = document.getElementById('sidebarNav');
    let menuItems = '';

    if (app.currentRole === 'admin') {
        menuItems = `
            <a class="nav-link active" href="#" onclick="setActiveMenu(this); showDashboard()">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a class="nav-link" href="#" onclick="setActiveMenu(this); showAllComplaints()">
                <i class="fas fa-list me-2"></i>Kelola Pengaduan
            </a>
            <a class="nav-link" href="#" onclick="setActiveMenu(this); showCategories()">
                <i class="fas fa-tags me-2"></i>Kategori
            </a>
            <a class="nav-link" href="#" onclick="setActiveMenu(this); showReports()">
                <i class="fas fa-chart-bar me-2"></i>Laporan
            </a>
        `;
    } else {
        menuItems = `
            <a class="nav-link active" href="#" onclick="setActiveMenu(this); showDashboard()">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a class="nav-link" href="#" onclick="setActiveMenu(this); showMyComplaints()">
                <i class="fas fa-list me-2"></i>Pengaduan Saya
            </a>
            <a class="nav-link" href="#" onclick="setActiveMenu(this); showCreateComplaint()">
                <i class="fas fa-plus me-2"></i>Buat Pengaduan
            </a>
        `;
    }

    sidebarNav.innerHTML = menuItems;
}

function setActiveMenu(clickedElement) {
    // Remove active class from all nav links
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.classList.remove('active');
    });
    // Add active class to clicked element
    if (clickedElement) {
        clickedElement.classList.add('active');
    }
}

function showDashboard() {
    const stats = app.getStatistics();
    const contentArea = document.getElementById('contentArea');

    const githubStatus = app.githubStorage.isAvailable()
        ? '<span class="badge bg-success"><i class="fab fa-github me-1"></i>GitHub Connected</span>'
        : '<button class="btn btn-outline-primary btn-sm" onclick="showGitHubSetup()"><i class="fab fa-github me-1"></i>Setup GitHub</button>';

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
            <div>
                ${githubStatus}
                <span class="badge bg-primary ms-2">${app.currentRole === 'admin' ? 'Admin' : 'Warga'}</span>
            </div>
        </div>

        ${!app.githubStorage.isAvailable() ? `
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fab fa-github me-2"></i>
            <strong>GitHub Integration Available!</strong>
            Simpan pengaduan sebagai GitHub Issues untuk akses publik dan transparansi.
            <button class="btn btn-sm btn-outline-primary ms-2" onclick="showGitHubSetup()">Setup Now</button>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        ` : ''}

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <h4>${stats.total}</h4>
                        <small>Total Pengaduan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h4>${stats.pending}</h4>
                        <small>Menunggu</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-cog fa-2x mb-2"></i>
                        <h4>${stats.process}</h4>
                        <small>Diproses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-check fa-2x mb-2"></i>
                        <h4>${stats.completed}</h4>
                        <small>Selesai</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list me-2"></i>Pengaduan Terbaru</h5>
            </div>
            <div class="card-body">
                ${getRecentComplaintsTable()}
            </div>
        </div>
    `;
}

function getRecentComplaintsTable() {
    const complaints = app.currentRole === 'admin'
        ? app.getAllComplaints().slice(0, 5)
        : app.getUserComplaints(app.currentUser.id).slice(0, 5);

    if (complaints.length === 0) {
        return '<p class="text-muted text-center">Belum ada pengaduan</p>';
    }

    let table = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        ${app.currentRole === 'admin' ? '<th>Pelapor</th>' : ''}
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
    `;

    complaints.forEach(complaint => {
        table += `
            <tr>
                <td>#${String(complaint.id).padStart(6, '0')}</td>
                <td>${complaint.title}</td>
                ${app.currentRole === 'admin' ? `<td>${complaint.user_name}</td>` : ''}
                <td>${complaint.category_name}</td>
                <td>${getStatusBadge(complaint.status)}</td>
                <td>${formatDate(complaint.created_at)}</td>
            </tr>
        `;
    });

    table += '</tbody></table></div>';
    return table;
}

function getStatusBadge(status) {
    const statusMap = {
        'pending': { class: 'status-pending', text: 'Menunggu' },
        'process': { class: 'status-process', text: 'Diproses' },
        'completed': { class: 'status-completed', text: 'Selesai' },
        'rejected': { class: 'status-rejected', text: 'Ditolak' }
    };

    const statusInfo = statusMap[status] || { class: 'status-pending', text: 'Unknown' };
    return `<span class="status-badge ${statusInfo.class}">${statusInfo.text}</span>`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function showMyComplaints() {
    const complaints = app.getUserComplaints(app.currentUser.id);
    const contentArea = document.getElementById('contentArea');

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-list me-2"></i>Pengaduan Saya</h2>
            <button class="btn btn-primary" onclick="showCreateComplaint()">
                <i class="fas fa-plus me-2"></i>Buat Pengaduan Baru
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                ${getMyComplaintsTable(complaints)}
            </div>
        </div>
    `;
}

function getMyComplaintsTable(complaints) {
    if (complaints.length === 0) {
        return `
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5>Belum Ada Pengaduan</h5>
                <p class="text-muted">Anda belum membuat pengaduan apapun</p>
                <button class="btn btn-primary" onclick="showCreateComplaint()">
                    <i class="fas fa-plus me-2"></i>Buat Pengaduan Pertama
                </button>
            </div>
        `;
    }

    let table = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
    `;

    complaints.forEach(complaint => {
        table += `
            <tr>
                <td>#${String(complaint.id).padStart(6, '0')}</td>
                <td>${complaint.title}</td>
                <td>${complaint.category_name}</td>
                <td>${getStatusBadge(complaint.status)}</td>
                <td>${formatDate(complaint.created_at)}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="viewComplaintDetail(${complaint.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    table += '</tbody></table></div>';
    return table;
}

function showCreateComplaint() {
    const categories = app.getCategories();
    const contentArea = document.getElementById('contentArea');

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-plus me-2"></i>Buat Pengaduan Baru</h2>
            <button class="btn btn-secondary" onclick="showMyComplaints()">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="createComplaintForm" onsubmit="submitComplaint(event)">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                ${categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi *</label>
                            <input type="text" class="form-control" name="location" placeholder="Contoh: Jl. Merdeka No. 123" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Judul Pengaduan *</label>
                            <input type="text" class="form-control" name="title" placeholder="Contoh: Jalan Rusak di Depan Sekolah" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi *</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Jelaskan detail pengaduan Anda..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Upload Foto</label>
                            <input type="file" class="form-control" name="photo" accept="image/*" onchange="previewPhoto(this)">
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 5MB</small>
                            <div id="photoPreview" class="mt-2"></div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pengaduan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    `;
}

function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="mt-2">
                    <img src="${e.target.result}" class="photo-preview border" alt="Preview">
                    <p class="small text-success mt-1">
                        <i class="fas fa-check"></i> Foto berhasil dipilih
                    </p>
                </div>
            `;
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function submitComplaint(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const complaintData = {
        category_id: formData.get('category_id'),
        title: formData.get('title'),
        description: formData.get('description'),
        location: formData.get('location'),
        photo: formData.get('photo')?.name || null
    };

    const newComplaint = app.addComplaint(complaintData);

    if (newComplaint) {
        alert('✅ Pengaduan berhasil dikirim!\n\nID Pengaduan: #' + String(newComplaint.id).padStart(6, '0'));
        showMyComplaints();
    } else {
        alert('❌ Gagal mengirim pengaduan. Silakan coba lagi.');
    }
}

function showAllComplaints() {
    const complaints = app.getAllComplaints();
    const contentArea = document.getElementById('contentArea');

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-list me-2"></i>Kelola Pengaduan</h2>
            <div class="btn-group">
                <button class="btn btn-outline-primary" onclick="filterComplaints('all')">Semua</button>
                <button class="btn btn-outline-warning" onclick="filterComplaints('pending')">Menunggu</button>
                <button class="btn btn-outline-info" onclick="filterComplaints('process')">Diproses</button>
                <button class="btn btn-outline-success" onclick="filterComplaints('completed')">Selesai</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                ${getAdminComplaintsTable(complaints)}
            </div>
        </div>
    `;
}

function getAdminComplaintsTable(complaints) {
    if (complaints.length === 0) {
        return '<p class="text-muted text-center">Tidak ada pengaduan</p>';
    }

    let table = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Pelapor</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
    `;

    complaints.forEach(complaint => {
        table += `
            <tr>
                <td>#${String(complaint.id).padStart(6, '0')}</td>
                <td>
                    <strong>${complaint.user_name}</strong><br>
                    <small class="text-muted">${complaint.user_email}</small>
                </td>
                <td>${complaint.title}</td>
                <td>${complaint.category_name}</td>
                <td>
                    ${complaint.photo ?
                        `<button class="btn btn-sm btn-outline-primary" onclick="showPhotoModal('${complaint.title}', '${complaint.photo}')">
                            <i class="fas fa-image"></i> Lihat Foto
                        </button>` :
                        '<span class="text-muted"><i class="fas fa-image-slash"></i> Tidak ada</span>'
                    }
                </td>
                <td>${getStatusBadge(complaint.status)}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewComplaintDetail(${complaint.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="updateStatus(${complaint.id}, 'process')">
                                    <i class="fas fa-cog text-info me-2"></i>Proses
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus(${complaint.id}, 'completed')">
                                    <i class="fas fa-check text-success me-2"></i>Selesai
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus(${complaint.id}, 'rejected')">
                                    <i class="fas fa-times text-danger me-2"></i>Tolak
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });

    table += '</tbody></table></div>';
    return table;
}

function showPhotoModal(title, photo) {
    const modal = new bootstrap.Modal(document.getElementById('photoModal'));
    const content = document.getElementById('modalPhotoContent');

    content.innerHTML = `
        <div class="bg-light p-5 rounded">
            <i class="fas fa-image fa-5x text-primary mb-3"></i>
            <h5>Foto: ${title}</h5>
            <p class="text-muted">File: ${photo}</p>
            <small>Dalam aplikasi asli, foto akan ditampilkan di sini</small>
        </div>
    `;

    modal.show();
}

function updateStatus(complaintId, newStatus) {
    const statusText = {
        'pending': 'Menunggu',
        'process': 'Diproses',
        'completed': 'Selesai',
        'rejected': 'Ditolak'
    };

    let response = null;
    if (newStatus === 'completed') {
        response = prompt('Masukkan keterangan penyelesaian:');
        if (!response) return;
    } else if (newStatus === 'rejected') {
        response = prompt('Masukkan alasan penolakan:');
        if (!response) return;
    } else if (newStatus === 'process') {
        response = 'Pengaduan sedang dalam proses penanganan';
    }

    if (app.updateComplaintStatus(complaintId, newStatus, response)) {
        alert(`✅ Status berhasil diubah menjadi: ${statusText[newStatus]}`);
        showAllComplaints();
    } else {
        alert('❌ Gagal mengubah status');
    }
}

function filterComplaints(status) {
    const allComplaints = app.getAllComplaints();
    const filteredComplaints = status === 'all'
        ? allComplaints
        : allComplaints.filter(c => c.status === status);

    const tableContainer = document.querySelector('.card-body');
    tableContainer.innerHTML = getAdminComplaintsTable(filteredComplaints);
}

function viewComplaintDetail(complaintId) {
    const complaints = app.currentRole === 'admin'
        ? app.getAllComplaints()
        : app.getUserComplaints(app.currentUser.id);

    const complaint = complaints.find(c => c.id === complaintId);
    if (!complaint) return;

    const contentArea = document.getElementById('contentArea');

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-eye me-2"></i>Detail Pengaduan</h2>
            <button class="btn btn-secondary" onclick="${app.currentRole === 'admin' ? 'showAllComplaints()' : 'showMyComplaints()'}">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </button>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Informasi Pengaduan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>ID Pengaduan</strong></td>
                                <td>#${String(complaint.id).padStart(6, '0')}</td>
                            </tr>
                            <tr>
                                <td><strong>Judul</strong></td>
                                <td>${complaint.title}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>${complaint.category_name}</td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi</strong></td>
                                <td>${complaint.location}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>${getStatusBadge(complaint.status)}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td>${formatDate(complaint.created_at)}</td>
                            </tr>
                            ${app.currentRole === 'admin' ? `
                            <tr>
                                <td><strong>Pelapor</strong></td>
                                <td>${complaint.user_name}<br><small class="text-muted">${complaint.user_email}</small></td>
                            </tr>
                            ` : ''}
                        </table>

                        <h6><strong>Deskripsi:</strong></h6>
                        <p>${complaint.description}</p>

                        ${complaint.admin_response ? `
                        <div class="alert alert-info">
                            <h6><i class="fas fa-reply me-2"></i>Tanggapan Admin:</h6>
                            <p class="mb-0">${complaint.admin_response}</p>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Foto Pengaduan</h5>
                    </div>
                    <div class="card-body text-center">
                        ${complaint.photo ? `
                        <div class="bg-light p-4 rounded">
                            <i class="fas fa-image fa-3x text-primary mb-3"></i>
                            <h6>Foto: ${complaint.photo}</h6>
                            <button class="btn btn-primary btn-sm" onclick="showPhotoModal('${complaint.title}', '${complaint.photo}')">
                                <i class="fas fa-eye me-2"></i>Lihat Foto
                            </button>
                        </div>
                        ` : `
                        <div class="text-muted">
                            <i class="fas fa-image-slash fa-3x mb-3"></i>
                            <p>Tidak ada foto</p>
                        </div>
                        `}
                    </div>
                </div>

                ${app.currentRole === 'admin' && complaint.status !== 'completed' && complaint.status !== 'rejected' ? `
                <div class="card">
                    <div class="card-header">
                        <h5>Aksi Admin</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-info" onclick="updateStatus(${complaint.id}, 'process')">
                                <i class="fas fa-cog me-2"></i>Proses
                            </button>
                            <button class="btn btn-success" onclick="updateStatus(${complaint.id}, 'completed')">
                                <i class="fas fa-check me-2"></i>Selesai
                            </button>
                            <button class="btn btn-danger" onclick="updateStatus(${complaint.id}, 'rejected')">
                                <i class="fas fa-times me-2"></i>Tolak
                            </button>
                        </div>
                    </div>
                </div>
                ` : ''}
            </div>
        </div>
    `;
}

function showCategories() {
    const categories = app.getCategories();
    const contentArea = document.getElementById('contentArea');

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tags me-2"></i>Kategori Pengaduan</h2>
        </div>

        <div class="row">
            ${categories.map(category => `
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">${category.name}</h5>
                        <p class="card-text">${category.description}</p>
                        <small class="text-muted">ID: ${category.id}</small>
                    </div>
                </div>
            </div>
            `).join('')}
        </div>
    `;
}

function showReports() {
    const stats = app.getStatistics();
    const complaints = app.getAllComplaints();
    const contentArea = document.getElementById('contentArea');

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-bar me-2"></i>Laporan</h2>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <h4>${stats.total}</h4>
                        <small>Total Pengaduan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h4>${stats.pending}</h4>
                        <small>Menunggu</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-cog fa-2x mb-2"></i>
                        <h4>${stats.process}</h4>
                        <small>Diproses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-check fa-2x mb-2"></i>
                        <h4>${stats.completed}</h4>
                        <small>Selesai</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Semua Pengaduan</h5>
            </div>
            <div class="card-body">
                ${getAdminComplaintsTable(complaints)}
            </div>
        </div>
    `;
}

function showProfile() {
    const contentArea = document.getElementById('contentArea');

    contentArea.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user me-2"></i>Profile</h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Informasi Akun</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>${app.currentUser.name}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>${app.currentUser.email}</td>
                            </tr>
                            <tr>
                                <td><strong>Role</strong></td>
                                <td><span class="badge bg-primary">${app.currentUser.role}</span></td>
                            </tr>
                            <tr>
                                <td><strong>ID</strong></td>
                                <td>${app.currentUser.id}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function showRegister() {
    const loginCard = document.querySelector('.login-card');

    loginCard.innerHTML = `
        <div class="text-center mb-4">
            <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
            <h3>Daftar Akun Baru</h3>
            <p class="text-muted">Buat akun untuk mengajukan pengaduan</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="regName" placeholder="Masukkan nama lengkap">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="regEmail" placeholder="Masukkan email">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" id="regPassword" placeholder="Masukkan password">
        </div>

        <button class="btn btn-primary w-100" onclick="register()">
            <i class="fas fa-user-plus me-2"></i>Daftar
        </button>

        <div class="text-center mt-3">
            <small class="text-muted">
                Sudah punya akun? <a href="#" onclick="showLogin()">Login di sini</a>
            </small>
        </div>
    `;
}

function showLogin() {
    location.reload(); // Reload to show login form
}

function register() {
    const name = document.getElementById('regName').value;
    const email = document.getElementById('regEmail').value;
    const password = document.getElementById('regPassword').value;

    if (!name || !email || !password) {
        alert('Mohon isi semua field');
        return;
    }

    const result = app.register({ name, email, password });

    if (result.success) {
        alert('✅ ' + result.message + '\n\nSilakan login dengan akun yang baru dibuat.');
        showLogin();
    } else {
        alert('❌ ' + result.message);
    }
}

// GitHub Token Management
function showGitHubSetup() {
    const modal = new bootstrap.Modal(document.getElementById('githubTokenModal'));
    modal.show();
}

function saveGitHubToken() {
    const token = document.getElementById('githubToken').value.trim();

    if (!token) {
        alert('Mohon masukkan GitHub token');
        return;
    }

    if (!token.startsWith('ghp_') && !token.startsWith('github_pat_')) {
        alert('Format token tidak valid. Token harus dimulai dengan "ghp_" atau "github_pat_"');
        return;
    }

    app.githubStorage.setToken(token);
    app.checkGitHubIntegration();

    const modal = bootstrap.Modal.getInstance(document.getElementById('githubTokenModal'));
    modal.hide();

    alert('✅ GitHub token berhasil disimpan!\n\nSekarang pengaduan akan disimpan sebagai GitHub Issues.');

    // Refresh current view
    if (app.currentRole === 'admin') {
        showAllComplaints();
    } else {
        showDashboard();
    }
}

function removeGitHubToken() {
    if (confirm('Hapus GitHub integration? Pengaduan akan kembali disimpan di localStorage.')) {
        localStorage.removeItem('github_token');
        app.githubStorage.token = null;
        app.useGitHubStorage = false;
        alert('GitHub integration dihapus.');
    }
}

// Update submit complaint to handle async
async function submitComplaint(event) {
    event.preventDefault();

    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    // Show loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
    submitBtn.disabled = true;

    try {
        const formData = new FormData(event.target);
        const complaintData = {
            category_id: formData.get('category_id'),
            title: formData.get('title'),
            description: formData.get('description'),
            location: formData.get('location'),
            photo: formData.get('photo')?.name || null
        };

        const newComplaint = await app.addComplaint(complaintData);

        if (newComplaint) {
            let message = '✅ Pengaduan berhasil dikirim!\n\nID Pengaduan: #' + String(newComplaint.id).padStart(6, '0');

            if (newComplaint.github_url) {
                message += '\n\n🔗 Lihat di GitHub: ' + newComplaint.github_url;
            }

            alert(message);
            showMyComplaints();
        } else {
            alert('❌ Gagal mengirim pengaduan. Silakan coba lagi.');
        }
    } catch (error) {
        console.error('Error submitting complaint:', error);
        alert('❌ Terjadi kesalahan: ' + error.message);
    } finally {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Landing Page Functions
function showLandingPage() {
    // Show landing page, hide others
    document.getElementById('landingPage').style.display = 'block';
    document.getElementById('loginScreen').style.display = 'none';
    document.getElementById('mainApp').style.display = 'none';

    loadLandingPage();
}

function loadLandingPage() {
    const landingContent = document.getElementById('landingContent');

    // Get public statistics
    const allComplaints = app.getAllComplaints();
    const stats = {
        total: allComplaints.length,
        pending: allComplaints.filter(c => c.status === 'pending').length,
        process: allComplaints.filter(c => c.status === 'process').length,
        completed: allComplaints.filter(c => c.status === 'completed').length
    };

    landingContent.innerHTML = `
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-3 fw-bold mb-4">
                            Sistem Pengaduan Warga
                        </h1>
                        <p class="lead mb-4">
                            Aplikasi web untuk mengelola pengaduan masyarakat dengan fitur upload
                            foto dan dashboard admin yang lengkap.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <button class="btn btn-light btn-lg" onclick="showLoginScreen()">
                                <i class="fas fa-tachometer-alt me-2"></i>Buka Dashboard
                            </button>
                            <a href="demo.html" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-play me-2"></i>Lihat Demo
                            </a>
                            <a href="https://github.com/Haruu01/pengaduan-warga" target="_blank" class="btn btn-outline-light btn-lg">
                                <i class="fab fa-github me-2"></i>GitHub
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="bg-light bg-opacity-10 p-5 rounded shadow">
                            <i class="fas fa-chart-bar fa-5x mb-3"></i>
                            <h4>Preview Dashboard</h4>
                            <p>Statistik real-time pengaduan masyarakat</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section id="stats" class="section-padding">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Statistik Real-time</h2>
                    <p class="text-muted">Data pengaduan yang masuk ke sistem</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card stats-card bg-primary text-white text-center">
                            <div class="card-body">
                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                <h2>${stats.total}</h2>
                                <p class="mb-0">Total Pengaduan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-warning text-white text-center">
                            <div class="card-body">
                                <i class="fas fa-clock fa-3x mb-3"></i>
                                <h2>${stats.pending}</h2>
                                <p class="mb-0">Menunggu</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-info text-white text-center">
                            <div class="card-body">
                                <i class="fas fa-cog fa-3x mb-3"></i>
                                <h2>${stats.process}</h2>
                                <p class="mb-0">Diproses</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-success text-white text-center">
                            <div class="card-body">
                                <i class="fas fa-check fa-3x mb-3"></i>
                                <h2>${stats.completed}</h2>
                                <p class="mb-0">Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="section-padding bg-light-custom">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Fitur Utama</h2>
                    <p class="text-muted">Solusi lengkap untuk sistem pengaduan masyarakat</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card card text-center p-4">
                            <i class="fas fa-user fa-4x text-primary mb-3"></i>
                            <h5>Untuk Warga</h5>
                            <p class="text-muted">Submit pengaduan dengan foto, tracking status real-time dari dashboard personal yang user friendly.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card card text-center p-4">
                            <i class="fas fa-user-tie fa-4x text-primary mb-3"></i>
                            <h5>Untuk Admin</h5>
                            <p class="text-muted">Dashboard lengkap, kelola pengaduan, view foto dan modal, dan generate laporan.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card card text-center p-4">
                            <i class="fas fa-camera fa-4x text-primary mb-3"></i>
                            <h5>Upload Foto</h5>
                            <p class="text-muted">Support multiple format, preview modal, download functionality, dan error handling.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Technology Section -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Teknologi yang Digunakan</h2>
                    <p class="text-muted">Built with modern web technologies</p>
                </div>

                <div class="row text-center">
                    <div class="col-md-2 col-4 mb-4">
                        <i class="fab fa-js-square fa-3x text-warning mb-2"></i>
                        <h6>JavaScript</h6>
                    </div>
                    <div class="col-md-2 col-4 mb-4">
                        <i class="fab fa-bootstrap fa-3x text-primary mb-2"></i>
                        <h6>Bootstrap 5</h6>
                    </div>
                    <div class="col-md-2 col-4 mb-4">
                        <i class="fab fa-html5 fa-3x text-danger mb-2"></i>
                        <h6>HTML5</h6>
                    </div>
                    <div class="col-md-2 col-4 mb-4">
                        <i class="fab fa-css3-alt fa-3x text-info mb-2"></i>
                        <h6>CSS3</h6>
                    </div>
                    <div class="col-md-2 col-4 mb-4">
                        <i class="fas fa-shield-alt fa-3x text-success mb-2"></i>
                        <h6>Security</h6>
                    </div>
                    <div class="col-md-2 col-4 mb-4">
                        <i class="fas fa-mobile-alt fa-3x text-secondary mb-2"></i>
                        <h6>Responsive</h6>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="section-padding bg-light-custom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="fw-bold mb-4">Tentang Sistem</h2>
                        <p class="lead mb-4">
                            Sistem Pengaduan Warga adalah platform digital yang memungkinkan masyarakat
                            untuk menyampaikan aspirasi dan pengaduan dengan mudah dan transparan.
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Interface yang user-friendly</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Dashboard admin yang lengkap</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Support upload foto</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tracking status real-time</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>GitHub integration untuk transparansi</li>
                        </ul>
                        <div class="mt-4">
                            <button class="btn btn-primary btn-lg me-3" onclick="showLoginScreen()">
                                <i class="fas fa-sign-in-alt me-2"></i>Mulai Sekarang
                            </button>
                            <a href="github-integration.html" class="btn btn-outline-primary btn-lg">
                                <i class="fab fa-github me-2"></i>Pelajari GitHub Integration
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="bg-primary bg-opacity-10 p-5 rounded">
                            <i class="fas fa-bullhorn fa-5x text-primary mb-3"></i>
                            <h4>Transparansi Publik</h4>
                            <p>Semua pengaduan dapat diakses publik melalui GitHub Issues</p>
                            <a href="https://github.com/Haruu01/pengaduan-warga/issues" target="_blank" class="btn btn-outline-primary">
                                <i class="fab fa-github me-2"></i>Lihat Pengaduan Publik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section-padding bg-primary text-white">
            <div class="container text-center">
                <h2 class="fw-bold mb-4">Siap Menyampaikan Pengaduan?</h2>
                <p class="lead mb-4">
                    Bergabunglah dengan sistem pengaduan digital yang transparan dan efisien
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <button class="btn btn-light btn-lg" onclick="showLoginScreen()">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                    <button class="btn btn-outline-light btn-lg" onclick="showRegisterScreen()">
                        <i class="fas fa-user-plus me-2"></i>Daftar Akun
                    </button>
                    <a href="demo.html" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-play me-2"></i>Lihat Demo
                    </a>
                </div>
            </div>
        </section>
    `;
}

function getPublicComplaintsTable(complaints) {
    if (complaints.length === 0) {
        return `
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5>Belum Ada Pengaduan</h5>
                <p class="text-muted">Belum ada pengaduan yang masuk ke sistem</p>
            </div>
        `;
    }

    let table = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
    `;

    complaints.forEach(complaint => {
        table += `
            <tr>
                <td>#${String(complaint.id).padStart(6, '0')}</td>
                <td>${complaint.title}</td>
                <td>${complaint.category_name || 'Unknown'}</td>
                <td>${getStatusBadge(complaint.status)}</td>
                <td>${formatDate(complaint.created_at)}</td>
            </tr>
        `;
    });

    table += '</tbody></table></div>';
    return table;
}

function showLoginScreen() {
    document.getElementById('landingPage').style.display = 'none';
    document.getElementById('loginScreen').style.display = 'flex';
    document.getElementById('mainApp').style.display = 'none';

    // Auto-fill demo credentials
    document.getElementById('loginEmail').value = 'user@demo.com';
    document.getElementById('loginPassword').value = 'password123';
}

function showRegisterScreen() {
    document.getElementById('landingPage').style.display = 'none';
    document.getElementById('loginScreen').style.display = 'flex';
    document.getElementById('mainApp').style.display = 'none';

    // Show register form
    showRegister();
}

// Update login function to hide public dashboard
function login() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const role = document.getElementById('loginRole').value;

    if (!email || !password) {
        alert('Mohon isi email dan password');
        return;
    }

    if (app.login(email, password, role)) {
        document.getElementById('landingPage').style.display = 'none';
        document.getElementById('loginScreen').style.display = 'none';
        document.getElementById('mainApp').style.display = 'block';
        document.getElementById('currentUser').textContent = app.currentUser.name;

        setupSidebar();
        showDashboard();
    } else {
        alert('Email, password, atau role tidak valid');
    }
}

// Update logout function to show public dashboard
function logout() {
    app.logout();
    document.getElementById('landingPage').style.display = 'block';
    document.getElementById('loginScreen').style.display = 'none';
    document.getElementById('mainApp').style.display = 'none';

    // Reset form
    document.getElementById('loginEmail').value = '';
    document.getElementById('loginPassword').value = '';
    document.getElementById('loginRole').value = 'user';

    // Reload landing page
    loadLandingPage();
}

// Initialize app when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Show landing page by default
    showLandingPage();

    // Show GitHub setup notification if not configured (only after login)
    setTimeout(() => {
        if (!app.githubStorage.isAvailable() && app.currentUser) {
            const showSetup = confirm(
                '🔗 GitHub Integration tersedia!\n\n' +
                'Dengan GitHub integration, pengaduan akan disimpan sebagai GitHub Issues dan bisa diakses secara publik.\n\n' +
                'Setup sekarang?'
            );

            if (showSetup) {
                showGitHubSetup();
            }
        }
    }, 2000);
});
