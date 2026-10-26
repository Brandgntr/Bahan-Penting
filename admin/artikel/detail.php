
<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../../config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID artikel tidak valid.');
}

$stmt = $pdo->prepare("SELECT * FROM artikel WHERE id = ?");
$stmt->execute([$id]);
$artikel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$artikel) {
    http_response_code(404);
    exit('Artikel tidak ditemukan.');
}

$pageTitle = 'Detail Artikel';
$activeMenu = 'artikel';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h4 class="fw-bold mb-3">
            <?= htmlspecialchars($artikel['judul'], ENT_QUOTES, 'UTF-8') ?>
        </h4>

        <p class="mb-2">
            <strong>ID:</strong>
            <?= (int)$artikel['id'] ?>
        </p>

        <p class="mb-4">
            <strong>Tanggal Dibuat:</strong>
            <?= htmlspecialchars($artikel['created_at'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <hr>

        <h5 class="fw-bold">Isi Artikel</h5>
        <div class="bg-light border rounded p-3 mt-3"
             style="white-space: pre-wrap;"><?= htmlspecialchars($artikel['isi'], ENT_QUOTES, 'UTF-8') ?></div>

        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">
                Kembali
            </a>

            <a href="edit.php?id=<?= (int)$artikel['id'] ?>"
               class="btn btn-warning">
                Edit Artikel
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
