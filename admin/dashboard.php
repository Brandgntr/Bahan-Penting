
<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$namaAdmin = $_SESSION['admin_nama'] ?? 'Administrator';
$csrfToken = $_SESSION['csrf_token'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            Admin Panel
        </a>

        <div class="d-flex align-items-center gap-3">
            <span class="text-white">
                <?= htmlspecialchars(
                    $namaAdmin,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </span>

            <form action="../logout.php" method="POST">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        $csrfToken,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
                <button type="submit"
                        class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-3 col-lg-2 bg-white border-end
                      min-vh-100 p-3">
            <h6 class="text-secondary text-uppercase mb-3">
                Menu Utama
            </h6>

            <a href="dashboard.php"
               class="btn btn-primary w-100 text-start mb-2">
                Dashboard
            </a>
            <a href="artikel/index.php" 
               class="btn btn-primary w-100 text-start mb-2">
                Kelola Artikel
            </a>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <h2 class="fw-bold">Dashboard</h2>
            <p class="text-secondary">
                Selamat datang di panel administrasi website.
            </p>

            <div class="row g-3 mt-2">
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-secondary mb-2">
                                Status Sistem
                            </p>
                            <h4 class="text-success">
                                Aktif
                            </h4>
                            <p class="small text-secondary mb-0">
                                Halaman admin berhasil diakses.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-secondary mb-2">
                                Pengguna Login
                            </p>
                            <h4>1 Admin</h4>
                            <p class="small text-secondary mb-0">
                                Ringkasan sesi saat ini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5>Informasi Admin</h5>
                    <p class="mb-1">
                        Nama:
                        <?= htmlspecialchars(
                            $namaAdmin,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>
                    <p class="mb-0">
                        Username:
                        <?= htmlspecialchars(
                            $_SESSION['admin_username'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>
                </div>
            </div>
        </main>

    </div>
</div>

</body>
</html>
