<?php
require_once __DIR__ . '/config.php';
$ga_id = null;
$stmt = $conn->prepare("SELECT settings_desc FROM settings WHERE settings_key = 'GA' LIMIT 1");
$stmt->execute();
$stmt->bind_result($ga_id);
$stmt->fetch();
$stmt->close();
if ($ga_id) {
    echo '<!-- Google tag (gtag.js) -->';
    echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . htmlspecialchars($ga_id) . "\"></script>\n";
    echo "<script>\nwindow.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);}\ngtag('js', new Date());\ngtag('config', '" . addslashes($ga_id) . "');\n</script>\n";
}
?>
