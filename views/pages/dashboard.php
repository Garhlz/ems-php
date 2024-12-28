<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}

$title = '仪表盘';
ob_start();
?>

<style>
.dashboard-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    padding: 20px;
}

.construction-message {
    text-align: center;
    padding: 40px;
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.construction-message h2 {
    color: #343a40;
    margin-bottom: 20px;
    font-size: 24px;
}

.construction-message p {
    color: #6c757d;
    font-size: 16px;
    line-height: 1.6;
}

.construction-icon {
    font-size: 48px;
    margin-bottom: 20px;
    color: #ffc107;
}
</style>

<div class="dashboard-container">
    <div class="construction-message">
        <div class="construction-icon">🚧</div>
        <h2>仪表盘正在建设中</h2>
        <p>我们正在努力为您打造一个功能强大的仪表盘<br>敬请期待！</p>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once ROOT_PATH . '/views/layouts/master.php';
?>
