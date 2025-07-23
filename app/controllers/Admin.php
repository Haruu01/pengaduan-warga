<?php
class Admin extends Controller {
    protected $complaintModel;
    protected $categoryModel;
    protected $userModel;

    public function __construct() {
        if (!$this->isLoggedIn() || !$this->isAdmin()) {
            $this->redirect('adminauth/pin');
        }

        $this->complaintModel = $this->model('Complaint');
        $this->categoryModel = $this->model('Category');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        // Get statistics
        $stats = $this->complaintModel->getStatistics();
        
        // Get recent complaints
        $complaints = $this->complaintModel->getComplaints();
        $recentComplaints = array_slice($complaints, 0, 5);
        
        $data = [
            'title' => 'Dashboard Admin - ' . APP_NAME,
            'stats' => $stats,
            'recentComplaints' => $recentComplaints
        ];
        
        $this->view('admin/index', $data);
    }
    
    public function complaints() {
        $complaints = $this->complaintModel->getComplaints();
        
        $data = [
            'title' => 'Kelola Pengaduan - ' . APP_NAME,
            'complaints' => $complaints
        ];
        
        $this->view('admin/complaints', $data);
    }
    
    public function complaint($id) {
        $complaint = $this->complaintModel->getComplaintById($id);
        
        if (!$complaint) {
            $this->redirect('admin/complaints');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePost();
            
            $status = trim($_POST['status']);
            $response = trim($_POST['response']);
            
            if ($this->complaintModel->updateStatus($id, $status, $response)) {
                $this->flash('complaint_message', 'Status pengaduan berhasil diperbarui');
                $this->redirect('admin/complaint/' . $id);
            } else {
                $this->flash('complaint_message', 'Gagal memperbarui status', 'alert-danger');
            }
        }
        
        $data = [
            'title' => 'Detail Pengaduan - ' . APP_NAME,
            'complaint' => $complaint
        ];
        
        $this->view('admin/complaint', $data);
    }
    
