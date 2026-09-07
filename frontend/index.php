<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalist Dashboard with Icons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./node_modules/preline/dist/preline.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline@latest/dist/preline.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/preline@2.7.0/dist/preline.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="d-flex">
        <div class="sidebar-container">
            <div class="sidebar-header">
                System Admin
            </div>

            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="tab-1-btn" data-bs-toggle="pill" data-bs-target="#tab-1"
                    type="button" role="tab">
                    <i class="bi bi-speedometer2"></i> General Info
                </button>
                <button class="nav-link" id="tab-2-btn" data-bs-toggle="pill" data-bs-target="#tab-2" type="button"
                    role="tab">
                    <i class="bi bi-person-gear"></i> Account Settings
                </button>
                <button class="nav-link" id="tab-3-btn" data-bs-toggle="pill" data-bs-target="#tab-3" type="button"
                    role="tab">
                    <i class="bi bi-shield-lock"></i> Security
                </button>
                <button class="nav-link" id="tab-4-btn" data-bs-toggle="pill" data-bs-target="#tab-4" type="button"
                    role="tab">
                    <i class="bi bi-bell"></i> Notifications
                </button>
                <button class="nav-link" id="tab-5-btn" data-bs-toggle="pill" data-bs-target="#tab-5" type="button"
                    role="tab">
                    <i class="bi bi-credit-card"></i> Billing History
                </button>
                <button class="nav-link" id="tab-6-btn" data-bs-toggle="pill" data-bs-target="#tab-6" type="button"
                    role="tab">
                    <i class="bi bi-people"></i> Team Members
                </button>
                <button class="nav-link" id="tab-7-btn" data-bs-toggle="pill" data-bs-target="#tab-7" type="button"
                    role="tab">
                    <i class="bi bi-puzzle"></i> Integrations
                </button>
                <button class="nav-link" id="tab-8-btn" data-bs-toggle="pill" data-bs-target="#tab-8" type="button"
                    role="tab">
                    <i class="bi bi-code-slash"></i> API Access
                </button>
            </div>
        </div>
        <div class="toast-container position-fixed top-0 end-0 p-3">

            <div id="liveToast" class="toast show align-items-center text-white  border-0" role="alert"
                aria-live="assertive" aria-atomic="true">

            </div>

        </div>
        <div class="main-content">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                    <?php include "components/department.php" ?>
                </div>

                <div class="tab-pane fade" id="tab-2" role="tabpanel">
                    <?php include "components/allowance.php" ?>
                </div>
                <div class="tab-pane fade" id="tab-3" role="tabpanel">
                    <?php include "components/deducation.php" ?>
                </div>
                <div class="tab-pane fade" id="tab-4" role="tabpanel">
                    <?php include "components/designation.php" ?>
                </div>
                <div class="tab-pane fade" id="tab-5" role="tabpanel">
                    <?php include "components/employees.php" ?>
                </div>
                <div class="tab-pane fade" id="tab-6" role="tabpanel">
                    <?php include "components/question.php" ?>
                </div>
                <div class="tab-pane fade" id="tab-7" role="tabpanel">
                    <?php include "components/bonus&subtraction.php" ?>
                </div>
                <div class="tab-pane fade" id="tab-8" role="tabpanel">
                    <?php include "components/payroll.php" ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>