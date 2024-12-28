<?php
session_start();

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'route.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'backend.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$backend = new Backend();

// 定义公开路由
$publicRoutes = [
    ROUTE_PREFIX . '/login',
    ROUTE_PREFIX . '/register'
];

switch ($requestUri) {
    case ROUTE_PREFIX . '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $backend->handleLogin($_POST['username'] ?? '', $_POST['password'] ?? '');
            if ($result['success']) {
                redirect('profile');
            } else {
                $_SESSION['login_error'] = $result['message'];
                redirect('login');
            }
        } else {
            require ROOT_PATH . '/views/pages/login.php';
        }
        break;

    case ROUTE_PREFIX . '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $backend->handleRegister($_POST);
            if ($result['success']) {
                $_SESSION['login_error'] = '注册成功，请登录';
                redirect('login');
            } else {
                $_SESSION['register_error'] = $result['message'];
                redirect('register');
            }
        } else {
            require ROOT_PATH . '/views/pages/register.php';
        }
        break;

    default:
        if (!in_array($requestUri, $publicRoutes) && !$backend->isLoggedIn()) {
            redirect('login');
        }
       
        switch ($requestUri) {
            case ROUTE_PREFIX . '/dashboard':
                require ROOT_PATH . '/views/pages/dashboard.php';
                break;
        
            case ROUTE_PREFIX . '/profile':
                require ROOT_PATH . '/views/pages/profile.php';
                break;
        
            case ROUTE_PREFIX . '/crud':
                require ROOT_PATH . '/views/pages/employees.php';
                break;
                
            case ROUTE_PREFIX . '/crud_handler':
                require ROOT_PATH . '/models/crud.php';
                break;
        
            case ROUTE_PREFIX . '/logout':
                $backend->handleLogout();
                redirect('login');
                break;
        
            default:
                // 404 页面
                header('HTTP/1.0 404 Not Found');
                require ROOT_PATH . '/views/pages/404.php';
                break;
        }
            
         
}