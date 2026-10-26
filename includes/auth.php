
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAdmin(): void
{
    if (
        !isset($_SESSION['admin_id'], $_SESSION['role']) ||
        $_SESSION['role'] !== 'admin'
    ) {
        header('Location: /website-admin/login.php');
        exit;
    }
}

function requireUser(): void
{
    if (
        !isset($_SESSION['admin_id'], $_SESSION['role']) ||
        $_SESSION['role'] !== 'user'
    ) {
        header('Location: /website-admin/login.php');
        exit;
    }
}
