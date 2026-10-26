
<?php
require_once __DIR__ . '/../includes/auth.php';
requireUser();

$csrfToken = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman User</title>
</head>
<body>

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

    <button type="submit">Logout</button>
</form>

</body>
</html>
