
 function alert(text,color){

    let liveToast = document.getElementById("liveToast");

     liveToast.innerHTML=`
     
      <div class="d-flex">
                
                <div class="toast-body d-flex align-items-center">
                    <div class="toast-icon">
                        
                    </div>
                    <div class="toast-message">
                        ${text}
                    </div>
                </div>

                <button type="button" id="close-toast" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
     `   

     liveToast.style.backgroundColor=`${color}`;
     liveToast.style.display="block";
     
    document.getElementById("close-toast").addEventListener("click" , (e) =>{
    
        
        liveToast.style.display="none";
        

    });

    }


function submit_department () {

    let form = document.querySelector("#deptForm");
    
    
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        
        let formData = new FormData(form);
        formData.append("command", "add_department_type");
        // console.log(formData);
    
    fetch("http://localhost/php/multipal%20inst/second_project/quary.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        show_department();
            data["status"] == "success" ? alert(`${data["message"]}`,"green"): alert(`${data["message"]}`,"green") ;
        // alert("","#000")
         document.getElementById('deptModal').style.display =`${data ? 'none' : 'block'}`;
         document.querySelector('.modal-backdrop').style.display ='none';
         document.getElementById("department_name").value = "";
         document.getElementById("save-update-btn").innerHTML="Save";

    })
    // .catch(error => {
    //     console.error("Error:", error);
    // });
    });
    
}
submit_department();

   function show_department(){
    
    let table ='';
    let option =`<option value="" selected disabled>Select Departments...</option>`;
    let optionQue =`<option value="all" selected>Select All Departments</option>`;
    let i = 1;
    let formData = new FormData();
    formData.append("command", "show_department_type");
    let Loading = "";
     fetch("http://localhost/php/ACR/backend/api/department/get.php",{
         method: "POST",
         body:formData
    })
       .then(response => response.json())
    .then(data=>{
        // console.log(response);
        // console.log(data);  
        if( data.success == true){
            Loading = false ;
            // console.log("success");
            
        }else{
            Loading = true;  
        }
        
        
        if(Loading == true){
                  document.querySelector("tbody").innerHTML=`
                      <tr>
                          <td colspan="5" class="text-center py-4">
                              <div class="spinner-border text-primary" role="status"></div>
                              <p class="mt-2 mb-0">Loading...</p>
                          </td>
                      </tr>
                  `;
              }else{
                 data.data.forEach(element => {
                    option+=`<option value="${element["id"]}">${element["department"]}</option>`;
                    optionQue+=`<option value="${element["id"]}">${element["department"]}</option>`;
                    

            table+=`  <tr>
                            <td style="padding: 12px;">#${i++}</td>
                            <td style="padding: 12px; color: var(--text-dark); font-weight: 500;">${element["department"]}</td>
                            <td style="padding: 12px;">
                                <span class="badge" style="color: #28a745; background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 1px;">${element["status"] == 1 ? 'Active' : 'Inactive'}</span>
                            </td>
                            
                            <td style="padding: 12px;" class="text-end">
                                <div class="action-container">
                                <button class="btn-action">Actions</button>
                                
                                    <div class="actions-dropdown">
                                        <button class="action-item btn btn-sm btn-link text-decoration-none "data-bs-toggle="modal" data-bs-target="#deptModal"  style="color: var(--primary-color);" onclick="document.querySelector('.modal-backdrop').style.display ='block'; updaterow_department(${element["id"]},'update_department')">Edit</button>
                                        <button class="action-item btn btn-sm btn-link text-decoration-none" style="color: var(--primary-color);"  onclick="deleterow_department(${element["id"]},'delete_department')">Delete</button>
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                    </div>
                                </div>
                            </td>
                        </tr>
                `;
            
            });
                  document.querySelector("tbody").innerHTML=table;
                  document.getElementById("department").innerHTML=option;
                  document.getElementById("employee_dept_select").innerHTML=option;
                  document.getElementById("question_department").innerHTML=option;
                  document.getElementById("departmentpayroll").innerHTML=option;
                  document.getElementById("bonus_Department").innerHTML=optionQue;
                  
              }
            
       
            
            
            
            // console.log(Loading);
            
    });

}
show_department();
 
   function deleterow_department(id,command){
 // example id

 
 fetch(`http://localhost/php/ACR/backend/api/department/delete.php?id=${id}&command=${command}`,)
  .then(response => response.json())
  .then(data => {
      console.log(data);
      show_department();
       data["status"] == "success" ? alert(`${data["message"]}`,"red"): alert(`${data["message"]}`,"red") ;

  })
//   .catch(error => console.error("Error:", error));

   }

   
   function updaterow_department(id,command){
    //    console.log("Deleting:", id, command);
      fetch(`http://localhost/php/ACR/backend/api/department/get.php?id=${id}&command=${command}`,)
    .then(response => response.json())
    .then(data => {
             data.data.forEach(element => {

                document.getElementById("department_name").value = element["department"];
                document.getElementById("department-id").value = element["id"];

              // Assuming element["department"] is 1 for active, 0 for inactive
                    const statusSelect = document.getElementById("status-select");

                    if (element["status"] == 1) {
                        statusSelect.value = "1"; // Active
                    } else {
                        statusSelect.value = "0"; // Inactive
                    }

                    document.getElementById("save-update-btn").innerHTML="Save Changes";

             });
      });
//       document.getElementById("edit-departement").addEventListener("click", () => {
    
// });

   }


function md_department (){
        document.querySelector('.modal-backdrop').style.display ='block'; 
        //  document.querySelector('.modal-backdrop').style.display ='none';
         document.getElementById("department_name").value = "";
         document.getElementById("save-update-btn").innerHTML="Save";
         document.getElementById("department-id").value="";
}



// allowances 



function submit_allowance () {

    let form = document.querySelector("#allowanceForm");
    
    
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        
        let formData = new FormData(form);
        formData.append("command", "add_allowance_type");
        // console.log(formData);
    
    fetch("http://localhost/php/ACR/backend/api/department/create.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        show_allowance();
        // alert("","#000")
            data["status"] == "success" ? alert(`${data["message"]}`,"green"): alert(`${data["message"]}`,"green") ;
         document.getElementById('allowanceModal').style.display =`${data ? 'none' : 'block'}`;
         document.querySelector('.modal-backdrop').style.display ='none';
;
         document.getElementById("allowance_name").value = "";
         document.getElementById("allowance_value").value = "";
         document.getElementById("allowance-save-btn").innerHTML="Save";

    })
    // .catch(error => {
    //     console.error("Error:", error);
    // });
    });
    
}


