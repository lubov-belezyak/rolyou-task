<?php

namespace Src\Controllers;

use Src\Models\User;

class UserController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new User();
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
    }

    public function store($data){
        $errors = $this->userModel->validate($data);
        $result = $this->userModel->create($data);
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