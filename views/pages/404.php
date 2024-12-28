<?php
$title = '404 页面未找到';
ob_start();
?>

<div class="error-container">
    <h1>404</h1>
    <h2>页面未找到</h2>
    <p>抱歉，您请求的页面不存在。</p>
    <a href="<?= getRoute('dashboard') ?>" class="back-btn">返回首页</a>
</div>

<style>
.error-container {
    text-align: center;
    padding: 50px 20px;
}

.error-container h1 {
    font-size: 72px;
    margin: 0;
    color: #e74c3c;
}

.error-container h2 {
    font-size: 24px;
    margin: 20px 0;
    color: #2c3e50;
}

.error-container p {
    font-size: 18px;
    color: #7f8c8d;
    margin-bottom: 30px;
}

.back-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color: #2980b9;
}
</style>

<?php
$content = ob_get_clean();
require_once ROOT_PATH . '/views/layouts/master.php';
?>
