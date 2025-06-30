<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../services/JwtService.php';

class AuthMeController
{
    public function whoami()
    {
        $jwtService = new JwtService();

        try {
            $token = $jwtService->getToken();
            $decodedPayload = $jwtService->verifyToken($token);
        } catch (Exception $e) {
            echo $e;
            http_response_code(500);
        }

        echo json_encode($decodedPayload->data);
    }
}
