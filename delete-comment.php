<?php
require "config.php";

// Ensure request is POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    if (!isset($_SESSION["username"])) {
        echo json_encode(["status" => "error", "message" => "You must be logged in."]);
        exit;
    }

    $commentId = (int) $_POST["id"]; // cast to int for extra safety
    $username = $_SESSION["username"];

    // Only delete if the comment belongs to the logged-in user
    $delete = $conn->prepare("DELETE FROM comments WHERE id = :id AND username = :username");
    $delete->execute([
        ":id" => $commentId,
        ":username" => $username
    ]);

    if ($delete->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Comment deleted."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed or not your comment."]);
    }
    exit;
}

// Invalid request
echo json_encode(["status" => "error", "message" => "Invalid request."]);
exit;
