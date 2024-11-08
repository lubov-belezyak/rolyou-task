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

    public function index(){
        $users = $this->userModel->getAll();
        echo json_encode([
            'success' => true,
            'result' => $users,
        ]);
    }

    public function show($id){
        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        echo json_encode([
            'success' => true,
            'result' => $user,
        ]);
    }

    public function store($data){
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

        echo json_encode([
            'success' => true,
            'result' => [
                'id' => $result['id']
            ]
        ]);
    }

    public function update($data, $id){
        $existingUser = $this->userModel->findById($id);
        $errors = $this->userModel->validate($data, $id);
        $result = $this->userModel->update($id, $data);
    }

    public function delete($id){
        $existingUser = $this->userModel->findById($id);
        $result = $this->userModel->delete($id);
    }
}