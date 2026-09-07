

<div class="emp-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="color: var(--primary-color); font-weight: 300;">Employees</h3>
        <button class="btn btn-dark" onclick="md_employee()" data-bs-toggle="modal" data-bs-target="#employeeModal" style="background-color: var(--primary-color); border-radius: var(--radius); border: none;">
            <i class="bi bi-plus-lg"></i> Add Employee
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table align-middle" style="border: 1px solid var(--border-color); font-size: 14px;">
            <thead style="background-color: var(--table-head-bg); color: var(--text-dark);">
                <tr>
                    <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">ID</th>
                    <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">Full Name</th>
                    <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">Dept</th>
                    <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">Contact Info</th>
                    <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">Desig</th>
                    <th style="padding: 12px; border-bottom: 2px solid var(--border-color);" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="employeeTableBody" style="color: var(--text-light);">
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="spinner-border text-secondary" role="status"></div>
                        <p class="mt-2 mb-0">Loading...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--radius); border: 1px solid var(--border-color);">
            <div class="modal-header" style="background-color: var(--bg-light); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" style="color: var(--text-dark); font-size: 1rem; font-weight: 600;">NEW EMPLOYEE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm">
                <div class="modal-body" style="padding: 25px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">FIRST NAME</label>
                            <input type="text" required id="first-name" name="first_name" class="form-control" placeholder="John">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">LAST NAME</label>
                            <input type="text" required id="last-name" name="last_name" class="form-control" placeholder="Doe">
                        </div>
                        <input type="text" name="employee_id" id="employee-id">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">GMAIL ADDRESS</label>
                            <input type="email" required id="employee-email" name="employee_email" class="form-control" placeholder="example@gmail.com">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">PHONE NUMBER</label>
                            <input type="tel" required id="employee-phone" name="employee_phone" class="form-control" placeholder="+123 456">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">MONTHLY SALARY</label>
                            <input type="number" required id="employee-salary" name="employee_salary" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">DEPARTMENT</label>
                            <select class="form-select" required onchange="show_desig()" id="employee_dept_select" name="department_id">
                                <option value="" selected disabled>Select Dept</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">DESIGNATION</label>
                            <select class="form-select" required id="employee_desig_select" name="designation_id">
                                <option value="" selected disabled>Select Desig</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">RESIDENTIAL ADDRESS</label>
                            <input name="employee_address" id="emploees-addrees" required class="form-control" placeholder="Street, City, Country...">
                        </div>

                        <hr style="border-color: var(--border-color); opacity: 0.1;">

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">ALLOWANCES</label>
                            <div id="allowance-wrapper">
                                <select id="allowance-select" name="allowances[]" class="hidden">
                                    <option value="house_rent">House Rent</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">DEDUCATIONS</label>
                            <div id="deduction-wrapper">
                                <select id="deducation-select" name="deducations[]" class="hidden">
                                    <option value="tax">Income Tax</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"id="employee-save-btn" class="btn btn-sm" style="background-color: var(--primary-color); color: #fff; padding: 8px 20px;">Save Employee</button>
                </div>
            </form> 
        </div>
    </div>
</div>

 



<div class="container-fluid mark-questions">
    <div class="main-container border">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="page-title">Mark Questions</h2>
                <div class="page-subtitle">Rate employee performance</div>
            </div>
            <button  class="back btn btn-outline-custom px-3 py-2 shadow-sm" onclick="back()">
                &larr; Back to Employees
            </button>
        </div>
        
        <hr style="border-color: #dee2e6;">

        <div class="section-header">
            Employee Information
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="info-label">Full Name</div>
                <div class="info-value">Sheri Sheri add howa ha</div>
            </div>
            <div class="col-md-4">
                <div class="info-label">Email</div>
                <div class="info-value">ahmadshehryar181@gmail.com</div>
            </div>
            <div class="col-md-4">
                <div class="info-label">Phone</div>
                <div class="info-value">03000756242</div>
            </div>
            
            <div class="col-md-4 mt-4">
                <div class="info-label">Department</div>
                <div class="info-value">IT Department</div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="info-label">Designation</div>
                <div class="info-value">Meneger</div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="info-label">Address</div>
                <div class="info-value">Caloni mehrabad dak khana Ahmad Pur sahiwal, sargodha</div>
            </div>
        </div>

        <div class="section-header thick-top-border">
            Questions & Rating
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 55%;">Question</th>
                        <th style="width: 20%;">Total Rating</th>
                        <th style="width: 20%;">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">1</td>
                        <td>898989908</td>
                        <td>10</td>
                        <td>
                            <input type="number" class="points-input" value="0" min="0" max="10">
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>

        <hr style="border-color: #dee2e6; margin-top: 2rem; margin-bottom: 1.5rem;">

        <div class="d-flex justify-content-end gap-2">
            <button class="btn btn-outline-custom px-4">Cancel</button>
            <button class="btn btn-black px-4">Save Ratings</button>
        </div>

    </div>
</div>

