<?php

require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '../models/user.php';

class RegisterRepository
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function createUser(User $data): void
    {
        $stmt = $this->conn->prepare("INSERT INTO users (
            first_name, last_name, username, email, phone, description,
            country, county, telegram_handle, profile_picture, banner_picture, password_hash
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "ssssssssssss",
            $data->first_name,
            $data->last_name,
            $data->username,
            $data->email,
            $data->phone_number,
            $data->description,
            $data->country,
            $data->county,
            $data->telegram_handle,
            $data->profile_picture,
            $data->banner_picture,
            $data->hash_password
        );

        $stmt->execute();
    }
}
