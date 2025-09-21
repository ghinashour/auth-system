<?php require "includes/header.php"; ?>
<?php require "config.php"; ?>

<?php
// Block guests, allow only logged-in users
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');

    if ($title === '' || $body === '') {
        $error = "All fields are required.";
    } else {
        $username = $_SESSION['username'];

        // Insert into DB
        $insert = $conn->prepare(
            'INSERT INTO posts (title, body, username) 
             VALUES (:title, :body, :username)'
        );

        $insert->execute([
            ':title' => $title,
            ':body' => $body,
            ':username' => $username
        ]);

        header('Location: index.php');
        exit;
    }
}
?>

<main class="form-signin w-50 m-auto">
    <form method="POST" action="create.php">
        <h1 class="h3 mt-5 fw-normal text-center">Create Post</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="form-floating mb-3">
            <input name="title" type="text" class="form-control" id="floatingInput" placeholder="Title"
                value="<?php echo htmlspecialchars($title ?? ''); ?>">
            <label for="floatingInput">Title</label>
        </div>

        <div class="form-floating mb-3">
            <textarea rows="9" name="body" placeholder="Body" class="form-control"
                id="floatingTextarea"><?php echo htmlspecialchars($body ?? ''); ?></textarea>
            <label for="floatingTextarea">Body</label>
        </div>

        <button name="submit" class="w-100 btn btn-lg btn-primary mt-4" type="submit">
            Create Post
        </button>
    </form>
</main>

<?php require "includes/footer.php"; ?>