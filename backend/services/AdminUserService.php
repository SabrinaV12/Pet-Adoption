<?php

require_once __DIR__ . '/../repositories/UserRepository.php';

class AdminUserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function deleteUserById($id)
    {
        if (empty($id) || !is_numeric($id)) {
            throw new InvalidArgumentException("Invalid User ID provided.");
        }

        $userToDelete = $this->userRepository->getUserById($id);

        if (!$userToDelete) {
            throw new \Exception("User not found.");
        }

        if (strtolower($userToDelete->getRole()) === 'admin') {
            throw new \Exception("Admins cannot be deleted. This action is forbidden.");
        }

        return $this->userRepository->delete($id);
    }
}
