<?php
// includes/page-parser.php
require_once __DIR__ . '/config.php';

print_r($_GET);

$path = $_GET['path'] ?? '';
// Try to find a page by slug
$stmt = $conn->prepare('SELECT title, description, meta_title, meta_description, meta_keywords FROM pages WHERE slug = ? LIMIT 1');
$stmt->bind_param('s', $path);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->bind_result($title, $description, $meta_title, $meta_description, $meta_keywords);
    $stmt->fetch();
    // Output the page with SEO meta
    echo "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n<meta charset=\"UTF-8\">\n<title>" . htmlspecialchars($meta_title ?: $title) . "</title>\n<meta name=\"description\" content=\"" . htmlspecialchars($meta_description) . "\">\n<meta name=\"keywords\" content=\"" . htmlspecialchars($meta_keywords) . "\">\n</head>\n<body>\n<h1>" . htmlspecialchars($title) . "</h1>\n<div>" . $description . "</div>\n</body>\n</html>";
    exit;
}
// If not found, show 404
http_response_code(404);
echo '<h1>404 Not Found</h1>';
exit;
