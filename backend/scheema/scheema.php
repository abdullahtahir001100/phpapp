<?php
require_once "../../class.php";

$db = new Database("database");

/**
 * 1. DEPARTMENTS
 */
$query = $db->rawQuery("CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department VARCHAR(100) NOT NULL,
    status ENUM('0','1') NOT NULL DEFAULT '0'   
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo json_encode(["success" => (bool)$query, "message" => $query ? "Table 'departments' created" : "Table 'departments' failed"]);

/**
 * 2. DESIGNATIONS (Created before Employees to satisfy Foreign Key)
 */
$query = $db->rawQuery("CREATE TABLE IF NOT EXISTS designation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    designation VARCHAR(100) NOT NULL,
    department_id INT NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo json_encode(["success" => (bool)$query, "message" => $query ? "Table 'designation' created" : "Table 'designation' failed"]);

/**
 * 3. ALLOWANCES
 */
$query = $db->rawQuery("CREATE TABLE IF NOT EXISTS allowances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    allowance_name VARCHAR(100) NOT NULL,
    allowance_value DECIMAL(10, 2) NOT NULL DEFAULT 0.00   
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo json_encode(["success" => (bool)$query, "message" => $query ? "Table 'allowances' created" : "Table 'allowances' failed"]);

/**
 * 4. DEDUCTIONS
 */
$query = $db->rawQuery("CREATE TABLE IF NOT EXISTS deductions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    deduction_name VARCHAR(100) NOT NULL,
    deduction_value DECIMAL(10, 2) NOT NULL DEFAULT 0.00   
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo json_encode(["success" => (bool)$query, "message" => $query ? "Table 'deductions' created" : "Table 'deductions' failed"]);

/**
 * 5. EMPLOYEES
 */
$query = $db->rawQuery("CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    salary DECIMAL(15, 2) DEFAULT 0.00,
    department_id INT,
    designation_id INT,
    residential_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (designation_id) REFERENCES designation(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo json_encode(["success" => (bool)$query, "message" => $query ? "Table 'employees' created" : "Table 'employees' failed"]);

/**
 * 6. EMPLOYEE RELATIONS (Allowances & Deductions)
 */
$db->rawQuery("CREATE TABLE IF NOT EXISTS employees_allowances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    allowance_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (allowance_id) REFERENCES allowances(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->rawQuery("CREATE TABLE IF NOT EXISTS employees_deductions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    deduction_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (deduction_id) REFERENCES deductions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/**
 * 7. PAYROLL BONUSES & FINES
 */
$db->rawQuery("CREATE TABLE IF NOT EXISTS payroll_bonuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    selection_mode VARCHAR(20), 
    month INT NOT NULL,
    year INT NOT NULL,
    type INT NOT NULL, -- 1: Fine, 2: Bonus, 3: Both
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->rawQuery("CREATE TABLE IF NOT EXISTS bonuses_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bonus_id INT NOT NULL,
    employee_id INT NOT NULL,
    bonus_amount DECIMAL(10,2) DEFAULT 0.00,
    fine_amount DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (bonus_id) REFERENCES payroll_bonuses(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/**
 * 8. QUESTION ACR
 */
$query = $db->rawQuery("CREATE TABLE IF NOT EXISTS question_acr (
    id INT AUTO_INCREMENT PRIMARY KEY,
    main_id INT DEFAULT 0,
    department_id INT NOT NULL,
    designation_id INT NOT NULL,
    question VARCHAR(255) NOT NULL,
    rating INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");



echo json_encode(["success" => (bool)$query, "message" => "Database Setup Complete"]);

/**
 * 9. PAYROLL
 */
$query = $db->rawQuery("CREATE TABLE IF NOT EXISTS payroll (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    department_id INT NULL,
    designation_id INT NULL,
    salary DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    allowance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    deduction DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    bonus DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    fine DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    net_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    month_year VARCHAR(10) NOT NULL,          -- Stores '01-2026' directly from #monthYearInput
    payroll_month INT NOT NULL,                 -- Extracted Month (1 - 12)
    payroll_year INT NOT NULL,                  -- Extracted Year (e.g., 2026)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (designation_id) REFERENCES designation(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo json_encode(["success" => (bool)$query, "message" => $query ? "Table 'payroll' created" : "Table 'payroll' failed"]);
?>