<?php
class UserService
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function updateUserFromAdminPanel(array $data, array $files)
    {
        $errors = [];
        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) $errors[] = 'Invalid user ID.';
        if (empty($data['username'])) $errors[] = 'Username is required.';
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
        if (!empty($data['new_password']) && $data['new_password'] !== $data['confirm_password']) $errors[] = 'Passwords do not match.';
        if (!in_array($data['role'], ['user', 'admin'])) $errors[] = 'Invalid role selected.';

        if (!empty($errors)) {
            throw new Exception(implode(' ', $errors), 400); // Bad Request
        }

        $user = $this->userRepo->getUserById($data['id']);
        $profilePicPath = $user->profile_picture;
        $bannerPicPath = $user->banner_picture;

        if (isset($files['profile_picture']) && $files['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $profilePicPath = $this->uploadFile($files['profile_picture'], 'profile');
        }

        if (isset($files['banner_picture']) && $files['banner_picture']['error'] === UPLOAD_ERR_OK) {
            $bannerPicPath = $this->uploadFile($files['banner_picture'], 'banner');
        }

        if (!$user) {
            throw new Exception('User to update not found.', 404);
        }

        $user->setFirstName(htmlspecialchars($data['first_name']));
        $user->setLastName(htmlspecialchars($data['last_name']));
        $user->setUsername(htmlspecialchars($data['username']));
        $user->setEmail(htmlspecialchars($data['email']));
        $user->setPhoneNumber(htmlspecialchars($data['phone_number']));
        $user->setRole(htmlspecialchars($data['role']));
        $user->setDescription(htmlspecialchars($data['description']));
        $user->setCountry(htmlspecialchars($data['country']));
        $user->setCounty(htmlspecialchars($data['county']));
        $user->setTelegramHandle(htmlspecialchars($data['telegram_handle']));
        $user->setProfilePicture(htmlspecialchars($profilePicPath));
        $user->setBannerPicture(htmlspecialchars($bannerPicPath));

        if (!empty($data['new_password'])) {
            $user->setHashPassword(password_hash($data['new_password'], PASSWORD_DEFAULT));
        } else {
            $user->setHashPassword('');
        }

        if (!$this->userRepo->updateUser($user)) {
            throw new Exception('Failed to update user in the database.', 500);
        }

        return true;
    }

    private function uploadFile(array $file, string $type): string
    {
        $uploadDir = __DIR__ . '/../../public/uploads/' . $type . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('file_upload_error');
        }

        return 'uploads/' . $type . '/' . $fileName;
    }
}
