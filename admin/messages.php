<?php 
    include '../config/config.php';
    include '../includes/session.php';

  class data extends Connection{ 
    public function managedata(){ 
        $admin_id = $_SESSION['users_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <link rel="stylesheet" href="../asset/css/modern-admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .chat-list { height: 600px; overflow-y: auto; }
        .chat-box { height: 500px; overflow-y: auto; background: #f8fafc; border-radius: 15px; padding: 20px; }
        .message { margin-bottom: 15px; max-width: 80%; padding: 12px 16px; border-radius: 15px; }
        .message.sent { background: var(--primary-color); color: #000; margin-left: auto; border-bottom-right-radius: 2px; }
        .message.received { background: #fff; color: #334155; border-bottom-left-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .supplier-item { cursor: pointer; transition: all 0.2s; border-radius: 12px; }
        .supplier-item:hover, .supplier-item.active { background: rgba(255, 193, 7, 0.1); }
        .low-stock-badge { font-size: 0.7rem; padding: 2px 8px; }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow-1" style="margin-left: 260px;">
        <?php include '../includes/navbar.php'; ?>

        <div class="p-4">
            <div class="row g-4">
                <!-- Supplier List -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Suppliers</h5>
                            <div class="chat-list">
                                <?php 
                                try {
                                    // Fetch suppliers and check if any of their products are low stock
                                    $sql = "SELECT s.*, 
                                            (SELECT COUNT(*) FROM products p WHERE p.supplier_id = s.id AND p.quantity < 10) as low_stock_count 
                                            FROM suppliers s ORDER BY low_stock_count DESC, s.name ASC";
                                    $stmt = $this->conn()->query($sql);
                                    while ($row = $stmt->fetch()) {
                                ?>
                                    <div class="supplier-item p-3 mb-2 d-flex align-items-center border border-light" onclick="loadChat(<?= $row['id'] ?>, '<?= addslashes($row['name']) ?>')">
                                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small"><?= $row['name'] ?></div>
                                            <?php if($row['low_stock_count'] > 0): ?>
                                                <span class="badge bg-danger low-stock-badge"><?= $row['low_stock_count'] ?> items low stock</span>
                                            <?php else: ?>
                                                <span class="text-muted small">All stock okay</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php 
                                    }
                                } catch (PDOException $e) {
                                    echo "<div class='alert alert-warning small'>Please run the SQL to add supplier_id to products.</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Window -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div id="chatPlaceholder" class="card-body d-flex flex-column align-items-center justify-content-center text-muted py-5">
                            <i class="bi bi-chat-dots fs-1 mb-3"></i>
                            <p>Select a supplier to start a conversation</p>
                        </div>
                        
                        <div id="chatInterface" class="card-body p-4 d-none">
                            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0" id="activeSupplierName">Supplier Name</h5>
                                </div>
                            </div>

                            <div class="chat-box mb-4" id="messageContainer">
                                <!-- Messages will load here -->
                            </div>

                            <form id="messageForm" class="d-flex gap-2">
                                <input type="hidden" id="receiverId">
                                <input type="text" id="messageInput" class="form-control rounded-pill px-4 border-0 bg-light" placeholder="Type your message here..." required>
                                <button type="submit" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadChat(supplierId, name) {
            $('#chatPlaceholder').addClass('d-none');
            $('#chatInterface').removeClass('d-none');
            $('#activeSupplierName').text(name);
            $('#receiverId').val(supplierId);
            $('.supplier-item').removeClass('active');
            $(event.currentTarget).addClass('active');
            
            fetchMessages(supplierId);
        }

        function fetchMessages(supplierId) {
            // This would normally be an AJAX call to get_messages.php
            $('#messageContainer').html('<div class="text-center py-5 text-muted small">Loading conversation...</div>');
            
            // Simulating a low stock auto-message
            setTimeout(() => {
                $('#messageContainer').html(`
                    <div class="message received">Hello! How can we help you today?</div>
                    <div class="message sent">Hi, I noticed some of your products are running low in our inventory. Can we discuss a restock?</div>
                `);
                scrollToBottom();
            }, 500);
        }

        $('#messageForm').submit(function(e) {
            e.preventDefault();
            const msg = $('#messageInput').val();
            if(msg.trim() == '') return;

            $('#messageContainer').append(`<div class="message sent">${msg}</div>`);
            $('#messageInput').val('');
            scrollToBottom();
            
            // Here you would send the message to the database via AJAX
        });

        function scrollToBottom() {
            const container = document.getElementById('messageContainer');
            container.scrollTop = container.scrollHeight;
        }
    </script>
</body>
</html>
<?php 
    }
} 
$data = new data(); 
$data->managedata(); 
?>