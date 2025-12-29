<?php
require_once 'includes/config.php';

if (isset($_SESSION['student_id'])) {
    session_destroy();
}
if (isset($_SESSION['admin_id'])) {
    session_destroy();
}

header('Location: index.php');
exit;
?>
