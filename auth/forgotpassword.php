<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body>
    <!-- Login Page -->
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="row w-100 justify-content-center">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="card border-0 shadow-sm" style="max-width: 450px; width: 100%;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-box-seam text-primary" style="font-size: 48px;"></i>
                            <h3 class="mt-3 fw-bold">Forgot Password</h3>
                            <p class="text-muted">Send OTP Code</p>
                        </div>
                        <form method="POST" action="../controller/forgotpasswordController.php">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Submit</button>
                            <div class="text-center mt-3">
                                <span class="text-muted">You want to login?</span>
                                <a href="login.php" class="text-decoration-none"> Sign in</a>
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