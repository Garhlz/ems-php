<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))));
}

$title = '员工管理';
ob_start();
?>

<style>
.employee-container {
    padding: 20px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-bar {
    flex: 1;
    margin-right: 20px;
}

.search-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    color: #333;
}

.search-input::placeholder {
    color: #999;
    font-style: italic;
}

.employee-container {
    padding: 20px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-bar {
    flex: 1;
    margin-right: 20px;
}

.search-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.add-btn {
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.add-btn:hover {
    background-color: #45a049;
}

.employees-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.employees-table th,
.employees-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.employees-table th {
    background-color: #f5f5f5;
    font-weight: bold;
}

.action-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 5px;
}

.edit-btn {
    background-color: #2196F3;
    color: white;
}

.delete-btn {
    background-color: #f44336;
    color: white;
}

/* 模态框样式 */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border-radius: 5px;
    width: 80%;
    max-width: 500px;
}

.close {
    position: absolute;
    right: 10px;
    top: 5px;
    font-size: 24px;
    cursor: pointer;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group select {
    height: 35px;
}

.submit-btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

.submit-btn:hover {
    background-color: #45a049;
}

.error-message {
    color: red;
    font-weight: bold;
    margin-bottom: 10px;
}
</style>

<div class="employee-container">
    <div class="top-bar">
        <div class="search-bar">
            <form action="<?= getRoute('crud_handler') ?>" method="GET">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="搜索员工（支持员工编号、姓名、职位）"
                       value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" style="display: none;"></button>
            </form>
        </div>
        <button class="add-btn" onclick="openModal()">添加员工</button>
    </div>

    <div class="table-container">
        <table class="employees-table">
            <thead>
                <tr>
                    <th>员工编号</th>
                    <th>姓名</th>
                    <th>职位</th>
                    <th>上级</th>
                    <th>入职日期</th>
                    <th>薪资</th>
                    <th>奖金</th>
                    <th>部门</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="employeesTableBody">
                <?php
                if (!isset($backend)) {
                    require_once ROOT_PATH . '/models/backend.php';
                    $backend = new Backend();
                }
                
                // 获取员工列表
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $employees = $backend->searchEmployees($_GET['search']);
                } else {
                    $employees = $backend->getAllEmployees();
                }
                
                foreach ($employees as $emp) {
                    echo "<tr>";
                    echo "<td>{$emp['EMPNO']}</td>";
                    echo "<td>{$emp['ENAME']}</td>";
                    echo "<td>{$emp['JOB']}</td>";
                    echo "<td>{$emp['MGR']}</td>";
                    echo "<td>{$emp['HIREDATE']}</td>";
                    echo "<td>{$emp['SAL']}</td>";
                    echo "<td>{$emp['COMM']}</td>";
                    echo "<td>{$emp['DNAME']}</td>";
                    echo "<td>
                            <button class='action-btn edit-btn' onclick='editEmployee(" . json_encode($emp) . ")'>编辑</button>
                            <form action='" . getRoute('crud_handler') . "' method='POST' style='display:inline;'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='EMPNO' value='{$emp['EMPNO']}'>
                                <button type='submit' class='action-btn delete-btn' onclick='return confirm(\"确定要删除这名员工吗？\")'>删除</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 添加/编辑员工的模态框 -->
<div id="employeeModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">添加员工</h2>
        <form id="employeeForm" action="<?= getRoute('crud_handler') ?>" method="POST">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="EMPNO" id="empno">
            
            <div class="form-group">
                <label for="ename">姓名：</label>
                <input type="text" id="ename" name="ENAME" required>
            </div>
            
            <div class="form-group">
                <label for="job">职位：</label>
                <input type="text" id="job" name="JOB" required>
            </div>
            
            <div class="form-group">
                <label for="mgr">上级编号：</label>
                <input type="number" id="mgr" name="MGR" required>
            </div>
            
            <div class="form-group">
                <label for="hiredate">入职日期：</label>
                <input type="date" id="hiredate" name="HIREDATE" required>
            </div>
            
            <div class="form-group">
                <label for="sal">薪资：</label>
                <input type="number" id="sal" name="SAL" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="comm">奖金：</label>
                <input type="number" id="comm" name="COMM" step="0.01">
            </div>
            
            <div class="form-group">
                <label for="deptno">部门：</label>
                <select id="deptno" name="DEPTNO" required>
                    <option value="10">财务部</option>
                    <option value="20">研发部</option>
                    <option value="30">销售部</option>
                    <option value="40">运营部</option>
                </select>
            </div>
            
            <button type="submit" class="submit-btn">提交</button>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').textContent = '添加员工';
    document.getElementById('formAction').value = 'create';
    document.getElementById('employeeForm').reset();
    document.getElementById('employeeModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('employeeModal').style.display = 'none';
}

function editEmployee(emp) {
    document.getElementById('modalTitle').textContent = '编辑员工';
    document.getElementById('formAction').value = 'update';
    
    // 填充表单
    document.getElementById('empno').value = emp.EMPNO;
    document.getElementById('ename').value = emp.ENAME.trim();
    document.getElementById('job').value = emp.JOB.trim();
    document.getElementById('mgr').value = emp.MGR;
    document.getElementById('hiredate').value = emp.HIREDATE;
    document.getElementById('sal').value = emp.SAL;
    document.getElementById('comm').value = emp.COMM;
    document.getElementById('deptno').value = emp.DEPTNO;
    
    document.getElementById('employeeModal').style.display = 'block';
}

// 点击模态框外部时关闭
window.onclick = function(event) {
    const modal = document.getElementById('employeeModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php
if (isset($_SESSION['error'])) {
    echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

$content = ob_get_clean();
require_once ROOT_PATH . '/views/layouts/master.php';
?>
