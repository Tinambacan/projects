<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shipment Page</title>
        <link rel="stylesheet" href="css/shipment.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body>

        <main>
            <div class="title-container">
                <h2>Shipment</h2>
            </div>

            <div class="shipment">
                <div class="status">
                    <div class="delivered-icon">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <div class="delivered-details">
                        <div class="delivered-text">
                            Shipped with Standard Local - Cybertech Logistics
                        </div>
                    </div>
                </div>

                <div class="tracking-info">
                    <div class="tracking-label">Tracking Number:</div>
                    <div class="tracking-number">
                        <?php 
                        echo isset($_SESSION['trackingNumber']) ? $_SESSION['trackingNumber'] : ''; 
                        ?>
                    </div>
                </div>
               
                <div class="progress-tracking">
                    <div class="timeline" id="timeline"></div>
                </div> 
            </div>
        </main>

        <footer>
            <div class="footer-icons">
                <a href="index.php" class="icon-link">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="shipment.php" class="icon-link active" id="shipmentButton">
                    <i class="fas fa-truck"></i>
                    <span>Shipment</span>
                </a>
            </div>
        </footer>
        <script src="js/get-delivery-history.js"></script>
        <script src="js/navigation.js"></script>
    </body>
</html>