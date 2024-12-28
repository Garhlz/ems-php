<?php

define('ROUTE_PREFIX', '/ems');  // 路由前缀

// 获取路由配置
function getRouteConfig() {
    return [
        'dashboard' => ROUTE_PREFIX . '/dashboard',
        'profile' => ROUTE_PREFIX . '/profile',
        'crud' => ROUTE_PREFIX . '/crud',
        'crud_handler' => ROUTE_PREFIX . '/crud_handler',  // 添加处理CRUD请求的路由
        'logout' => ROUTE_PREFIX . '/logout',
        'login' => ROUTE_PREFIX . '/login',
        'register' => ROUTE_PREFIX . '/register'
    ];
}

function getRoute($name) {
    $routes = getRouteConfig();
    return $routes[$name] ?? ROUTE_PREFIX . '/';
}

function redirect($route, $params = []) {
    $url = getRoute($route);
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

function isCurrentRoute($name) {
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $currentUri === getRoute($name);
}

function getCurrentRouteName() {
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $routes = array_flip(getRouteConfig());
    return $routes[$currentUri] ?? '';
}