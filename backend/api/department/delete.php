<?php
require_once "../../class.php";

header('Content-Type: application/json');

$db = new Database("root");


if (isset($_GET["command"]) && $_GET["command"] === "delete_department") {

    $id = ($_GET["id"]); // security: force integer
        // echo"". $id ."";
    $delete = $db->delete($id, "departments");
    
    if ($delete) {
        echo json_encode([
            "status"  => "success",
            "message" => "Department deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Failed to delete department"
        ]);
    }

    exit;
}

// delete_allowances 

if (isset($_GET["command"]) && $_GET["command"] === "delete_allowances") {

    $id = ($_GET["id"]); // security: force integer
        // echo"". $id ."";
        // die();
    $delete = $db->delete($id, "departments_allowance");
    
    if ($delete) {
        echo json_encode([
            "status"  => "success",
            "message" => "allowance deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Failed to allowance department"
        ]);
    }

    exit;
}


// delete deducation 

if (isset($_GET["command"]) && $_GET["command"] === "delete_deducation") {

    $id = ($_GET["id"]); // security: force integer
        // echo"". $id ."";
        // die();
    $delete = $db->delete($id, "departments_deducation");
    
    if ($delete) {
        echo json_encode([
            "status"  => "success",
            "message" => "allowance deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Failed to allowance department"
        ]);
    }

    exit;
}


if (isset($_GET["command"]) && $_GET["command"] === "delete_employees") {

    $id = ($_GET["id"]); // security: force integer
        // echo"". $id ."";
        // die();
    $delete = $db->delete($id, "employees");
    $delete_allo = $db->deleteWhere( "employees_allowances" , "main_id = $id");
    $delete_ded = $db->deleteWhere("employees_deducations" , "main_id = $id");
    
    if ($delete && $delete_allo && $delete_ded) {
        echo json_encode([
            "status"  => "success",
            "message" => "employees deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Failed to employees department"
        ]);
    }

    exit;
}


if (isset($_GET["command"]) && $_GET["command"] === "delete_question") {

    $id = ($_GET["id"]); // security: force integer
        // echo"". $id ."";
        // die();
    $delete = $db->deleteWhere( "question_acr","designation_id = $id");

    
    if ($delete) {
        echo json_encode([
            "status"  => "success",
            "message" => "question deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Failed to question department"
        ]);
    }

    exit;
}

if (isset($_GET["command"]) && $_GET["command"] === "delete_bonus") {

    $id = ($_GET["id"]); // security: force integer
        // echo"". $id ."";
        // die();
    $delete = $db->delete($id,"payroll_bonuses");
    $delete_details = $db->deleteWhere("bonuses_details","bonus_id = $id");

    
    if ($delete && $delete_details) {
        echo json_encode([
            "status"  => "success",
            "message" => "bonus deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Failed to bonus department"
        ]);
    }

    exit;
}


?>