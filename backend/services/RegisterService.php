<?php

require_once __DIR__ . '/../repositories/RegisterRepository.php';

class RegisterService {
    private $repo;

    public function __construct() {
        $this->repo = new RegisterRepository();
    }

    public function register(array $data, array $files): void {
        if ($data['password'] !== $data['confirm_password']) {
            throw new Exception('password_mismatch');
        }

        if ($this->repo->emailExists($data['email'])) {
            throw new Exception('email_exists');
        }

        if ($this->repo->usernameExists($data['username'])) {
            throw new Exception('username_exists');
        }

        $profilePicPath = $this->uploadFile($files['profile_picture'], 'profile');
        $bannerPicPath = $this->uploadFile($files['banner_picture'], 'banner');

        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'description' => $data['description'],
            'country' => $data['country'],
            'county' => $data['county'],
            'telegram_handle' => $data['telegram_handle'],
            'profile_picture' => $profilePicPath,
            'banner_picture' => $bannerPicPath,
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT)
        ];

        $this->repo->createUser($userData);
    }

    private function uploadFile(array $file, string $type): string {
        $uploadDir = __DIR__ . '/../../public/uploads/' . $type . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('file_upload_error');
        }

        return '/uploads/' . $type . '/' . $fileName;
    }
}
