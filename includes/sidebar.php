<div class="bg-white border-end vh-100 position-fixed" style="width: 250px; z-index: 1000;">
    <div class="p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                <i class="bi bi-cart-fill text-white"></i>
            </div>
            <h5 class="mb-0"><span class="text-warning">M-</span><span class="fw-bold">633</span></h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active text-primary' : 'text-dark' ?> d-flex align-items-center" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-3"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active text-primary' : 'text-dark' ?> d-flex align-items-center" href="inventory.php">
                    <i class="bi bi-box-seam me-3"></i> Inventory
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active text-primary' : 'text-dark' ?> d-flex align-items-center" href="reports.php">
                    <i class="bi bi-file-earmark-text me-3"></i> Reports
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'suppliers.php' ? 'active text-primary' : 'text-dark' ?> d-flex align-items-center" href="suppliers.php">
                    <i class="bi bi-truck me-3"></i> Suppliers
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active text-primary' : 'text-dark' ?> d-flex align-items-center" href="orders.php">
                    <i class="bi bi-cart3 me-3"></i> Orders
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pos.php' ? 'active text-primary' : 'text-dark' ?> d-flex align-items-center" href="pos.php">
                    <i class="bi bi-shop me-3"></i> POS
                </a>
            </li>
        </ul>
    </div>
    <div class="position-absolute bottom-0 w-100 p-4 border-top">
        <a class="nav-link text-dark d-flex align-items-center" href="../auth/logout.php">
            <i class="bi bi-box-arrow-right me-3"></i> Log Out
        </a>
    </div>
</div>