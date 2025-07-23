<?php
class Category {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Get all categories
    public function getCategories() {
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        
        $results = $this->db->resultSet();
        return $results;
    }
    
    // Get category by ID
    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        return $row;
    }
    
    // Add category
    public function addCategory($data) {
        // Check if category name already exists
        $this->db->query('SELECT COUNT(*) as count FROM categories WHERE name = :name');
        $this->db->bind(':name', $data['name']);
        $result = $this->db->single();

        if ($result->count > 0) {
            return false; // Category already exists
        }

        $this->db->query('INSERT INTO categories (name, description) VALUES(:name, :description)');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update category
    public function updateCategory($data) {
        $this->db->query('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Delete category
    public function deleteCategory($id) {
        $this->db->query('DELETE FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Check if category has complaints
    public function hasComplaints($id) {
        $this->db->query('SELECT COUNT(*) as count FROM complaints WHERE category_id = :id');
        $this->db->bind(':id', $id);

        $result = $this->db->single();
        return $result->count > 0;
    }

    // Check if category name exists (for validation)
    public function categoryNameExists($name, $excludeId = null) {
        if ($excludeId) {
            $this->db->query('SELECT COUNT(*) as count FROM categories WHERE name = :name AND id != :id');
            $this->db->bind(':name', $name);
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->query('SELECT COUNT(*) as count FROM categories WHERE name = :name');
            $this->db->bind(':name', $name);
        }

        $result = $this->db->single();
        return $result->count > 0;
    }

    // Get categories with complaint count
    public function getCategoriesWithCount() {
        $this->db->query('
            SELECT c.*, COUNT(comp.id) as complaint_count
            FROM categories c
            LEFT JOIN complaints comp ON c.id = comp.category_id
            GROUP BY c.id
            ORDER BY c.name ASC
        ');

        return $this->db->resultSet();
    }
}
?>
