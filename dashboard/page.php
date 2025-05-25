<?php
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');
require_once '../config.php';

include 'menubar.php';

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

<div class="page-flex-container">
  <div class="page-form-left">
    <form id="pageForm" method="post">
      <label for="title">Title</label>
      <input name="title" id="title" placeholder="Title" required value="<?= isset($page) ? htmlspecialchars($page['title']) : '' ?>">
      <label for="slug">Slug</label>
      <input name="slug" id="slug" placeholder="Slug (e.g. about-us)" required value="<?= isset($page) ? htmlspecialchars($page['slug']) : '' ?>">
      <label for="meta_title">Meta Title</label>
      <input name="meta_title" id="meta_title" placeholder="Meta Title" value="<?= isset($page) ? htmlspecialchars($page['meta_title']) : '' ?>">
      <label for="meta_keywords">Meta Keywords</label>
      <input name="meta_keywords" id="meta_keywords" placeholder="Meta Keywords (comma separated)" value="<?= isset($page) ? htmlspecialchars($page['meta_keywords']) : '' ?>">
      <label for="meta_description">Meta Description</label>
      <textarea name="meta_description" id="meta_description" placeholder="Meta Description"><?= isset($page) ? htmlspecialchars($page['meta_description']) : '' ?></textarea>
      <button type="button" onclick="submitPageForm()">
        <?= isset($page) ? 'Update Page' : 'Add Page' ?>
      </button>
      <input type="hidden" name="description" id="descInput">
    </form>
  </div>
  <div class="page-form-right">
    <label>Contents</label>
    <div id="description"><?= isset($page) ? $page['description'] : '' ?></div>
  </div>
</div>
<script>

  const quill = new Quill('#description', {
  modules: {
    toolbar: [
      [{ header: [1, 2, false] }],
      ['bold', 'italic', 'underline',],
      ['image','link'],
    ],
  },
  placeholder: '',
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