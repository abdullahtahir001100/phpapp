    <?php


class Database
{
    private $conn;

    // ==============================
    // DATABASE CONFIGURATION
    // ==============================
    private $servername = "uzgoah.stackhero-network.com";
    private $username   = "root";
    private $password   = "YOUR_DATABASE_PASSWORD";
    private $port       = 7406;

    // ==============================
    // Constructor connects to DB
    // ==============================
    public function __construct($databaseName)
    {
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
            die("Connection failed: " . $this->conn->connect_error);
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


header('Content-Type: application/json');

$db = new Database("database");

// echo $_POS-T["camond"];


// department


if (isset($_POST["command"]) && $_POST["command"] == "add_department_type") {
    // $date ="";
    if(isset($_POST["department_id"]) && !empty($_POST["department_id"])){
        
        $id = $_POST["department_id"];
        
        $data = [
            "department" => $_POST["department_name"],
            "status"     => $_POST["status"]
            ];
            // print_r($data) ;
            // die();
            
            $update =   $db->update($id,$data,"departments");
            if ($update) {
                echo json_encode([
                    "status"  => "success",
                    "message" => "Department updated successfully"
                ]);
            } else {
                echo json_encode([
                    "status"  => "error",
                    "message" => "updation failed"
                ]);
                }
                exit();
                }
                        
                        
    if (!empty($_POST["department_name"]) && isset($_POST["status"])) {

        $data = [
            "department" => $_POST["department_name"],
            "status"     => $_POST["status"]
            ];
            
            $insert = $db->insert($data, "departments");

        if ($insert) {
            echo json_encode([
                "status"  => "success",
                "message" => "Department added successfully"
            ]);
        } else {
            echo json_encode([
                "status"  => "error",
                "message" => "Insert failed"
            ]);
        }

    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Required fields missing"
        ]);
    }

}

// allowances 

if (isset($_POST["command"]) && $_POST["command"] == "add_allowance_type") {
    // $date ="";
    if(isset($_POST["allowance_id"]) && !empty($_POST["allowance_id"])){
        
        $id = $_POST["allowance_id"];
        
        $data = [
            "department_allowances" => $_POST["allowance_name"],
            "allowance_value"     => $_POST["allowance_value"]
            ];
            // print_r($data) ;
            // die();
            
            $update =   $db->update($id,$data,"departments_allowance");
            if ($update) {
                echo json_encode([
                    "status"  => "success",
                    "message" => "Department updated successfully"
                ]);
            } else {
                echo json_encode([
                    "status"  => "error",
                    "message" => "updation failed"
                ]);
                }
                exit();
                }
                        
                        
    if (!empty($_POST["allowance_name"]) && isset($_POST["allowance_value"])) {

        $data = [
            "department_allowances" => $_POST["allowance_name"],
            "allowance_value"     => $_POST["allowance_value"]
            ];
            
            $insert = $db->insert($data, "departments_allowance");

        if ($insert) {
            echo json_encode([
                "status"  => "success",
                "message" => "Department added successfully"
            ]);
        } else {
            echo json_encode([
                "status"  => "error",
                "message" => "Insert failed"
            ]);
        }

    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Required fields missing"
        ]);
    }

}

// deducations


if (isset($_POST["command"]) && $_POST["command"] == "add_deducation_type") {
    // $date ="";
    if(isset($_POST["deducation_id"]) && !empty($_POST["deducation_id"])){
        
        $id = $_POST["deducation_id"];
        
        $data = [
            "department_deducations" => $_POST["deducation_name"],
            "deducation_value"     => $_POST["deducation_value"]
            ];
            // print_r($data) ;
            // die();
            
            $update =   $db->update($id,$data,"departments_deducation");
            if ($update) {
                echo json_encode([
                    "status"  => "success",
                    "message" => "Department updated successfully"
                ]);
            } else {
                echo json_encode([
                    "status"  => "error",
                    "message" => "updation failed"
                ]);
                }
                exit();
                }
                        
                        
    if (!empty($_POST["deducation_name"]) && isset($_POST["deducation_value"])) {

        $data = [
            "department_deducations" => $_POST["deducation_name"],
            "deducation_value"     => $_POST["deducation_value"]
            ];
            // print_r($data);
            // die();
            
            $insert = $db->insert($data, "departments_deducation");

        if ($insert) {
            echo json_encode([
                "status"  => "success",
                "message" => "Department added successfully"
            ]);
        } else {
            echo json_encode([
                "status"  => "error",
                "message" => "Insert failed"
            ]);
        }

    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Required fields missing"
        ]);
    }

}

// designation 

