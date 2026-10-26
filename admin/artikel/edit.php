
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

$error = '';
$csrfToken = $_SESSION['csrf_token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !is_string($csrfToken) ||
        $csrfToken === '' ||
        !hash_equals($csrfToken, $submittedToken)
    ) {
        http_response_code(403);
        exit('Token tidak valid. Silakan muat ulang halaman.');
    }

    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');

    if ($judul === '' ||  $isi === '') {
        $error = 'Semua kolom wajib diisi.';
        $artikel['judul'] = $judul;
        $artikel['isi'] = $isi;
    } else {
        $update = $pdo->prepare(
            "UPDATE artikel
             SET judul = ?, isi = ?
             WHERE id = ?"
        );
        $update->execute([$judul, $isi, $id]);

        header('Location: detail.php?id=' . $id);
        exit;
    }
}

$pageTitle = 'Edit Artikel';
$activeMenu = 'artikel';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <?php if ($error !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit.php?id=<?= (int)$id ?>">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <div class="mb-3">
                <label for="judul" class="form-label">Judul Artikel</label>
                <input type="text" id="judul" name="judul"
                       class="form-control" maxlength="200"
                       value="<?= htmlspecialchars($artikel['judul'], ENT_QUOTES, 'UTF-8') ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="isi" class="form-label">Isi Artikel</label>
                <textarea id="isi" name="isi" class="form-control"
                          rows="8" required><?= htmlspecialchars($artikel['isi'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="index.php" class="btn btn-secondary">
                Batal
            </a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
