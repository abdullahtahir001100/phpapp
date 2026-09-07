<style>
  /* =========================================
   1. SCOPED VARIABLES & BASE STYLES
   ========================================= */
  .payroll-page-scope {
    --primary-color: #555;
    --text-dark: #222;
    --text-light: #666;
    --bg-white: #ffffff;
    --bg-light: #fafafa;
    --bg-hover: #f5f5f5;
    --border-color: #ddd;
    --transition: all 0.15s ease;

    background-color: var(--bg-light);
    color: var(--text-dark);
    font-family:
      "Inter",
      -apple-system,
      BlinkMacSystemFont,
      "Segoe UI",
      Roboto,
      Helvetica,
      Arial,
      sans-serif;
    min-height: 100vh;
    padding: 20px 0;
  }

  /* =========================================
   2. CONTAINERS & CARDS
   ========================================= */
  .payroll-page-scope .custom-card {
    background: var(--bg-white);
    padding: 25px;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin: 0 auto;
    max-width: 1400px;
  }

  .payroll-page-scope .filter-container {
    border: 1px solid var(--border-color);
    padding: 15px 20px;
    border-radius: 6px;
    background-color: var(--bg-white);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
  }

  /* =========================================
   3. FORMS & INPUTS
   ========================================= */
  .payroll-page-scope .form-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.4rem;
  }

  .payroll-page-scope .form-select,
  .payroll-page-scope .form-control {
    border-color: var(--border-color);
    color: var(--text-dark);
    font-size: 0.9rem;
    border-radius: 4px;
    padding: 8px 12px;
  }

  .payroll-page-scope .form-select:focus,
  .payroll-page-scope .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    outline: none;
  }

  .payroll-page-scope .evaluation-input {
    width: 100px;
    padding: 6px 10px;
    font-size: 0.95rem;
    background-color: transparent;
    border: 1px solid #ced4da;
    border-radius: 4px;
    text-align: center;
  }

  .payroll-page-scope .evaluation-input:focus {
    border-color: #86b7fe;
    background-color: #fff;
  }

  /* =========================================
   4. BUTTONS
   ========================================= */
  .payroll-page-scope .btn {
    font-weight: 500;
    font-size: 0.95rem;
    transition: var(--transition);
  }

  .payroll-page-scope .btn-dark-custom {
    background-color: #2c3338;
    color: #fff;
    border: none;
  }

  .payroll-page-scope .btn-dark-custom:hover {
    background-color: #1a1e21;
    color: #fff;
  }

  .payroll-page-scope .btn-print {
    background-color: #2b78c5;
    color: white;
    border: none;
  }

  .payroll-page-scope .btn-print:hover {
    background-color: #21609e;
    color: white;
  }

  .payroll-page-scope .btn-outline-secondary {
    border-color: #ced4da;
    color: #495057;
    background-color: transparent;
  }

  .payroll-page-scope .btn-outline-secondary:hover {
    background-color: var(--bg-hover);
    color: var(--text-dark);
  }

  /* =========================================
   5. TABLES & DATA DISPLAY
   ========================================= */
  .payroll-page-scope .table-responsive {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background-color: var(--bg-white);
    overflow-x: auto !important;
  }

  .payroll-page-scope .table {
    margin-bottom: 0;
  }

  .payroll-page-scope .table th,
  .payroll-page-scope .table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
  }

  .payroll-page-scope .table thead th {
    background-color: var(--bg-hover);
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border-color);
  }

  .payroll-page-scope .table thead tr th {
    border-right: 1px solid var(--border-color);
    text-align: center;
  }

  .payroll-page-scope .table thead tr th:last-child {
    border-right: none;
  }

  .payroll-page-scope .table thead tr:last-child th {
    text-transform: none;
    font-weight: 500;
    color: #555;
  }

  .payroll-page-scope .table td {
    border-right: 1px solid var(--border-color);
    font-size: 0.95rem;
  }

  .payroll-page-scope .table td:last-child {
    border-right: none;
  }

  .payroll-page-scope .highlight-row {
    background-color: #fcf9f9 !important;
  }

  /* =========================================
   6. TYPOGRAPHY UTILITIES
   ========================================= */
  .payroll-page-scope .contact-info {
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.4;
  }

  .payroll-page-scope .item-count {
    font-size: 0.75rem;
    color: #888;
    margin-top: 4px;
    font-weight: 500;
  }

  .payroll-page-scope .prev-value {
    font-size: 0.75rem;
    color: #888;
    display: block;
    margin-top: 5px;
    font-style: italic;
  }

  /* Net Amount Styling */
  .payroll-page-scope .net-amount-header {
    background-color: #eef2f7 !important;
    color: #1e40af !important;
  }

  .payroll-page-scope .net-amount-cell {
    font-size: 1.05rem;
    background-color: #f8fafc;
  }
  .month-picker-wrapper {
  position: relative;
}

