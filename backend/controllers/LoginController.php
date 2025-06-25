<?php
class LoginController {
    public function showLoginForm() {
        session_start();
        require_once '../../backend/database/check_auth.php';

        readfile('../../frontend/view/login.html');
    }
}
