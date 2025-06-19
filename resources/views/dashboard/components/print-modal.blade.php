<div class="modal fade" id="printReportModal" tabindex="-1" aria-labelledby="printReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="printReportModalLabel">Print Report</h5>
                <button type="button" class="border-0 bg-transparent text-lg" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="startDate">Start Date</label>
                    <input type="datetime-local" name="startDate" id="startDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="endDate">End Date</label>
                    <input type="datetime-local" name="endDate" id="endDate" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <a target="_blank" id="printBtn" class="btn btn-primary">Print</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
