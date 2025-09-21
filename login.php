<?php
require "includes/header.php";
require "config.php";

// Redirect if user is already logged in
if (isset($_SESSION["username"])) {
  header("Location: index.php");
  exit();
}

// Initialize error message
$error = "";

if (isset($_POST["submit"])) {
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  if (empty($email) || empty($password)) {
    $error = "Please fill in both email and password.";
  } else {
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (password_verify($password, $user["mypassword"])) {
        // Set session variables
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["user_id"] = $user["id"];

        header("Location: index.php");
        exit();
      } else {
        $error = "Invalid email or password.";
      }
    } else {
      $error = "Invalid email or password.";
    }
  }
}
?>

<main class="form-signin w-50 m-auto">
  <form method="POST" action="login.php">
    <h1 class="h3 mt-5 fw-normal text-center">Please Login</h1>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-floating mb-3">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating mb-3">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
    </div>

    <button name="submit" class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <h6 class="mt-3">Don't have an account? <a href="register.php">Create your account</a></h6>
  </form>
</main>

<?php require "includes/footer.php"; ?>