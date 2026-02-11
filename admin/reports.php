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
<?php include '../includes/header.php'; ?>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow-1" style="margin-left: 250px;">
        <?php include '../includes/navbar.php'; ?>

        <div class="p-4">
            <h4 class="fw-bold mb-4">Reports & Analytics</h4>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <p class="mb-1">Total Revenue</p>
                            <h3 class="fw-bold">₱<?= number_format($total_sales, 2) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body">
                            <p class="mb-1">Online Sales</p>
                            <h3 class="fw-bold">₱<?= number_format($online_sales, 2) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-info text-white">
                        <div class="card-body">
                            <p class="mb-1">POS Sales</p>
                            <h3 class="fw-bold">₱<?= number_format($pos_sales, 2) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-warning text-white">
                        <div class="card-body">
                            <p class="mb-1">Low Stock Items</p>
                            <h3 class="fw-bold"><?= $low_stock ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Recent Online Orders</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
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
                                                echo "<tr><td>{$row['order_number']}</td><td>₱".number_format($row['amount'], 2)."</td><td>".date('M d, Y', strtotime($row['order_date']))."</td></tr>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "<tr><td colspan='3' class='text-center text-danger'>'order_date' column not found. Please add it to 'customers_info' table.</td></tr>";
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
                        <div class="card-body">
                            <h5 class="card-title mb-3">Recent POS Transactions</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
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
                                            echo "<tr><td>{$row['transaction_no']}</td><td>₱".number_format($row['total'], 2)."</td><td>".date('M d, Y', strtotime($row['transaction_date']))."</td></tr>";
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