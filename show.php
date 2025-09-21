<?php
require "includes/header.php";
require "config.php";

// Fetch post details
if (isset($_GET["id"])) {
    $post_id = (int) $_GET["id"];

    // Fetch the post
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute([':id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_OBJ);

    // Fetch comments if post exists
    if ($post) {
        $stmt = $conn->prepare("SELECT * FROM comments WHERE post_id = :id ORDER BY id DESC");
        $stmt->execute([':id' => $post_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

// Fetch user rating if logged in
$userRating = null;
if (isset($_SESSION["user_id"]) && isset($post)) {
    $stmt = $conn->prepare("SELECT * FROM ratings WHERE post_id = :id AND user_id = :uid");
    $stmt->execute([':id' => $post_id, ':uid' => $_SESSION['user_id']]);
    $userRating = $stmt->fetch(PDO::FETCH_OBJ);
}
?>

<div class="row mb-4">
    <div class="card">
        <div class="card-body">
            <?php if ($post): ?>
                <h3 class="card-title"><?= htmlspecialchars($post->title) ?></h3>
                <p class="card-text"><?= nl2br(htmlspecialchars($post->body)) ?></p>

                <!-- Rating Form -->
                <?php if (isset($_SESSION["user_id"])): ?>
                    <form id="form-data" method="POST">
                        <div id="rateYo" class="my-rating"></div>
                        <input type="hidden" id="rating" name="rating" value="<?= $userRating->rating ?? 0 ?>">
                        <input type="hidden" name="post_id" value="<?= $post->id ?>">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-danger">Post not found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Comments Section -->
<?php if ($post): ?>
    <div class="row">
        <h4>Comments</h4>
        <div id="comments-container">
            <?php foreach ($comments ?? [] as $c): ?>
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
        </div>

        <!-- Add Comment Form -->
        <?php if (isset($_SESSION['username'])): ?>
            <form id="comment_data">
                <input type="hidden" name="username" value="<?= htmlspecialchars($_SESSION['username']) ?>">
                <input type="hidden" name="post_id" value="<?= $post->id ?>">
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="comment" placeholder="Write your comment..." required></textarea>
                    <label>Comment</label>
                </div>
                <button type="submit" class="btn btn-primary">Add Comment</button>
            </form>
            <div id="msg" class="mt-2"></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require "includes/footer.php"; ?>