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
    <title>EMS员工管理系统 - 登录</title>
    <link rel="stylesheet" href="/style.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 60px auto;
            padding: 2.5rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .system-title {
            color: #1a73e8;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .auth-title {
            font-size: 1.75rem;
            color: #333;
            margin-bottom: 0.75rem;
        }

        .auth-subtitle {
            color: #666;
            font-size: 1rem;
        }

        .auth-form {
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #444;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
            outline: none;
        }

        .btn-primary {
            background: #1a73e8;
            color: white;
            border: none;
            padding: 0.875rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1557b0;
            transform: translateY(-1px);
        }

        .auth-footer {
            text-align: center;
            font-size: 0.95rem;
            color: #666;
        }

        .auth-footer a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1 class="system-title">EMS员工管理系统</h1>
            <h2 class="auth-title">欢迎回来</h2>
            <p class="auth-subtitle">请登录您的账号继续使用</p>
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