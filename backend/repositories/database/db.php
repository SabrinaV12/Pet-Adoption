<?php

class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            $host = 'localhost';
            $db   = 'pet_adopt';
            $user = 'root';
            $pass = '';

            self::$conn = new mysqli($host, $user, $pass, $db);

            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }

        return self::$conn;
    }
}
