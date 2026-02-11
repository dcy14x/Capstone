<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body>
    <!-- Login Page -->
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="row w-100">
            <div class="col-md-7 d-none d-md-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                        <i class="bi bi-cart-fill text-white" style="font-size: 80px;"></i>
                    </div>
                    <h1 class="mt-4" style="font-size: clamp(0.8125rem, -0.0285rem + 5.1754vw, 4.5rem);"><span class="text-warning">M-</span><span class="text-dark fw-bold">633</span></h1>
                </div>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                <div class="card border-0 shadow-sm" style="max-width: 450px; width: 100%;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-box-seam text-primary" style="font-size: 48px;"></i>
                            <h3 class="mt-3 fw-bold">Log in to your account</h3>
                            <p class="text-muted">Welcome back! Please enter your details</p>
                        </div>
                        <form method="POST" action="../controller/loginController.php">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••">
                            </div>
                            <div class="text-end mb-3">
                                <a href="forgotpassword.php" class="text-decoration-none">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Sign in</button>
                            <div class="text-center mt-3">
                                <span class="text-muted">Don't have an account?</span>
                                <a href="register.php" class="text-decoration-none"> Sign up</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>