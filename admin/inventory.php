<?php 
    include '../config/config.php';
    include '../includes/session.php';

  class data extends Connection{ 
    public function managedata(){ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <link rel="stylesheet" href="../asset/css/modern-admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow-1" style="margin-left: 260px;">
        <?php include '../includes/navbar.php'; ?>

        <div class="p-4">            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1 fw-bold">Product Inventory</h5>
                            <p class="text-muted small mb-0">Manage and track your stock levels</p>
                        </div>
                        <button class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus-lg me-2"></i>Add New Product
                        </button>
                    </div>                        

                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Product Details</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT * FROM products ORDER BY id DESC";
                                $stmt = $this->conn()->query($sql);
                                while ($row = $stmt->fetch()) { 
                                    $status_class = $row['quantity'] > 10 ? 'bg-success' : ($row['quantity'] > 0 ? 'bg-warning' : 'bg-danger');
                                    $status_text = $row['quantity'] > 10 ? 'In Stock' : ($row['quantity'] > 0 ? 'Low Stock' : 'Out of Stock');
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../images/<?= $row['images']; ?>" class="rounded-3 me-3" width="50" height="50" style="object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold"><?= $row['name']; ?></div>
                                                    <div class="text-muted small text-truncate" style="max-width: 200px;"><?= $row['description']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold">₱<?= number_format($row['price'], 2); ?></td>
                                        <td>
                                            <div class="fw-bold"><?= $row['quantity']; ?></div>
                                        </td>
                                        <td>
                                            <span class="badge <?= $status_class ?> bg-opacity-10 text-<?= explode('-', $status_class)[1] ?> px-3 py-2 rounded-pill">
                                                <?= $status_text ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button class="edit btn btn-sm btn-light rounded-circle p-2 me-1" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                            data-edit_id="<?= $row['id']; ?>"
                                            data-edit_name="<?= $row['name']; ?>"
                                            data-edit_description="<?= $row['description']; ?>"
                                            data-edit_price="<?= $row['price']; ?>"
                                            data-edit_quantity="<?= $row['quantity']; ?>"
                                            >
                                                <i class="bi bi-pencil text-primary"></i>
                                            </button>
                                            <button class="delete btn btn-sm btn-light rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-delete_id="<?= $row['id']; ?>">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <?php include 'modal/inventory.php'; ?>
    <?php include 'modal/delete.php'; ?>
    <?php include '../includes/footer.php'; ?>

    <script>
        $('.edit').click(function(){
            $('#edit_id').val($(this).data('edit_id'))
            $('#edit_name').val($(this).data('edit_name'))
            $('#edit_description').val($(this).data('edit_description'))
            $('#edit_price').val($(this).data('edit_price'))
            $('#edit_quantity').val($(this).data('edit_quantity'))
        })
        $('.delete').click(function(){
            $('#delete_id').val($(this).data('delete_id'))
        })
    </script>

</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>