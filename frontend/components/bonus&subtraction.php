
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #555;
            --text-dark: #222;
            --text-light: #666;
            --bg-white: #ffffff;
            --bg-light: #fafafa;
            --bg-hover: #f5f5f5;
            --border-color: #ddd;
            --transition: all 0.15s ease;
        }

        .add_bonuses{
            display: none;
        }
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Inter', -apple-system, sans-serif;
        }

        /* Salary Breakdown Styling */
        .salary-box {
            display: flex;
            flex-direction: column;
        }

        .breakdown-item {
            display: inline-flex;
            align-items: center;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            background-color: var(--bg-light);
            border: 1px solid var(--border-color);
        }

        .text-success-custom { color: #1a8754; }
        .text-danger-custom { color: #d63384; }

        /* Alignment fix */
        .table th, .table td {
            vertical-align: middle !important;
        }

        .custom-card { 
            background: var(--bg-white); 
            padding: 20px; 
            border-radius: 4px; 
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.02); 
            margin: 20px auto; 
            max-width: 1300px; 
        }

        .header-title { 
            font-size: 1.4rem; 
            font-weight: 600; 
            color: var(--text-dark); 
        }
        .main-content{
            overflow: hidden;
        }

        .header-subtitle { 
            font-size: 0.9rem; 
            color: var(--text-light); 
        }

        .form-label { 
            font-size: 0.7rem; 
            font-weight: 700; 
            color: var(--text-light); 
            text-transform: uppercase; 
            letter-spacing: 0.8px; 
        }

        .form-select, .form-control {
            border-color: var(--border-color);
            color: var(--text-dark);
            font-size: 0.9rem;
            border-radius: 4px;
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .table thead th { 
            background-color: var(--bg-light);
            font-size: 0.75rem; 
            font-weight: 700; 
            color: var(--text-light); 
            text-transform: uppercase;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }
            /* Ensures everything inside the row is centered vertically */
        .table td {
        vertical-align: middle !important;
        }

        /* If you want the "Adjustment" labels to always stay top-aligned with the input */
        .table td:has(.fine-input, .bonus-input) {
        vertical-align: top !important;
        padding-top: 12px;
        }

        .table td { 
            font-size: 0.9rem; 
            padding: 15px;
            vertical-align: middle; 
            border-bottom: 1px solid var(--border-color);
        }

        .btn-submit { 
            background-color: var(--primary-color); 
            color: white; 
            border: none; 
            padding: 10px;
            transition: var(--transition);
        }

        .btn-submit:hover { 
            background-color: #333; 
            color: white; 
        }

        .badge-dept {
            background: var(--bg-hover);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        .total-payout-display {
            font-family: monospace;
            font-size: 1rem;
        }
        /* Dark Add Button Styling */
.btn-add-new-bonus {
    background-color: #444; /* Dark gray to match your image */
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: var(--transition);

}

.btn-add-new-bonus:hover {
    background-color: var(--text-dark);
    color: #fff;
    transform: translateY(-1px);
}

/* Header Divider */
.header-divider {
    border: 0;
    border-top: 1px solid var(--border-color);
    margin-bottom: 0;
    opacity: 0.6;
}

/* ID Cell Specificity */
.id-cell {
    color: var(--text-light) !important;
    font-family: var(--font-monospace);
}

/* Container Adjustments */
.custom-table-container {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    padding: 25px 15px; /* Added more top/bottom padding */
    margin: 20px;
}
    </style>
</head>
<body>
<div class="custom-table-container bonuses_table">
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h4 class="mb-0 fw-bold" style="color: var(--text-dark); font-size: 1.5rem;">Bonuses</h4>
        <button class="btn btn-add-new-bonus" onclick="showEditForm()">
            <i class="fas fa-plus me-2"></i> Add New Bonus
        </button>
    </div>

    <hr class="header-divider">

    <div class="table-responsive">
        <table class="table custom-table text-nowrap">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">MONTH</th>
                    <th scope="col">YEAR</th>
                    <th scope="col">TYPE</th>
                    <th scope="col">FINE</th>
                    <th scope="col">BONUS</th>
                    <th scope="col" class="text-end">ACTIONS</th>
                </tr>
            </thead>
<tbody id="bonus-list-content">
        </tbody>
        </table>
    </div>
</div>


<div class="container-fluid add_bonuses">
    <div class="custom-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="header-title mb-1">Performance Evaluation & Payroll</h4>
                <div class="header-subtitle">Apply bonuses or fines based on employee performance reviews</div>
            </div>
            <button class="btn btn-outline-secondary btn-sm px-3" onclick="showTableList()" >
                <i class="fas fa-arrow-left me-1"></i> Back
            </button>
        </div>
        
        <div class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label class="form-label">Department Filter</label>
                <select id="bonus_Department" onchange="handleDepartmentChange()" class="form-select">
                    <option value="all">All Departments</option>
                    <option value="52">Accounts Department</option>
                    <option value="1">IT Department</option>
                    <option value="2">HR Department</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Employee</label>
                <select id="bonus_Employees" onchange="filterByEmployee()" class="form-select">
                    <option value="all">All Employees</option> 
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Adjustment Type</label>
                <select id="fine" onchange="fineChange(true)" class="form-select">
                    <option value="0" selected disabled>Select Type</option>
                    <option value="1">Fine Only</option>
                    <option value="2">Bonus Only</option>
                    <option value="3">Both</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Month</label>
                <select id="month" class="form-select">
                    <option value="1">January</option><option value="2">February</option>
                    <option value="3">March</option><option value="4">April</option>
                    <option value="5">May</option><option value="6">June</option>
                    <option value="7">July</option><option value="8">August</option>
                    <option value="9">September</option><option value="10">October</option>
                    <option value="11">November</option><option value="12">December</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Year</label>
                <select id="year" class="form-select"></select>
            </div>
            <input type="hidden" id="bonus_id" value="">
            <div class="col-md-2">
                <button onclick="submitFandB()" class="btn btn-submit w-100">
                    <i class="fas fa-check-circle me-1"></i> Save Changes
                </button>
            </div>
        </div>

        <div class="table-responsive border rounded" style="
    overflow: scroll !important;
    max-width: 952px;
">
            <table class="table mb-0">
                <thead>
                    <tr class="align-middle"> <th width="80" style="white-space: nowrap;">ID</th>
                        <th style="white-space: nowrap;">Employee</th>
                        <th style="white-space: nowrap;">Department</th>
                        <th id="dynamic-header" style="white-space: nowrap; min-width: 280px;">Adjustment</th> 
                        <th style="white-space: nowrap;">Base Salary</th>
                        <th class="text-end" style="white-space: nowrap;">Final Payout</th>
                    </tr>
                </thead>
                <tbody id="bouns-body">
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Loading payroll data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>




