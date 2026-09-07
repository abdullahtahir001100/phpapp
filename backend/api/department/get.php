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
        $this->password = getenv('DB_PASSWORD') ?: 'zukBywBtRPTqfrwFTTqv2lPYG1cjKaPn';
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


header('Content-Type: application/json');

$db = new Database("root");



// departments 

if (isset($_POST["command"]) && $_POST["command"] === "show_department_type") {
$data = $db->selectAll("departments");
if($data){

    $data = json_encode(['data' =>$data, 'success' => true]); 
}else{
    $data = json_encode(['data'=> '','success'=> false]);
}
print_r($data);
}



if (isset($_GET["command"]) && $_GET["command"] === "update_department") {

    $id = ($_GET["id"]); // security: force integer
$data = $db->selectAllWhere("departments", "id = '$id'");
 echo json_encode(["data"=>$data,"success"=> true]);
    // $data = json_encode(["data"=>$data,"success"=> true]);
// //     exit;
}



// allowances 

if (isset($_POST["command"]) && $_POST["command"] === "show_allowance_type") {
$data = $db->selectAll("departments_allowance");
if($data){

    $data = json_encode(['data' =>$data, 'success' => true]); 
}else{
    $data = json_encode(['data'=> '','success'=> false]);
}
print_r($data);
}

if (isset($_GET["command"]) && $_GET["command"] === "update_allowance") {

    $id = ($_GET["id"]); // security: force integer
$data = $db->selectAllWhere("departments_allowance", "id = '$id'");
//  $data = $data[0];
// print_r($data); 
 echo json_encode(["data"=>$data,"success"=> true]);
    // $data = json_encode(["data"=>$data,"success"=> true]);
// //     exit;
}


// deducations 
if (isset($_POST["command"]) && $_POST["command"] === "show_deducation_type") {
$data = $db->selectAll("departments_deducation");
if($data){

    $data = json_encode(['data' =>$data, 'success' => true]); 
}else{
    $data = json_encode(['data'=> '','success'=> false]);
}
print_r($data);
}

if (isset($_GET["command"]) && $_GET["command"] === "update_deducation") {

    $id = ($_GET["id"]); // security: force integer
$data = $db->selectAllWhere("departments_deducation", "id = '$id'");
//  $data = $data[0];
// print_r($data); 
 echo json_encode(["data"=>$data,"success"=> true]);
    // $data = json_encode(["data"=>$data,"success"=> true]);
// //     exit;
}

// designation

if (isset($_POST["command"]) && $_POST["command"] === "show_designation_type") {
$data = $db->rawQuery("SELECT des.*,departments.department  FROM designation des LEFT JOIN departments ON des.department_id = departments.id");

if($data){

    $data = json_encode(['data' =>$data, 'success' => true]); 
}else{
    $data = json_encode(['data'=> '','success'=> false]);
}
print_r($data);
}

if (isset($_GET["command"]) && $_GET["command"] === "update_designation") {

    $id = ($_GET["id"]); // security: force integer
$data = $db->rawQuery("SELECT des.*,departments.department, departments.id AS dep_id FROM designation des LEFT JOIN departments ON des.department_id = departments.id WHERE des.id = $id");
//  $data = $data[0];
// print_r($data); 
 echo json_encode(["data"=>$data,"success"=> true]);
    // $data = json_encode(["data"=>$data,"success"=> true]);
// //     exit;
}    

if (isset($_POST["command"]) && $_POST["command"] === "show_desig_type") {
    $id =$_POST["id"];
// $data = $db->rawQuery("SELECT des.*,departments.department  FROM designation des LEFT JOIN departments ON des.department = departments.id");
    $data = $db->selectAllWhere("designation","department_id = $id ");
if($data){

    $data = json_encode(['data' =>$data, 'success' => true]); 
}else{
    $data = json_encode(['data'=> '','success'=> false]);
}
print_r($data);
}


