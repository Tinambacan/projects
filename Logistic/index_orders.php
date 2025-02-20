<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Page</title>
    <link rel="stylesheet" href="css/order_style.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <button class="back-button">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="menu-button">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    </header>

    <div class="title-container">
        <h2>Tracking</h2>
    </div>

    <main>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Enter tracking number" value="123456" readonly>
        </div>

        <div class="information">
            <div class="info-heading">
                <h4>INFORMATION</h4>
            </div>

            <div class="shipper">
                <h4><i class="fas fa-box"></i> Shipper Address</h4>
                <hr>
                <p>
                <p>
                <h5>
                    <strong id="shipper_name">Shipper Name:</strong> <br>
                    <strong id="phone_num">Phone Number:</strong> <br>
                    <strong id="address">Address:</strong> <br>
                    <strong id="email_ship">Email:</strong>
                </h5>
                </p>
            </div>

            <div class="receiver">
                <h4><i class="fas fa-box-open"></i> Receiver Address</h4>
                <hr>
                <p>
                <h5>
                    <strong id="receiver_name">Receiver Name:</strong> <br>
                    <strong id="phone_num">Phone Number:</strong> <br>
                    <strong id="address">Address:</strong> <br>
                    <strong id="email_receiver">Email:</strong>
                </h5>
                </p>
            </div>
        </div>

        <div class="shipment-container">
            <div class="status">
                <h4>SHIPMENT STATUS: IN TRANSIT</h4>
            </div>
            <div class="status-container">
                <h4>Shipment Information</h4>
                <hr>
                <p>
                <h5>
                    <strong id="order_num">Order Number:</strong> <br>
                    <strong id="order_date">Order Date:</strong> <br>
                    <strong id="courier">Courier:</strong> <br>
                    <strong id="payment_mode">Payment Mode:</strong> <br>
                    <strong id="payment_time">Payment Time:</strong> <br>
                    <strong id="ship_date">Shipment Date:</strong> <br>
                    <strong id="track_num">Tracking Number:
                </h5>
                </h5>
                </p>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-icons">
            <a href="index.php" class="icon-link">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="index_orders.php" class="icon-link">
                <i class="fas fa-file-alt"></i>
                <span>Orders</span>
            </a>
            <a href="shipment.php" class="icon-link">
                <i class="fas fa-truck"></i>
                <span>Shipment</span>
            </a>
        </div>
    </footer>
</body>

</html>