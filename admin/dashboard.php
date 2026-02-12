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
        try {
            $sql_online_cost = "SELECT SUM(p.cost * o.qty) FROM orders o JOIN products p ON o.products_id = p.id";
            $online_cost = $this->conn()->query($sql_online_cost)->fetchColumn() ?? 0;

            $sql_pos_cost = "SELECT SUM(p.cost * pti.quantity) FROM pos_transaction_items pti JOIN products p ON pti.products_id = p.id";
            $pos_cost = $this->conn()->query($sql_pos_cost)->fetchColumn() ?? 0;
            
            $total_cost = $online_cost + $pos_cost;
        } catch (PDOException $e) {}

        // Profit
        $profit = $total_sales - $total_cost;

        // Monthly Sales Chart Data
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $sales_data = array_fill(0, 12, 0); 

        $sql_monthly_pos = "SELECT MONTH(transaction_date) as month, SUM(total) as total FROM pos_transactions WHERE YEAR(transaction_date) = YEAR(CURDATE()) GROUP BY MONTH(transaction_date)";
        $stmt_monthly_pos = $this->conn()->query($sql_monthly_pos);
        while($row = $stmt_monthly_pos->fetch()){
            $sales_data[$row['month'] - 1] = (float)$row['total'];
        }
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
            <!-- Modern Stat Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-currency-dollar fs-4"></i>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">+12.5%</span>
                            </div>
                            <h6 class="text-muted mb-1 fw-medium">Total Sales</h6>
                            <h3 class="fw-bold mb-0">₱ <?= number_format($total_sales, 2); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-danger bg-opacity-10 text-danger">
                                    <i class="bi bi-cart-dash fs-4"></i>
                                </div>
                                <span class="badge bg-danger bg-opacity-10 text-danger">-2.4%</span>
                            </div>
                            <h6 class="text-muted mb-1 fw-medium">Total Cost</h6>
                            <h3 class="fw-bold mb-0">₱ <?= number_format($total_cost, 2); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm stat-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-graph-up-arrow fs-4"></i>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">+8.2%</span>
                            </div>
                            <h6 class="text-muted mb-1 fw-medium">Total Profit</h6>
                            <h3 class="fw-bold mb-0">₱ <?= number_format($profit, 2); ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Chart Section -->
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="fw-bold mb-1">Sales Overview</h5>
                                    <p class="text-muted small mb-0">Monthly performance analytics</p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Year <?= date('Y') ?>
                                    </button>
                                </div>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="modernSalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Table Section -->
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Top Selling Products</h5>
                                <a href="inventory.php" class="btn btn-primary btn-sm px-3 rounded-pill">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Product Details</th>
                                            <th>Price</th>
                                            <th>Sold</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * FROM products ORDER BY selling DESC LIMIT 5";
                                        $stmt = $this->conn()->query($sql);
                                        while ($row = $stmt->fetch()) {
                                            $status_class = $row['quantity'] > 10 ? 'bg-success' : 'bg-danger';
                                            $status_text = $row['quantity'] > 10 ? 'In Stock' : 'Low Stock';
                                         ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../images/<?= $row['images'] ?>" class="rounded-3 me-3" width="48" height="48" style="object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold"><?= $row['name'] ?></div>
                                                            <div class="text-muted small">ID: #PROD-<?= $row['id'] ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold">₱<?= number_format($row['price'],2); ?></td>
                                                <td>
                                                    <div class="fw-bold text-primary"><?= $row['selling']; ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $status_class ?> bg-opacity-10 text-<?= explode('-', $status_class)[1] ?> px-3 py-2 rounded-pill">
                                                        <?= $status_text ?>
                                                    </span>
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

    <?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('modernSalesChart').getContext('2d');

// Gradient for Sales
const salesGradient = ctx.createLinearGradient(0, 0, 0, 400);
salesGradient.addColorStop(0, 'rgba(255, 193, 7, 0.2)');
salesGradient.addColorStop(1, 'rgba(255, 193, 7, 0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Sales',
            data: <?= json_encode($sales_data) ?>,
            borderColor: '#FFC107',
            borderWidth: 3,
            backgroundColor: salesGradient,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#FFC107',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                grid: {
                    drawBorder: false,
                    color: '#f1f5f9'
                },
                ticks: {
                    color: '#94a3b8',
                    callback: function(value) { return '₱' + value.toLocaleString(); }
                }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#94a3b8' }
            }
        }
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