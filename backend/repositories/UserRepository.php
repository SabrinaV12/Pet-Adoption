<?php

require_once __DIR__ . '/database/db.php';
require_once __DIR__ . '/../models/user.php';

class UserRepository
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getUserById($id): ?User
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $stmt->close();
            return null;
        }

        $stmt->bind_result(
            $db_id,
            $db_first_name,
            $db_last_name,
            $db_username,
            $db_email,
            $db_phone_number,
            $db_hash_password,
            $db_role,
            $db_description,
            $db_country,
            $db_county,
            $db_telegram_handle,
            $db_profile_picture,
            $db_banner_picture
        );


        $stmt->fetch();
        $stmt->close();

        return new User(
            $db_id,
            $db_profile_picture,
            $db_first_name,
            $db_last_name,
            $db_username,
            $db_email,
            $db_phone_number,
            $db_hash_password,
            $db_description,
            $db_role,
            $db_county,
            $db_country,
            $db_telegram_handle,
            $db_banner_picture
        );
    }

    public function getUserByUsername($username): ?User
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        if ($stmt === false) {
            error_log('Prepare failed: ' . $this->conn->error);
            return null;
        }


        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            echo 'asrasras';
            $stmt->close();
            return null;
        }

        $stmt->bind_result(
            $db_id,
            $db_first_name,
            $db_last_name,
            $db_username,
            $db_email,
            $db_phone_number,
            $db_hash_password,
            $db_role,
            $db_description,
            $db_country,
            $db_county,
            $db_telegram_handle,
            $db_profile_picture,
            $db_banner_picture
        );


        $stmt->fetch();
        $stmt->close();

        return new User(
            $db_id,
            $db_profile_picture,
            $db_first_name,
            $db_last_name,
            $db_username,
            $db_email,
            $db_phone_number,
            $db_hash_password,
            $db_description,
            $db_role,
            $db_county,
            $db_country,
            $db_telegram_handle,
            $db_banner_picture
        );
    }

 public function getPetsByUser($userId): array
{
    $stmt = $this->conn->prepare("SELECT id, name, age, animal_type, breed, size, image_path FROM pets WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $pets = [];
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }

    $stmt->close();
    return $pets;
}




    public function getAllUsers(): array
    {
        $users = [];
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id ASC");

        if ($stmt === false) {
            error_log('Prepare failed: ' . $this->conn->error);
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = new User(
                    $row['id'],
                    $row['profile_picture'],
                    $row['first_name'],
                    $row['last_name'],
                    $row['username'],
                    $row['email'],
                    $row['phone_number'],
                    $row['hash_password'],
                    $row['description'],
                    $row['role'],
                    $row['county'],
                    $row['country'],
                    $row['telegram_handle'],
                    $row['banner_picture']
                );
            }
        }

        $stmt->close();
        return $users;
    }

    public function updateUser(User $user): bool
    {
        $sql = "UPDATE users SET 
                    first_name = ?, 
                    last_name = ?, 
                    username = ?, 
                    email = ?, 
                    phone_number = ?, 
                    role = ?, 
                    description = ?, 
                    country = ?, 
                    county = ?, 
                    telegram_handle = ?,
                    profile_picture = ?,
                    banner_picture = ?";

        $types = "ssssssssssss";

        $params = [
            $user->getFirstName(),
            $user->getLastName(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getPhoneNumber(),
            $user->getRole(),
            $user->getDescription(),
            $user->getCountry(),
            $user->getCounty(),
            $user->getTelegramHandle(),
            $user->getProfilePicture(),
            $user->getBannerPicture()
        ];

        if (!empty($user->getHashPassword())) {
            $sql .= ", hash_password = ?";
            $types .= "s";
            $params[] = $user->getHashPassword();
        }

        $sql .= " WHERE id = ?";
        $types .= "i";
        $params[] = $user->getId();

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log('Prepare failed: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param($types, ...$params);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log('Prepare failed: ' . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
