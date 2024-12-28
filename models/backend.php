<?php
/**
 * 后端处理类
 * 处理所有的业务逻辑
 */
class Backend {
    private $conn;

    /**
     * 构造函数，初始化数据库连接
     */
    public function __construct() {
        try {
            // 加载数据库配置
            $config = include(ROOT_PATH . '/config/db.php');
            
            if (!is_array($config) || !isset($config['database'])) {
                throw new Exception("数据库配置错误");
            }

            $dbConfig = $config['database'];

            // 验证配置参数
            $requiredFields = ['host', 'username', 'password', 'dbname'];
            foreach ($requiredFields as $field) {
                if (!isset($dbConfig[$field])) {
                    throw new Exception("数据库配置缺少 {$field} 参数");
                }
            }

            // 创建数据库连接
            $this->conn = new mysqli(
                $dbConfig['host'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['dbname']
            );

            if ($this->conn->connect_error) {
                throw new Exception("数据库连接失败: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset('utf8');
        } catch (Exception $e) {
            error_log("数据库连接错误: " . $e->getMessage());
            die("系统错误，请稍后重试");
        }
    }

    /**
     * 处理登录请求
     * @param string $username 用户名
     * @param string $password 密码
     * @return array 登录结果
     */
    public function handleLogin($username, $password) {
        try {
            // 1. 参数验证
            if (empty($username) || empty($password)) {
                return [
                    'success' => false,
                    'message' => '用户名和密码不能为空'
                ];
            }

            // 2. 准备SQL语句
            $stmt = $this->conn->prepare("SELECT * FROM login WHERE USERNAME = ? AND PASSWORD = ?");
            if (!$stmt) {
                throw new Exception("SQL准备失败");
            }

            // 3. 绑定参数并执行
            $stmt->bind_param("ss", $username, $password);
            if (!$stmt->execute()) {
                throw new Exception("SQL执行失败");
            }

            // 4. 获取结果
            $result = $stmt->get_result();
            
            // 5. 验证登录
            if ($result->num_rows === 1) {
                // 登录成功
                $user = $result->fetch_assoc();
                
                // 设置会话
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['USERNAME'];  
                $_SESSION['userid'] = $user['USERID'];      
                
                return [
                    'success' => true,
                    'message' => '登录成功'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => '用户名或密码错误'
                ];
            }

        } catch (Exception $e) {
            // 记录错误日志
            error_log("登录错误: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '系统错误，请稍后重试'
            ];
        } finally {
            // 关闭语句
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }


    /**
     * 检查用户是否已登录
     * @return bool
     */
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * 处理登出请求
     */
    public function handleLogout() {
        session_destroy();
    }


    /**
     * 处理注册请求
     * @param array $data 注册表单数据
     * @return array 注册结果
     */
    public function handleRegister($data) {
        try {
            // 1. 参数验证
            $username = trim($data['username'] ?? '');
            $password = trim($data['password'] ?? '');
            $confirmPassword = trim($data['confirm_password'] ?? '');

            if (empty($username) || empty($password)) {
                return [
                    'success' => false,
                    'message' => '用户名和密码不能为空'
                ];
            }

            if ($password !== $confirmPassword) {
                return [
                    'success' => false,
                    'message' => '两次输入的密码不一致'
                ];
            }

            // 2. 检查用户名是否已存在
            $stmt = $this->conn->prepare("SELECT `USERID` FROM login WHERE USERNAME = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                return [
                    'success' => false,
                    'message' => '用户名已存在'
                ];
            }
            $stmt->close();

            // 3. 获取当前最大 USERID
            $stmt = $this->conn->prepare("SELECT MAX(USERID) AS max_id FROM login");
            $stmt->execute();
            $result = $stmt->get_result();
            $maxId = $result->fetch_assoc()['max_id'];
            $newUserId = $maxId ? $maxId + 1 : 1; // 如果没有用户，则从 1 开始

            // 4. 创建新用户
            $stmt = $this->conn->prepare("INSERT INTO login (USERID, USERNAME, PASSWORD) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $newUserId, $username, $password);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => '注册成功'
                ];
            } else {
                throw new Exception("创建用户失败");
            }

        } catch (Exception $e) {
            error_log("注册错误: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '系统错误，请稍后重试'
            ];
        }
    }


    /**
     * 添加新员工
     * @param array $data 员工数据
     * @return array 操作结果
     */
    public function addEmployee($data) {
        try {
            // 验证必填字段
            $requiredFields = ['ENAME', 'JOB', 'MGR', 'HIREDATE', 'SAL', 'DEPTNO'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return [
                        'success' => false,
                        'message' => "{$field} 是必填字段"
                    ];
                }
            }

            // 生成新的员工编号
            $stmt = $this->conn->query("SELECT MAX(EMPNO) as max_empno FROM emp");
            $result = $stmt->fetch_assoc();
            $newEmpno = $result['max_empno'] + 1;

            // 准备SQL语句
            $stmt = $this->conn->prepare(
                "INSERT INTO emp (EMPNO, ENAME, JOB, MGR, HIREDATE, SAL, COMM, DEPTNO) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            // 绑定参数
            $stmt->bind_param(
                "issssddi",
                $newEmpno,
                $data['ENAME'],
                $data['JOB'],
                $data['MGR'],
                $data['HIREDATE'],
                $data['SAL'],
                $data['COMM'],
                $data['DEPTNO']
            );

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => '员工添加成功',
                    'empno' => $newEmpno
                ];
            } else {
                throw new Exception("添加员工失败");
            }
        } catch (Exception $e) {
            error_log("添加员工错误: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '系统错误，请稍后重试'
            ];
        }
    }

