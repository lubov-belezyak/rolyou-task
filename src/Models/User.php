<?php

namespace Src\Models;

use PDO;
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
        if (!$id) { //create validation
            if (empty($data['full_name']) || strlen($data['full_name']) > 255) {
                $errors['full_name'] = 'Full name is required and max length is 255';
            }
            if (empty($data['role']) || strlen($data['role']) > 255) {
                $errors['role'] = 'Role is required and max length is 255';
            }
            if (!isset($data['efficiency']) || !is_int($data['efficiency']) || $data['efficiency'] < 0 || $data['efficiency'] > 100) {
                $errors['efficiency'] = 'Efficiency must be an integer between 0 and 100';
            }
        } else { //update validation
            if (isset($data['full_name']) && (empty($data['full_name']) || strlen($data['full_name']) > 255)) {
                $errors['full_name'] = 'Full name is required and max length is 255';
            }
            if (isset($data['role']) && (empty($data['role']) || strlen($data['role']) > 255)) {
                $errors['role'] = 'Role is required and max length is 255';
            }
            if (isset($data['efficiency']) && (!is_int($data['efficiency']) || $data['efficiency'] < 0 || $data['efficiency'] > 100)) {
                $errors['efficiency'] = 'Efficiency must be an integer between 0 and 100';
            }
        }
        return $errors;
    }

    public function getAll($filters = [])
    {
        $query = "SELECT * FROM " . $this->table;

        if (!empty($filters)) {
            $query .= " WHERE 1";  // Для удобства добавляем всегда "WHERE 1"
            foreach ($filters as $key => $value) {
                $query .= " AND {$key} = :{$key}";
            }
        }
        $stmt = $this->db->prepare($query);

        foreach ($filters as $key => $value) {
            $stmt->bindParam(":{$key}", $value);
        }

        $stmt->execute();
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
        if ($stmt->execute()) {
            return ['id' => $this->db->lastInsertId()];
        }
        return false;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE " . $this->table . " SET full_name = :full_name, role = :role, 
                                    efficiency = :efficiency WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':efficiency', $data['efficiency'], \PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findById($id);
        }
        return false;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteAll()
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table);
        return $stmt->execute();
    }
}
