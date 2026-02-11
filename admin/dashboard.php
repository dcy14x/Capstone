<?php 
    include '../config/config.php';
    include '../includes/session.php';

  class data extends Connection{ 
    public function managedata(){ 

        // Sales
        $online_sales = $this->conn()->query("SELECT SUM(amount) FROM customers_info")->fetchColumn() ?? 0;
        $pos_sales = $this->conn()->query("SELECT SUM(total) FROM pos_transactions")->fetchColumn() ?? 0;
        $total_sales = $online_sales + $pos_sales;

        // Cost
        $total_cost = 0;
        $cost_warning = false;
        try {
            $sql_online_cost = "SELECT SUM(p.cost * o.qty) FROM orders o JOIN products p ON o.products_id = p.id";
            $online_cost = $this->conn()->query($sql_online_cost)->fetchColumn() ?? 0;

            $sql_pos_cost = "SELECT SUM(p.cost * pti.quantity) FROM pos_transaction_items pti JOIN products p ON pti.products_id = p.id";
            $pos_cost = $this->conn()->query($sql_pos_cost)->fetchColumn() ?? 0;
            
            $total_cost = $online_cost + $pos_cost;
        } catch (PDOException $e) {
            $cost_warning = true;
        }

        if ($total_cost == 0 && $total_sales > 0) {
            $cost_warning = true;
        }

        // Profit
        $profit = $total_sales - $total_cost;

        // Monthly Sales Chart
        $monthly_sales = array_fill(0, 12, 0); 
        $sql_monthly_pos = "SELECT MONTH(transaction_date) as month, SUM(total) as total FROM pos_transactions WHERE YEAR(transaction_date) = YEAR(CURDATE()) GROUP BY MONTH(transaction_date)";
        $stmt_monthly_pos = $this->conn()->query($sql_monthly_pos);
        while($row = $stmt_monthly_pos->fetch()){
            $monthly_sales[$row['month'] - 1] = $row['total'];
        }
        $sales_data_js = json_encode(array_values($monthly_sales));
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow-1" style="margin-left: 250px;">
        <?php include '../includes/navbar.php'; ?>

        <div class="p-4">
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">Total Sales</p>
                                    <h4 class="fw-bold mb-0">₱ <?= number_format($total_sales, 2); ?></h4>
                                </div>
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <i class="bi bi-bag-fill text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">Total Cost</p>
                                    <h4 class="fw-bold mb-0">₱ <?= number_format($total_cost, 2); ?></h4>
                                </div>
                                <div class="bg-danger bg-opacity-10 rounded p-3">
                                    <i class="bi bi-receipt text-danger fs-4"></i>
                                </div>
                            </div>
                            <?php if ($cost_warning): ?>
                            <div class="p-2 bg-warning-soft text-center small rounded-bottom">
                                <i class="bi bi-exclamation-triangle me-1"></i>Update product costs to calculate profit.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">Total Profit</p>
                                    <h4 class="fw-bold mb-0">₱ <?= number_format($profit, 2); ?></h4>
                                </div>
                                <div class="bg-success bg-opacity-10 rounded p-3">
                                    <i class="bi bi-wallet2 text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Monthly Sales (POS)</h5>
                            <canvas id="salesChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Top Selling Products</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Total Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * FROM products ORDER BY selling DESC LIMIT 5";
                                        $stmt = $this->conn()->query($sql);
                                        while ($row = $stmt->fetch()) {
                                         ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../images/<?= $row['images'] ?>" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                                        <span><?= $row['name'] ?></span>
                                                    </div>
                                                </td>
                                                <td class="fw-semibold">₱<?= number_format($row['price'],2); ?></td>
                                                <td><?= $row['selling']; ?></td>
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

    <?php include '../includes/footer.php'; ?>

<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sales (₱)',
            data: <?= $sales_data_js; ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
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