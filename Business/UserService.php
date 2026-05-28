<?php
//business/UserService.php

namespace Business;

use Entities\User;
use Data\UserDAO;

class UserService
{
    public function verifyUser(string $userName, string $password): bool
    {
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByName($userName);
        if ($user && password_verify($password, $user->getPassword())) {
            return true;
        } else {
            return false;
        }
    }
    public function registerUser(string $userName, string $email, string $password)
    {
        $userDAO = new UserDAO();
        $userId = $userDAO->register($userName, $email, $password);
        return $userId;
    }
    public function getUserById(int $id): User
    {
        $userDAO = new UserDAO();
        return $userDAO->getUserById($id);
    }
    public function updateSkin(int $id, string $newSkin)
    {
        $userDataDAO = new UserDAO();
        $userDataDAO->updateSkin($id, $newSkin);
    }
}
