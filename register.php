<?php
require "includes/header.php";
require "config.php";

// Initialize error and success messages
$error = "";
$success = "";

if (isset($_POST["submit"])) {
  $email = trim($_POST["email"]);
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Check for empty inputs
  if (empty($email) || empty($username) || empty($password)) {
    $error = "Please fill in all fields.";
  } else {
    // Check if email or username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username LIMIT 1");
    $stmt->execute([':email' => $email, ':username' => $username]);

    if ($stmt->rowCount() > 0) {
      $error = "Email or username already exists.";
    } else {
      // Insert user into database
      $insert = $conn->prepare("INSERT INTO users (email, username, mypassword) VALUES (:email, :username, :mypassword)");
      $insert->execute([
        ':email' => $email,
        ':username' => $username,
        ':mypassword' => password_hash($password, PASSWORD_DEFAULT),
      ]);

      $success = "Registration successful. You can now <a href='login.php'>login</a>.";
    }
  }
}
?>

<main class="form-signin w-50 m-auto">
  <form method="POST" action="register.php">
    <h1 class="h3 mt-5 fw-normal text-center">Please Register</h1>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="form-floating mb-3">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email address</label>
    </div>

    <div class="form-floating mb-3">
      <input name="username" type="text" class="form-control" id="floatingUsername" placeholder="username" required>
      <label for="floatingUsername">Username</label>
    </div>

    <div class="form-floating mb-3">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
    </div>

    <button name="submit" class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
    <h6 class="mt-3">Already have an account? <a href="login.php">Login</a></h6>
  </form>
</main>

<?php require "includes/footer.php"; ?>