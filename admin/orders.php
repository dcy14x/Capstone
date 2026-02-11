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
            <h4 class="fw-bold mb-4">Orders Management</h4>

            <ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="online-tab" data-bs-toggle="tab" data-bs-target="#online" type="button">Online Orders</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="pos-tab" data-bs-toggle="tab" data-bs-target="#pos" type="button">POS Transactions</button>
                </li>
            </ul>

            <div class="tab-content" id="orderTabsContent">
                <!-- Online Orders -->
                <div class="tab-pane fade show active" id="online">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover datatable" style="font-size: 0.9rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Address</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Action</th>
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
                                                    <td class="fw-bold"><?= $row['order_number'] ?></td>
                                                    <td><?= isset($row['order_date']) ? date('M d, Y', strtotime($row['order_date'])) : 'N/A' ?></td>
                                                    <td><?= $row['customer_name'] ?></td>
                                                    <td><?= $row['email'] ?></td>
                                                    <td><?= $row['contact'] ?></td>
                                                    <td class="text-truncate" style="max-width: 150px;" title="<?= $row['address'] . ', ' . $row['city'] ?>">
                                                        <?= $row['address'] . ', ' . $row['city'] ?>
                                                    </td>
                                                    <td>₱<?= number_format($row['amount'], 2) ?></td>
                                                    <td><span class="badge bg-info"><?= $row['payment_method'] ?></span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary view-details" data-id="<?= $row['order_number'] ?>" data-type="online">View</button>
                                                    </td>
                                                </tr>
                                            <?php 
                                            }
                                        } catch (PDOException $e) {
                                            echo "<tr><td colspan='9' class='text-center text-danger'>Error loading orders. Please check database columns.</td></tr>";
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * FROM pos_transactions ORDER BY id DESC";
                                        $stmt = $this->conn()->query($sql);
                                        while ($row = $stmt->fetch()) {
                                        ?>
                                            <tr>
                                                <td class="fw-bold"><?= $row['transaction_no'] ?></td>
                                                <td><?= date('M d, Y h:i A', strtotime($row['transaction_date'])) ?></td>
                                                <td>₱<?= number_format($row['total'], 2) ?></td>
                                                <td><?= $row['payment_method'] ?></td>
                                                <td><span class="badge bg-success"><?= $row['status'] ?></span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary view-details" data-id="<?= $row['transaction_no'] ?>" data-type="pos">View</button>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details - <span id="modalOrderNo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <div class="text-center py-4">
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
            $('#orderDetailsContent').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
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