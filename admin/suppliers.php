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
                            <h5 class="mb-1 fw-bold">Suppliers</h5>
                            <p class="text-muted small mb-0">Manage your product vendors and contact info</p>
                        </div>
                        <button class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg me-2"></i>Add Supplier
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle" id="datatable">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Contact Person</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th class="text-end">Action</th>
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
                                            <td class="fw-bold text-primary"><?= $row['name'] ?></td>
                                            <td><?= $row['contact_person'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['phone'] ?></td>
                                            <td class="text-muted small"><?= $row['address'] ?></td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-light rounded-circle p-2 me-1"><i class="bi bi-pencil text-primary"></i></button>
                                                <button class="btn btn-sm btn-light rounded-circle p-2"><i class="bi bi-trash text-danger"></i></button>
                                            </td>
                                        </tr>
                                    <?php 
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='6' class='text-center text-danger py-4'>Suppliers table not found.</td></tr>";
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
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold">Add New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="supplier_action.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Supplier Name</label>
                            <input type="text" name="name" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control rounded-3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Phone</label>
                            <input type="text" name="phone" class="form-control rounded-3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Address</label>
                            <textarea name="address" class="form-control rounded-3" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_supplier" class="btn btn-primary px-4 rounded-pill">Save Supplier</button>
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