function show_allowance(){
    
    let table ='';
    let i = 1;
    let formData = new FormData();
    formData.append("command", "show_allowance_type");
    let Loading = "";
     fetch("http://localhost/php/ACR/backend/api/department/get.php",{
         method: "POST",
         body:formData
    })
       .then(response => response.json())
    .then(data=>{
        // console.log(response);
        // console.log(data);  
        if( data.success == true){
            Loading = false ;
            // console.log("success");
            
        }else{
            Loading = true;  
        }
        
        
        if(Loading == true){
                  document.querySelector("#allowanceTableBody").innerHTML=`
                      <tr>
                          <td colspan="5" class="text-center py-4">
                              <div class="spinner-border text-primary" role="status"></div>
                              <p class="mt-2 mb-0">Loading...</p>
                          </td>
                      </tr>
                  `;
              }else{
                 data.data.forEach(element => {

                table+=`  <tr>
                            <td style="padding: 12px;">#${i++}</td>
                            <td style="padding: 12px; color: var(--text-dark); font-weight: 500;">${element["department_allowances"]}</td>
                            <td style="padding: 12px;">
                                <span style="color: #3353e2; font-weight: 600;">$${element["allowance_value"]}</span>
                            </td>
                            <td style="padding: 12px;" class="text-end">
                                <div class="action-container">
                                    <button class="btn-action">Actions</button>
                                    
                                    <div class="actions-dropdown">
                                       <button class="action-item action-item  btn btn-sm btn-link text-decoration-none "data-bs-toggle="modal" data-bs-target="#allowanceModal"  style="color: var(--primary-color);" onclick="document.querySelector('.modal-backdrop').style.display ='block'; updaterow_allowance(${element["id"]},'update_allowance')">Edit</button>
                                       <button class="action-item action-item  btn btn-sm btn-link text-decoration-none "data-bs-toggle="modal" data-bs-target="#allowanceModal"  style="color: var(--primary-color);" onclick="document.querySelector('.modal-backdrop').style.display ='block'; deleterow_allowance(${element["id"]},'delete_allowance')">Delete</button>
                                    
                                    </div>
                                </div>
                            </td>
                        </tr>
                `;
            
            });
                  document.querySelector("#allowanceTableBody").innerHTML=table;
              }
            
       
            
            
            
            // console.log(Loading);
            
    });

}

;
 
   function deleterow_allowance(id,command){
 // example id

 
 fetch(`http://localhost/php/ACR/backend/api/department/delete.php?id=${id}&command=${command}`,)
  .then(response => response.json())
  .then(data => {
      console.log(data);
      show_allowance();
       data["status"] == "success" ? alert(`${data["message"]}`,"red"): alert(`${data["message"]}`,"red") ;

  })
//   .catch(error => console.error("Error:", error));

   }



 function updaterow_allowance(id,command){
    //    console.log("Deleting:", id, command);
      fetch(`http://localhost/php/ACR/backend/api/department/get.php?id=${id}&command=${command}`,)
    .then(response => response.json())
    .then(data => {
             data.data.forEach(element => {

                document.getElementById("allowance_name").value = element["department_allowances"];
                document.getElementById("allowance_id").value = element["id"];
                document.getElementById("allowance_value").value = element["allowance_value"];

                    document.getElementById("allowance-save-btn").innerHTML="Save Changes";

             });
      });

}


submit_allowance();
show_allowance();


function md_allowance (){
        document.querySelector('.modal-backdrop').style.display ='block'; 
        //  document.querySelector('.modal-backdrop').style.display ='none'; "";
         document.getElementById("save-update-btn").innerHTML="Save";
         document.getElementById("allowance_id").value="";
         document.getElementById("allowance_name").value="";
         document.getElementById("allowance_value").value="";
}




// deducation script 



function submit_deducation() {
    let form = document.querySelector("#deducationForm");
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData();
        formData.append("command", "add_deducation_type");

        fetch("http://localhost/php/ACR/backend/api/department/create.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            add_deducation();
            data["status"] == "success" ? alert(`${data["message"]}`, "green") : alert(`${data["message"]}`, "red");
            document.getElementById('deducationModal').style.display = 'none';
            document.querySelector('.modal-backdrop').style.display = 'none';
            document.getElementById("deducation_name").value = "";
            document.getElementById("deducation_value").value = "";
            document.getElementById("deducation-save-btn").innerHTML = "Save";
        });
    });
}
submit_deducation();

function add_deducation() {
    let table = '';
    let i = 1;
    let formData = new FormData();
    formData.append("command", "show_deducation_type");

    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success == true) {
            data.data.forEach(element => {
                table += `<tr>
                    <td style="padding: 12px;">#${i++}</td>
                    <td style="padding: 12px; color: var(--text-dark); font-weight: 500;">${element["department_deducations"]}</td>
                    <td style="padding: 12px;">
                        <span style="color: #dc3545; font-weight: 600;">$${element["deducation_value"]}</span>
                    </td>
                    <td style="padding: 12px;" class="text-end">
                        <div class="action-container">
                        <button class="btn-action">Actions</button>
                        
                            <div class="actions-dropdown">
                                <button class="action-item btn btn-sm btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#deducationModal" style="color: var(--primary-color);" onclick="document.querySelector('.modal-backdrop').style.display ='block'; updaterow_deducation(${element["id"]},'update_deducation')">Edit</button>
                                <button class="action-item btn btn-sm btn-link text-decoration-none" style="color: var(--primary-color);" onclick="deleterow_deducation(${element["id"]},'delete_deducation')">Delete</button>

                            </div>
                        </div>
                    </td>
                </tr>`;
            });
            document.querySelector("#deducationTableBody").innerHTML = table;
        } else {
            document.querySelector("#deducationTableBody").innerHTML = `<tr><td colspan="4" class="text-center py-4">No records found.</td></tr>`;
        }
    });
}
add_deducation();

function deleterow_deducation(id, command) {
    fetch(`http://localhost/php/ACR/backend/api/department/delete.php?id=${id}&command=${command}`)
    .then(response => response.json())
    .then(data => {
        add_deducation();
        data["status"] == "success" ? alert(`${data["message"]}`, "red") : alert(`${data["message"]}`, "red");
    });
}

function updaterow_deducation(id, command) {
    fetch(`http://localhost/php/ACR/backend/api/department/get.php?id=${id}&command=${command}`)
    .then(response => response.json())
    .then(data => {
        data.data.forEach(element => {
            document.getElementById("deducation_name").value = element["department_deducations"];
            document.getElementById("deducation_value").value = element["deducation_value"];
            document.getElementById("deducation-id").value = element["id"];
            document.getElementById("deducation-save-btn").innerHTML = "Save Changes";
        });
    });
}

function md_deducation() {
    if(document.querySelector('.modal-backdrop')) document.querySelector('.modal-backdrop').style.display = 'block';
    document.getElementById("deducation_name").value = "";
    document.getElementById("deducation_value").value = "";
    document.getElementById("deducation-id").value = "";
    document.getElementById("deducation-save-btn").innerHTML = "Save";
}

// designation 


function submit_designation() {
    let form = document.querySelector("#designationForm");
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(form);
        formData.append("command", "add_designation_type");

        fetch("http://localhost/php/ACR/backend/api/department/create.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            add_designation();
            data["status"] == "success" ? alert(`${data["message"]}`, "green") : alert(`${data["message"]}`, "red");
            document.getElementById('designationModal').style.display = 'none';
            document.querySelector('.modal-backdrop').style.display = 'none';
            document.getElementById("designation_name").value = "";
            document.getElementById("department").value = "";
            document.getElementById("designation-save-btn").innerHTML = "Save";
        });
    });
}

function add_designation() {
    let table = '';
    let i = 1;
    let formData = new FormData();
    formData.append("command", "show_designation_type");

    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success == true) {
            data.data.forEach(element => {
                table += `<tr>
                    <td style="padding: 12px;">#${i++}</td>
                    <td style="padding: 12px; color: var(--text-dark); font-weight: 500;">${element["designation"]}</td>
                    <td style="padding: 12px;">
                        <span style="font-weight: 600;">${element["department"]}</span>
                    </td>
                    <td style="padding: 12px;" class="text-end">
                        <div class="action-container">
                        <button class="btn-action">Actions</button>
                        
                            <div class="actions-dropdown">
                                <button class="action-item btn btn-sm btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#designationModal" style="color: var(--primary-color);" onclick="document.querySelector('.modal-backdrop').style.display ='block'; updaterow_designation(${element["id"]},'update_designation')">Edit</button>
                                  <button class="action-item btn btn-sm btn-link text-decoration-none" style="color: var(--primary-color);" onclick="deleterow_designation(${element["id"]},'delete_designation')">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>`;
            });
            document.querySelector("#designationTableBody").innerHTML = table;
        } else {
            document.querySelector("#designationTableBody").innerHTML = `<tr><td colspan="4" class="text-center py-4">No records found.</td></tr>`;
        }
    });
}

