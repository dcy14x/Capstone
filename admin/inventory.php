<?php 
    include '../config/config.php';
    include '../includes/session.php';
?>
<?php 
  class data extends Connection{ 
    public function managedata(){ 
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body class="bg-light">

    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-grow-1" style="margin-left: 250px;">

        <!-- Navbar -->
        <?php include '../includes/navbar.php'; ?>

        <!-- Page Content -->
        <div class="p-4">            

            <!-- Inventory Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold">Product Inventory</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus-circle me-2"></i>Add New Product
                        </button>
                    </div>                        

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sql = "SELECT * FROM products";
                                $stmt = $this->conn()->query($sql);
                                while ($row = $stmt->fetch()) { ?>
                                    <tr>
                                        <td><img src="../images/<?= $row['images']; ?>" width="100"></td>
                                        <td><?= $row['name']; ?></td>
                                        <td><?= $row['price']; ?></td>
                                        <td><?= $row['quantity']; ?></td>
                                        <td>
                                            <button class="edit btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                            data-edit_id="<?= $row['id']; ?>"
                                            data-edit_name="<?= $row['name']; ?>"
                                            data-edit_description="<?= $row['description']; ?>"
                                            data-edit_price="<?= $row['price']; ?>"
                                            data-edit_quantity="<?= $row['quantity']; ?>"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="delete btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-delete_id="<?= $row['id']; ?>">
                                                <i class="bi bi-trash"></i>
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
    </script>

</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>