    public function categories() {
        $categories = $this->categoryModel->getCategories();
        
        $data = [
            'title' => 'Kelola Kategori - ' . APP_NAME,
            'categories' => $categories
        ];
        
        $this->view('admin/categories', $data);
    }
    
    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePost();
            
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'description_err' => ''
            ];
            
            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Silakan masukkan nama kategori';
            } elseif ($this->categoryModel->categoryNameExists($data['name'])) {
                $data['name_err'] = 'Nama kategori sudah ada';
            }
            
            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['description_err'])) {
                if ($this->categoryModel->addCategory($data)) {
                    $this->flash('category_message', 'Kategori berhasil ditambahkan');
                    $this->redirect('admin/categories');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/addCategory', $data);
            }
        } else {
            $data = [
                'title' => 'Tambah Kategori - ' . APP_NAME,
                'name' => '',
                'description' => '',
                'name_err' => '',
                'description_err' => ''
            ];
            
            $this->view('admin/addCategory', $data);
        }
    }
    
    public function editCategory($id) {
        $category = $this->categoryModel->getCategoryById($id);
        
        if (!$category) {
            $this->redirect('admin/categories');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePost();
            
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'name_err' => '',
                'description_err' => ''
            ];
            
            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Silakan masukkan nama kategori';
            } elseif ($this->categoryModel->categoryNameExists($data['name'], $data['id'])) {
                $data['name_err'] = 'Nama kategori sudah ada';
            }
            
            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['description_err'])) {
                if ($this->categoryModel->updateCategory($data)) {
                    $this->flash('category_message', 'Kategori berhasil diperbarui');
                    $this->redirect('admin/categories');
                } else {
                    die('Something went wrong');
                }
            } else {
                $data['category'] = $category;
                $this->view('admin/editCategory', $data);
            }
        } else {
            $data = [
                'title' => 'Edit Kategori - ' . APP_NAME,
                'category' => $category,
                'name_err' => '',
                'description_err' => ''
            ];
            
            $this->view('admin/editCategory', $data);
        }
    }
    
    public function deleteCategory($id) {
        $category = $this->categoryModel->getCategoryById($id);
        
        if (!$category) {
            $this->redirect('admin/categories');
        }
        
        // Check if category has complaints
        if ($this->categoryModel->hasComplaints($id)) {
            $this->flash('category_message', 'Kategori tidak dapat dihapus karena masih memiliki pengaduan', 'alert-danger');
            $this->redirect('admin/categories');
        }
        
        if ($this->categoryModel->deleteCategory($id)) {
            $this->flash('category_message', 'Kategori berhasil dihapus');
        } else {
            $this->flash('category_message', 'Gagal menghapus kategori', 'alert-danger');
        }
        
        $this->redirect('admin/categories');
    }
    
    public function reports() {
        $categories = $this->categoryModel->getCategories();

        // Get filter parameters
        $filters = [];
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        $complaints = $this->complaintModel->getComplaintsForReport($filters);

        $data = [
            'title' => 'Laporan - ' . APP_NAME,
            'complaints' => $complaints,
            'categories' => $categories,
            'filters' => $filters
        ];

        $this->view('admin/reports', $data);
    }

    public function exportExcel() {
        // Get filter parameters
        $filters = [];
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        $complaints = $this->complaintModel->getComplaintsForReport($filters);

        // Set headers for Excel download
        $filename = 'laporan_pengaduan_' . date('Y-m-d_H-i-s') . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Start output buffering
        ob_start();

        echo '<html>';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<style>';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th, td { border: 1px solid #000; padding: 8px; text-align: left; }';
        echo 'th { background-color: #f2f2f2; font-weight: bold; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';

        echo '<h2>' . APP_NAME . '</h2>';
        echo '<h3>Laporan Pengaduan Warga</h3>';
        echo '<p>Dicetak pada: ' . date('d/m/Y H:i:s') . '</p>';

        if (!empty($filters)) {
            echo '<p>Filter: ';
            if (isset($filters['status'])) {
                echo 'Status: ' . ucfirst($filters['status']) . ' | ';
            }
            if (isset($filters['date_from']) && isset($filters['date_to'])) {
                echo 'Periode: ' . date('d/m/Y', strtotime($filters['date_from'])) . ' - ' . date('d/m/Y', strtotime($filters['date_to']));
            }
            echo '</p>';
        }

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>ID Pengaduan</th>';
        echo '<th>Tanggal</th>';
        echo '<th>Nama Pelapor</th>';
        echo '<th>Email</th>';
        echo '<th>Kategori</th>';
        echo '<th>Judul</th>';
        echo '<th>Deskripsi</th>';
        echo '<th>Lokasi</th>';
        echo '<th>Status</th>';
        echo '<th>Tanggapan Admin</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $no = 1;
        foreach ($complaints as $complaint) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>#' . str_pad($complaint->id, 6, '0', STR_PAD_LEFT) . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($complaint->created_at)) . '</td>';
            echo '<td>' . htmlspecialchars($complaint->user_name) . '</td>';
            echo '<td>' . htmlspecialchars($complaint->user_email) . '</td>';
            echo '<td>' . htmlspecialchars($complaint->category_name) . '</td>';
            echo '<td>' . htmlspecialchars($complaint->title) . '</td>';
            echo '<td>' . htmlspecialchars($complaint->description) . '</td>';
            echo '<td>' . htmlspecialchars($complaint->location) . '</td>';

            $statusText = '';
            switch ($complaint->status) {
                case 'pending':
                    $statusText = 'Menunggu';
                    break;
                case 'process':
                    $statusText = 'Diproses';
                    break;
                case 'completed':
                    $statusText = 'Selesai';
                    break;
                case 'rejected':
                    $statusText = 'Ditolak';
                    break;
            }
            echo '<td>' . $statusText . '</td>';
            echo '<td>' . htmlspecialchars($complaint->admin_response) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';

        echo '<br>';
        echo '<p><strong>Total Pengaduan: ' . count($complaints) . '</strong></p>';

        echo '</body>';
        echo '</html>';

        // Get the content and clean the buffer
        $content = ob_get_clean();

        // Output the content
        echo $content;
        exit;
    }
}
?>
