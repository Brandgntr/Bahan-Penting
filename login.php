
<?php
session_start();

require_once __DIR__ . '/../../config/database.php';

function redirectByRole(string $role): void
{
    if ($role === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: user/index.php');
    }
    exit;
}

if (isset($_SESSION['admin_id'], $_SESSION['role'])) {
    redirectByRole($_SESSION['role']);
}

$error = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        $error = 'Permintaan tidak valid. Silakan coba lagi.';
    } elseif ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, nama, username, password, role
             FROM users
             WHERE username = :username
             LIMIT 1'
        );

        $stmt->execute(['username' => $username]);
        $account = $stmt->fetch();

        if (
            $account &&
            password_verify($password, $account['password'])
        ) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = (int) $account['id'];
            $_SESSION['admin_nama'] = $account['nama'];
            $_SESSION['admin_username'] = $account['username'];
            $_SESSION['role'] = $account['role'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            redirectByRole($account['role']);
        }

        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container min-vh-100 d-flex
            align-items-center justify-content-center">
    <div class="card border-0 shadow-sm"
         style="width:100%;max-width:400px">
        <div class="card-body p-4">
            <h3 class="text-center mb-2">Login</h3>
            <p class="text-secondary text-center mb-4">
                Silakan masuk ke akun kamu
            </p>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars(
                           $_SESSION['csrf_token'],
                           ENT_QUOTES,
                           'UTF-8'
                       ) ?>">

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>
                    <input type="text" id="username"
                           name="username" class="form-control"
                           autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password
                    </label>
                    <input type="password" id="password"
                           name="password" class="form-control"
                           autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Masuk
                </button>
            </form>

            <p class="text-center mt-3 mb-0">
                Belum punya akun?
                <a href="register.php">Register</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
