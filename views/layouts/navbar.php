<aside class="navbar">
    <nav class="nav-menu">
        <div class="nav-header">
            <h3>功能导航</h3>
        </div>
        <ul>
            <li>
                <a href="<?= getRoute('dashboard') ?>" class="nav-item <?= isCurrentRoute('dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    控制面板
                </a>
            </li>
            <li>
                <a href="<?= getRoute('crud') ?>" class="nav-item <?= isCurrentRoute('crud') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    员工管理
                </a>
            </li>
            <li>
                <a href="<?= getRoute('profile') ?>" class="nav-item <?= isCurrentRoute('profile') ? 'active' : '' ?>">
                    <i class="fas fa-user"></i>
                    个人信息
                </a>
            </li>
            <li>
                <a href="<?= getRoute('logout') ?>" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    退出登录
                </a>
            </li>
        </ul>
    </nav>
</aside>

<style>
.navbar {
    width: 250px;
    background: #2c3e50;
    min-height: 100vh;
    padding: 20px 0;
    color: white;
    position: fixed;
    left: 0;
    top: 0;
}

.nav-menu {
    padding: 0 15px;
}

.nav-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    text-align: center;
}

.nav-header h3 {
    margin: 0;
    color: #ecf0f1;
    font-size: 1.5em;
}

.nav-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-menu li {
    margin: 8px 0;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #ecf0f1;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.nav-item i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.nav-item:hover {
    background: #34495e;
    color: #3498db;
}

.nav-item.active {
    background: #3498db;
    color: white;
}

.nav-item.active:hover {
    background: #2980b9;
    color: white;
}
</style>