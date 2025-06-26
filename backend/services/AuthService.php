<?php

require_once __DIR__ . '/../repositories/UserRepository.php';

class AuthService
{
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function login($username, $password): ?User
{
    $user = $this->userRepo->getUserByUsername($username);

    if (!$user) {
        return null; // Or throw an exception, or return an error message
    }

    if (password_verify($password, $user->hash_password)) {
        $_SESSION['user_id'] = $user->id;
        return $user;
    }

    return null;
}

}
