# EMS员工管理系统 (Employee Management System)

[English Version](#english-version)

本项目是一个基于 PHP 的简单员工管理系统，提供员工信息的增删改查（CRUD）功能。 

前端：全部使用原生三大件实现。 

后端：为了简化开发，没有完全采用mvc模式，将交互接口和后端逻辑全部集中在 backend 文件夹中。使用原生php实现了前端表单提交，前端接口处理，后端数据库操作，没有使用任何框架。

系统采用简单的表单提交方式实现增删改查功能，而非使用 AJAX 技术。同时，使用会话管理机制来维护用户的登录状态，而不是采用 JWT，确保了用户安全。

与原有项目结构相比，本项目首先实现了模块化设计，尽可能做到前后端分离。通过解耦合路由，减少了代码量，提高了前端的可维护性和后端的开发效率。此外，配置文件也进行了解耦合处理。 

页面布局文件被分开存放，以便于复用，并保持统一的风格。要求实现的所有增删改查功能集中在同一页面中，方便管理，避免了频繁跳转到不同页面的操作，更符合用户的逻辑使用习惯。

## 技术架构

### 1. 路由系统
- **自定义路由管理**：
  - 在`utils/route.php`中实现路由管理
  - 使用统一的路由前缀`/ems`
  - 支持路由名称到URL的映射
  - 提供`getRoute()`函数获取路由URL
  - 支持路由参数传递
  - 实现了路由重定向功能

### 2. 会话管理
- **Session处理**：
  - 用户认证状态维护
  - 登录信息存储（用户名、ID）
  - 错误消息的临时存储
  - 安全退出机制
  - 登录状态检查

### 3. 架构设计
- **目录结构**：
  ```
  ems-php/
  ├── config/             # 配置文件
  │   └── db.php         # 数据库配置
  ├── models/            # 模型层
  │   ├── backend.php    # 业务逻辑
  │   └── crud.php       # CRUD操作
  ├── utils/             # 工具类
  │   └── route.php      # 路由工具
  ├── views/             # 视图层
  │   ├── layouts/       # 布局模板
  │   │   └── master.php # 主布局
  │   └── pages/         # 页面模板
  ├── database.sql       # 数据库结构
  └── index.php          # 入口文件
  ```

- **设计模式**：
  - 采用部分的MVC架构思想
  - 模型层处理业务逻辑
  - 视图层负责界面展示
  - 控制器通过路由分发请求

### 4. 数据库设计
- **表结构**：
  - `EMP`：员工信息表
  - `LOGIN`：用户账号表

- **配置方式**：
  ```php
  // config/db.php
  return [
      'database' => [
          'host' => 'localhost',
          'username' => 'root',
          'password' => '',
          'dbname' => 'abc_company'
      ]
  ];
  ```

## 启动说明

### 1. 环境要求
- PHP
- MySQL
- PHP内置服务器
- 有轻微可能会产生版本错误

### 2. 安装步骤

1. **克隆项目**：
   ```bash
   git clone [项目地址]
   cd ems-php
   ```

2. **导入数据库**：
   ```bash
   # 创建数据库
   mysql -u root -p
   CREATE DATABASE abc_company;
   exit;
   
   # 导入数据结构
   mysql -u root -p abc_company < database.sql
   ```

3. **配置数据库**：
   - 修改`config/db.php`为自己使用的数据库信息 
### 3. 如何启动
1. **使用启动脚本**：
  直接启动start.bat

2. **启动服务器**：
   ```bash
   # 在项目根目录运行
   php -S localhost:8080
   ```

3. **访问系统**：
   - 打开浏览器访问：`http://localhost:8080/ems/login`
   - 默认账号：
     - 用户名：TOM
     - 密码：123456

## 功能特性

### 1. 用户管理
- 用户注册和登录
- 个人资料查看
- Session状态管理

### 2. 员工管理
- 员工信息的CRUD操作
- 员工搜索（支持编号、姓名、职位）
- 表单验证和错误处理

### 3. 界面设计
- 响应式布局
- 极简主义设计
- 统一的视觉风格
- 友好的用户交互

## 技术亮点

### 1. 路由解耦
- 集中管理所有路由
- 支持路由命名和参数
- 灵活的URL生成机制

### 2. 视图模板
- 可复用的布局系统
- 统一的错误处理
- 清晰的代码组织

## 开发规范

### 1. 代码规范
- 清晰的代码注释
- 统一的命名规则

### 2. 文件组织
- 模块化的目录结构
- 清晰的文件命名
- 逻辑分层明确

---

# English Version

# EMS (Employee Management System)

This project is a simple employee management system based on PHP, providing CRUD (Create, Read, Update, Delete) functionality for employee information. To simplify the development process, all backend-related logic is centralized rather than using a layered design.

The system implements CRUD functionality using simple form submissions rather than AJAX technology. It uses session management to maintain user login status instead of JWT (JSON Web Token).

To simplify development, the project doesn't fully adopt the MVC pattern, with all frontend-backend interaction interfaces and backend processing logic centralized in the backend folder.

Compared to the original project structure, this project first implements a modular design, aiming for maximum frontend-backend separation. By decoupling the routing, code volume is reduced, improving frontend maintainability and backend development efficiency. Additionally, configuration files are also decoupled.

Page layouts are stored separately for reusability while maintaining a consistent style. All CRUD functionality is centralized on a single page for easier management, avoiding frequent page transitions and aligning better with user logic.

## Technical Architecture

### 1. Routing System
- **Custom Route Management**:
  - Route management implemented in `utils/route.php`
  - Uses unified route prefix `/ems`
  - Supports route name to URL mapping
  - Provides `getRoute()` function for URL retrieval
  - Supports route parameters
  - Implements route redirection

### 2. Session Management
- **Session Handling**:
  - User authentication state maintenance
  - Login information storage (username, ID)
  - Temporary error message storage
  - Secure logout mechanism
  - Login status verification

### 3. Architecture Design
- **Directory Structure**:
  ```
  ems-php/
  ├── config/             # Configuration files
  │   └── db.php         # Database config
  ├── models/            # Model layer
  │   ├── backend.php    # Business logic
  │   └── crud.php       # CRUD operations
  ├── utils/             # Utilities
  │   └── route.php      # Routing tool
  ├── views/             # View layer
  │   ├── layouts/       # Layout templates
  │   │   └── master.php # Main layout
  │   └── pages/         # Page templates
  ├── database.sql       # Database structure
  └── index.php          # Entry file
  ```

- **Design Pattern**:
  - Partial MVC architecture
  - Model layer handles business logic
  - View layer manages interface display
  - Controller routes requests

### 4. Database Design
- **Table Structure**:
  - `EMP`: Employee information table
  - `LOGIN`: User account table

- **Configuration**:
  ```php
  // config/db.php
  return [
      'database' => [
          'host' => 'localhost',
          'username' => 'root',
          'password' => '',
          'dbname' => 'abc_company'
      ]
  ];
  ```

## Setup Instructions

### 1. Requirements
- PHP
- MySQL
- PHP built-in server
- Minor version compatibility issues possible

### 2. Installation Steps

1. **Clone Project**:
   ```bash
   git clone [project-url]
   cd ems-php
   ```

2. **Import Database**:
   ```bash
   # Create database
   mysql -u root -p
   CREATE DATABASE abc_company;
   exit;
   
   # Import structure
   mysql -u root -p abc_company < database.sql
   ```

3. **Configure Database**:
   - Modify `config/db.php` with your database information

4. **Start Server**:
   ```bash
   # Run in project root
   php -S localhost:8080
   ```

5. **Access System**:
   - Open browser: `http://localhost:8080/ems/login`
   - Default account:
     - Username: TOM
     - Password: 123456

## Features

### 1. User Management
- User registration and login
- Profile viewing
- Session state management

### 2. Employee Management
- Employee information CRUD operations
- Employee search (by ID, name, position)
- Form validation and error handling

### 3. Interface Design
- Responsive layout
- Minimalist design
- Consistent visual style
- User-friendly interaction

## Technical Highlights

### 1. Route Decoupling
- Centralized route management
- Route naming and parameters
- Flexible URL generation

### 2. View Templates
- Reusable layout system
- Unified error handling
- Clear code organization

## Development Standards

### 1. Code Standards
- Clear code comments
- Consistent naming conventions

### 2. File Organization
- Modular directory structure
- Clear file naming
- Distinct logical layers
