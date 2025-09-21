<?php
require "config.php";

// Ensure request is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["username"])) {
        echo json_encode(["status" => "error", "message" => "You must be logged in to comment."]);
        exit;
    }

    $username = $_SESSION['username'];
    $post_id = intval($_POST['post_id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($post_id <= 0 || $comment === '') {
        echo json_encode(["status" => "error", "message" => "Invalid input."]);
        exit;
    }

    // Insert into database
    $insert = $conn->prepare(
        'INSERT INTO comments (username, post_id, comment) 
         VALUES (:username, :post_id, :comment)'
    );

    $insert->execute([
        ':username' => $username,
        ':post_id' => $post_id,
        ':comment' => $comment,
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Comment added successfully.",
        "data" => [
            "username" => htmlspecialchars($username),
            "comment" => nl2br(htmlspecialchars($comment))
        ]
    ]);
    exit;
}

// Invalid request
echo json_encode(["status" => "error", "message" => "Invalid request."]);
exit;