if (isset($_POST["command"]) && $_POST["command"] === "show_amounts") {
    
    // 1. Initialize the main response structure
    $final_output = [
        'success'    => true,
        'allowance'  => $db->selectAll("departments_allowance") ?: [],
        'deducation' => $db->selectAll("departments_deducation") ?: [],
        'employees'  => [] // We will fill this list
    ];

    // 2. Run your main query
    $employees = $db->rawQuery("SELECT emp.*, dep.department, des.designation 
                                FROM employees emp 
                                LEFT JOIN departments dep ON emp.department_id = dep.id 
                                LEFT JOIN designation des ON des.id = emp.designation_id");
    // print_r($employees);
    // die();

    if ($employees) {
        foreach ($employees as $employee) {
            // Fetch individual allowances/deductions
            $emp_allowances = $db->selectAllWhere("employees_allowances", "main_id = " . $employee['id']);
            $emp_deducations = $db->selectAllWhere("employees_deducations", "main_id = " . $employee['id']);

            // 3. Push this employee into the 'employees' array
            $final_output['employees'][] = [
                "id"                  => $employee["id"],
                "first_name"          => $employee["first_name"],
                "last_name"           => $employee["last_name"],
                "email"               => $employee["email"],
                "phone"               => $employee["phone"],
                "salary"              => $employee["salary"],
                "department"       => $employee["department"],
                "designation"      => $employee["designation"],
                "residential_address" => $employee["residential_address"],
                "designation_id" => $employee["designation_id"],
                "allowance"           => $emp_allowances ?: [],
                "deducation"          => $emp_deducations ?: [],
            ];
        }
    } else {
        $final_output['success'] = false;
    }

    // 4. Output everything ONCE
    header('Content-Type: application/json');
    echo json_encode($final_output);
    exit; // Stop execution to prevent any other text from leaking out
}    


if (isset($_GET["command"]) && $_GET["command"] === "update_employees") {
    $id = $_GET["id"] ?? '0';
    $final_output = [
        "success"=> true,
        'employees'  => [] // We will fill this list
    ];

    // 2. Run your main query
    $employees = $db->rawQuery("SELECT emp.*, dep.department, des.designation 
                                FROM employees emp 
                                LEFT JOIN departments dep ON emp.department_id = dep.id 
                                LEFT JOIN designation des ON des.id = emp.designation_id WHERE emp.id = $id");
    // print_r($employees);
    // die();

    if ($employees) {
        foreach ($employees as $employee) {
            // Fetch individual allowances/deductions
            $emp_allowances = $db->selectAllWhere("employees_allowances", "main_id = $id");
            $emp_deducations = $db->selectAllWhere("employees_deducations", "main_id = $id");

            // 3. Push this employee into the 'employees' array
        $final_output['employees'][] = [
                "id"                  => $employee["id"],
                "first_name"          => $employee["first_name"],
                "last_name"           => $employee["last_name"],
                "email"               => $employee["email"],
                "phone"               => $employee["phone"],
                "salary"              => $employee["salary"],
                "department_id"       => $employee["department_id"],
                "designation_id"      => $employee["designation_id"],
                "residential_address" => $employee["residential_address"],
                "allowance"           => $emp_allowances ?: [],
                "deducation"          => $emp_deducations ?: [],
            ];
        }
    } else {
        $final_output['success'] = false;
    }


    
    echo json_encode($final_output);
    exit; // Stop execution to prevent any other text from leaking out
}



if (isset($_POST["command"]) && $_POST["command"] === "show_question_depts") {

    $question_depts = $db->rawQuery("SELECT que.department_id, que.designation_id, dep.department, des.designation FROM question_acr que LEFT JOIN  departments dep ON que.department_id = dep.id LEFT JOIN designation des ON que.designation_id  = des.id ");
    echo  json_encode(['data' =>$question_depts, 'success' => true]); 
    exit;
    // echo"sdsdssdsd";
}

if (isset($_GET["command"]) && $_GET["command"] === "update_question") {
      $id = $_GET["id"];
// print_r("id is".$id);

      $question = $db->selectAllWhere("question_acr", "designation_id = $id");
    // echo"sdsdssdsd";
    echo  json_encode(['data' =>$question, 'success' => true]); 
    exit;
}    


if (isset($_POST["command"]) && $_POST["command"] === "mark_question") {

    $id = $_POST["emp_id"];
    $question_emp_det = $db->rawQuery("SELECT emp.*, dep.department, des.designation FROM employees emp LEFT JOIN  departments dep ON emp.department_id = dep.id LEFT JOIN designation des ON emp.designation_id  = des.id WHERE emp.id = $id");
    // LEFT JOIN  question_acrquestion_emp_det que ON que.desgnation_id = des.id
    // $des_id = $question_emp_det[0]["designation_id"];
    $main_id = $question_emp_det[0]["designation_id"];
    $emp_allowances = $db->selectAllWhere("employees_allowances", "main_id = $id");
    $emp_deducations = $db->selectAllWhere("employees_deducations", "main_id = $id");
    $emp_allowances_id = $emp_allowances[0]["allowance_id"];
    $emp_deducations_id = $emp_deducations[0]["deducation_id"];
     $des_id = $question_emp_det[0]["designation_id"];
    $allowances = $db->selectAllWhere("departments_allowance","id = $emp_allowances_id");
    $deducations = $db->selectAllWhere("departments_deducation","id = $emp_deducations_id");
    $emp_que = $db->rawQuery("SELECT que.* FROM question_acr que WHERE que.designation_id = $des_id");
    // echo  json_encode(['data' =>$emp_que, 'success' => true]); 
    echo  json_encode(['data' =>$question_emp_det,'question' => $emp_que, 'allowances' => $allowances, 'deducations' => $deducations , 'success' => true]); 
  
    exit;
    // echo"sdsdssdsd";
}

if (isset($_POST["command"]) && $_POST["command"] === "show_bonus_employees") {
    ob_clean();
    $update_id = $_POST["update_id"] ?? null; // Get the update_id from POST data if available
    // 1. Fetch all employees (You can add your WHERE clause here later if filtering by dept)
    $employees = $db->rawQuery("
        SELECT emp.id AS empid , emp.*, dep.department, des.designation 
        FROM employees emp 
        LEFT JOIN departments dep ON emp.department_id = dep.id 
        LEFT JOIN designation des ON emp.designation_id = des.id 
    ");

    // 2. ONE Single loop to grab all related data per employee
    foreach ($employees as $key => $emp) {
        $emp_id = $emp['id'];
            if ($update_id) {
                // If update_id is provided, fetch payroll bonuses for that specific bonus ID
                $payroll_bonuses_details = $db->selectAllWhere("payroll_bonuses", "id = $update_id");
            $employees[$key]['payroll_bonuses_details'] = $payroll_bonuses_details;
            $employees[$key]['bonuses_details'] = $db->selectAllWhere("bonuses_details", "bonus_id = $update_id");
            }
        // Get Allowances
        $allowances = $db->rawQuery("
            SELECT f.allowance_id, d.department_allowances, d.allowance_value 
            FROM employees_allowances f 
            LEFT JOIN departments_allowance d ON f.allowance_id = d.id 
            WHERE f.main_id = $emp_id
        ");
        $employees[$key]['allowances'] = $allowances;

        // Get Deductions
        $deducation = $db->rawQuery("
            SELECT d.deducation_id, dl.department_deducations, dl.deducation_value 
            FROM employees_deducations d 
            LEFT JOIN departments_deducation dl ON d.deducation_id = dl.id 
            WHERE d.main_id = $emp_id
        ");
        $employees[$key]['deducation'] = $deducation;

        // Get Payroll Bonuses & Details
        $payroll_data = $db->selectAllWhere("bonuses_details","employee_id = $emp_id");
        $employees[$key]['payroll_fine_bonus'] = $payroll_data;
    }

    echo json_encode([
        'success' => true,
        'data' => $employees
    ]);
    exit;
}

if (isset($_POST["command"]) && $_POST["command"] === "show_payroll") {
$payroll = $db->rawQuery("SELECT pay.*, dep.department,emp.first_name,emp.last_name FROM payroll_bonuses pay LEFT JOIN  departments dep ON pay.department_id = dep.id LEFT JOIN  employees emp ON pay.selection_mode = emp.id");
foreach($payroll as $key => $pay)
    {
        $id = $pay["id"];
        $bonuses_details = $db->selectAllWhere("bonuses_details", "bonus_id = $id");
        $payroll[$key]['bonuses_details'] = $bonuses_details;
    }
 echo  json_encode(['data' =>$payroll, 'success' => true]);
}
if (isset($_POST["command"]) && $_POST["command"] === "update_bonus") {     
$id = $_POST["id"];


$update_bonus_detail  = $db->rawQuery("SELECT emp.*, dep.department, des.designation FROM employees emp LEFT JOIN  departments dep ON emp.department_id = dep.id LEFT JOIN designation des ON emp.designation_id  = des.id");

foreach($update_bonus_detail as $key => $details){
    $update_bonus_detail [$key] ['bonuses_details'] = $db->rawQuery("SELECT bon_det.*, emp.first_name, emp.last_name, emp.salary, dep.department, des.designation FROM bonuses_details AS bon_det LEFT JOIN employees AS emp ON emp.id = bon_det.employee_id LEFT JOIN departments AS dep ON emp.department_id = dep.id LEFT JOIN designation AS des ON emp.designation_id = des.id WHERE bon_det.bonus_id = $id");
    $update_bonus_detail [$key] ['payroll_bonuses_details']= $db->selectAllWhere("payroll_bonuses", "id = $id");
}
	 echo  json_encode(['data' =>$update_bonus_detail, 'success' => true]);
}

if (isset($_POST["command"]) && $_POST["command"] === "show_payroll_employees") {

    $payroll_employees = $db->rawQuery("SELECT 
    emp.*,
    dep.department,
    des.designation,

    COALESCE(allowance_totals.total_allowance, 0) AS total_allowance,
    COALESCE(deduction_totals.total_deduction, 0) AS total_deduction,

    COALESCE(bonus_totals.total_bonus, 0) AS total_bonus,
    COALESCE(bonus_totals.total_fine, 0) AS total_fine

FROM employees emp

LEFT JOIN departments dep 
    ON emp.department_id = dep.id

LEFT JOIN designation des 
    ON emp.designation_id = des.id


/* ================= ALLOWANCES ================= */

LEFT JOIN (
    SELECT 
        ea.main_id AS employee_id,
        SUM(da.allowance_value) AS total_allowance
    FROM employees_allowances ea

    LEFT JOIN departments_allowance da
        ON ea.allowance_id = da.id

    GROUP BY ea.main_id
) allowance_totals
    ON emp.id = allowance_totals.employee_id


/* ================= DEDUCTIONS ================= */

LEFT JOIN (
    SELECT 
        ed.main_id AS employee_id,
        SUM(dd.deducation_value) AS total_deduction
    FROM employees_deducations ed

    LEFT JOIN departments_deducation dd
        ON ed.deducation_id = dd.id

    GROUP BY ed.main_id
) deduction_totals
    ON emp.id = deduction_totals.employee_id


/* ================= BONUS + FINE ================= */

LEFT JOIN (
    SELECT 
        employee_id,
        SUM(bonus_amount) AS total_bonus,
        SUM(fine_amount) AS total_fine
    FROM bonuses_details
    GROUP BY employee_id
) bonus_totals
    ON emp.id = bonus_totals.employee_id;");
    echo  json_encode(['data' =>$payroll_employees, 'success' => true]);
    // print_r( $payroll_employees);



}
?>