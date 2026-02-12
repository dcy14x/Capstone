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
            <h4 class="fw-bold mb-4">Orders Management</h4>

            <ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm d-inline-flex" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active rounded-3 px-4" id="online-tab" data-bs-toggle="tab" data-bs-target="#online" type="button">Online Orders</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-3 px-4" id="pos-tab" data-bs-toggle="tab" data-bs-target="#pos" type="button">POS Transactions</button>
                </li>
            </ul>

            <div class="tab-content" id="orderTabsContent">
                <!-- Online Orders -->
                <div class="tab-pane fade show active" id="online">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table align-middle datatable">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Contact Info</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        try {
                                            $sql = "SELECT ci.*, u.name as customer_name, u.email, u.contact, u.address, u.city 
                                                    FROM customers_info ci 
                                                    JOIN users u ON ci.users_id = u.users_id 
                                                    ORDER BY ci.id DESC";
                                            $stmt = $this->conn()->query($sql);
                                            while ($row = $stmt->fetch()) {
                                            ?>
                                                <tr>
                                                    <td class="fw-bold text-primary"><?= $row['order_number'] ?></td>
                                                    <td class="text-muted small"><?= isset($row['order_date']) ? date('M d, Y', strtotime($row['order_date'])) : 'N/A' ?></td>
                                                    <td>
                                                        <div class="fw-bold"><?= $row['customer_name'] ?></div>
                                                        <div class="text-muted small text-truncate" style="max-width: 150px;"><?= $row['address'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div class="small"><?= $row['email'] ?></div>
                                                        <div class="small text-muted"><?= $row['contact'] ?></div>
                                                    </td>
                                                    <td class="fw-bold">₱<?= number_format($row['amount'], 2) ?></td>
                                                    <td><span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill"><?= $row['payment_method'] ?></span></td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary px-3 rounded-pill view-details" data-id="<?= $row['order_number'] ?>" data-type="online">View Details</button>
                                                    </td>
                                                </tr>
                                            <?php 
                                            }
                                        } catch (PDOException $e) {
                                            echo "<tr><td colspan='7' class='text-center text-danger py-4'>Error loading orders.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- POS Transactions -->
                <div class="tab-pane fade" id="pos">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table align-middle datatable">
                                    <thead>
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * FROM pos_transactions ORDER BY id DESC";
                                        $stmt = $this->conn()->query($sql);
                                        while ($row = $stmt->fetch()) {
                                        ?>
                                            <tr>
                                                <td class="fw-bold text-primary"><?= $row['transaction_no'] ?></td>
                                                <td class="text-muted small"><?= date('M d, Y h:i A', strtotime($row['transaction_date'])) ?></td>
                                                <td class="fw-bold">₱<?= number_format($row['total'], 2) ?></td>
                                                <td><?= $row['payment_method'] ?></td>
                                                <td><span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><?= $row['status'] ?></span></td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-primary px-3 rounded-pill view-details" data-id="<?= $row['transaction_no'] ?>" data-type="pos">View Details</button>
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
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold">Order Details - <span id="modalOrderNo" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="orderDetailsContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.view-details').on('click', function() {
            const orderNo = $(this).data('id');
            const type = $(this).data('type');
            
            $('#modalOrderNo').text(orderNo);
            $('#orderDetailsContent').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
            $('#orderDetailsModal').modal('show');

            $.ajax({
                url: 'order_details.php',
                method: 'POST',
                data: { order_number: orderNo, type: type },
                success: function(response) {
                    $('#orderDetailsContent').html(response);
                },
                error: function() {
                    $('#orderDetailsContent').html('<div class="alert alert-danger">Failed to load order details.</div>');
                }
            });
        });
    });
    </script>
</body>
</html>
<?php 
    }
} 
$data = new data(); 
$data->managedata(); 
?>