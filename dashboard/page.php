<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
require_once '../config.php';

// Handle update if page id is set
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $page = null;
    $stmt = $conn->prepare("SELECT title, description, meta_title, meta_description, meta_keywords, slug FROM pages WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $stmt->bind_result($edit_title, $edit_desc, $edit_meta_title, $edit_meta_desc, $edit_meta_keywords, $edit_slug);
    if ($stmt->fetch()) {
        $page = [
            'title' => $edit_title,
            'description' => $edit_desc,
            'meta_title' => $edit_meta_title,
            'meta_description' => $edit_meta_desc,
            'meta_keywords' => $edit_meta_keywords,
            'slug' => $edit_slug
        ];
    }
    $stmt->close();
    // Handle update POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $meta_title = $_POST['meta_title'];
        $meta_desc = $_POST['meta_description'];
        $meta_keywords = $_POST['meta_keywords'];
        $slug = $_POST['slug'];
        $stmt = $conn->prepare("UPDATE pages SET title=?, description=?, meta_title=?, meta_description=?, meta_keywords=?, slug=? WHERE id=?");
        $stmt->bind_param("ssssssi", $title, $desc, $meta_title, $meta_desc, $meta_keywords, $slug, $edit_id);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $meta_title = $_POST['meta_title'];
    $meta_desc = $_POST['meta_description'];
    $meta_keywords = $_POST['meta_keywords'];
    $slug = $_POST['slug'];
    $stmt = $conn->prepare("INSERT INTO pages (title, description, meta_title, meta_description, meta_keywords, slug) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $desc, $meta_title, $meta_desc, $meta_keywords, $slug);
    $stmt->execute();
    header('Location: index.php');
    exit;
}
?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<form id="pageForm" method="post">
    <input name="title" placeholder="Title" required value="<?= isset($page) ? htmlspecialchars($page['title']) : '' ?>">
    <input name="slug" placeholder="Slug (e.g. about-us)" required value="<?= isset($page) ? htmlspecialchars($page['slug']) : '' ?>">
    <input name="meta_title" placeholder="Meta Title" value="<?= isset($page) ? htmlspecialchars($page['meta_title']) : '' ?>">
    <input name="meta_keywords" placeholder="Meta Keywords (comma separated)" value="<?= isset($page) ? htmlspecialchars($page['meta_keywords']) : '' ?>">
    <textarea name="meta_description" placeholder="Meta Description"><?= isset($page) ? htmlspecialchars($page['meta_description']) : '' ?></textarea>
    <div id="description"><?= isset($page) ? $page['description'] : '<h2>Demo Content</h2>\n<p>Preset build with <code>snow</code> theme, and some common formats.</p>' ?></div>
    <input type="hidden" name="description" id="descInput">
    <button type="button" onclick="submitPageForm()">
        <?= isset($page) ? 'Update Page' : 'Add Page' ?>
    </button>
</form>
<script>

  const quill = new Quill('#description', {
  modules: {
    toolbar: [
      [{ header: [1, 2, false] }],
      ['bold', 'italic', 'underline',],
      ['image','link'],
    ],
  },
  placeholder: 'Compose an epic...',
  theme: 'snow', // or 'bubble'
});
  <?php if (isset($page)): ?>
    quill.root.innerHTML = <?= json_encode($page['description']) ?>;
  <?php endif; ?>
  function submitPageForm() {
    document.getElementById('descInput').value = quill.root.innerHTML;
    document.getElementById('pageForm').submit();
  }
</script>