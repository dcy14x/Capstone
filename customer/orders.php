<?php 
  include '../config/config.php';
  include '../includes/session.php';
  class data extends Connection{ 
    public function managedata(){ 
        $users_id = $_SESSION['users_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <link rel="stylesheet" href="../asset/css/modern-admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%);
            padding: 60px 0;
            border-radius: 0 0 40px 40px;
        }
        .order-card {
            border-radius: 20px;
            transition: all 0.3s;
        }
        .order-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Modern Page Header -->
    <div class="page-header text-dark mb-5 shadow-sm">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">My Orders</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-dark text-decoration-none fw-semibold">Home</a></li>
                    <li class="breadcrumb-item active text-dark opacity-75" aria-current="page">My Orders</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Orders Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php 
                $sql = "SELECT * FROM customers_info WHERE users_id = ? ORDER BY id DESC";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$users_id]);
                $orders = $stmt->fetchAll();

                if (count($orders) > 0) {
                    foreach ($orders as $order) {
                ?>
                    <div class="card border-0 shadow-sm mb-4 order-card">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <span class="text-muted small d-block">Order Number</span>
                                    <span class="fw-bold text-primary">#<?= $order['order_number'] ?></span>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <span class="text-muted small d-block">Date Placed</span>
                                    <span class="fw-bold"><?= date('M d, Y', strtotime($order['order_date'])) ?></span>
                                </div>
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <span class="text-muted small d-block">Total Amount</span>
                                    <span class="fw-bold">₱<?= number_format($order['amount'], 2) ?></span>
                                </div>
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <span class="text-muted small d-block">Status</span>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Completed</span>
                                </div>
                                <div class="col-md-2 text-md-end">
                                    <button class="btn btn-light rounded-pill px-4 fw-bold view-details" data-id="<?= $order['order_number'] ?>">
                                        Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                } else {
                ?>
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                        <i class="bi bi-bag-x display-1 text-muted mb-4 d-block"></i>
                        <h4 class="fw-bold">No orders found</h4>
                        <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                        <a href="products.php" class="btn btn-primary px-5 rounded-pill fw-bold">Start Shopping</a>
                    </div>
                <?php } ?>
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

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.view-details').on('click', function() {
            const orderNo = $(this).data('id');
            
            $('#modalOrderNo').text('#' + orderNo);
            $('#orderDetailsContent').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
            $('#orderDetailsModal').modal('show');

            $.ajax({
                url: '../admin/order_details.php', // Reusing the admin details fetcher
                method: 'POST',
                data: { order_number: orderNo, type: 'online' },
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
<?php } } $data = new data(); $data->managedata(); ?>