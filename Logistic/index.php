<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>home</title>
        <link rel="stylesheet" href="css/index2.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="css/order_style.css">
    </head>

    <body>
        <div class="wrapper">
            <div class="container">
                <!-- Existing content in home file -->
                <i class="fas fa-map-marker-alt"></i>
                <input class="input-location" type="text" placeholder="Lower Bicutan, Taguig" readonly>
                <h2 class="h2-1">Let's track your package</h2>
                <p class="p-1">Please enter your tracking number: </p>
                <div class="search-container">
                    <i class="fas fa-search fa-2xl"></i>
                    <input type="text" class="input-tracking" placeholder="Enter tracking number" id="delivery_reference_input" value="<?php echo $_SESSION['trackingNumber']?>">
                    <button class="search-tracking" onclick="searchTracking()">Search</button>
                </div>
            </div>
            <main>
                <div id="trackingMessage" class="tracking-message">
                <p> Kindly provide a tracking number to display tracking details. </p>
                </div>

                <div class="tracking-history">
                    <h2>Tracking History</h2>
                    <!-- Existing tracking information -->
                    <div class="shop">
                        <img src="images/shop_location.png" alt="" class="shop-location">
                        <div class="shop-info">
                            <p></p>
                            <p>Tracking #: </p>
                        </div>
                    </div>
                    <div class="shop">
                        <img src="images/shop_location.png" alt="" class="shop-location">
                        <div class="shop-info">
                            <p><strong></strong></p>
                            <p>Tracking #: </p>
                        </div>
                    </div>
                </div>

                <!-- Receiver and Shipper information from Orders Page -->
                <div class="information">
                    <div class="info-heading">
                        <h4>INFORMATION</h4>
                    </div>

                    <div class="shipper">
                        <h4><i class="fas fa-box"></i> Shipper Details</h4>
                        <hr>
                        <p>
                            <h5>
                                <strong id="shipper_name">Shipper Name:</strong> <br>
                                <strong id="address">Address:</strong> <br>
                            </h5>
                        </p>
                    </div>

                    <div class="receiver">
                        <h4><i class="fas fa-box-open"></i> Receiver Details</h4>
                        <hr>
                        <p>
                            <h5>
                                <strong id="receiver_name">Receiver Name:</strong> <br>
                                <strong id="address">Address:</strong> <br>
                            </h5>
                        </p>
                    </div>
                </div>
            </main>

            <footer>
                <div class="footer-icons">
                    <a href="index.php" class="icon-link active" id="homeButton">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="shipment.php" class="icon-link">
                        <i class="fas fa-truck"></i>
                        <span>Shipment</span>
                    </a>
                </div>
            </footer>

        </div>
        <script src="js/search_tracking.js"></script>
        <script src="js/navigation.js"></script>
        <script src="get_homepage_info.php"></script>
    </body>
</html>