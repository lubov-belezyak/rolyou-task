<?php

namespace Src\Controllers;

use Src\Models\User;

class UserController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        header('Content-Type: application/json');
    }

    public function index()
    {
        $filters = [];
        if (isset($_GET['role'])) {
            $filters['role'] = $_GET['role'];
        }
        if (isset($_GET['efficiency'])) {
            $filters['efficiency'] = $_GET['efficiency'];
        }

        $users = $this->userModel->getAll($filters);
        if ($users) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'result' => [
                    'users' => $users,
                ],
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'No users found'
            ]);
        }
    }

    public function show($id)
    {
        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'result' => $user,
        ]);
    }

    public function store($data)
    {
        $errors = $this->userModel->validate($data);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            return;
        }
        $result = $this->userModel->create($data);

        if (!$result) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to save user data'
            ]);
            return;
        }

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'result' => [
                'id' => (int) $result['id']
            ]
        ]);
    }

    public function update($data, $id)
    {
        $existingUser = $this->userModel->findById($id);
        if (!$existingUser) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'User not found'
            ]);
            return;
        }

        $errors = $this->userModel->validate($data, $id);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            return;
        }

        $updatedData = array_merge($existingUser, $data);
        $result = $this->userModel->update($id, $updatedData);

        if ($result) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'result' => $result
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to update user'
            ]);
        }
    }


    public function delete($id)
    {
        $existingUser = $this->userModel->findById($id);
        if (!$existingUser) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'User not found'
            ]);
            return;
        }

        $result = $this->userModel->delete($id);

        if ($result) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'result' => $existingUser
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to delete user'
            ]);
        }


    }

    public function deleteAll()
    {
        $result = $this->userModel->deleteAll();
        if ($result) {
            http_response_code(200);
            echo json_encode([
                'success' => true
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to delete all users'
            ]);
        }
    }

}