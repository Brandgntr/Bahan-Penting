
<?php
$namaAdmin = $_SESSION['admin_nama'] ?? 'Administrator';
$csrfToken = $_SESSION['csrf_token'] ?? '';
$pageTitle = $pageTitle ?? 'Admin Panel';
$activeMenu = $activeMenu ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold"
           href="/website-admin/admin/dashboard.php">
            Admin Panel
        </a>

        <div class="d-flex align-items-center gap-3">
            <span class="text-white">
                <?= htmlspecialchars($namaAdmin, ENT_QUOTES, 'UTF-8') ?>
            </span>

            <form action="/website-admin/logout.php" method="POST">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
                >
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-3 col-lg-2 bg-white border-end min-vh-100 p-3">
            <h6 class="text-secondary text-uppercase mb-3">
                Menu Utama
            </h6>

            <a href="/website-admin/admin/dashboard.php"
               class="btn <?= $activeMenu === 'dashboard' ? 'btn-primary' : 'btn-outline-primary' ?> w-100 text-start mb-2">
                Dashboard
            </a>

            <a href="/website-admin/admin/artikel/index.php"
               class="btn <?= $activeMenu === 'artikel' ? 'btn-primary' : 'btn-outline-primary' ?> w-100 text-start mb-2">
                Kelola Artikel
            </a>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <h2 class="fw-bold"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h2>
