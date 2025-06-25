<?php
class HomepageController {
    public function showHomepage() {
        session_start();
        require_once '../../backend/database/check_auth.php';
        readfile('../../frontend/view/homepage.html');
    }
}
