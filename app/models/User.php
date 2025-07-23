<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Register user
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, phone, address) VALUES(:name, :email, :password, :phone, :address)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Login user
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Get all users
    public function getUsers() {
        $this->db->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
    
    // Update user profile
    public function updateProfile($data) {
        $this->db->query('UPDATE users SET name = :name, phone = :phone, address = :address WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Change password
    public function changePassword($id, $password) {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');

        $this->db->bind(':id', $id);
        $this->db->bind(':password', $password);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Check if email exists (for validation)
    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $this->db->query('SELECT COUNT(*) as count FROM users WHERE email = :email AND id != :id');
            $this->db->bind(':email', $email);
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->query('SELECT COUNT(*) as count FROM users WHERE email = :email');
            $this->db->bind(':email', $email);
        }

        $result = $this->db->single();
        return $result->count > 0;
    }

}
?>
