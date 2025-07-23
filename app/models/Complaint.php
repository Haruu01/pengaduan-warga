<?php
class Complaint {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Add complaint
    public function addComplaint($data) {
        $this->db->query('INSERT INTO complaints (user_id, category_id, title, description, location, photo) VALUES(:user_id, :category_id, :title, :description, :location, :photo)');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':photo', $data['photo']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get all complaints
    public function getComplaints() {
        $this->db->query('SELECT c.*, u.name as user_name, u.email as user_email, cat.name as category_name
                         FROM complaints c
                         LEFT JOIN users u ON c.user_id = u.id
                         LEFT JOIN categories cat ON c.category_id = cat.id
                         ORDER BY c.created_at DESC');

        $results = $this->db->resultSet();
        return $results;
    }
    
    // Get complaints by user
    public function getComplaintsByUser($user_id) {
        $this->db->query('SELECT c.*, cat.name as category_name 
                         FROM complaints c 
                         LEFT JOIN categories cat ON c.category_id = cat.id 
                         WHERE c.user_id = :user_id 
                         ORDER BY c.created_at DESC');
        
        $this->db->bind(':user_id', $user_id);
        $results = $this->db->resultSet();
        return $results;
    }
    
    // Get complaint by ID
    public function getComplaintById($id) {
        $this->db->query('SELECT c.*, u.name as user_name, u.email as user_email, u.phone as user_phone, cat.name as category_name 
                         FROM complaints c 
                         LEFT JOIN users u ON c.user_id = u.id 
                         LEFT JOIN categories cat ON c.category_id = cat.id 
                         WHERE c.id = :id');
        
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }
    
    // Update complaint status
    public function updateStatus($id, $status, $response = '') {
        $this->db->query('UPDATE complaints SET status = :status, admin_response = :response WHERE id = :id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        $this->db->bind(':response', $response);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Delete complaint
    public function deleteComplaint($id) {
        $this->db->query('DELETE FROM complaints WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get complaint statistics
    public function getStatistics() {
        $stats = [];
        
        // Total complaints
        $this->db->query('SELECT COUNT(*) as total FROM complaints');
        $stats['total'] = $this->db->single()->total;
        
        // Pending complaints
        $this->db->query('SELECT COUNT(*) as pending FROM complaints WHERE status = "pending"');
        $stats['pending'] = $this->db->single()->pending;
        
        // Process complaints
        $this->db->query('SELECT COUNT(*) as process FROM complaints WHERE status = "process"');
        $stats['process'] = $this->db->single()->process;
        
        // Completed complaints
        $this->db->query('SELECT COUNT(*) as completed FROM complaints WHERE status = "completed"');
        $stats['completed'] = $this->db->single()->completed;
        
        // Rejected complaints
        $this->db->query('SELECT COUNT(*) as rejected FROM complaints WHERE status = "rejected"');
        $stats['rejected'] = $this->db->single()->rejected;
        
        return $stats;
    }
    
    // Get complaints for report
    public function getComplaintsForReport($filters = []) {
        $sql = 'SELECT c.*, u.name as user_name, u.email as user_email, cat.name as category_name 
                FROM complaints c 
                LEFT JOIN users u ON c.user_id = u.id 
                LEFT JOIN categories cat ON c.category_id = cat.id WHERE 1=1';
        
        if (!empty($filters['status'])) {
            $sql .= ' AND c.status = :status';
        }
        
        if (!empty($filters['category'])) {
            $sql .= ' AND c.category_id = :category';
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= ' AND DATE(c.created_at) >= :date_from';
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= ' AND DATE(c.created_at) <= :date_to';
        }
        
        $sql .= ' ORDER BY c.created_at DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        
        if (!empty($filters['category'])) {
            $this->db->bind(':category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->bind(':date_from', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->bind(':date_to', $filters['date_to']);
        }
        
        $results = $this->db->resultSet();
        return $results;
    }
}
?>
