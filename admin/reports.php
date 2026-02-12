<?php 
    include '../config/config.php';
    include '../includes/session.php';

  class data extends Connection{ 
    public function managedata(){ 
        // Summary Stats
        $online_sales = $this->conn()->query("SELECT SUM(amount) FROM customers_info")->fetchColumn() ?? 0;
        $pos_sales = $this->conn()->query("SELECT SUM(total) FROM pos_transactions")->fetchColumn() ?? 0;
        $total_sales = $online_sales + $pos_sales;

        $total_products = $this->conn()->query("SELECT COUNT(*) FROM products")->fetchColumn() ?? 0;
        $low_stock = $this->conn()->query("SELECT COUNT(*) FROM products WHERE quantity < 10")->fetchColumn() ?? 0;
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
            <h4 class="fw-bold mb-4">Reports & Analytics</h4>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4">
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary mb-3">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                            <p class="text-muted small mb-1 fw-medium">Total Revenue</p>
                            <h4 class="fw-bold mb-0">₱<?= number_format($total_sales, 2) ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4">
                            <div class="icon-shape bg-success bg-opacity-10 text-success mb-3">
                                <i class="bi bi-globe fs-4"></i>
                            </div>
                            <p class="text-muted small mb-1 fw-medium">Online Sales</p>
                            <h4 class="fw-bold mb-0">₱<?= number_format($online_sales, 2) ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4">
                            <div class="icon-shape bg-info bg-opacity-10 text-info mb-3">
                                <i class="bi bi-shop fs-4"></i>
                            </div>
                            <p class="text-muted small mb-1 fw-medium">POS Sales</p>
                            <h4 class="fw-bold mb-0">₱<?= number_format($pos_sales, 2) ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4">
                            <div class="icon-shape bg-warning bg-opacity-10 text-warning mb-3">
                                <i class="bi bi-exclamation-triangle fs-4"></i>
                            </div>
                            <p class="text-muted small mb-1 fw-medium">Low Stock Items</p>
                            <h4 class="fw-bold mb-0"><?= $low_stock ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Recent Online Orders</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        try {
                                            $sql = "SELECT order_number, amount, order_date FROM customers_info ORDER BY id DESC LIMIT 5";
                                            $stmt = $this->conn()->query($sql);
                                            while ($row = $stmt->fetch()) {
                                                echo "<tr>
                                                        <td class='fw-bold'>{$row['order_number']}</td>
                                                        <td class='fw-bold text-primary'>₱".number_format($row['amount'], 2)."</td>
                                                        <td class='text-muted small'>".date('M d, Y', strtotime($row['order_date']))."</td>
                                                      </tr>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "<tr><td colspan='3' class='text-center text-danger'>'order_date' column not found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Recent POS Transactions</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>TXN #</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT transaction_no, total, transaction_date FROM pos_transactions ORDER BY id DESC LIMIT 5";
                                        $stmt = $this->conn()->query($sql);
                                        while ($row = $stmt->fetch()) {
                                            echo "<tr>
                                                    <td class='fw-bold'>{$row['transaction_no']}</td>
                                                    <td class='fw-bold text-primary'>₱".number_format($row['total'], 2)."</td>
                                                    <td class='text-muted small'>".date('M d, Y', strtotime($row['transaction_date']))."</td>
                                                  </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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