function deleterow_designation(id, command) {
    fetch(`http://localhost/php/ACR/backend/api/department/delete.php?id=${id}&command=${command}`)
    .then(response => response.json())
    .then(data => {
        add_designation();
        data["status"] == "success" ? alert(`${data["message"]}`, "red") : alert(`${data["message"]}`, "red");
    });
}

function updaterow_designation(id, command) {
    fetch(`http://localhost/php/ACR/backend/api/department/get.php?id=${id}&command=${command}`)
    .then(response => response.json())
    .then(data => {
        data.data.forEach(element => {
            let department = document.getElementById("department");
            document.getElementById("designation_name").value = element["designation"];
            // set the select's value to the department id (selects matching option)
            if (element["dep_id"] !== undefined && element["dep_id"] !== null) {
                department.value = element["dep_id"];
            }

            // .innerHTML +=  `<option selected value="${element["dep_id"]}">${element["department"]}`;
            // .innerHTML = "";
            document.getElementById("designation-id").value = element["id"];
            document.getElementById("designation-save-btn").innerHTML = "Save Changes";
            
        });
    });
}

function md_designation() {
    if(document.querySelector('.modal-backdrop')) document.querySelector('.modal-backdrop').style.display = 'block';
    document.getElementById("designation_name").value = "";
    document.getElementById("department").value = "";
    document.getElementById("designation-id").value = "";
    document.getElementById("designation-save-btn").innerHTML = "Save";
}
add_designation();
submit_designation();




function show_desig (){
    let desig = document.getElementById("employee_desig_select");
    let dept_id = document.getElementById("employee_dept_select").value;
    let Option="";

    
    // console.log(dept_id);
    let dataToSend = {
        "command": "show_desig_type",
        "id" :dept_id
    };
    let formData = new FormData();
    for (let key in dataToSend) {
        formData.append(key, dataToSend[key]);
    }
    // formData.append(

    // );
    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        data.data.forEach(element => {
            Option +=`<option value="${element['id']}">${element['designation']}</option>`
        });
        
        desig.innerHTML=Option;
    });
}


function showamounts() {
    let table = "";
    let Option_al = '<option value="" disabled>Select Allowances...</option>';
    let Option_ded = '<option value="" disabled>Select Deducations...</option>';
    
    let formData = new FormData();
    formData.append("command", "show_amounts");

    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // 1. Build Allowance options from dynamic data
        data.allowance.forEach(element => {
            Option_al += `<option value="${element['id']}">${element['department_allowances']} $${element['allowance_value']}</option>`;
        });

        // 2. Build Deduction options from dynamic data
        data.deducation.forEach(element => {
            Option_ded += `<option value="${element['id']}">${element['department_deducations']} $${element['deducation_value']}</option>`;
        });

        // 3. Build Employee Table Rows
        // let emp_option =`<option value="" selected disabled>Search...</option><option value="all">All Employees</option>`;
        let i = 1;
        data.employees.forEach(element => {
            // emp_option += `<option value="${element['id']}">${element['first_name']} $${element['last_name']}</option>`
            table += `
                <tr>
                    <td class="emp-id">#EMP-00${i++}</td>
                    <td>
                        <div class="emp-info">
                            <span class="emp-name">${element["first_name"]} ${element["last_name"]}</span>
                            <span class="emp-email">${element["email"]}</span>
                        </div>
                    </td>
                    <td>${element["department"]}</td>
                    <td>${element["phone"]}</td>
                    <td><span class="badge-status">${element["designation"]}</span></td>
                    <td class="text-end">
                        <div class="action-container">
                            <button class="btn-action">Actions</button>
                            <div class="actions-dropdown">
                                <button class="action-item" data-bs-toggle="modal" data-bs-target="#employeeModal" onclick="updaterow_employees(${element["id"]},'update_employees')">Edit Employee Detail</button>
                                <button class="action-item" onclick="deleterow_employees(${element["id"]},'delete_employees')">Delete Employee</button>
                                <button class="action-item mark-question-button" onclick="fuEmp(${element["id"]})">Mark Question</button>
                            </div>
                        </div>
                    </td>
                </tr>`;
        });

        // --- UPDATE DOM ---

        // Update the table first
        const tableBody = document.getElementById("employeeTableBody");
        // const optionBody = document.getElementById("bonus_Employees");
        // console.log(emp_option);
        

        if (tableBody) tableBody.innerHTML = table;
        // if (optionBody) optionBody.innerHTML = emp_option;

        // 4. Define Preline Configurations (using backticks for multiline)
        const allowanceConfig = `{
            "placeholder": "Select Allowances...",
            "toggleTag": "<button type=\\"button\\" aria-expanded=\\"false\\"></button>",
            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3 pe-9 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500",
            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden overflow-y-auto",
            "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100",
            "optionTemplate": "<div class=\\"flex justify-between items-center w-full\\"><span data-title></span><span class=\\"hs-selected-icon\\"><svg class=\\"shrink-0 size-3.5 text-blue-600\\" xmlns=\\"http://www.w3.org/2000/svg\\" width=\\"24\\" height=\\"24\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"3\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\"><polyline points=\\"20 6 9 17 4 12\\"/></svg></span></div>",
            "extraMarkup": "<div class=\\"absolute top-1/2 end-3 -translate-y-1/2\\"><svg class=\\"shrink-0 size-3.5 text-gray-500\\" xmlns=\\"http://www.w3.org/2000/svg\\" width=\\"24\\" height=\\"24\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"2\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\"><path d=\\"m7 15 5 5 5-5\\"/><path d=\\"m7 9 5-5 5 5\\"/></svg></div>"
        }`;

        const deductionConfig = `{
            "placeholder": "Select Deductions...",
            "toggleTag": "<button type=\\"button\\" aria-expanded=\\"false\\"></button>",
            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-3 pe-9 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500",
            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden overflow-y-auto",
            "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100",
            "optionTemplate": "<div class=\\"flex justify-between items-center w-full\\"><span data-title></span><span class=\\"hs-selected-icon\\"><svg class=\\"shrink-0 size-3.5 text-red-600\\" xmlns=\\"http://www.w3.org/2000/svg\\" width=\\"24\\" height=\\"24\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"3\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\"><polyline points=\\"20 6 9 17 4 12\\"/></svg></span></div>",
            "extraMarkup": "<div class=\\"absolute top-1/2 end-3 -translate-y-1/2\\"><svg class=\\"shrink-0 size-3.5 text-gray-500\\" xmlns=\\"http://www.w3.org/2000/svg\\" width=\\"24\\" height=\\"24\\" viewBox=\\"0 0 24 24\\" fill=\\"none\\" stroke=\\"currentColor\\" stroke-width=\\"2\\" stroke-linecap=\\"round\\" stroke-linejoin=\\"round\\"><path d=\\"m7 15 5 5 5-5\\"/><path d=\\"m7 9 5-5 5 5\\"/></svg></div>"
        }`;

        // 5. Inject full HTML into wrappers to reset Preline memory
        const alWrapper = document.getElementById('allowance-wrapper');
        const dedWrapper = document.getElementById('deduction-wrapper');

        if (alWrapper) {
            alWrapper.innerHTML = `<select id="allowance-select" name="allowances[]" multiple data-hs-select='${allowanceConfig}' class="hidden">${Option_al}</select>`;
        }
        if (dedWrapper) {
            dedWrapper.innerHTML = `<select id="deducation-select" name="deducations[]" multiple data-hs-select='${deductionConfig}' class="hidden">${Option_ded}</select>`;
        }

        // 6. Re-run Preline Auto-Init
        setTimeout(() => {
            if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
                window.HSStaticMethods.autoInit();
            }
        }, 150);
    })
    .catch(error => {
        console.error(`Submission error: ${error}`);
    });
}
showamounts();

