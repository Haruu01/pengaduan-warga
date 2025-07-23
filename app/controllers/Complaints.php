<?php
class Complaints extends Controller {
    protected $complaintModel;
    protected $categoryModel;

    public function __construct() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }

        $this->complaintModel = $this->model('Complaint');
        $this->categoryModel = $this->model('Category');
    }
    
    public function index() {
        // Get user's complaints
        $complaints = $this->complaintModel->getComplaintsByUser($_SESSION['user_id']);
        
        $data = [
            'title' => 'Pengaduan Saya - ' . APP_NAME,
            'complaints' => $complaints
        ];
        
        $this->view('complaints/index', $data);
    }
    
    public function create() {
        $categories = $this->categoryModel->getCategories();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = sanitizePost();
            
            // Handle file upload
            $photo = '';
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['photo']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);
                
                if (in_array(strtolower($filetype), $allowed)) {
                    if ($_FILES['photo']['size'] <= MAX_FILE_SIZE) {
                        $photo = time() . '_' . $filename;
                        $upload_path = 'public/uploads/' . $photo;
                        
                        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                            $photo = '';
                        }
                    }
                }
            }
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'category_id' => trim($_POST['category_id']),
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'location' => trim($_POST['location']),
                'photo' => $photo,
                'category_id_err' => '',
                'title_err' => '',
                'description_err' => '',
                'location_err' => '',
                'photo_err' => ''
            ];
            
            // Validate Category
            if (empty($data['category_id'])) {
                $data['category_id_err'] = 'Silakan pilih kategori';
            }
            
            // Validate Title
            if (empty($data['title'])) {
                $data['title_err'] = 'Silakan masukkan judul pengaduan';
            }
            
            // Validate Description
            if (empty($data['description'])) {
                $data['description_err'] = 'Silakan masukkan deskripsi pengaduan';
            }
            
            // Validate Location
            if (empty($data['location'])) {
                $data['location_err'] = 'Silakan masukkan lokasi';
            }
            
            // Make sure errors are empty
            if (empty($data['category_id_err']) && empty($data['title_err']) && empty($data['description_err']) && empty($data['location_err'])) {
                // Add complaint
                if ($this->complaintModel->addComplaint($data)) {
                    $this->flash('complaint_message', 'Pengaduan berhasil dibuat');
                    $this->redirect('complaints');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $data['categories'] = $categories;
                $this->view('complaints/create', $data);
            }
        } else {
            $data = [
                'title' => 'Buat Pengaduan - ' . APP_NAME,
                'categories' => $categories,
                'category_id' => '',
                'title' => '',
                'description' => '',
                'location' => '',
                'category_id_err' => '',
                'title_err' => '',
                'description_err' => '',
                'location_err' => '',
                'photo_err' => ''
            ];
            
            $this->view('complaints/create', $data);
        }
    }
    
    public function show($id) {
        $complaint = $this->complaintModel->getComplaintById($id);
        
        // Check if complaint exists and belongs to user
        if (!$complaint || $complaint->user_id != $_SESSION['user_id']) {
            $this->redirect('complaints');
        }
        
        $data = [
            'title' => 'Detail Pengaduan - ' . APP_NAME,
            'complaint' => $complaint
        ];
        
        $this->view('complaints/show', $data);
    }
    
    public function delete($id) {
        $complaint = $this->complaintModel->getComplaintById($id);
        
        // Check if complaint exists and belongs to user
        if (!$complaint || $complaint->user_id != $_SESSION['user_id']) {
            $this->redirect('complaints');
        }
        
        // Only allow deletion if status is pending
        if ($complaint->status != 'pending') {
            $this->flash('complaint_message', 'Pengaduan yang sudah diproses tidak dapat dihapus', 'alert-danger');
            $this->redirect('complaints');
        }
        
        if ($this->complaintModel->deleteComplaint($id)) {
            // Delete photo if exists
            if (!empty($complaint->photo) && file_exists('public/uploads/' . $complaint->photo)) {
                unlink('public/uploads/' . $complaint->photo);
            }
            
            $this->flash('complaint_message', 'Pengaduan berhasil dihapus');
        } else {
            $this->flash('complaint_message', 'Gagal menghapus pengaduan', 'alert-danger');
        }
        
        $this->redirect('complaints');
    }
}
?>
