<?php
function renderPage($title, $content) {
    ob_start();
    include __DIR__ . '/../views/layouts/master.php';
    return ob_get_clean();
}