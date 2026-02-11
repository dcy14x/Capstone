<!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="text-warning mb-3">About M-633</h5>
                <p class="text-white-50">Your trusted online shopping destination. We offer quality products at competitive prices with excellent customer service.</p>
                <div class="mt-3">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="text-warning mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="homepage.html" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="products.html" class="text-white-50 text-decoration-none">Products</a></li>
                    <li class="mb-2"><a href="cart.html" class="text-white-50 text-decoration-none">Shopping Cart</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">My Account</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Track Order</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="text-warning mb-3">Contact Us</h5>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i>Quezon City, Philippines</li>
                    <li class="mb-2"><i class="bi bi-telephone-fill me-2"></i>+63 912 345 6789</li>
                    <li class="mb-2"><i class="bi bi-envelope-fill me-2"></i>support@m633.com</li>
                    <li class="mb-2"><i class="bi bi-clock-fill me-2"></i>Mon - Fri: 9AM - 6PM</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-white-50">
            <p class="mb-0">&copy; 2024 M-633. All rights reserved.</p>
        </div>
    </div>
</footer>


<script src="../asset/js/jquery.min.js"></script>
<script src="../asset/js/bootstrap.bundle.min.js"></script>
<script src="../asset/js/jquery.dataTables.min.js"></script>
<script src="../asset/js/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('.addtocart').click(function () {
        
        let products_id = $(this).val();

        $.ajax({
            url: '../controller/cartController.php',
            type: 'POST',
            data: { action: 'add', products_id: products_id, },
            success: function (response) {

                response = response.trim();

                if (response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        text: 'Item successfully added to your cart.',
                        showConfirmButton: false,
                        timer: 1500
                    });

                } else if (response === 'exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Already Added',
                        text: 'This item is already in your cart.'
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Something went wrong.'
                });
            }
        });
    });


    $('.qty-btn').click(function () {
    let btn = $(this); // ✅ save reference

        let products_id = $(this).data('id');
        let type = $(this).data('type');
        let qtyInput = $(this).closest('.input-group').find('.qty-input');

        $.ajax({
            url: '../controller/cartController.php',
            type: 'POST',
            data: {
                action: 'updateQty',
                products_id: products_id,
                type: type
            },
            success: function (response) {

                let data = JSON.parse(response);

                if (data.status === 'success') {
                    qtyInput.val(data.cart_qty);
                    
                    btn.closest('.row').find('.product_total').text('₱' + Number(data.products_total).toLocaleString());

                    $('.overall').text('₱' + data.overall)

                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Notice',
                        text: data.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Something went wrong.'
                });
            }
        });
    });

    function countCart() {
        $.ajax({
            url: '../controller/cartController.php',
            type: 'POST',
            data: {
                action: 'count'
            },
            success: function (response) {
                let data = JSON.parse(response);
                $('#countcart').text(data.count);
            }
        });
    }

    setInterval(() => {
        countCart();
    }, 100);

</script>