function submit_employee() {   
    let form = document.querySelector("#employeeForm");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Show loading state on button
        let saveBtn = document.getElementById("employee-save-btn");
        saveBtn.innerHTML = "Saving...";
        saveBtn.disabled = true;
        let formData = new FormData(form);
        formData.append("command", "add_employee");


        fetch("http://localhost/php/ACR/backend/api/department/create.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            
            
            if (data.status === "success") {
                // 1. Show success alert
                alert(`${data.message}`, "green");

                // 2. Refresh the employee list table
                if (typeof showamounts() === "function") {
                    showamounts();
                }

                // 3. Close the Modal properly (Bootstrap way)
                let modalEl = document.getElementById('employeeModal');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    // Fallback if instance isn't found
                    modalEl.style.display = 'none';
                    document.querySelector('.modal-backdrop')?.remove();
                }

                // 4. Reset the form
                form.reset();
                
                // 5. Re-initialize Preline Selects to clear the silver pills
                // if (window.HSSelect) {
                //     HSSelect.reinit('#allowance-select');
                //     HSSelect.reinit('#deduction-select');
                // }
            } else {
                alert(`${data.message}`, "red");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Something went wrong!", "red");
        })
        .finally(() => {
            // Restore button state
            saveBtn.innerHTML = "Save Employee";
            saveBtn.disabled = false;
        });
    });
}

// Initialize the listener
submit_employee();
 function updaterow_employees(id,command){
     //    console.log("Deleting:", id, command);
     let saveBtn = document.getElementById("employee-save-btn");
     saveBtn.innerHTML="Edit Employees"
      fetch(`http://localhost/php/ACR/backend/api/department/get.php?id=${id}&command=${command}`,)
    .then(response => response.json())
    .then(data => {
             data.employees.forEach(element => {

               
                    
                    document.getElementById("first-name").value = element["first_name"];
                    document.getElementById("last-name").value = element["last_name"];
                    document.getElementById("employee-id").value = element["id"]; // Hidden input
                    document.getElementById("employee-email").value = element["email"];
                    document.getElementById("employee-phone").value = element["phone"];
                    document.getElementById("employee-salary").value = element["salary"];
                    document.getElementById("employee_dept_select").value = element["department_id"];
                    show_desig();
                    document.getElementById("employee_desig_select").value = element["designation_id"];
                    document.getElementById("emploees-addrees").value = element["residential_address"];
                    // console.log(element["designation_id"]);
                  // 1. Make sure the data actually exists before trying to map it
                if (element["allowance"] && element["allowance"].length > 0) {
                    
                    // 2. Extract the IDs into an array of strings: ["4", "9"]
                    let selectedAllowanceIds = element["allowance"].map(item => String(item.allowance_id));
                    
                    // 3. Use Preline's built-in v2 method to set the values
                    if (window.HSSelect) {
                        const hsSelectInstanceAllowance = window.HSSelect.getInstance('#allowance-select');
                        
                        if (hsSelectInstanceAllowance) {
                            // Preline v2 uses .setValue() with an array for multi-selects
                            hsSelectInstanceAllowance.setValue(selectedAllowanceIds);
                            
                        } else {
                            console.warn("Preline instance not found for #allowance-select");
                        }
                    }
                        
                        if (element["deducation"] && element["deducation"].length > 0) {
                            
                            let selectedDeducationIds = element["deducation"].map(item => String(item.deducation_id));
                            
                            if (window.HSSelect) {
                                
                                const hsSelectInstanceDeducation = window.HSSelect.getInstance('#deducation-select');
                                if (hsSelectInstanceDeducation) {
                                    // Preline v2 uses .setValue() with an array for multi-selects
                                    
                                    hsSelectInstanceDeducation.setValue(selectedDeducationIds);
                                } else {
                                    console.warn("Preline instance not found for #deducaton-select");
                                }
                             }




                        }
                    
                }

            

             });
      });

}



function deleterow_employees(id, command) {
    fetch(`http://localhost/php/ACR/backend/api/department/delete.php?id=${id}&command=${command}`)
    .then(response => response.json())
    .then(data => {
        showamounts()
        data["status"] == "success" ? alert(`${data["message"]}`, "red") : alert(`${data["message"]}`, "red");
    });
}

function md_employee(){
     let saveBtn = document.getElementById("employee-save-btn");
                        document.getElementById("first-name").value = "";
                    document.getElementById("last-name").value = "";
                    document.getElementById("employee-id").value = ""; // Hidden input
                    document.getElementById("employee-email").value = "";
                    document.getElementById("employee-phone").value = "";
                    document.getElementById("employee-salary").value = "";
                    document.getElementById("employee_dept_select").value = "";
                    document.getElementById("employee_desig_select").value = "";
                    document.getElementById("emploees-addrees").value = "";
                    document.getElementById("#allowance-select").innerHTML = "";
                    document.querySelectorAll('[data-hs-select]').forEach(select => {
                            const instance = HSSelect.getInstance(select, true);
                            if (instance) instance.setValue([]); 
                        });
                    saveBtn.innerHTML = "Save Employee";
}


    function fuEmp(id){
        let table ="";
        let htmlContent = "";
        let formData = new FormData();
        formData.append("command","mark_question");
        formData.append("emp_id",id);
        fetch("http://localhost/php/ACR/backend/api/department/get.php",{
            method: "POST",
            body: formData
        }).then(response => response.json())
        .then(data => {
            // console.log(data);
        if (data.success) {

            

            table = `<div class="main-container border">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="page-title">Mark Questions</h2>
                        <div class="page-subtitle">Rate employee performance</div>
                    </div>
                    <button class="back btn btn-outline-custom px-3 py-2 shadow-sm" onclick="back()">
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
                        <div class="info-value">${data.data[0].first_name} ${data.data[0].last_name}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Email</div>
                        <div class="info-value">${data.data[0].email}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Phone</div>
                        <div class="info-value">${data.data[0].phone}</div>
                    </div>
                    
                    <div class="col-md-4 mt-4">
                        <div class="info-label">Department</div>
                        <div class="info-value">${data.data[0].department}</div>
                    </div>
                    <div class="col-md-4 mt-4">
                        <div class="info-label">Designation</div>
                        <div class="info-value">${data.data[0].designation}</div>
                    </div>
                    <div class="col-md-4 mt-4">
                        <div class="info-label">Address</div>
                        <div class="info-value">${data.data[0].residential_address}</div>
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
                                <th style="width: 13%;">Total Rating</th>
                                <th style="width: 10%;text-align: center;">Points</th>
                            </tr>
                        </thead>
                        <tbody id="mark-question-table-body">
                        
                        </tbody>
                    </table>
                </div>`
                if(data.question != ""){
                        data.question.forEach(element => {
                        if (element["main_id"] && element["main_id"] != 0) {
                    // Sub-row Template
                    htmlContent += `
                    <tr class="question_row sub-row">
                        <td class="fw-bold subindex"></td>
                        <td>
                            <input type="text" name="sub_questions[][]" class="form-control input-question" value="${element["question"]}" placeholder="Enter sub-question...">
                        </td>
                        <td class="text-center">
                            <input type="number" name="sub_ratings[][]" value="${element["rating"]}" class="rating-box">
                        </td>
                        <td class="py-4 text-end">                         
                            <input type="number" name="ratings[]" value="0" class="rating-box" min="1">                                                
                        </td>
                    </tr>`;
                } else {
                    // Main-row Template
                    htmlContent += `
                    <tr class="question_row main-row border-b">
                        <td class="fw-bold py-4">1</td>
                        <td class="py-4">
                            <input type="text" name="questions[]" class="form-control input-question" value="${element["question"]}" placeholder="e.g. Technical Proficiency" required>
                        </td>
                        <td class="py-4 text-center">
                            <input type="number" name="ratings[]" value="${element["rating"]}" class="rating-box" min="1">
                        </td>
                        <td class="py-4 text-end">                         
                            <input type="number" name="ratings[]" value="0" class="rating-box" min="1">                        
                        </td>
                    </tr>`;
                }
                        });        
            document.querySelector(".mark-questions").innerHTML = table;
            document.querySelector("#mark-question-table-body").innerHTML = htmlContent;
            updateRowQueIndexes();
            }else {
                document.querySelector("#mark-question-table-body").innerHTML = `<tr><td colspan="4" class="text-center py-4">No records found.</td></tr>`;
            }

        }
            
        })

        document.querySelector(".emp-table").style.display="none";
       
    document.querySelector(".mark-questions").style.display="block"
    }
    
