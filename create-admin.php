
<?php
require_once __DIR__ . '/config/database.php';

$nama = 'Administrator';
$username = 'admin';
$password = 'Admin123!';

// Periksa apakah username sudah digunakan
$check = $pdo->prepare(
    'SELECT id FROM admins WHERE username = :username'
);
$check->execute(['username' => $username]);

if ($check->fetch()) {
    exit('Username admin sudah tersedia.');
}

// Hash password sebelum disimpan
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = 'INSERT INTO admins (nama, username, password)
        VALUES (:nama, :username, :password)';

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nama' => $nama,
    'username' => $username,
    'password' => $passwordHash
]);

echo 'Akun admin berhasil dibuat.';
