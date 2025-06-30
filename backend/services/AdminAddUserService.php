<?php

require_once __DIR__ . '/../repositories/RegisterRepository.php';
require_once __DIR__ . '/../models/user.php';

class AdminAddUserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new RegisterRepository();
    }

    public function createUserByAdmin(array $data, array $files): User
    {
        if (empty($data['username']) || empty($data['email'])) {
            throw new Exception('Field is required', 400);
        }
        if ($data['password'] !== $data['confirm_password']) {
            throw new Exception('password_mismatch', 400);
        }
        if ($this->userRepository->emailExists($data['email'])) {
            throw new Exception('email_exists', 409);
        }
        if ($this->userRepository->usernameExists($data['username'])) {
            throw new Exception('username_exists', 409);
        }

        $profilePicPath = $this->uploadFile($files['profile_picture'], 'profile');
        $bannerPicPath = $this->uploadFile($files['banner_picture'], 'banner');

        $userData = new User(
            0,
            $profilePicPath,
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['email'],
            $data['phone'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['description'],
            $data['role'],
            $data['county'],
            $data['country'],
            $data['telegram_handle'],
            $bannerPicPath
        );

        $this->userRepository->createUser($userData);

        return $userData;
    }

    private function uploadFile(array $file, string $type): string
{
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    $uploadDir = __DIR__ . '/../../public/uploads/' . $type . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $originalName = $file['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
        throw new Exception('Invalid file type.');
    }

    $fileName = uniqid() . '.' . $extension;
    $filePath = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        throw new Exception('file_upload_error');
    }

    return '/uploads/' . $type . '/' . $fileName;
}

}