function back(){
    document.querySelector(".emp-table").style.display="block";    
    document.querySelector(".mark-questions").style.display="none"
}
function updateRowQueIndexes() {
    let mainIndex = 0;
    let subIndex = 0;
    const rows = document.querySelectorAll('.question_row');

    rows.forEach(row => {
        const indexCell = row.querySelector('td:first-child');

        if (row.classList.contains('main-row')) {
            mainIndex++;
            subIndex = 0; // Reset sub-index for new main question
            indexCell.textContent = mainIndex;
        } else if (row.classList.contains('sub-row')) {
            subIndex++;
            indexCell.textContent = `${mainIndex}.${subIndex}`;
        }
    });
}

// question acr 
    
 function fu(){

const questionBody = document.querySelector('.question_body');
const defaultQuestionBodyHTML = questionBody ? questionBody.innerHTML : '';
const form = document.getElementById("questionForm");

document.addEventListener("click", function(e){

    // BACK BUTTON
    if(e.target.closest(".show_question")){
        form.reset();
        document.getElementById("question_add").style.display = "none";
        document.getElementById("question_show").style.display = "block";

        if (questionBody) {
            questionBody.innerHTML = defaultQuestionBodyHTML;
        }
    }

    // ADD QUESTION BUTTON
    if(e.target.closest(".add-question-btn")){
        document.getElementById("question_add").style.display = "block";
        document.getElementById("question_show").style.display = "none";
        document.getElementById("question_desgnation").innerHTML = `<option value="">Select Designation</option>`;
    
        submit_evaluation_form();
        // form.reset;
    }

    if(e.target.closest(".edit-question")){
        let btn = e.target.closest(".edit-question");
        let dep_id = btn.dataset.dep;
        let des_id = btn.dataset.des;
        document.getElementById("question_department").value = dep_id;
        show_question_desig();  
        document.getElementById("question_desgnation").value = des_id;
        
 
    }

});

}
fu();
function show_question_desig() {    
    let desig = document.getElementById("question_desgnation");
    let dept_id = document.getElementById("question_department").value;
    let Option = "";


    // console.log(dept_id);
    let dataToSend = {
        "command": "show_desig_type",
        "id": dept_id
    };
    let formData = new FormData();
    for (let key in dataToSend) {
        formData.append(key, dataToSend[key]);
    }
    // formData.append(

    // );
    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            data.data.forEach(element => {
                Option += `<option value="${element['id']}">${element['designation']}</option>`
            });

            desig.innerHTML = Option;
        });
}
// Function to recalculate all indexes correctly
function updateRowIndexes() {
    let mainIndex = 0;
    let subIndex = 0;
    const rows = document.querySelectorAll('.question_body .question_row');

    rows.forEach(row => {
        const indexCell = row.querySelector('td:first-child');

        if (row.classList.contains('main-row')) {
            mainIndex++;
            subIndex = 0; // Reset sub-index for new main question
            indexCell.textContent = mainIndex;
        } else if (row.classList.contains('sub-row')) {
            subIndex++;
            indexCell.textContent = `${mainIndex}.${subIndex}`;
        }
    });
}

// Updated to insert exactly below the parent and use the "sub-row" class
// Replace your existing addSubRow function with this
function addSubRow(btn) {
    // Find the closest main row to get its index
    const parentRow = btn.closest('.main-row') || btn.closest('.sub-row').previousElementSibling;
    // Get the main question index from the first cell (the # column)
    const mainIndex = parseInt(parentRow.querySelector('td:first-child').textContent) - 1;

    const tr = document.createElement('tr');
    tr.className = "question_row sub-row";

    tr.innerHTML = `
    <td class="fw-bold subindex"></td>
    <td>
        <input type="text" name="sub_questions[${mainIndex}][]" class="form-control input-question" placeholder="Enter sub-question...">
    </td>
    <td class="text-center">
        <input type="number" name="sub_ratings[${mainIndex}][]" class="rating-box" value="10">
    </td>
    <td class="text-end">
        <div class="btn-group shadow-sm gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExtraRow(this)">×</button>
        </div>
    </td>`;

    // Logic to insert after the last sub-row of this group
    let insertAfterRow = btn.closest('.question_row');
    while (insertAfterRow.nextElementSibling && insertAfterRow.nextElementSibling.classList.contains('sub-row')) {
        insertAfterRow = insertAfterRow.nextElementSibling;
    }
    insertAfterRow.after(tr);
    updateRowIndexes();
}
// Updated to use the "main-row" class
function addExtraRow() {
    let question_body = document.querySelector(".question_body");
    const tr = document.createElement('tr');
    tr.className = "question_row main-row border-b"; // Tagged as main-row
    let n = 0;
    tr.innerHTML = `
    <td class="fw-bold py-4"></td>
    <td class="py-4">
        <input type="text" name="questions[]" class="form-control input-question" placeholder="e.g. Technical Proficiency" required>
    </td>
    <td class="py-4 text-center">
        <input type="number" name="ratings[]" class="rating-box" value="10" min="1">
    </td>
    <td class="py-4 text-end">
        <div class="btn-group gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addSubRow(this)">+ Sub</button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExtraRow(this)">×</button>
        </div>
    </td>
`;

    question_body.appendChild(tr);
    updateRowIndexes(); // Recalculate
}

    // Updated to recalculate after deleting
function removeExtraRow(button) {
    const row = button.closest('.question_row');

    // If we are deleting a main row, we should also delete all 
    // sub-rows immediately following it until the next main row.
    if (row.classList.contains('main-row')) {
        let nextRow = row.nextElementSibling;
        while (nextRow && nextRow.classList.contains('sub-row')) {
            let rowToRemove = nextRow;
            nextRow = nextRow.nextElementSibling; // Move to next before removing
            rowToRemove.remove();
        }
    }

    // Remove the actual row clicked
    row.remove();

    // Recalculate numbering
    updateRowIndexes();
}
    
