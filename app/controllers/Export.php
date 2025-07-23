<?php
class Export extends Controller {
    protected $complaintModel;
    protected $categoryModel;

    public function __construct() {
        if (!$this->isLoggedIn() || !$this->isAdmin()) {
            $this->redirect('auth/login');
        }

        $this->complaintModel = $this->model('Complaint');
        $this->categoryModel = $this->model('Category');
    }
    
    public function excel() {
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
    
    public function pdf() {
        // For PDF export, you would typically use a library like TCPDF or FPDF
        // For now, we'll create a simple HTML to PDF conversion
        
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
            'title' => 'Laporan Pengaduan - PDF',
            'complaints' => $complaints,
            'filters' => $filters
        ];
        
        $this->view('export/pdf', $data);
    }
}
?>
