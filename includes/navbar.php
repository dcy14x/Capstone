<?php
$users_id = $_SESSION['users_id'];
$db = new Connection();
$conn = $db->conn();

// Fetch user info for profile image
$stmt_user = $conn->prepare("SELECT images FROM users WHERE users_id = ?");
$stmt_user->execute([$users_id]);
$user_nav = $stmt_user->fetch();

// Fetch low stock products for notifications
$stmt_low_stock = $conn->query("SELECT name, quantity FROM products WHERE quantity < 10 ORDER BY quantity ASC LIMIT 5");
$low_stock_products = $stmt_low_stock->fetchAll();
$low_stock_count = $conn->query("SELECT COUNT(*) FROM products WHERE quantity < 10")->fetchColumn();

$currentPage = basename($_SERVER['PHP_SELF']);
$title = "System";
if ($currentPage == 'dashboard.php') $title = "Dashboard";
else if ($currentPage == 'pos.php') $title = "POS";
else if ($currentPage == 'inventory.php') $title = "Inventory";
else if ($currentPage == 'suppliers.php') $title = "Suppliers";
else if ($currentPage == 'orders.php') $title = "Orders";
else if ($currentPage == 'reports.php') $title = "Reports";
else if ($currentPage == 'profile.php') $title = "My Profile";
?>

<nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1"><?= $title; ?></span>
        
        <div class="d-flex align-items-center">
            <!-- Notifications -->
            <div class="dropdown me-3">
                <button class="btn btn-light position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <?php if ($low_stock_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $low_stock_count ?>
                        </span>
                    <?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="width: 300px; max-height: 400px; overflow-y: auto;">
                    <li><h6 class="dropdown-header">Low Stock Alerts</h6></li>
                    <?php if ($low_stock_count > 0): ?>
                        <?php foreach($low_stock_products as $product): ?>
                        <li><a class="dropdown-item text-wrap small" href="inventory.php">
                            <div class="d-flex w-100 justify-content-between">
                                <span class="fw-bold"><?= $product['name'] ?></span>
                                <span class="badge bg-danger ms-2">Only <?= $product['quantity'] ?> left</span>
                            </div>
                            <div class="text-muted small">Please restock soon.</div>
                        </a></li>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center small" href="inventory.php">View All Low Stock Items</a></li>
                    <?php else: ?>
                        <li><span class="dropdown-item small text-muted text-center">No low stock alerts</span></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                    <?php if (!empty($user_nav['images'])): ?>
                        <img src="../images/<?= $user_nav['images'] ?>" class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                            <i class="bi bi-person"></i>
                        </div>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i> My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>