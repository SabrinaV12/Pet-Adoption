<?php
class HomepageController
{
    public function showHomepage()
    {
        session_start();
        readfile(__DIR__ . '/../../frontend/view/pages/home.html');
    }
}
