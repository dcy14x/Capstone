<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="../controller/inventoryController.php" enctype="multipart/form-data">
                <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Image</label>
                                <input type="file" name="images" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Product Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter product name">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" rows="6" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="price" class="form-control" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock Quantity</label>
                                <input type="number" name="quantity" class="form-control" placeholder="0">
                            </div>
                        </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="../controller/inventoryController.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <input hidden name="id" id="edit_id">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Image</label>
                                <input type="file" name="images" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Product Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter product name">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="edit_description" rows="6" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="price" id="edit_price" class="form-control" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock Quantity</label>
                                <input type="number" name="quantity" id="edit_quantity" class="form-control" placeholder="0">
                            </div>
                        </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

