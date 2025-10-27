<?php
include 'config.php';
include 'common_header.php';
show_header('Sign up');
show_header();

// Form processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        echo "<p>Passwords do not match.</p>";
    } elseif (!$email) {
        echo "<p>Invalid email address.</p>";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "<p>Username already exists.</p>";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hash);
            if ($stmt->execute()) {
                echo "<p>Registration successful. <a href='login.php'>Go to login</a></p>";
            } else {
                echo "<p>Registration error. Please try again later.</p>";
            }
        }
        $stmt->close();
    }
}
?>
<form method="post" action="">
  <label>Username: <input type="text" name="username" required></label><br>
  <label>Email: <input type="email" name="email" required></label><br>
  <label>Password: <input type="password" name="password" required></label><br>
  <label>Confirm Password: <input type="password" name="confirm" required></label><br>
  <input type="submit" value="Sign Up">
</form>
</body></html>