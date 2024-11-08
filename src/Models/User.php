<?php

namespace Src\Models;

use Src\Services\Database;

class User
{
    protected $db;
    private $table = 'users';
    private $id;
    private $full_name;
    private $role;
    private $efficiency;
    private $created_at;
    private $updated_at;

    public function __construct()
    {
        $this->db = (new Database())->getPdo();
    }

    public function validate($data, $id = null)
    {
        $errors = [];
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Full name is required';
        }
        if (empty($data['role'])) {
            $errors['role'] = 'Role is required';
        }
        if (!isset($data['efficiency']) || !is_int($data['efficiency'])) {
            $errors['efficiency'] = 'Efficiency must be an integer';
        }
        return $errors;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT id, full_name, role, efficiency FROM " . $this->table);
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT id, full_name, role, efficiency FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (full_name, role, efficiency) 
                                    VALUES (:full_name, :role, :efficiency)");
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':efficiency', $data['efficiency'], \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE " . $this->table . " SET full_name = :full_name, role = :role, 
                                    efficiency = :efficiency WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':efficiency', $data['efficiency'], \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
