<?php


class LogoutController
{
    public function logout()
    {
        $cookie_name = "jwt";
        $cookie_path = "/";
        $cookie_domain = "localhost";

        setcookie($cookie_name, "", time() - 3600, $cookie_path, $cookie_domain, false, true);

        echo json_encode(['status' => 'success', 'message' => 'Logged out and cookie cleared']);
    }
}
