<header class="main-header">
    <div class="logo">
        <h1>员工管理系统</h1>
    </div>
    <div class="user-info">
        <!-- 用户信息和操作区 -->
        <span class="username">
            <a href="<?= getRoute('profile') ?>" class="profile-link">
                <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
            </a>
        </span>
        <a href="<?= getRoute('logout') ?>" class="logout-btn">退出登录</a>
    </div>
</header>

<style>
.main-header {
    background: #333;
    color: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.profile-link {
    color: white;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 4px;
    transition: background 0.3s;
}

.profile-link:hover {
    background: rgba(255, 255, 255, 0.1);
}

.logout-btn {
    background: #dc3545;
    color: white;
    padding: 5px 15px;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.3s;
}

.logout-btn:hover {
    background: #c82333;
}
</style>