#monthYearInput {
  cursor: pointer;
  background-color: #fff;
}

.month-picker-dropdown {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  min-width: 250px;
  background: #ffffff;
  border: 1px solid #e0e0e0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  z-index: 1050;
  padding-bottom: 16px;
  margin-top: 4px;
}

.month-picker-dropdown.show {
  display: block;
}

.month-picker-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  background-color: #fafafa;
  border-bottom: 1px solid #eee;
  margin-bottom: 12px;
}

.year-text {
  font-weight: 700;
  font-size: 1.1rem;
  color: #111;
}

.btn-nav {
  background: none;
  border: none;
  font-size: 0.85rem;
  color: #777;
  cursor: pointer;
  padding: 2px 8px;
}

.btn-nav:hover {
  color: #000;
}

.month-picker-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px 8px;
  padding: 0 16px;
}

.month-cell {
  padding: 10px 0;
  text-align: center;
  font-size: 0.95rem;
  color: #666;
  cursor: pointer;
  border-radius: 2px;
  transition: background-color 0.15s ease, color 0.15s ease;
}

.month-cell:hover:not(.selected) {
  background-color: #f2f2f2;
  color: #111;
}

/* Dark grey block for currently selected month */
.month-cell.selected {
  background-color: #4f4f4f;
  color: #ffffff;
  font-weight: 700;
}

/* Bold text for target/highlighted month (e.g., Sep) */
.month-cell.highlight {
  font-weight: 700;
  color: #222;
}
</style>
<div class="container-fluid payroll-page-scope">
  <div class="custom-card">
    <!-- Top Action Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <button class="btn btn-dark-custom px-4 py-2 rounded">
        <i class="fas fa-plus me-2"></i> Process Payroll
      </button>

      <button class="btn btn-print px-4 py-2 rounded">
        <i class="fas fa-print me-2"></i> Print
      </button>

      <button class="btn btn-outline-secondary px-3 py-2">
        <i class="fas fa-arrow-left me-1"></i> Back
      </button>
    </div>

    <!-- Filter Row -->
    <div class="filter-container mb-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-2">
          <label class="form-label">PROCESS ID</label>
          <input
            type="text"
            class="form-control bg-light"
            value="_r_1_"
            readonly
            style="color: #999"
          />
        </div>
        <div class="col-md-3">
          <label class="form-label">DEPARTMENT</label>
          <select class="form-select" id="departmentpayroll">
            <option>Clerk</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">EMPLOYEE</label>
          <select class="form-select" id="employepayroll">
            <option>All Employees</option>
          </select>
        </div>
<div class="col-md-2">
  <label class="form-label">MONTH & YEAR</label>
  <div class="month-picker-wrapper">
    <input type="text" class="form-control" id="payroll_month_year" value="01-2026" readonly>
    
    <div class="month-picker-dropdown" id="monthPickerDropdown">
      <div class="month-picker-header">
        <button type="button" class="btn-nav" id="prevYear">&lt;</button>
        <span class="year-text" id="yearDisplay">2026</span>
        <button type="button" class="btn-nav" id="nextYear">&gt;</button>
      </div>
      <div class="month-picker-grid" id="monthGrid"></div>
    </div>
  </div>
