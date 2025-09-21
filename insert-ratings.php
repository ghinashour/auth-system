<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["status" => "error", "message" => "You must be logged in to rate."]);
        exit;
    }

    $post_id = intval($_POST["post_id"] ?? 0);
    $rating = intval($_POST["rating"] ?? 0);
    $user_id = $_SESSION["user_id"];

    // Validate
    if ($post_id <= 0 || $rating < 1 || $rating > 5) {
        echo json_encode(["status" => "error", "message" => "Invalid rating."]);
        exit;
    }

    // Delete old rating for this user & post
    $delete = $conn->prepare("DELETE FROM rates WHERE post_id = :post_id AND user_id = :user_id");
    $delete->execute([
        ':post_id' => $post_id,
        ':user_id' => $user_id,
    ]);

    // Insert new rating
    $insert = $conn->prepare("INSERT INTO rates (post_id, rating, user_id) VALUES (:post_id, :rating, :user_id)");
    $insert->execute([
        ':post_id' => $post_id,
        ':rating' => $rating,
        ':user_id' => $user_id,
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Rating submitted successfully.",
        "data" => [
            "post_id" => $post_id,
            "rating" => $rating
        ]
    ]);
    exit;
}
