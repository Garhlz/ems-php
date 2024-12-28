<?php
// 如果已经登录，重定向到个人信息页面
if (isset($_SESSION['logged_in'])) {
    redirect('profile');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录 - 员工管理系统</title>
    <link rel="stylesheet" href="/style.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: #666;
            font-size: 0.875rem;
        }

        .auth-form {
            margin-bottom: 1.5rem;
        }

        .auth-footer {
            text-align: center;
            font-size: 0.875rem;
            color: #666;
        }

        .auth-footer a {
            color: #333;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1 class="auth-title">欢迎回来</h1>
            <p class="auth-subtitle">请登录您的账号</p>
        </div>

        <form action="<?= ROUTE_PREFIX ?>/login" method="post" class="auth-form">
            <div class="form-group">
                <label class="form-label" for="username">用户名</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-control" 
                       required 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">密码</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control" 
                       required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">登录</button>
        </form>

        <div class="auth-footer">
            <p>还没有账号？ <a href="<?= ROUTE_PREFIX ?>/register">立即注册</a></p>
        </div>
    </div>
</body>
</html>