if (isset($_POST["command"]) && $_POST["command"] == "add_designation_type") {
    // $date ="";
    if(isset($_POST["designation_id"]) && !empty($_POST["designation_id"])){
        
        $id = $_POST["designation_id"];
        
        $data = [
            "designation" => $_POST["designation_name"],
            "department_id"     => $_POST["department_id"]
            ];
            // print_r($data) ;
            // die();
            
            $update =   $db->update($id,$data,"designation");
            if ($update) {
                echo json_encode([
                    "status"  => "success",
                    "message" => "Department updated successfully"
                ]);
            } else {
                echo json_encode([
                    "status"  => "error",
                    "message" => "updation failed"
                ]);
                }
                exit();
                }
                        
                        
    if (!empty($_POST["designation_name"]) && isset($_POST["department_id"])) {

        $data = [
            "designation" => $_POST["designation_name"],
            "department_id"     => $_POST["department_id"]
            ];
            // print_r($data);
            // die();
            
            $insert = $db->insert($data, "designation");

        if ($insert) {
            echo json_encode([
                "status"  => "success",
                "message" => "Department added successfully"
            ]);
        } else {
            echo json_encode([
                "status"  => "error",
                "message" => "Insert failed"
            ]);
        }

    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Required fields missing"
        ]);
    }

}

// employees 

if (isset($_POST["command"]) && $_POST["command"] == "add_employee") {  
    $allowances = $_POST["allowances"] ?? "";
    $deductions = $_POST["deducations"] ?? "";
    //  print_r($deductions);
    //  die();
    // 1. Prepare the data array from $_POST
    // Note: Use json_encode for arrays so they can be stored in a single text/json column
    $data = [
        "first_name"     => $_POST["first_name"] ?? '',
        "last_name"      => $_POST["last_name"] ?? '',
        "email"          => $_POST["employee_email"] ?? '',
        "phone"          => $_POST["employee_phone"] ?? '',
        "salary"         => $_POST["employee_salary"] ?? 0,
        "department_id"  => $_POST["department_id"] ?? null,
        "designation_id" => $_POST["designation_id"] ?? null,
        "residential_address"        => $_POST["employee_address"] ?? '',
    ];

    // 2. CHECK FOR UPDATE (If ID exists)
    if (isset($_POST["employee_id"]) && !empty($_POST["employee_id"])) {
        $id = $_POST["employee_id"];
        
        // die($id);
        $update = $db->update($id, $data, "employees");
        $delete_allo = $db->deleteWhere(  "employees_allowances" , "main_id=$id");
        $delete_ded = $db->deleteWhere( "employees_deducations","main_id=$id");
        if(isset($allowances) && !empty($allowances)){ 

                    foreach ($allowances as $allowance) {
                                $data = [
                                    "main_id" => $id,
                                    "allowance_id" => $allowance
                                ];
                                $insert_allo = $db->insert($data,"employees_allowances");
                                    
                        }
                    
                    }
                // print_r($deductions);
        if(isset($deductions) && !empty($deductions)){
        foreach ($deductions as $deduction) {
            $data = [
                "main_id" => $id,
                "deducation_id" => $deduction
            ];
        }
            $insert_ded = $db->insert($data,"employees_deducations");
        }
        if ($update && $delete_allo && $delete_ded) {
            echo json_encode([
                "status"  => "success",
                "message" => "Employee updated successfully"
            ]);
        } else {
            echo json_encode([
                "status"  => "error",
                "message" => "Update failed"
            ]);
        }
        exit();
    }

    // 3. CHECK FOR INSERT (New Employee)
    // Validate required fields
    if (!empty($_POST["first_name"]) && !empty($_POST["employee_email"])) {
        
        $insert = $db->insert($data, "employees");
        if(isset($allowances) && !empty($allowances)){ 
        
            foreach ($allowances as $allowance) {
                $data = [
                    "main_id" => $insert,
                    "allowance_id" => $allowance
                ];
                $insert_allo = $db->insert($data,"employees_allowances");

            }
        }
        if(isset($deductions) && !empty($deductions)){
        foreach ($deductions as $deduction) {
            $data = [
                "main_id" => $insert,
                "deducation_id" => $deduction
            ];

            $insert_ded = $db->insert($data,"employees_deducations");
        }
        }
        if ($insert) {
            echo json_encode([
                "status"  => "success",
                "message" => "Employee added successfully"
            ]);
        } else {
            echo json_encode([
                "status"  => "error",
                "message" => "Insertion failed"
            ]);
        }
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Required fields (Name and Email) are missing"
        ]);
    }
}

// question 



