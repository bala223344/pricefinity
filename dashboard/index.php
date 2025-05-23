<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
require_once '../config.php';
$res = $conn->query("SELECT * FROM pages ORDER BY id DESC");
?>
<a href="page.php">Add Page</a> | <a href="logout.php">Logout</a>
<h2>Pages</h2>
<ul>
<?php while ($row = $res->fetch_assoc()): ?>
    <li>
        <strong><?=htmlspecialchars($row['title'])?></strong>: <?=htmlspecialchars($row['description'])?>
        [<a href="page.php?edit=<?= $row['id'] ?>">Edit</a>]
        [<a href="dashboard.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this page?');">Delete</a>]
    </li>
<?php endwhile; ?>
</ul>
<?php
// Handle delete
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM pages WHERE id=?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    header('Location: dashboard.php');
    exit;
}
?>