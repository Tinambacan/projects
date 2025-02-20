<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shipment Page</title>
        <link rel="stylesheet" href="css/tracking_history.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                    <div class="logistic-data">
                        <img src="images/logistic logo-circle.png" alt="" class="logistic-image">
                        <div class="logistic">Cybertech Logistics</div>
                    </div>
                    <h2 class="h2-1">Let's track your package</h2>
                    
                    <div class="search-container">
                        <i class="fas fa-search fa-2xl"></i>
                        <input type="text" class="input-tracking" placeholder="Enter 5-digit tracking number" id="deliveryReferenceInput" maxlength="5" onkeypress="handleKeyPress(event)">
                        <button class="search-tracking" onclick="searchDeliveryHistory()">Search</button>
                        <button class="reset-search" onclick="resetSearch()" style="display: none;">X</button>

                    </div>
                    <div id="inlineErrorMessage" class="inline-error-message"></div>
            </div>

            <main>
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

                    <div id="trackingMessage" class="tracking-message">
                        <p> Kindly provide a tracking number to display tracking details. </p>
                    </div>
                
                    <div class="progress-tracking">
                        <div class="timeline" id="timeline"></div>
                    </div> 
                </div>
            </main>
        </div>
        <script src="js/get-delivery-history.js"></script>
    </body>
</html>