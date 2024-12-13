<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function getUserById($id);
    public function getUserByEmail($email);
    public function createUser(User $user);
    public function updateUser(user $user, int $id);
    public function deleteUser($id):void;
}
