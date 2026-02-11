<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="../controller/inventoryController.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <input hidden name="id" id="delete_id">
                    <h4 class="text-center">Are you sure you delete this data?</h4>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>