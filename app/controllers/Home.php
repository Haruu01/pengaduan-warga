<?php
class Home extends Controller {
    protected $complaintModel;
    protected $categoryModel;

    public function __construct() {
        $this->complaintModel = $this->model('Complaint');
        $this->categoryModel = $this->model('Category');
    }
    
    public function index() {
        // Get statistics for homepage
        $stats = $this->complaintModel->getStatistics();
        $categories = $this->categoryModel->getCategories();
        
        $data = [
            'title' => 'Beranda - ' . APP_NAME,
            'stats' => $stats,
            'categories' => $categories
        ];
        
        $this->view('home/index', $data);
    }
    
    public function about() {
        $data = [
            'title' => 'Tentang Kami - ' . APP_NAME
        ];
        
        $this->view('home/about', $data);
    }
    
    public function contact() {
        $data = [
            'title' => 'Kontak - ' . APP_NAME
        ];
        
        $this->view('home/contact', $data);
    }
}
?>
