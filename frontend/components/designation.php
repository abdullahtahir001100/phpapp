<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--primary-color); font-weight: 300;">Designations</h3>
    <button onclick="md_designation()" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#designationModal" style="background-color: var(--primary-color); border-radius: var(--radius); border: none;">
        <i class="bi bi-plus-lg"></i> Add Designation
    </button>
</div>

<div class="table-responsive">
    <table class="table align-middle" style="border: 1px solid var(--border-color); font-size: 14px;">
        <thead style="background-color: var(--table-head-bg); color: var(--text-dark);">
            <tr>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">ID</th>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">Designation Title</th>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">Level/Type</th>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);" class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody id="designationTableBody" style="color: var(--text-light);">
            <tr>
                <td colspan="4" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 mb-0">Loading...</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="designationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--radius); border: 1px solid var(--border-color);">
            <div class="modal-header" style="background-color: var(--bg-light); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" style="color: var(--text-dark); font-size: 1rem; font-weight: 600;">NEW DESIGNATION</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <form id="designationForm">
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">DESIGNATION TITLE</label>
                        <input type="text" id="designation_name" name="designation_name" class="form-control" placeholder="e.g. Senior Manager" style="border-radius: var(--radius); border: 1px solid var(--border-color);">
                    </div>
                    
                    <input type="hidden" id="designation-id" name="designation_id">
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">RANK / LEVEL</label>
                        <select class="form-select" id="department" name="department_id" style="border-radius: var(--radius); border: 1px solid var(--border-color);">
                            
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="color: var(--text-light);">Cancel</button>
                    <button type="submit" id="designation-save-btn" class="btn btn-sm" style="background-color: var(--primary-color); color: #fff; border-radius: var(--radius); padding: 8px 20px;">Save</button>
                </div>
            </form> 
        </div>
    </div>
</div>
