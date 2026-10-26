
<?php
session_start();

if (
    isset($_SESSION['admin_id'], $_SESSION['role'])
) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit;
    }

    if ($_SESSION['role'] === 'user') {
        header('Location: user/index.php');
        exit;
    }
}

header('Location: login.php');
exit;