</div>
        <div class="col-md-2">
          <button class="btn btn-dark-custom w-100 py-2">Search</button>
        </div>
      </div>
    </div>

    <!-- Payroll Data Table -->
    <div class="table-responsive">
      <table class="table mb-0 text-nowrap">
        <thead>
          <!-- Top level header groupings -->
          <tr>
            <th rowspan="2" style="width: 50px">ID</th>
            <th colspan="2">Employee Info</th>
            <th colspan="2">Job Details</th>
            <th colspan="2">Fixed Adjustments</th>
            <th colspan="2">Current Evaluation</th>
            <th rowspan="2" class="text-center">Base Salary</th>
            <th rowspan="2" class="text-center net-amount-header">
              Net Amount
            </th>
          </tr>
          <!-- Sub level column definitions -->
          <tr>
            <th>Name</th>
            <th>Contact Info</th>
            <th>Dept</th>
            <th>Designation</th>
            <th>Allowances</th>
            <th>Deductions</th>
            <th>Bonus</th>
            <th>Fine</th>
          </tr>
        </thead>
        <tbody id="payrollinstTableBody">
          <!-- Example Data Row -->
          <!-- <tr class="highlight-row">
                        <td>1</td>
                        <td class="fw-bold">Taha Ammar</td>
                        <td class="contact-info">
                            +923067280760<br>
                            tahaammar1314@gmail.com
                        </td>
                        <td>Clerk</td>
                        <td>Senior Clerk</td>
                        <td>
                            <div class="fw-bold text-dark fs-6">$6,786</div>
                            <div class="item-count">Items: 1</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark fs-6">$0</div>
                            <div class="item-count">Items: 0</div>
                        </td>
                        <td>
                            <input type="number" class="form-control evaluation-input" value="7897">
                            <span class="prev-value">Prev: 7897</span>
                        </td>
                        <td>
                            <input type="number" class="form-control evaluation-input" value="89779">
                            <span class="prev-value">Prev: 89779</span>
                        </td>
                        <td class="fw-bold text-dark fs-6 text-center align-middle">
                            $30,000
                        </td>
                        <td class="fw-bold fs-6 text-center align-middle net-amount-cell text-danger">
                            -$45,096
                        </td>
                    </tr> -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("monthYearInput");
  const dropdown = document.getElementById("monthPickerDropdown");
  const yearDisplay = document.getElementById("yearDisplay");
  const monthGrid = document.getElementById("monthGrid");
  const prevYearBtn = document.getElementById("prevYear");
  const nextYearBtn = document.getElementById("nextYear");

  const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

  // Parse initial values from input (Format: MM-YYYY)
  const initialParts = input.value.split("-");
  let selectedMonthIndex = parseInt(initialParts[0], 10) - 1 || 0;
  let selectedYear = parseInt(initialParts[1], 10) || 2026;
  let currentDisplayedYear = selectedYear;

  function renderGrid() {
    monthGrid.innerHTML = "";
    yearDisplay.textContent = currentDisplayedYear;

    monthNames.forEach((name, index) => {
      const cell = document.createElement("div");
      cell.classList.add("month-cell");
      cell.textContent = name;

      // Active selection styling (Dark block)
      if (index === selectedMonthIndex && currentDisplayedYear === selectedYear) {
        cell.classList.add("selected");
      }

      // Optional highlight styling for specific months like "Sep"
      if (name === "Sep" && !cell.classList.contains("selected")) {
        cell.classList.add("highlight");
      }

      cell.addEventListener("click", () => {
        selectedMonthIndex = index;
        selectedYear = currentDisplayedYear;
        
        // Format to MM-YYYY
        const formattedMonth = (selectedMonthIndex + 1).toString().padStart(2, "0");
        input.value = `${formattedMonth}-${selectedYear}`;
        
        dropdown.classList.remove("show");
        renderGrid();
      });

      monthGrid.appendChild(cell);
    });
  }

  // Toggle Dropdown
  input.addEventListener("click", () => {
    dropdown.classList.toggle("show");
    renderGrid();
  });

  // Year Controls
  prevYearBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    currentDisplayedYear--;
    renderGrid();
  });

  nextYearBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    currentDisplayedYear++;
    renderGrid();
  });

  // Close when clicking outside
  document.addEventListener("click", (e) => {
    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove("show");
    }
  });

  renderGrid();
});
  function show_payroll_employees() {
    let table = "";
    let i = 1;

    let formData = new FormData();
    formData.append("command", "show_payroll_employees");

    document.querySelector("#payrollinstTableBody").innerHTML = `
    <tr>
        <td colspan="11" class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 mb-0">Loading...</p>
        </td>
    </tr>
`;

    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data.success === true) {
          table = "";
          i = 1;

          data.data.forEach((element) => {
            // Values from database
            let salary = Number(element.salary) || 0;
            let allowance = Number(element.total_allowance) || 0;
            let deduction = Number(element.total_deduction) || 0;
            let bonus = Number(element.total_bonus) || 0;
            let fine = Number(element.total_fine) || 0;

            /*
                Net Salary Calculation

                Salary
                + Allowance
                + Bonus
                - Deduction
                - Fine
            */
            let netAmount = salary + allowance + bonus - deduction - fine;

            table += `
                <tr class="highlight-row">

                    <!-- # -->
                    <td>
                        ${i++}
                    </td>

                    <!-- Employee Name -->
                    <td class="fw-bold" id= "payroll_emp_name">
                         ${element.first_name ?? ""} ${element.last_name ?? ""}
                         <input type="number" id="payroll_emp_id" value = "${element.id ?? ""}" >
                    </td>

                    <!-- Contact -->
                    <td class="contact-info">
                        ${element.phone ?? ""}<br>
                        ${element.email ?? ""}
                    </td>

                    <!-- Department -->
                    <td>
                        ${element.department ?? ""}
                        <input type="number" id="payroll_dep_id" value = "${element.department_id ?? ""}" >
                    </td>

                    <!-- Designation -->
                    <td>
                        ${element.designation ?? ""}
                        <input type="number" id="payroll_desg_id" value = "${element.designation_id ?? ""}" >
                    </td>

                    <!-- Allowance -->
                    <td>
                        <div class="fw-bold text-dark fs-6" id="payroll_allowance">
                            $${allowance.toLocaleString()}
                        </div>

    
                    </td>

                    <!-- Deduction -->
                    <td>
                        <div class="fw-bold text-dark fs-6" id="payroll_deduction">
                            $${deduction.toLocaleString()}
                        </div>

                    </td>

                    <!-- Bonus -->
                    <td>
                        <input id="payroll_bonus"
                            type="number"
                            class="form-control evaluation-input bonus-input"
                            value="${bonus}" disabled>

                        <span class="prev-value">
                            Prev: ${bonus.toLocaleString()}
                        </span>
                    </td>

                    <!-- Fine -->
                    <td>
                        <input id="payroll_fine"
                            type="number"
                            class="form-control evaluation-input fine-input"
                            value="${fine}" disabled>

                        <span class="prev-value">
                            Prev: ${fine.toLocaleString()}
                        </span>
                    </td>

                    <!-- Salary -->
                    <td class="fw-bold text-dark fs-6 text-center align-middle" id="payroll_salary">
                        $${salary.toLocaleString()}
                    </td>

                    <!-- Net Amount -->
                    <td class="
                        fw-bold 
                        fs-6 
                        text-center 
                        align-middle 
                        net-amount-cell
                        ${netAmount < 0 ? "text-danger" : "text-success"}
                    " id="payroll_net_amount">
                        ${netAmount < 0 ? "-" : ""}$${Math.abs(netAmount).toLocaleString()}
                    </td>

                </tr>
            `;
          });

          document.querySelector("#payrollinstTableBody").innerHTML = table;
        } else {
          document.querySelector("#payrollinstTableBody").innerHTML = `
            <tr>
                <td colspan="11" class="text-center py-4 text-danger">
                    No employee data found.
                </td>
            </tr>
        `;
        }
      })
      .catch((error) => {
        console.error(error);

        document.querySelector("#payrollinstTableBody").innerHTML = `
        <tr>
            <td colspan="11" class="text-center py-4 text-danger">
                Error loading data.
            </td>
        </tr>
    `;
      });
  }
  show_payroll_employees();

 function payroll_insert() {
    let formData = new FormData();
    formData.append("command", "payroll_insert");
    formData.append("employee_id", document.getElementById("payroll_emp_id").value);
    formData.append("month_year", document.getElementById("payroll_month_year").value);
    formData.append("department_id", document.getElementById("payroll_dep_id").value);
    formData.append("designation_id", document.getElementById("payroll_desg_id").value);
    formData.append("salary", document.getElementById("payroll_salary").textContent.replace(/[^0-9.-]+/g, ""));
    formData.append("allowance", document.getElementById("payroll_allowance").textContent.replace(/[^0-9.-]+/g, ""));
    formData.append("deduction", document.getElementById("payroll_deduction").textContent.replace(/[^0-9.-]+/g, ""));
    formData.append("bonus", document.getElementById("payroll_bonus").value);
    formData.append("fine", document.getElementById("payroll_fine").value);
    formData.append("net_amount", document.getElementById("payroll_net_amount").textContent.replace(/[^0-9.-]+/g, ""));
console.log(formData);

    // fetch("http://localhost/php/ACR/backend/api/payroll/payroll_insert.php", {
    //     method: "POST",
    //     body: formData,
    // })
    //     .then((response) => response.json())
    //     .then((data) => {
    //         console.log(data);
    //         if (data.success === true) {
    //             alert(data.message || "Payroll inserted successfully.");
    //         } else {
    //             alert(data.message || "Failed to insert payroll.");
    //         }
    //     })
    //     .catch((error) => {
    //         console.error(error);
    //         alert("Error inserting payroll.");
    //     });
}
payroll_insert();
</script>
