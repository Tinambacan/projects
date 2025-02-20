<?php require_once('admin_header.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Delivery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/assign_style.css">
</head>

<body>
    <div class="table-container">
        <table class="delivery-table">
            <thead>
                <tr>
                    <th>Tracking No.</th>
                    <th>Shipper Name</th>
                    <th>Receiver Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="deliveryData">
                <!-- Data will be populated here -->
            </tbody>
        </table>
    </div>

    <div id="view_modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="close_modal()">&times;</span>
            <div id="order_info"></div>
        </div>
    </div>

    <!-- Assign Modal -->
    <div class="assign-overlay" id="assign_overlay"></div>
    <div class="assign-modal" id="assign_modal">
        <h2>Assign Delivery</h2>
        <span class="close-btn" onclick="close_modal('assign-modal', 'assign-overlay')">
            &times;</span>
        <div class="assign-table-container">
            <table class="assign-table" id="delivery_table">
                <thead>
                    <tr>
                        <th>Delivery Partner</th>
                        <th>Vehicle</th>
                        <th>App</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- radio will be added here -->
                </tbody>
            </table>
            
        </div>
        <div class="assign-button-container">
                <button type="submit" class="assign" disabled>
                    Assign Order</button>
            </div>
    </div>


    <script src="js/assign_delivery.js"></script>
    <script src="js/get_rider.js"></script>

</body>
</html>

<?php require_once('admin_footer.php'); ?>