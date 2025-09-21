<?php
session_start();
require 'config.php';
$post_id = (int) $_GET['post_id'];

$stmt = $conn->prepare("SELECT * FROM comments WHERE post_id = :post_id ORDER BY id DESC");
$stmt->execute([':post_id' => $post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_OBJ);

foreach ($comments as $c):
    ?>
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="card-title"><?= htmlspecialchars($c->username) ?></h6>
            <p class="card-text"><?= nl2br(htmlspecialchars($c->comment)) ?></p>
            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $c->username): ?>
                <button class="btn btn-danger btn-sm delete-comment" data-id="<?= $c->id ?>">Delete</button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>