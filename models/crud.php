<?php
session_start();

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)));
}

require_once ROOT_PATH . '/models/backend.php';
require_once ROOT_PATH . '/utils/route.php';

$backend = new Backend();

// 处理搜索请求
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    // 重定向回员工列表页面，带上搜索参数
    redirect('crud', ['search' => $_GET['search']]);
    exit();
}

// 处理POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $result = $backend->addEmployee([
                'ENAME' => $_POST['ENAME'],
                'JOB' => $_POST['JOB'],
                'MGR' => $_POST['MGR'],
                'HIREDATE' => $_POST['HIREDATE'],
                'SAL' => $_POST['SAL'],
                'COMM' => $_POST['COMM'] ?? 0,
                'DEPTNO' => $_POST['DEPTNO']
            ]);
            break;
            
        case 'update':
            $result = $backend->updateEmployee([
                'EMPNO' => $_POST['EMPNO'],
                'ENAME' => $_POST['ENAME'],
                'JOB' => $_POST['JOB'],
                'MGR' => $_POST['MGR'],
                'HIREDATE' => $_POST['HIREDATE'],
                'SAL' => $_POST['SAL'],
                'COMM' => $_POST['COMM'] ?? 0,
                'DEPTNO' => $_POST['DEPTNO']
            ]);
            break;
            
        case 'delete':
            $result = $backend->deleteEmployee($_POST['EMPNO']);
            break;
            
        default:
            $result = ['success' => false, 'message' => '未知的操作'];
    }
    
    if (isset($result) && !$result['success']) {
        $_SESSION['error'] = $result['message'];
    }
}

// 重定向回员工列表页面
redirect('crud');
exit();
