<?php
require "config.php";

// Check if search input is sent
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);

    if ($search !== '') {
        // Prepared statement for safety
        $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE :search ORDER BY id DESC");
        $stmt->execute([':search' => $search . '%']);
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        if ($rows) {
            foreach ($rows as $row): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row->title) ?></h5>
                        <p class="card-text"><?= substr(htmlspecialchars($row->body), 0, 80) . '...' ?></p>
                        <a href="show.php?id=<?= $row->id ?>" class="btn btn-primary">More</a>
                    </div>
                </div>
            <?php endforeach;
        } else {
            echo "<p>No posts found for '<strong>" . htmlspecialchars($search) . "</strong>'</p>";
        }
    } else {
        echo "<p>Please type something to search.</p>";
    }
}
?>