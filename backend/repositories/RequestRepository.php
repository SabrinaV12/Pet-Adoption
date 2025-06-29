<?php

require_once __DIR__ . '/database/db.php';

class RequestRepository {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getById($requestId) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.username
            FROM pet_requests r
            JOIN users u ON r.user_id = u.id
            WHERE r.id = ?
        ");
        $stmt->bind_param('i', $requestId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function acceptRequest($requestId) {
        error_log("🔄 Accepting request ID: $requestId");

        $stmt = $this->conn->prepare("SELECT * FROM pet_requests WHERE id = ?");
        $stmt->bind_param('i', $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        $request = $result ? $result->fetch_assoc() : null;


        error_log("📦 Request: " . print_r($request, true));

        
        if (!$request || $request['status'] !== 'pending') {
            return false;
        }
   
        try {
            $this->conn->begin_transaction();

            $insert = $this->conn->prepare("
                INSERT INTO pets (
                    name, gender, breed, age, color, weight, height,
                    animal_type, image_path, size,
                    vaccinated, house_trained, neutered, microchipped,
                    good_with_children, shots_up_to_date,
                    restrictions, recommended, adopted, adoption_date,
                    description, user_id, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?,
                    ?, ?, 1, NOW(),
                    ?, ?, NOW()
                )
            ");

            $name         = $request['name'] ?? 'Unnamed';
            $gender       = $request['gender'] ?? 'Male';
            $breed        = $request['breed'] ?? null;
            $age          = (int)($request['age'] ?? 0);
            $color        = $request['color'] ?? null;
            $weight       = isset($request['weight']) ? (float)$request['weight'] : null;
            $height       = isset($request['height']) ? (float)$request['height'] : null;
            $animalType   = $request['animal_type'] ?? 'Dog';
            $imagePath    = $request['image_path'] ?? null;
            $size         = $request['size'] ?? 'Medium';
            $vaccinated   = (int)($request['vaccinated'] ?? 0);
            $houseTrained = (int)($request['house_trained'] ?? 0);
            $neutered     = (int)($request['neutered'] ?? 0);
            $microchipped = (int)($request['microchipped'] ?? 0);
            $goodChildren = (int)($request['good_with_children'] ?? 0);
            $shotsUpTo    = (int)($request['shots_up_to_date'] ?? 0);
            $restrictions = $request['restrictions'] ?? null;
            $recommended  = $request['recommended'] ?? null;
            $description  = $request['description'] ?? null;
            $userId       = (int)($request['user_id'] ?? 0);

            $insert->bind_param(
                'sssisddsssiiiiiisssi',
                $name, $gender, $breed, $age, $color, $weight, $height,
                $animalType, $imagePath, $size,
                $vaccinated, $houseTrained, $neutered, $microchipped,
                $goodChildren, $shotsUpTo,
                $restrictions, $recommended,
                $description, $userId
            );

            $insert->execute();

            if ($insert->error) {
                throw new Exception("Insert failed: " . $insert->error);
            }

            $update = $this->conn->prepare("UPDATE pet_requests SET status = 'accepted' WHERE id = ?");
            $update->bind_param('i', $requestId);
            $update->execute();

            if ($update->error) {
                throw new Exception("Update failed: " . $update->error);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            http_response_code(500);
            echo json_encode([
                'error' => $e->getMessage(),
                'sql_error' => $this->conn->error
            ]);
            exit();
        }
    }

    public function denyRequest($requestId) {

        $stmt = $this->conn->prepare("UPDATE pet_requests SET status = 'denied' WHERE id = ?");
        $stmt->bind_param('i', $requestId);
        $stmt->execute();

        if ($stmt->error) {
            error_log("Deny failed: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }
}
