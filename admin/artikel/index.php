
<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

require_once __DIR__ . '/../../config/database.php';

$stmt = $pdo->query(
    "SELECT id, judul, created_at
     FROM artikel ORDER BY id DESC"
);
$artikels = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Kelola Artikel';
$activeMenu = 'artikel';

require_once __DIR__ . '/../includes/header.php';
?>

<p class="text-secondary">
    Kelola data artikel melalui menu berikut.
</p>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No.</th>
                        <th>Judul</th>
                        <th>Tanggal Dibuat</th>
                        <th style="width: 240px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($artikels)): ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada data artikel.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($artikels as $i => $artikel): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <?= htmlspecialchars($artikel['judul'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($artikel['created_at'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td>
                                <a href="detail.php?id=<?= (int)$artikel['id'] ?>"
                                   class="btn btn-info btn-sm">
                                    Detail
                                </a>

                                <a href="edit.php?id=<?= (int)$artikel['id'] ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="hapus.php" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    <input type="hidden" name="id"
                                           value="<?= (int)$artikel['id'] ?>">
                                    <input type="hidden" name="csrf_token"
                                           value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
