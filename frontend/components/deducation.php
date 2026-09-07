<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 style="color: var(--primary-color); font-weight: 300;">Deducation</h3>
    <button onclick="md_deducation()" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#deducationModal" style="background-color: var(--primary-color); border-radius: var(--radius); border: none;">
        <i class="bi bi-plus-lg"></i> Add DEDUCATION
    </button>
</div>

<div class="table-responsive">
    <table class="table align-middle" style="border: 1px solid var(--border-color); font-size: 14px;">
        <thead style="background-color: var(--table-head-bg); color: var(--text-dark);">
            <tr>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">ID</th>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">DEDUCATION Reason</th>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);">DEDUCATION Amount</th>
                <th style="padding: 12px; border-bottom: 2px solid var(--border-color);" class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody id="deducationTableBody" style="color: var(--text-light);">
            <tr>
                <td colspan="4" class="text-center py-4">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2 mb-0">Loading...</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="deducationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--radius); border: 1px solid var(--border-color);">
            <div class="modal-header" style="background-color: var(--bg-light); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" style="color: var(--text-dark); font-size: 1rem; font-weight: 600;">NEW DEDUCATION</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <form id="deducationForm">
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">DEDUCATION REASON</label>
                        <input type="text" id="deducation_name" name="deducation_name" class="form-control" placeholder="e.g. Late Arrival" style="border-radius: var(--radius); border: 1px solid var(--border-color);">
                    </div>
                    
                    <input type="hidden" id="deducation-id" name="deducation_id">
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--text-light); font-size: 13px; font-weight: 600;">DEDUCATION AMOUNT</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--bg-light); border: 1px solid var(--border-color); color: var(--text-light);">$</span>
                            <input type="number" step="0.01" id="deducation_value" name="deducation_value" class="form-control" placeholder="0.00" style="border-radius: 0 var(--radius) var(--radius) 0; border: 1px solid var(--border-color);">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="color: var(--text-light);">Cancel</button>
                    <button type="submit" id="deducation-save-btn" class="btn btn-sm" style="background-color: var(--primary-color); color: #fff; border-radius: var(--radius); padding: 8px 20px;">Save</button>
                </div>
            </form> 
        </div>
    </div>
</div>

