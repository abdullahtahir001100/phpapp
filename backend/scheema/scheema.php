<?php


class Database
{
    private $conn;

    // ==============================
    // DATABASE CONFIGURATION
    // ==============================
    private $servername;
    private $username;
    private $password;
    private $port;

    // ==============================
    // Constructor connects to DB
    // ==============================
    public function __construct($databaseName)
    {
        $this->servername = getenv('DB_HOST') ?: 'uzgoah.stackhero-network.com';
        $this->username = getenv('DB_USERNAME') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->port = (int)(getenv('DB_PORT') ?: 7406);

        $this->conn = mysqli_init();

        // Connect using SSL and custom port
        $connected = $this->conn->real_connect(
            $this->servername,
            $this->username,
            $this->password,
            $databaseName,
            $this->port,
            NULL,
            MYSQLI_CLIENT_SSL
        );

        if (!$connected) {
            http_response_code(500);
            die(json_encode([
                'success' => false,
                'message' => 'Database connection failed'
            ]));
        }

        // UTF-8 support
        $this->conn->set_charset("utf8mb4");
    }


    // ==============================
    // SELECT TABLE
    // ==============================
    public function selectTable($tablename)
    {
        $sql = "SELECT * FROM `$tablename`";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {

            $columns = array_keys(mysqli_fetch_assoc($result));
            mysqli_data_seek($result, 0);

            echo "<tbody>";

            // Foreign key mappings
            $foreignKeys = [
                "vendor"   => ["users", "name"],
                "customer" => ["users", "name"],
                "item"     => ["items", "name"]
            ];

            while ($row = mysqli_fetch_assoc($result)) {

                echo "<tr>";

                foreach ($columns as $col) {

                    if (isset($foreignKeys[$col])) {

                        [$table, $colName] = $foreignKeys[$col];

                        $id = $row[$col];

                        $value = $this->selectOneWhere(
                            $table,
                            $colName,
                            "id='" . mysqli_real_escape_string($this->conn, $id) . "'"
                        );

                        echo "<td>" .
                            htmlspecialchars($value ?? $id) .
                            "</td>";

                    } else {

                        echo "<td>" .
                            htmlspecialchars($row[$col]) .
                            "</td>";
                    }
                }

                // Update & Delete buttons
                echo "<td>
                    <a href='" . htmlspecialchars($tablename) . "_view.php?editid=" . intval($row["id"]) . "'
                       class='btn btn-warning btn-sm text-dark btn-update-" . htmlspecialchars($tablename) . "'>
                       ✏️ Update
                    </a>

                    <a href='delete.php?deleteid=" . intval($row["id"]) . "'
                       class='btn btn-danger btn-sm text-dark'>
                       🗑️ Delete
                    </a>
                </td>";

                echo "</tr>";
            }

            echo "</tbody>";

        } else {

            echo "<p class='text-danger'>
                    No records found in table
                    <b>" . htmlspecialchars($tablename) . "</b>.
                  </p>";
        }
    }


    // ==============================
    // SELECT ONE WHERE
    // ==============================
    public function selectOneWhere($table, $column, $condition)
    {
        $sql = "SELECT `$column`
                FROM `$table`
                WHERE $condition
                LIMIT 1";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            return $row[$column];
        }

