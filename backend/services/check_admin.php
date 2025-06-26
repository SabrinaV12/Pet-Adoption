<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JwtService
{
    private $secretKey;
    private $algorithm = 'HS256';

    public function __construct()
    {
        $this->secretKey = $_ENV['JWT_SECRET_KEY'] ?? "SECRET_KEY";
        if (is_null($this->secretKey)) {
            throw new Exception('JWT secret key is not configured on the server.');
        }
    }

    public function verifyAdminToken(?string $token): object
    {
        if (is_null($token) || empty($token)) {
            throw new Exception('Authentication token was not provided.');
        }

        try {
            $decodedPayload = JWT::decode($token, new Key($this->secretKey, $this->algorithm));

            if (!isset($decodedPayload->data->role)) {
                throw new UnexpectedValueException('Token is valid, but role information is missing.');
            }

            if ($decodedPayload->data->role !== 'admin') {
                throw new Exception('Access denied. Administrator privileges are required.');
            }

            return $decodedPayload;
        } catch (SignatureInvalidException $e) {
            throw new Exception('Invalid token signature. Access denied.');
        } catch (ExpiredException $e) {
            throw new Exception('Provided token has expired. Please log in again.');
        } catch (UnexpectedValueException | Exception $e) {
            throw new Exception('Authentication error: ' . $e->getMessage());
        }
    }

    public function getBearerToken(): ?string
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if ($authHeader) {
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
