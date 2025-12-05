<?php
// app/controllers/DashboardController.php

class DashboardController extends Controller
{
    public function index()
    {
        $this->requireLogin();
        $title = 'Dashboard';
        $this->view('dashboard/index', compact('title'));
    }
}
