<?php

require_once __DIR__ . '/../repositories/UserRepository.php';

class AuthService {
    private $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login($username, $password) {
        $user = $this->userRepo->findByUsername($username);

        if (!$user) return false;

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }
}