function submit_evaluation_form() {
    const form = document.getElementById("questionForm");
    
    if (!form) {
        console.log("Form not found!");
        return;
    }

    // ✅ Prevent duplicate listeners with a simple flag
    if (form.dataset.listenerAttached === "true") return;
    form.dataset.listenerAttached = "true";
    // save the original default rows (the HTML that was on page load)
    const questionBody = document.querySelector('.question_body');
    const defaultQuestionBodyHTML = questionBody ? questionBody.innerHTML : '';

    const saveBtn = document.getElementById("question-save-btn");

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const originalBtnText = saveBtn.innerHTML;
        saveBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Saving...`;
        saveBtn.disabled = true;

        let formData = new FormData(form);
        formData.append("command", "add_question");

        fetch("http://localhost/php/ACR/backend/api/department/create.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            if (data.status === "success") {                
                alert(data.message || "Evaluation saved successfully!","green");
                document.getElementById("question_add").style.display = "none";
                document.getElementById("question_show").style.display = "block";
                form.reset();
                if (questionBody) {
                    questionBody.innerHTML = defaultQuestionBodyHTML;
                }
                question_table();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error(`Submission error: ${error}`);
            alert("Network error. Please check if the backend is running.","red");
        })
        .finally(() => {
            saveBtn.innerHTML = originalBtnText;
            saveBtn.disabled = false;
        });
    });
}
    
function question_table() {
    let i = 1;
    let table = "";
    let formData = new FormData();
    formData.append("command", "show_question_depts");

    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const seen = new Set();
            if(data.data != ""){
            data.data.forEach(element => {
                // console.log(data);
                
                // Unique key = department_id + '-' + designation_id
                const key = element.department_id + '-' + element.designation_id;
                // console.log(key);
                
                if (!seen.has(key)) {
                    seen.add(key);

                    table += `<tr>
                        <td style="padding: 12px;">#${i++}</td>
                        <td style="padding: 12px; color: var(--text-dark); font-weight: 500;">${element.department}</td>
                        <td style="padding: 12px; color: var(--text-dark); font-weight: 500;">${element.designation}</td>
                        <td style="padding: 12px;" class="text-end">
                            <div class="action-container">
                                <button class="btn-action">Actions</button>
                                <div class="actions-dropdown">
                                    <button class="action-item add-question-btn edit-question" data-dep="${element.department_id}" data-des="${element.designation_id}" onclick="edit_question(${element.designation_id},'update_question')">Edit Question Detail</button>
                                    <button class="action-item" onclick="deleterow_question(${element.designation_id},'delete_question')">Delete Question</button>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                }
            });

            document.querySelector("#question-table-body").innerHTML = table;
        } else {
            document.querySelector("#question-table-body").innerHTML = `<tr><td colspan="4" class="text-center py-4">No records found.</td></tr>`;
        }
        }
    });
}

question_table()
function deleterow_question(id,command){
 // example id

 
 fetch(`http://localhost/php/ACR/backend/api/department/delete.php?id=${id}&command=${command}`,)
  .then(response => response.json())
  .then(data => {
    //   console.log(data);
      question_table();
       data["status"] == "success" ? alert(`${data["message"]}`,"red"): alert(`${data["message"]}`,"red") ;

  })
//   .catch(error => console.error("Error:", error));

   }

function edit_question(desId, command) {
    // 1. Target the actual DOM element, not just a string
    let tableBody = document.querySelector(".question_body"); 
    tableBody.innerHTML = ""; // Clear existing content before loading new data

    document.getElementById("desgnation_id").value = desId;

    let formData = new FormData();
    formData.append("command", "update_question");

    fetch(`http://localhost/php/ACR/backend/api/department/get.php?id=${desId}&command=${command}`, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            let htmlContent = ""; // Build string first for better performance

            data.data.forEach(element => {
                // 2. Logic fix: Check if it's a sub-question or main question
                // Assuming element["main_id"] exists and is NOT 0/null for sub-questions
                if (element["main_id"] && element["main_id"] != 0) {
                    // Sub-row Template
                    htmlContent += `
                    <tr class="question_row sub-row">
                        <td class="fw-bold subindex"></td>
                        <td>
                            <input type="text" name="sub_questions[][]" class="form-control input-question" value="${element["question"]}" placeholder="Enter sub-question...">
                        </td>
                        <td class="text-center">
                            <input type="number" name="sub_ratings[][]" value="${element["rating"]}" class="rating-box">
                        </td>
                        <td class="text-end">
                            <div class="btn-group shadow-sm gap-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExtraRow(this)">×</button>
                            </div>
                        </td>
                    </tr>`;
                } else {
                    // Main-row Template
                    htmlContent += `
                    <tr class="question_row main-row border-b">
                        <td class="fw-bold py-4">1</td>
                        <td class="py-4">
                            <input type="text" name="questions[]" class="form-control input-question" value="${element["question"]}" placeholder="e.g. Technical Proficiency" required>
                        </td>
                        <td class="py-4 text-center">
                            <input type="number" name="ratings[]" value="${element["rating"]}" class="rating-box" min="1">
                        </td>
                        <td class="py-4 text-end">
                            <div class="btn-group gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addSubRow(this)">+ Sub</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExtraRow(this)">×</button>
                            </div>
                        </td>
                    </tr>`;
                }
            });

            // 3. Inject the compiled HTML into the table
            tableBody.innerHTML = htmlContent;
            updateRowIndexes();
            document.getElementById("question_add").style.display = "block";
            document.getElementById("question_show").style.display = "none";
        }
    })
    .catch(error => console.error('Error:', error));
}

// bonus & subtraction


function showEditForm() {
    document.querySelector(".bonuses_table").style.display = "none";
    document.querySelector(".add_bonuses").style.display = "block";
    
}

