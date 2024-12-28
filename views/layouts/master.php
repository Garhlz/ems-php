<?php
// 检查是否已经启动会话
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 检查是否登录
if (!isset($_SESSION['logged_in'])) {
    header('Location: ' . getRoute('login'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'EMS系统' ?></title>
    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        /* Layout */
        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: #fff;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            font-size: 1.25rem;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        .nav-link {
            color: #666;
            text-decoration: none;
            padding: 0.5rem;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #333;
        }

        .nav-link.active {
            color: #333;
            font-weight: 500;
        }

        /* Main Content */
        .main {
            flex: 1;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Footer */
        .footer {
            background: #fff;
            padding: 1rem;
            text-align: center;
            color: #666;
            font-size: 0.875rem;
            margin-top: auto;
        }

        /* Error Messages */
        .error-message {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn-primary {
            background-color: #333;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #444;
        }

        .btn-secondary {
            background-color: #e9ecef;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #dee2e6;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #333;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <nav class="nav">
                <a href="<?= getRoute('dashboard') ?>" class="nav-brand">EMS系统</a>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <ul class="nav-links">
                        <li><a href="<?= getRoute('dashboard') ?>" class="nav-link <?= isCurrentRoute('dashboard') ? 'active' : '' ?>">仪表盘</a></li>
                        <li><a href="<?= getRoute('crud') ?>" class="nav-link <?= isCurrentRoute('crud') ? 'active' : '' ?>">员工管理</a></li>
                        <li><a href="<?= getRoute('profile') ?>" class="nav-link <?= isCurrentRoute('profile') ? 'active' : '' ?>">个人资料</a></li>
                        <li><a href="<?= getRoute('logout') ?>" class="nav-link">退出</a></li>
                    </ul>
                <?php else: ?>
                    <ul class="nav-links">
                        <li><a href="<?= getRoute('login') ?>" class="nav-link <?= isCurrentRoute('login') ? 'active' : '' ?>">登录</a></li>
                        <li><a href="<?= getRoute('register') ?>" class="nav-link <?= isCurrentRoute('register') ? 'active' : '' ?>">注册</a></li>
                    </ul>
                <?php endif; ?>
            </nav>
        </header>

        <main class="main">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>

        <footer class="footer">
            <p>&copy; <?= date('Y') ?> EMS系统. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>