if (isset($_POST["command"]) && $_POST["command"] == "add_question") {

    // Handle Edit/Delete existing if desgnation_id is provided
    if (isset($_POST['desgnation_id']) && (!empty($_POST['desgnation_id']))) {
        $id = $_POST['desgnation_id'];
        $db->deleteWhere("question_acr", "designation_id = $id");
    }

    $all_success = true;

    if (isset($_POST['questions']) && is_array($_POST['questions'])) {
        
        foreach ($_POST['questions'] as $key => $question_text) {
            
            if (empty(trim($question_text))) continue;

            $rating_value = $_POST['ratings'][$key] ?? 10; 

            $data = [
                "department_id"  => $_POST["question_dept"] ?? '',
                "designation_id" => $_POST["question_desig"] ?? '',
                "question"       => htmlspecialchars($question_text),
                "rating"         => intval($rating_value),
                "main_id"        => 0 // Main questions have 0 as parent
            ]; 

            $parent_id = $db->insert($data, "question_acr");

            if (!$parent_id) {
                $all_success = false;
                continue; 
            }

            // CHECK FOR SUB-QUESTIONS BELONGING TO THIS SPECIFIC MAIN QUESTION
            if (isset($_POST['sub_questions'][$key]) && is_array($_POST['sub_questions'][$key])) {
                
                foreach ($_POST['sub_questions'][$key] as $sub_key => $sub_question_text) {
                    
                    // Now $sub_question_text IS a string, so trim() works!
                    if (empty(trim($sub_question_text))) continue;

                    $sub_rating_value = $_POST['sub_ratings'][$key][$sub_key] ?? 10; 

                    $sub_data = [
                        "main_id"        => $parent_id,
                        "department_id"  => $_POST["question_dept"] ?? '',
                        "designation_id" => $_POST["question_desig"] ?? '',
                        "question"       => htmlspecialchars($sub_question_text),
                        "rating"         => intval($sub_rating_value),
                    ]; 
                    
                    $db->insert($sub_data, "question_acr");
                }
            }
        }

        echo json_encode([
            "status"  => $all_success ? "success" : "error",
            "message" => $all_success ? "Evaluation saved successfully" : "Some errors occurred"
        ]);
        exit;
    }
}
    
// Assuming you have already included your Database class and initialized it


// Get the JSON payload
$json = file_get_contents('php://input');
$request = json_decode($json, true);

if (isset($request["command"]) && $request["command"] == "Add_bonus&fine") {
    if($request['update_id'] && !empty($request['update_id'])){
        $id = $request['update_id'];
        $db->deleteWhere("bonuses_details", "bonus_id = $id");
        // echo $id;
    if ($id) {
        $employees = $request['data'];
        $successCount = 0;

        // 3. Loop through employees to insert into Details table
        foreach ($employees as $emp) {
            // Only insert if there's actually a value (optional check)
            if ($emp['bonus'] > 0 || $emp['fine'] > 0) {
                
                $detailData = [
                    "bonus_id"    => $id, // Using the ID from the first insert
                    "employee_id" => $emp['employee_id'],
                    "bonus_amount" => $emp['bonus'],
                    "fine_amount"  => $emp['fine']
                ];

                $db->insert($detailData, "bonuses_details");
                $successCount++;
            }
        }

        echo json_encode([
            "success" => true,
            "message" => "Successfully updated with $successCount employee records."
        ]);
        return; // Exit after processing the update
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to create payroll header."
        ]);
        return; // Exit after processing the update
    }
    }
    // 1. Prepare Header Data
// Use the null coalescing operator (??) to default to 0 or null
    $headerData = [
        "department_id"  => (int)($request['selected_department_id'] ?? 0), 
        "selection_mode" => $request['selection_mode'] ?? '',
        "month"          => $request['month'] ?? '',
        "year"           => $request['year'] ?? '',
        "type"           => $request['type'] ?? ''
    ];
    // echo json_encode($headerData);
    
    // 2. Insert into Header table (payroll_bonuses)
    // Your class insert() method returns the auto-increment ID
    $last_inserted_id = $db->insert($headerData, "payroll_bonuses");

    if ($last_inserted_id) {
        $employees = $request['data'];
        $successCount = 0;

        // 3. Loop through employees to insert into Details table
        foreach ($employees as $emp) {
            // Only insert if there's actually a value (optional check)
            if ($emp['bonus'] > 0 || $emp['fine'] > 0) {
                
                $detailData = [
                    "bonus_id"    => $last_inserted_id, // Using the ID from the first insert
                    "employee_id" => $emp['employee_id'],
                    "bonus_amount" => $emp['bonus'],
                    "fine_amount"  => $emp['fine']
                ];

                $db->insert($detailData, "bonuses_details");
                $successCount++;
            }
        }

        echo json_encode([
            "success" => true,
            "message" => "Successfully saved with $successCount employee records."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to create payroll header."
        ]);
    }
}