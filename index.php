<?php require "includes/header.php"; ?>
<?php require "config.php"; ?>

<?php
// Fetch posts (latest first)
$stmt = $conn->prepare("SELECT * FROM posts ORDER BY id DESC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<main class="form-signin w-50 m-auto mt-5">
    <?php if (!empty($rows)): ?>
        <?php foreach ($rows as $row): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo htmlspecialchars($row->title); ?>
                    </h5>
                    <p class="card-text">
                        <?php echo htmlspecialchars(substr($row->body, 0, 80)) . "...."; ?>
                    </p>
                    <a href="show.php?id=<?php echo urlencode($row->id); ?>" class="btn btn-primary">
                        More
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No posts found. Be the first to create one!
        </div>
    <?php endif; ?>
</main>

<?php require "includes/footer.php"; ?>