function showTableList() {
    document.querySelector(".add_bonuses").style.display = "none";
    document.querySelector(".bonuses_table").style.display = "block";
    let bonous_id = document.getElementById("bonus_id").value = '';
        let bonus_Department = document.getElementById("bonus_Department").value = 'all';
        let bonus_Employees = document.getElementById("bonus_Employees").value = 'all';
        let month = document.getElementById("month").value = 1;
        let year = document.getElementById("year").value = 2026;
        let Adjustment_Type = document.getElementById("fine").value= 0;
        fetchAllEmployees();
        fineChange(true);

}



    let globalEmployeesData = []; 
    let currentInputTemplate = '';

    // Initialize Page
    (function init() {
        const yearSelect = document.getElementById("year");
        const currentYear = new Date().getFullYear();
        for (let i = 2024; i <= 2035; i++) {
            yearSelect.innerHTML += `<option value="${i}" ${i === currentYear ? 'selected' : ''}>${i}</option>`;
        }
        fetchAllEmployees();
    })();

    // Fetch data from backend
    function fetchAllEmployees(id = null, command = null) {
        let formData = new FormData();

        if (id && command) {
            // This is an EDIT/UPDATE call
            if (command === "update_bonus") {
                showEditForm();
               formData.append("command", "show_bonus_employees");
               formData.append("update_id", id);
               if (id) {
                let bonous_id = document.getElementById("bonus_id");
                if(bonous_id){
                    bonous_id.value = id;
                }
               }
            }

            }
            else if (command === "delete_bonus") {
                if (!confirm("Are you sure you want to delete this bonus record?")) return;
                formData.append("command", command);
                formData.append("id", id);
                // let type = document.getElementById("fine").value = 3 ;
                // fineChange();   
                // CRITICAL: Switch to the form view so the user sees the result
                showEditForm();
            }

         else {
            // This is the INITIAL LOAD call
            formData.append("command", "show_bonus_employees");
        }

        fetch("http://localhost/php/ACR/backend/api/department/get.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            
            // console.log(res);
            
            if (res.success && res.data) {
                // If it's a single employee update, we update the global data
                // or filter it to show just that person in the edit table
                globalEmployeesData = Array.isArray(res.data) ? res.data : [res.data];
                
                populateEmployeeSelect(globalEmployeesData);
                renderTable(globalEmployeesData);
            } else {
                renderEmptyTable();
            }
        })
        .catch((err) => {
            console.error("Fetch Error:", err);
            renderEmptyTable();
        });
    }
    
    // FIX 1: Handle Department Change Cleanly
    function handleDepartmentChange() {
        const deptId = document.getElementById("bonus_Department").value;
        
        // Filter the main data set based on department_id
        console.log(globalEmployeesData);
        
        const filtered = (deptId === "all") 
            ? globalEmployeesData 
            : globalEmployeesData.filter(emp => emp.department_id == deptId);
    console.log(filtered);
    
        // Update the employee dropdown
        populateEmployeeSelect(filtered);
        
        // FIX 2: Trigger the employee filter immediately to refresh the table
        filterByEmployee();
    }

    function filterByEmployee() {
        let data = globalEmployeesData;
        const empId = document.getElementById("bonus_Employees").value;
        const deptId = document.getElementById("bonus_Department").value;

        // 1. Filter by Department first
        if(deptId){
             data = (deptId === "all") 
                ? globalEmployeesData 
                : globalEmployeesData.filter(emp => emp.department_id == deptId);
        }
        // 2. Filter by Specific Employee if not "all"
        if (empId !== "all") {
            data = data.filter(emp => emp.id == empId);
        }

        // 3. Render whatever is left (even if empty)
        renderTable(data);
    }

    function populateEmployeeSelect(data) {
        const select = document.getElementById("bonus_Employees");
        const selectpayroll = document.getElementById("employepayroll");
        let options = '<option value="all">All Employees</option>';
        data.forEach(emp => {
            options += `<option value="${emp.id}">${emp.first_name} ${emp.last_name}</option>`;
        });
        select.innerHTML = options;
        selectpayroll.innerHTML = options;
    }


function fineChange(shouldRender = true) {
    const typeVal = document.getElementById("fine").value;
    const header = document.getElementById("dynamic-header");
    
    // We use SPANS with classes as placeholders so we can inject 
    // the correct individual data after the table renders.
    switch (typeVal) {
        case '1': // Fine Only
            header.innerText = "Fine Amount";
            currentInputTemplate = `<td>
                <input type="number" oninput="cal(this)" class="fine-input mt-4 form-control form-control-sm" placeholder="Fine $">
                <div class="mt-1 text-danger fw-medium" style="font-size: 0.65rem;">
                    <i class="fas fa-minus-circle opacity-50 me-1"></i> Prev: $<span class="ind-fine">0.00</span>
                </div>
            </td>`;
            break;

        case '2': // Bonus Only
            header.innerText = "Bonus Amount";
            currentInputTemplate = `` + "<td>" + `
                <input type="number" oninput="cal(this)" class="bonus-input mt-4 form-control form-control-sm" placeholder="Bonus $">
                <div class="mt-1 text-success fw-medium" style="font-size: 0.65rem;">
                    <i class="fas fa-plus-circle opacity-50 me-1"></i> Prev: $<span class="ind-bonus">0.00</span>
                </div>
            </td>`;
            break;

        case '3': // Both
            header.innerText = "Fine & Bonus";
            currentInputTemplate = `<td>
                <div class="d-flex gap-1 mt-4">
                    <input type="number" oninput="cal(this)" class="fine-input form-control form-control-sm" placeholder="Fine $">
                    <input type="number" oninput="cal(this)" class="bonus-input form-control form-control-sm" placeholder="Bonus $">
                </div>
                <div class="d-flex justify-content-between mt-1 px-1">
                    <span class="text-success fw-medium" style="font-size: 0.65rem;">B: $<span class="ind-bonus">0.00</span></span>
                    <span class="text-danger fw-medium" style="font-size: 0.65rem;">F: $<span class="ind-fine">0.00</span></span>
                </div>
            </td>`;
            break;

        default:
            header.innerText = "Adjustment";
            currentInputTemplate = `<td>-</td>`;
    }

    if (shouldRender) {
        filterByEmployee();
        refreshIndividualTotals();
    }
}