    /**
     * 更新员工信息
     * @param array $data 员工数据
     * @return array 操作结果
     */
    public function updateEmployee($data) {
        try {
            if (!isset($data['EMPNO'])) {
                return [
                    'success' => false,
                    'message' => '员工编号是必需的'
                ];
            }

            $updateFields = [];
            $types = "";
            $values = [];

            // 动态构建更新字段
            $allowedFields = ['ENAME', 'JOB', 'MGR', 'HIREDATE', 'SAL', 'COMM', 'DEPTNO'];
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateFields[] = "$field = ?";
                    $types .= $this->getParamType($field);
                    $values[] = $data[$field];
                }
            }

            if (empty($updateFields)) {
                return [
                    'success' => false,
                    'message' => '没有提供要更新的字段'
                ];
            }

            // 添加EMPNO到参数列表
            $types .= "i";
            $values[] = $data['EMPNO'];

            $sql = "UPDATE emp SET " . implode(", ", $updateFields) . " WHERE EMPNO = ?";
            $stmt = $this->conn->prepare($sql);

            // 动态绑定参数
            $stmt->bind_param($types, ...$values);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => '员工信息更新成功'
                ];
            } else {
                throw new Exception("更新员工信息失败");
            }
        } catch (Exception $e) {
            error_log("更新员工错误: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '系统错误，请稍后重试'
            ];
        }
    }

    /**
     * 删除员工
     * @param int $empno 员工编号
     * @return array 操作结果
     */
    public function deleteEmployee($empno) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM emp WHERE EMPNO = ?");
            $stmt->bind_param("i", $empno);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => '员工删除成功'
                ];
            } else {
                throw new Exception("删除员工失败");
            }
        } catch (Exception $e) {
            error_log("删除员工错误: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '系统错误，请稍后重试'
            ];
        }
    }

    /**
     * 获取所有员工信息
     */
    public function getAllEmployees() {
        try {
            $sql = "SELECT * FROM emp ORDER BY EMPNO";
            $result = $this->conn->query($sql);
            
            if (!$result) {
                throw new Exception("查询失败: " . $this->conn->error);
            }
            
            $employees = [];
            while ($row = $result->fetch_assoc()) {
                // 处理NULL值和格式化数据
                $row['COMM'] = $row['COMM'] === null ? '0' : $row['COMM'];
                $row['DNAME'] = $this->getDepartmentName($row['DEPTNO']);
                // 去除多余的空格
                $row['ENAME'] = trim($row['ENAME']);
                $row['JOB'] = trim($row['JOB']);
                $employees[] = $row;
            }
            
            return $employees;
        } catch (Exception $e) {
            error_log("获取员工列表错误: " . $e->getMessage());
            return [];
        }
    }

    /**
     * 根据部门编号获取部门名称
     */
    private function getDepartmentName($deptno) {
        switch ($deptno) {
            case 10:
                return '财务部';
            case 20:
                return '研发部';
            case 30:
                return '销售部';
            case 40:
                return '运营部';
            default:
                return '未分配';
        }
    }

    /**
     * 获取参数类型
     * @param string $field 字段名
     * @return string 参数类型
     */
    private function getParamType($field) {
        $types = [
            'EMPNO' => 'i',
            'ENAME' => 's',
            'JOB' => 's',
            'MGR' => 'i',
            'HIREDATE' => 's',
            'SAL' => 'd',
            'COMM' => 'd',
            'DEPTNO' => 'i'
        ];
        return $types[$field] ?? 's';
    }

    /**
     * 析构函数，关闭数据库连接
     */
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * 添加新员工
     */
    public function createEmployee($data) {
        try {
            $sql = "INSERT INTO emp (EMPNO, ENAME, JOB, MGR, HIREDATE, SAL, COMM, DEPTNO) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issssddi", 
                $data['empno'],
                $data['ename'],
                $data['job'],
                $data['mgr'],
                $data['hiredate'],
                $data['sal'],
                $data['comm'],
                $data['deptno']
            );
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                throw new Exception("添加员工失败: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("添加员工错误: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * 更新员工信息
     */
    public function updateEmployeeInfo($data) {
        try {
            $sql = "UPDATE emp SET 
                    ENAME = ?, 
                    JOB = ?, 
                    MGR = ?, 
                    HIREDATE = ?, 
                    SAL = ?, 
                    COMM = ?, 
                    DEPTNO = ? 
                    WHERE EMPNO = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssddii", 
                $data['ename'],
                $data['job'],
                $data['mgr'],
                $data['hiredate'],
                $data['sal'],
                $data['comm'],
                $data['deptno'],
                $data['empno']
            );
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                throw new Exception("更新员工信息失败: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("更新员工信息错误: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * 删除员工
     */
    public function deleteEmployeeInfo($empno) {
        try {
            $sql = "DELETE FROM emp WHERE EMPNO = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $empno);
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                throw new Exception("删除员工失败: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("删除员工错误: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * 搜索员工
     */
    public function searchEmployees($query) {
        try {
            $searchTerm = "%{$query}%";
            $sql = "SELECT * FROM emp WHERE 
                    ENAME LIKE ? OR 
                    JOB LIKE ? OR 
                    CAST(EMPNO AS CHAR) LIKE ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $employees = [];
            
            while ($row = $result->fetch_assoc()) {
                $row['COMM'] = $row['COMM'] === null ? '0' : $row['COMM'];
                $row['DNAME'] = $this->getDepartmentName($row['DEPTNO']);
                $row['ENAME'] = trim($row['ENAME']);
                $row['JOB'] = trim($row['JOB']);
                $employees[] = $row;
            }
            
            return $employees;
        } catch (Exception $e) {
            error_log("搜索员工错误: " . $e->getMessage());
            return [];
        }
    }
}