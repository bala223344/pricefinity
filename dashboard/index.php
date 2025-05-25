<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
require_once '../config.php';
include 'menubar.php';
echo '<link rel="stylesheet" href="dashboard-style.css">';

$res = $conn->query("SELECT * FROM pages ORDER BY id DESC");
?>
<h2>Pages</h2>
<a href="page.php" class="btn-new-page">+ New Page</a>
<ul>
<?php while ($row = $res->fetch_assoc()): ?>
    <li>
        <strong><?=htmlspecialchars($row['title'])?></strong>
        [<a href="page.php?edit=<?= $row['id'] ?>">Edit</a>]
        [<a href="index.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this page?');">Delete</a>]
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
    header('Location: index.php');
    exit;
}
?>