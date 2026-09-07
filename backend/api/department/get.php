<?php
require_once "../../class.php";

header('Content-Type: application/json');

$db = new Database("database");



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