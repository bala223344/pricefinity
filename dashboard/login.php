<?php
session_start();
require_once '../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    if ($stmt->fetch() && password_verify($pass, $hash)) {
        $_SESSION['user_id'] = $id;
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid login";
    }
}
?>
<form method="post">
    <input name="username" placeholder="Username" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
</form>