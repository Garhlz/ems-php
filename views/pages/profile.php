<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}

$title = '个人资料';
ob_start();

// 确保session中有用户信息
if (!isset($_SESSION['username'])) {
    header('Location: ' . getRoute('login'));
    exit();
}
?>

<style>
.profile-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
}

.profile-header {
    text-align: center;
    margin-bottom: 40px;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background-color: #e9ecef;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
    color: #6c757d;
}

.profile-name {
    font-size: 24px;
    color: #343a40;
    margin-bottom: 10px;
}

.profile-role {
    color: #6c757d;
    font-size: 16px;
    margin-bottom: 20px;
}

.profile-info {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.info-group {
    margin-bottom: 20px;
}

.info-label {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 5px;
}

.info-value {
    color: #343a40;
    font-size: 16px;
}
</style>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            👤
        </div>
        <h2 class="profile-name"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></h2>
        <div class="profile-role">系统管理员</div>
    </div>

    <div class="profile-info">
        <div class="info-group">
            <div class="info-label">用户名</div>
            <div class="info-value"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></div>
        </div>
        <?php if (isset($_SESSION['userid'])): ?>
        <div class="info-group">
            <div class="info-label">账号ID</div>
            <div class="info-value"><?= htmlspecialchars($_SESSION['userid']) ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once ROOT_PATH . '/views/layouts/master.php';
?>