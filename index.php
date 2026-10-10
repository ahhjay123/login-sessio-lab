<?php

function start_app_session()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function escape_html($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect_by_role($role)
{
    $destination = $role === 'admin' ? 'admin-page.php' : 'regular-page.php';
    header('Location: ' . $destination);
    exit();
}

function check_login($required_role = null)
{
    start_app_session();
    if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
        header('Location: login.php');
        exit();
    }
    if ($required_role !== null && $_SESSION['role'] !== $required_role) {
        redirect_by_role($_SESSION['role']);
    }
}

function handle_logout_request()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        logout();
    }
}

function logout()
{
    start_app_session();
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit();
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    start_app_session();
    if (isset($_SESSION['user_id'], $_SESSION['role'])) {
        redirect_by_role($_SESSION['role']);
    }
    header('Location: login.php');
    exit();
}