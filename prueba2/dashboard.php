<?php
session_start();
include 'config.php';
include 'common_header.php';

show_header('Successful');
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT username, email FROM users");
echo "<h2>Registered users</h2><ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['username']) . " (" . htmlspecialchars($row['email']) . ")</li>";
}
echo "</ul>";
echo "<a href='login.php'>Log out</a>";
echo '</body></html>';
?>