        return null;
    }


    // ==============================
    // SELECT ALL WHERE
    // ==============================
    public function selectAllWhere($table, $condition)
    {
        $sql = "SELECT *
                FROM `$table`
                WHERE $condition";

        $result = mysqli_query($this->conn, $sql);

        $data = [];

        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }


    // ==============================
    // RAW QUERY
    // ==============================
    public function rawQuery($sql)
    {
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {

            die(
                "Raw Query Error: " .
                mysqli_error($this->conn) .
                " | SQL: " .
                $sql
            );
        }

        if ($result instanceof mysqli_result) {

            $data = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            mysqli_free_result($result);

            return $data;
        }

        return true;
    }


    // ==============================
    // SAFE QUERY
    // ==============================
    public function safeQuery($sql, $params = [], $types = "")
    {
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            die("Statement Error: " . mysqli_error($this->conn));
        }

        if (!empty($params)) {

            $types = $types ?: str_repeat("s", count($params));

            mysqli_stmt_bind_param(
                $stmt,
                $types,
                ...$params
            );
        }

        if (!mysqli_stmt_execute($stmt)) {

            die(
                "Execute Error: " .
                mysqli_stmt_error($stmt)
            );
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result) {

            $data = mysqli_fetch_all(
                $result,
                MYSQLI_ASSOC
            );

            mysqli_free_result($result);

            return $data;
        }

        return mysqli_stmt_affected_rows($stmt);
    }


    // ==============================
    // GET TOTAL QUANTITY
    // ==============================
    public function getTotalQuantity(
        $table,
        $columnToSum,
        $whereColumn,
        $whereValue
    ) {

        $whereValue = mysqli_real_escape_string(
            $this->conn,
            $whereValue
        );

        $sql = "SELECT SUM(`$columnToSum`) AS total
                FROM `$table`
                WHERE `$whereColumn` = '$whereValue'
                GROUP BY `$whereColumn`";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            return (float)$row['total'];
        }

        return 0;
    }


    // ==============================
    // SELECT COLUMN
    // ==============================
    public function selectColumn($table, $column)
    {
        $sql = "SELECT `$column`
                FROM `$table`";

        $result = mysqli_query($this->conn, $sql);

        $data = [];

        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                $data[] = $row[$column];
            }
        }

        return $data;
    }


    // ==============================
    // SELECT ALL
    // ==============================
    public function selectAll($table)
    {
        $sql = "SELECT *
                FROM `$table`";

        $result = mysqli_query($this->conn, $sql);

        $data = [];

        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                $data[] = $row;
            }
        }

        return $data;
    }


    // ==============================
    // SELECT COLUMN WHERE
    // ==============================
    public function selectColumnWhere(
        $table,
        $column,
        $condition
    ) {

        $sql = "SELECT `$column`
                FROM `$table`
                WHERE $condition";

        $result = mysqli_query($this->conn, $sql);

        $data = [];

        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                $data[] = $row[$column];
            }
        }

        return $data;
    }


    // ==============================
    // UPDATE BY ID
    // ==============================
    public function update($Id, $arr, $table)
    {
        $vals = [];

        foreach ($arr as $key => $value) {

            $key = mysqli_real_escape_string(
                $this->conn,
                $key
            );

            $value = mysqli_real_escape_string(
                $this->conn,
                $value
            );

            $vals[] = "`$key`='$value'";
        }

        $cols = implode(",", $vals);

        $Id = intval($Id);

        $sql = "UPDATE `$table`
                SET $cols
                WHERE id = $Id";

        if (mysqli_query($this->conn, $sql)) {

            return true;

        } else {

            echo "Error: " .
                mysqli_error($this->conn);

            return false;
        }
    }


    // ==============================
    // UPDATE WHERE
    // ==============================
    public function updateWhere(
        $table,
        $data,
        $condition
    ) {

        $updateParts = [];

        foreach ($data as $column => $value) {

            $column = mysqli_real_escape_string(
                $this->conn,
                $column
            );

            $value = mysqli_real_escape_string(
                $this->conn,
                $value
            );

            $updateParts[] =
                "`$column` = '$value'";
        }

        $updateString =
            implode(', ', $updateParts);

        $sql = "UPDATE `$table`
                SET $updateString
                WHERE $condition";

        return mysqli_query(
            $this->conn,
            $sql
        );
    }


    // ==============================
    // INSERT
    // ==============================
    public function insert($arr, $table)
    {
        $columns = [];
        $values = [];

        foreach ($arr as $key => $value) {

            $columns[] =
                "`" .
                mysqli_real_escape_string(
                    $this->conn,
                    $key
                ) .
                "`";

            $values[] =
                "'" .
                mysqli_real_escape_string(
                    $this->conn,
                    $value
                ) .
                "'";
        }

        $cols = implode(",", $columns);
        $vals = implode(",", $values);

        $sql = "INSERT INTO `$table`
                ($cols)
                VALUES
                ($vals)";

        if ($this->conn->query($sql) === TRUE) {

            return $this->conn->insert_id;

        } else {

            die(
                "Insert Error: " .
                $this->conn->error
            );
        }
    }


    // ==============================
    // DELETE BY ID
    // ==============================
    public function delete($Id, $table)
    {
        $safeId = intval($Id);

        $sql = "DELETE FROM `$table`
                WHERE `id` = $safeId";

        if (mysqli_query($this->conn, $sql)) {

            return true;

        } else {

            echo "Error: " .
                mysqli_error($this->conn);

            return false;
        }
    }


    // ==============================
    // DELETE WHERE
    // ==============================
    public function deleteWhere(
        $table,
        $condition
    ) {

        $sql = "DELETE FROM `$table`
                WHERE $condition";

        return mysqli_query(
            $this->conn,
            $sql
        );
    }


    // ==============================
    // AUTO GENERATE CODE
    // ==============================
    public function autogencode(
        $table,
        $col,
        $cod
    ) {

        $sql = "SELECT `$col`
                FROM `$table`
                ORDER BY id DESC
                LIMIT 1";

        $result = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$result) {

            die(
                "Query failed: " .
                mysqli_error($this->conn)
            );
        }

        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            $lastCode = $row[$col];

            if (!empty($lastCode)) {

                $numericPart =
                    str_replace(
                        $cod,
                        '',
                        $lastCode
                    );

                $newNumericPart =
                    (int)$numericPart + 1;

                return $cod .
                    str_pad(
                        $newNumericPart,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );
            }
        }

        return $cod . '001';
    }


    // ==============================
    // CLOSE CONNECTION
    // ==============================
    public function close()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }


    // ==============================
    // GET CONNECTION
    // ==============================
    public function getConnection()
    {
        return $this->conn;
    }
}


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