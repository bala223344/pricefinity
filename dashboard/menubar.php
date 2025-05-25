<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard-style.css">
</head>
<body>
    <div class="dashboard-header">Pricefinity Dashboard</div>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="dashboard-menubar">
            <a href="index.php">Pages</a> | <a href="settings.php">Settings</a> | <a href="logout.php">Logout</a>
        </div>
    <?php endif; ?>
    <!-- Page content will be included below -->
