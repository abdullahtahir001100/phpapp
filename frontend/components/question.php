
<div id="question_show">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 style="color: var(--primary-color); font-weight: 300;">Evaluation Questions</h3>
            <button  class="btn btn-dark add-question-btn" data-bs-toggle="modal"
                data-bs-target="#questionModal"
                style="background-color: var(--primary-color); border-radius: var(--radius); border: none;">
                <i class="bi bi-plus-lg"></i> Add Question
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle" style="border: 1px solid var(--border-color); font-size: 14px;">
                <thead style="background-color: var(--table-head-bg); color: var(--text-dark);">
                    <tr>
                        <th style="border-bottom: 2px solid var(--border-color);">ID</th>
                        <th style="border-bottom: 2px solid var(--border-color);">Question Description
                        </th>
                        <th style="border-bottom: 2px solid var(--border-color);">Status
                        </th>
                        <th style="border-bottom: 2px solid var(--border-color);"
                            class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="question-table-body" style="color: var(--text-light);">
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="spinner-border text-warning" role="status"></div>
                            <p class="mt-2 mb-0" style="font-size: 13px;">Loading questions...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

</div>

<div class="bg-white border border-gray-200 shadow-sm max-w-5xl mx-auto text-gray-800 font-sans" id="question_add">
    
    <div class="flex justify-between items-center p-6 border-b border-gray-100">
        <div>
            <h2 class="text-[22px] font-semibold text-gray-800 tracking-tight">Create Evaluation</h2>
            <p class="text-gray-500 mt-1">Configure department-specific performance metrics</p>
        </div>
        <button type="button" class="show_question py-1.5 px-3 inline-flex items-center gap-x-2 font-medium rounded-sm border border-gray-300 bg-white text-gray-600 shadow-sm hover:bg-gray-50">
            &larr; Back
        </button>
    </div>

    <form id="questionForm">
        
        <div class="p-6 bg-[#fafafa] border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-gray-600">Department</label>
                    <select id="question_department" name="question_dept" onchange="show_question_desig()" required
                        class="py-2.5 px-3 block w-full border border-gray-300 rounded-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Select Department</option>
                        </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-gray-600">Designation</label>
                    <select id="question_desgnation"  name="question_desig"required
                        class="py-2.5 px-3 block w-full border border-gray-300 rounded-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Select Designation</option>
                        </select>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="table-responsive">
                <table class="table w-full align-middle">
                    <thead>
                        <tr class="text-uppercase text-xs font-bold text-gray-500 border-b">
                            <th class="pb-3 text-start" style="width: 60px;">#</th>
                            <th class="pb-3 text-start">Question Description</th>
                            <th class="pb-3 text-center" style="width: 120px;">Max Rating</th>
                            <th class="pb-3 text-end" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <input type="text" id="desgnation_id" name="desgnation_id">
                    <tbody class="question_body">
                        <tr class="question_row main-row border-b">
                            <td class="fw-bold py-4">1</td>
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
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" onclick="addExtraRow();" 
                class="mt-4 w-full py-3 flex justify-center items-center gap-x-2 text-sm font-medium text-gray-500 border border-dashed border-gray-300 rounded-sm hover:bg-gray-50 hover:border-gray-400 transition-all">
                <i class="bi bi-plus-circle"></i> Add Main Question
            </button>
        </div>

        <div class="border-t border-gray-200 p-6 flex justify-end gap-x-3 bg-white">
            <button type="button" class="show_question py-2 px-5 text-sm font-medium rounded-sm border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit" id="question-save-btn" class="py-2 px-8 text-sm font-medium rounded-sm bg-[#1c1c1c] text-white hover:bg-black transition-colors">
                Save Evaluation
            </button>
        </div>
    </form>
</div>