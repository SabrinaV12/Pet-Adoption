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
        // Folosim `SELECT *` dar vom lega fiecare coloană manual
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);


        // PAS 1: Execută statement-ul (acesta lipsea)
        $stmt->execute();

        // PAS 2: Stochează rezultatul pentru a elibera conexiunea
        $stmt->store_result();

        // Dacă nu găsim utilizatorul, returnăm null
        if ($stmt->num_rows === 0) {
            $stmt->close();
            return null;
        }

        // PAS 3: Legăm TOATE coloanele din `SELECT *` de variabile PHP, în ordinea din tabelă
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


        // PAS 4: Preluează datele în variabilele de mai sus
        $stmt->fetch();
        $stmt->close();

        // PAS 5: Creează obiectul User folosind variabilele
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
        // Aplicăm exact același model sigur și aici
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        if ($stmt === false) {
            // Tratează eroarea la preparare
            error_log('Prepare failed: ' . $this->conn->error);
            return null;
        }


        // PAS 1: Execută
        $stmt->execute();

        // PAS 2: Stochează
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            echo 'asrasras';
            $stmt->close();
            return null;
        }

        // PAS 3: Leagă variabilele (aceeași listă ca mai sus)
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



        // PAS 4: Preia datele
        $stmt->fetch();
        $stmt->close();

        // PAS 5: Returnează obiectul
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

    // Am corectat și această funcție pentru când vei avea nevoie de ea.
    // Aceasta returnează un array de rezultate, deci folosim o buclă `while`.
    public function getPetsByUser($userId): array
    {
        $pets = [];
        $stmt = $this->conn->prepare("SELECT * FROM pets WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Aici trebuie să legi coloanele din tabela `pets`
            // Exemplu: $stmt->bind_result($pet_id, $pet_name, $pet_age, ...);

            // Folosim `while` pentru a itera prin toate animalele găsite
            while ($stmt->fetch()) {
                // Creezi un array sau un obiect Pet pentru fiecare rând
                $pets[] = [
                    // 'id' => $pet_id,
                    // 'name' => $pet_name,
                    // 'age' => $pet_age,
                    // ...etc
                ];
            }
        }

        $stmt->close();
        return $pets;
    }
}
