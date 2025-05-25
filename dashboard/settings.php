<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
require_once '../config.php';
include 'menubar.php';

// Handle Edit
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    
    
    $desc = trim($_POST['settings_desc']);
    
    $stmt = $conn->prepare("UPDATE settings SET  settings_desc=? WHERE id=?");
    $stmt->bind_param('si',  $desc,  $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        header('Location: settings.php');
        exit;
    } else {
    }
}
// Fetch for edit
$edit_setting = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM settings WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_setting = $result->fetch_assoc();
}
// Fetch all settings
$res = $conn->query("SELECT * FROM settings ORDER BY id DESC");
?>

<?php
// Handle password update
$password_message = '';
if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['user_id'];
    if ($new_password !== $confirm_password) {
        $password_message = 'New passwords do not match.';
    } else {
        $stmt = $conn->prepare('SELECT password FROM users WHERE id=?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($hash);
        if ($stmt->fetch() && password_verify($current_password, $hash)) {
            $stmt->close();
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE users SET password=? WHERE id=?');
            $stmt->bind_param('si', $new_hash, $user_id);
            if ($stmt->execute()) {
                $password_message = 'Password updated successfully!';
            } else {
                $password_message = 'Failed to update password.';
            }
        } else {
            $password_message = 'Current password is incorrect.';
        }
        $stmt->close();
    }
}
?>



<h2>Settings</h2>
<form method="post">
    <?php if ($edit_setting): ?>
        <input type="hidden" name="id" value="<?= $edit_setting['id'] ?>">
        <?= htmlspecialchars($edit_setting['settings_desc_label']) ?>
        <input type="text" name="settings_desc" value="<?= htmlspecialchars($edit_setting['settings_desc']) ?>" placeholder="">
        
        <button type="submit" name="edit">Update</button>
        <a href="settings.php">Cancel</a>
    <?php endif; ?>
</form>

<!-- Password Update Section -->

<ul>
    <?php if (!$edit_setting): ?>
<?php while ($row = $res->fetch_assoc()): ?>
    <li>
        <?= htmlspecialchars($row['settings_desc_label']) ?> : 
        <?= htmlspecialchars($row['settings_desc']) ?> 
        [<a href="settings.php?edit=<?= $row['id'] ?>">Edit</a>]
    </li>
<?php endwhile; ?>
</ul>
<?php endif; ?>



<div class="settings-password-section">
  <h3>Change Password</h3>
  <form method="post" autocomplete="off">
    <input type="password" name="current_password" placeholder="Current Password" required>
    <input type="password" name="new_password" placeholder="New Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
    <button type="submit" name="update_password">Update Password</button>
    <?php if (!empty($password_message)) echo '<p>' . htmlspecialchars($password_message) . '</p>'; ?>
  </form>
</div>