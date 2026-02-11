<?php
$users_id = $_SESSION['users_id'] ?? null;
$cart_count = 0;
$user_nav = null;

if ($users_id) {
    $db = new Connection();
    $conn = $db->conn();
    
    // Cart count
    $stmt_cart = $conn->prepare("SELECT SUM(cart_qty) FROM cart WHERE users_id = ?");
    $stmt_cart->execute([$users_id]);
    $cart_count = $stmt_cart->fetchColumn() ?? 0;

    // User profile image
    $stmt_user = $conn->prepare("SELECT images FROM users WHERE users_id = ?");
    $stmt_user->execute([$users_id]);
    $user_nav = $stmt_user->fetch();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php">
            <span class="text-warning">M-</span><span class="text-dark">633</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active fw-bold text-primary' : '' ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active fw-bold text-primary' : '' ?>" href="products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active fw-bold text-primary' : '' ?>" href="orders.php">My Orders</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="cart.php">
                        <i class="bi bi-cart3 fs-5"></i>
                        <?php if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                <?= $cart_count ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <?php if ($users_id): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <?php if (!empty($user_nav['images'])): ?>
                                <img src="../images/<?= $user_nav['images'] ?>" class="rounded-circle border" width="35" height="35" style="object-fit: cover;">
                            <?php else: ?>
                                <i class="bi bi-person-circle fs-4"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="../auth/login.php" class="btn btn-primary btn-sm px-4 rounded-pill">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>