// 2. The Fixer Function (Logic to handle individual row data)
function refreshIndividualTotals() {
    // Select all rows in the body
    const rows = document.querySelectorAll("#bouns-body tr");

    rows.forEach(row => {
        // Find the Employee ID input that renderTable generated
        const empIdInput = row.querySelector(".bonus_Employees_id");
        if (!empIdInput) return;

        const empId = empIdInput.value;

        // Find this specific employee in your global array
        const empData = globalEmployeesData.find(emp => emp.id == empId);

        if (empData && empData.payroll_fine_bonus) {
            let pBonus = 0;
            let pFine = 0;

            // Calculate ONLY for this one employee
            empData.payroll_fine_bonus.forEach(detail => {
                pBonus += parseFloat(detail.bonus_amount || 0);
                pFine += parseFloat(detail.fine_amount || 0);
            });

            // Find the placeholder spans we put in the template
            const bonusSpan = row.querySelector(".ind-bonus");
            const fineSpan = row.querySelector(".ind-fine");

            // Inject the correct individual numbers
            if (bonusSpan) bonusSpan.innerText = pBonus.toLocaleString(undefined, { minimumFractionDigits: 2 });
            if (fineSpan) fineSpan.innerText = pFine.toLocaleString(undefined, { minimumFractionDigits: 2 });
        }
    });
}
function cal(input) {
        let row = input.closest('tr');
        let base = parseFloat(row.querySelector('.base-salary-cell').dataset.base) || 0;
        let fine = parseFloat(row.querySelector('.fine-input')?.value) || 0;
        let bonus = parseFloat(row.querySelector('.bonus-input')?.value) || 0;
        
        let total = base + bonus - fine;
        row.querySelector('.total-payout-display').innerText = `$ ${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    }
function renderTable(dataArray) {
    let tableBody = document.getElementById("bouns-body");
    let N=1;
    let html = "";

    if (dataArray && dataArray.length > 0) {
        // console.log(dataArray);
        
    let bonuses_details = dataArray[0].bonuses_details;
    let payroll_bonuses_details = dataArray[0].payroll_bonuses_details;


       if (Array.isArray(payroll_bonuses_details) && payroll_bonuses_details.length > 0) {
    payroll_bonuses_details.forEach(detail => {

        let bonous_id = document.getElementById("bonus_id");
        let bonus_Department = document.getElementById("bonus_Department");
        let bonus_Employees = document.getElementById("bonus_Employees");
        let month = document.getElementById("month");
        let year = document.getElementById("year");
        let Adjustment_Type = document.getElementById("fine").value= detail.type;

        if (bonous_id) {
            bonous_id.value = detail.id;
            bonus_Department.value = "all";
            bonus_Employees.value = detail.selection_mode;
            month.value = detail.month;
            year.value = detail.year;
         
           
        }
    });

    // Set the input template without triggering a nested table render.
    fineChange(false);
}
        dataArray.forEach(emp => {
            const allowances = emp.allowances?.reduce((s, n) => s + parseFloat(n.allowance_value || 0), 0) || 0;
            const deductions = (emp.deducation || emp.deducations)?.reduce((s, n) => s + parseFloat(n.deducation_value || 0), 0) || 0;
            const baseAmount = (parseFloat(emp.salary) || 0) + allowances - deductions;
            const salary = (parseFloat(emp.salary) || 0);
html += `
    <tr class="align-middle"> 
        <td class="text-muted font-monospace small" style="width: 50px;">#${N++}</td>
        
        <td>
            <div class="fw-bold mb-0" style="color: var(--text-dark); line-height: 1.2;">
                ${emp.first_name} ${emp.last_name}
            </div>
            <div class="text-muted" style="font-size: 0.75rem;">${emp.designation || 'Staff'}</div>
            <input type="hidden" value="${emp.department_id}" class="bonus_Department_id">
            <input type="hidden" value="${emp.id}" class="bonus_Employees_id">
            
        </td>
        
        <td style="white-space: nowrap;">
            <span class="badge-dept">${emp.department}</span>
        </td>
        
       ${currentInputTemplate || `
            <td style="min-width: 250px;">
                <div class="adjustment-container">
                    <span class="text-muted small italic">Select adjustment type...</span>
                </div>
            </td>
        `}
        
        <td class="base-salary-cell" data-base="${baseAmount}">
            <div class="d-flex flex-column">
                <div class="fw-bold mb-1" style="color: var(--text-dark)">$${salary.toLocaleString()}</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="breakdown-item text-success-custom py-0 px-1" style="font-size: 0.65rem;">
                        <i class="fas fa-plus-circle opacity-50"></i> All: $${allowances}
                    </div>
                    <div class="breakdown-item text-danger-custom py-0 px-1" style="font-size: 0.65rem;">
                        <i class="fas fa-minus-circle opacity-50"></i> Ded: $${deductions}
                    </div>
                </div>
            </div>
        </td>

        <td class="text-end">
            <div class="total-payout-display fw-bold" style="color: var(--primary-color); font-size: 1.15rem;">
                $${baseAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}
            </div>
        </td>
    </tr>`;
});
        tableBody.innerHTML = html;

        if (Array.isArray(bonuses_details) && bonuses_details.length > 0) {
            bonuses_details.forEach(detail => {
                const row = [...tableBody.querySelectorAll("tr")].find(row =>
                    row.querySelector(".bonus_Employees_id")?.value == detail.employee_id
                );
                     
                if (!row) return;

                const fineInput = row.querySelector(".fine-input");
                const bonusInput = row.querySelector(".bonus-input");

                if (fineInput) fineInput.value = detail.fine_amount || 0;
                if (bonusInput) bonusInput.value = detail.bonus_amount || 0;
                if (fineInput || bonusInput) cal(fineInput || bonusInput);
            });
        }

        refreshIndividualTotals();
    } else {
        renderEmptyTable();
    }
}


function renderEmptyTable() {
        document.getElementById("bouns-body").innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">No records found for this selection.</td></tr>`;
    }

    function submitFandB() {
        const rows = document.querySelectorAll("#bouns-body tr");
        const submissionData = [];
        

        rows.forEach(row => {
            const EmpId = row.querySelector('.bonus_Employees_id').value;
            const DepId = row.querySelector('.bonus_Department_id').value;
            if(!EmpId || isNaN(EmpId)) return;
                console.log(EmpId);
                console.log("dep id:" + DepId);
                
            submissionData.push({
                employee_id: EmpId || 0 ,
                department_id: DepId || 0 ,
                bonus: parseFloat(row.querySelector('.bonus-input')?.value) || 0,
                fine: parseFloat(row.querySelector('.fine-input')?.value) || 0
            });
        });

        if(submissionData.length === 0) {
            alert("No data to save.");
            return;
        }
        let type = document.getElementById("fine").value;
        if(type > 0){

        const payload = {
            selected_department_id: document.getElementById("bonus_Department").value || null,
            update_id: document.getElementById("bonus_id").value || null,
            month: document.getElementById("month").value,
            year: document.getElementById("year").value,
            selection_mode:document.getElementById("bonus_Employees").value,
            type: document.getElementById("fine").value,
            data: submissionData,
            command: "Add_bonus&fine"
        };
                console.log(payload);
             
        fetch("http://localhost/php/ACR/backend/api/department/create.php", {
            method: "POST",
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            
            alert(data.message || "Payroll adjustments saved successfully!","green");
        })
        .catch(err => alert("Submission failed.","red"));
        }else{
                alert("select Adjustment Type","black")
            }  
    }
function bonous_table() {
        let formData = new FormData();
        formData.append("command", "show_payroll");



    fetch("http://localhost/php/ACR/backend/api/department/get.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        let html = '';
        const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
        let n = 1;
        if (res.success && res.data) {
            res.data.forEach(item => {
                // Map the 'type' number to a label
                let typeLabel = "N/A";
                if (item.type == "1") typeLabel = "Bonus";
                else if (item.type == "2") typeLabel = "Fine";
                else if (item.type == "3") typeLabel = "Both";

                // Array to map month numbers to names


                // Get the name using the index (item.month - 1 because arrays start at 0)
                let monthLabel = monthNames[parseInt(item.month) - 1] || "Unknown";



                                
                                // Handle Employee Name if null
                let employeeName = "";
                if (item.first_name) {
                    employeeName = `${item.first_name} ${item.last_name}`;
                } else if (!item.department && !item.first_name) {
                    employeeName = '<span class="badge bg-secondary-subtle text-secondary">All Employees</span>';
                } else {
                    employeeName = '<span class="text-muted small">Entire Dept</span>';
                }

                // 2. Determine Department Display
                let departmentName = item.department ? item.department : "Global / All";

                html += `
                    <tr>
                        <td class="id-cell">#BOS-00${n++}</td>
                        <td>${monthLabel}</td>
                        <td>${item.year}</td>
                        <td><span class="badge bg-light text-dark border">${typeLabel}</span></td>
                        <td style="white-space: nowrap;">${departmentName}</td>
                        <td>${employeeName}</td>
                        <td class="text-end">
                            <div class="action-container">
                                <button class="btn-action">Actions</button>
                                <div class="actions-dropdown">
                                    <button class="action-item" onclick="fetchAllEmployees(${item.id}, 'update_bonus')">Edit Bonus Detail</button>
                                    <button class="action-item" onclick="deleterow_bonus(${item.id},'delete_bonus')">Delete Bonus</button>
                                </div>
                            </div>
                        </td>
                    </tr>`;
            });
        }
        // Inject the rows into the tbody
        document.getElementById("bonus-list-content").innerHTML = html;
    })
    .catch(err => console.error("Error loading table:", err));
}

// Call the function when the page loads
bonous_table();
function deleterow_bonus(id,command){
     fetch(`http://localhost/php/ACR/backend/api/department/delete.php?id=${id}&command=${command}`,)
  .then(response => response.json())
  .then(data => {
    //   console.log(data);
      bonous_table();
       data["status"] == "success" ? alert(`${data["message"]}`,"red"): alert(`${data["message"]}`,"red") ;

  })
}

function edit_bonus(id,command) {

     fetch(`http://localhost/php/ACR/backend/api/department/get.php?id=${id}&command=${command}`,)
  .then(response => response.json())
  .then(data => {
     console.log(data);
    
  })
}