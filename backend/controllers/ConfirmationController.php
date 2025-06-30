<?php
class ConfirmationController {
    public function showConfirmationPage() {
        readfile('../../frontend/view/confirmation.html');
    }
}
