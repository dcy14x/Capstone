<?php 
    include '../config/config.php';
    include '../includes/session.php';

  class data extends Connection{ 
    public function managedata(){ 
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow-1" style="margin-left: 250px;">
        <?php include '../includes/navbar.php'; ?>

        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Suppliers</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="bi bi-plus-lg me-2"></i>Add Supplier
                </button>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Contact Person</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                try {
                                    $sql = "SELECT * FROM suppliers ORDER BY id DESC";
                                    $stmt = $this->conn()->query($sql);
                                    while ($row = $stmt->fetch()) {
                                    ?>
                                        <tr>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['contact_person'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['phone'] ?></td>
                                            <td><?= $row['address'] ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php 
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='6' class='text-center text-danger'>Suppliers table not found. Please create it in the database.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="supplier_action.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Supplier Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_supplier" class="btn btn-primary">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
<?php 
    }
} 
$data = new data(); 
$data->managedata(); 
?>