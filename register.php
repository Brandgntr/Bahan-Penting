
<?php
session_start();

require_once __DIR__ . '/../../config/database.php';

if (isset($_SESSION['admin_id'], $_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: user/index.php');
    }
    exit;
}

$error = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        $error = 'Permintaan tidak valid. Silakan coba lagi.';
    } elseif (
        $nama === '' ||
        $username === '' ||
        $password === '' ||
        $konfirmasi === ''
    ) {
        $error = 'Semua kolom wajib diisi.';
    } elseif (
        strlen($nama) > 100 ||
        strlen($username) > 50 ||
        !preg_match('/\A[a-zA-Z0-9_.-]+\z/', $username)
    ) {
        $error = 'Nama atau format username tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak sesuai.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id FROM users WHERE username = :username LIMIT 1'
        );
        $stmt->execute(['username' => $username]);

        if ($stmt->fetch()) {
            $error = 'Username sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO users (nama, username, password, role)
                     VALUES (:nama, :username, :password, :role)'
                );

                $stmt->execute([
                    'nama' => $nama,
                    'username' => $username,
                    'password' => $hash,
                    'role' => 'user'
                ]);

                // Login otomatis setelah registrasi berhasil
                session_regenerate_id(true);

                $_SESSION['admin_id'] = (int) $pdo->lastInsertId();
                $_SESSION['admin_nama'] = $nama;
                $_SESSION['admin_username'] = $username;
                $_SESSION['role'] = 'user';
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header('Location: user/index.php');
                exit;
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $error = 'Registrasi gagal. Silakan coba kembali.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Akun</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container min-vh-100 d-flex
            align-items-center justify-content-center py-4">
    <div class="card border-0 shadow-sm"
         style="width:100%;max-width:440px">
        <div class="card-body p-4">
            <h3 class="text-center mb-2">Buat Akun</h3>
            <p class="text-secondary text-center mb-4">
                Daftar untuk masuk ke website
            </p>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars(
                           $_SESSION['csrf_token'],
                           ENT_QUOTES,
                           'UTF-8'
                       ) ?>">

                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama lengkap
                    </label>
                    <input type="text" id="nama" name="nama"
                           class="form-control" maxlength="100"
                           autocomplete="name" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>
                    <input type="text" id="username" name="username"
                           class="form-control" maxlength="50"
                           pattern="[a-zA-Z0-9_.-]+"
                           autocomplete="username" required>
                    <div class="form-text">
                        Gunakan huruf, angka, titik, garis bawah,
                        atau tanda hubung.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password
                    </label>
                    <input type="password" id="password" name="password"
                           class="form-control" minlength="8"
                           autocomplete="new-password" required>
                </div>

                <div class="mb-3">
                    <label for="konfirmasi_password"
                           class="form-label">
                        Konfirmasi password
                    </label>
                    <input type="password"
                           id="konfirmasi_password"
                           name="konfirmasi_password"
                           class="form-control"
                           minlength="8"
                           autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Daftar
                </button>
            </form>

            <p class="text-center mt-3 mb-0">
                Sudah punya akun?
                <a href="login.php">Login</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
