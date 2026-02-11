<script src="../asset/js/jquery.min.js"></script>
<script src="../asset/js/bootstrap.bundle.min.js"></script>
<script src="../asset/js/jquery.dataTables.min.js"></script>
<script src="../asset/js/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function initializeTable() {
        // Initialize DataTables
        $('#datatable').DataTable({
            responsive: true,
        });
    }
    initializeTable();

    $('.delete').click(function(){
        $('#delete_id').val($(this).data('delete_id'))
    })

    function posData(search = '') {
        $.ajax({
            url: 'posdata.php',
            type: 'POST',
            data: {
                search: search
            },
            success: function (response) {
                $('#posdata').html(response);
            }
        });
    }

    // realtime search
    $('#search').on('input', function () {
        let search = $(this).val();
        posData(search);
    });

    posData();


    // POS Process
    let cart = [];

        $(document).ready(function() {
            posData();

            // Realtime search
            $('#search').on('input', function() {
                let search = $(this).val();
                posData(search);
            });

            // Amount received input - calculate change
            $('#amountReceived').on('input', function() {
                calculateChange();
            });

            // Complete payment
            $('#completePayment').on('click', function() {
                completePayment();
            });

            // Cancel order
            $('#cancelOrder').on('click', function() {
                Swal.fire({
                    title: 'Cancel Order?',
                    text: "Are you sure you want to cancel this order?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel',
                    cancelButtonText: 'No',
                    reverseButtons: true // optional: Cancel button on left
                }).then((result) => {
                    if (result.isConfirmed) {
                        cancelOrder(); // call your function here
                    }
                });

            });
        });

        // Load products
        function posData(search = '') {
            $.ajax({
                url: 'posdata.php',
                type: 'POST',
                data: { search: search },
                success: function(response) {
                    $('#posdata').html(response);
                }
            });
        }

        // Add to cart
        function addToCart(productId, productName, productPrice, productImage) {
            let existingItem = cart.find(item => item.id === productId);
            
            if(existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: parseFloat(productPrice),
                    quantity: 1,
                    image: productImage
                });
            }
            
            updateCart();
        }

        // Update cart display
        function updateCart() {
            let html = '';
            
            if(cart.length === 0) {
                html = '<p class="text-center text-muted">No items in cart</p>';
            } else {
                cart.forEach(function(item) {
                    html += `
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">₱${item.price.toFixed(2)}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQuantity(${item.id})">-</button>
                                <span class="mx-2 fw-bold">${item.quantity}</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="increaseQuantity(${item.id})">+</button>
                                <button class="btn btn-sm btn-danger ms-2" onclick="removeFromCart(${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
            }
            
            $('#cartItems').html(html);
            calculateTotals();
        }

        // Increase quantity
        function increaseQuantity(productId) {
            let item = cart.find(item => item.id === productId);
            if(item) {
                item.quantity++;
                updateCart();
            }
        }

        // Decrease quantity
        function decreaseQuantity(productId) {
            let item = cart.find(item => item.id === productId);
            if(item) {
                if(item.quantity > 1) {
                    item.quantity--;
                } else {
                    removeFromCart(productId);
                    return;
                }
                updateCart();
            }
        }

        // Remove from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;
            
            cart.forEach(function(item) {
                subtotal += item.price * item.quantity;
            });
            
            let total = subtotal;
            
            $('#subtotal').text('₱' + subtotal.toFixed(2));
            $('#totalAmount').text('₱' + total.toFixed(2));
            
            calculateChange();
        }

        // Calculate change
        function calculateChange() {
            let total = parseFloat($('#totalAmount').text().replace('₱', '').replace(',', ''));
            let amountReceived = parseFloat($('#amountReceived').val()) || 0;
            
            if(amountReceived >= total && amountReceived > 0) {
                let change = amountReceived - total;
                $('#changeAmount').text('₱' + change.toFixed(2));
                $('#changeSection').show();
            } else {
                $('#changeSection').hide();
            }
        }

        // Complete payment
        function completePayment() {
            if(cart.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart is Empty',
                    text: 'Add item to continue',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            let total = parseFloat($('#totalAmount').text().replace('₱', '').replace(',', ''));
            let amountReceived = parseFloat($('#amountReceived').val()) || 0;
            let paymentMethod = $('#paymentMethod').val();
            
            if(amountReceived < total) {
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Payment',
                    text: 'Amount is not enough',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            let subtotal = parseFloat($('#subtotal').text().replace('₱', '').replace(',', ''));
            let change = amountReceived - total;
            
            // Send to server
            $.ajax({
                url: '../controller/posController.php',
                type: 'POST',
                data: {
                    action: 'completePayment',
                    cart: JSON.stringify(cart),
                    subtotal: subtotal,
                    total: total,
                    payment_method: paymentMethod,
                    amount_received: amountReceived,
                    change: change
                },
                success: function(response) {
                    response = response.trim();
                    if(response === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful',
                            text: 'Transaction completed',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        cancelOrder();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Insufficient Payment',
                            text: 'Amount is not enough',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    alert('Error processing payment!');
                    Swal.fire({
                        icon: 'error',
                        text: 'Error processing payment!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Cancel order
        function cancelOrder() {
            cart = [];
            $('#amountReceived').val('');
            updateCart();
        }

</script>