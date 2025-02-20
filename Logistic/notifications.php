<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notifications Page</title>
        <link rel="stylesheet" href="css/notifications.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body>
        <header>
            <button class="button back-button">
                <img src="images/back_icon.png" alt="Back">
            </button>
            <button class="button menu-button">
                <img src="images/menu_icon.png" alt="Menu">
            </button>
        </header>

        <div class="title-container">
            <h2>Notifications</h2>
            <button class="button notification-button">
                <img src="images/notification_icon.png" alt="Notifications">
            </button>
        </div>

        <main>
            <div class="notification-item">
                <div class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="notification-details">
                    <div class="notification-title">Package arriving soon</div>
                    <div class="notification-text">
                        Your package 75311260294302 is almost at its final destination. 54m
                    </div>
                </div>
            </div>
            
            <div class="notification-item">
                <div class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="notification-details">
                    <div class="notification-title">Package shipped</div>
                    <div class="notification-text">
                        Your order 578195051452533365 was shipped. 16h
                    </div>
                    <img src="images/mini_fan.png" alt="Product Image" class="product-image">
                </div>
            </div>

            <div class="notification-item">
                <div class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="notification-details">
                    <div class="notification-title">Package picked up</div>
                    <div class="notification-text">
                        Your order 578195051452533365 has been picked up. 20h
                    </div>
                    <img src="images/mini_fan.png" alt="Product Image" class="product-image">
                </div>
            </div>

            <div class="notification-item">
                <div class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="notification-details">
                    <div class="notification-title">Package delivered</div>
                    <div class="notification-text">
                        Your package PT6126252U8F2AG was delivered. 1d
                    </div>
                    <img src="images/blouse.png" alt="Product Image" class="product-image">
                </div>
            </div>

            <div class="notification-item">
                <div class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="notification-details">
                    <div class="notification-title">Package arriving soon</div>
                    <div class="notification-text">
                        Your package PT6126252U8F2AG is almost at its final destination.
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <div class="footer-button">
                <i class="fas fa-home"></i>
                <p>Home</p>
            </div>
            <div class="footer-button">
                <i class="fas fa-clipboard-list"></i>
                <p>Orders</p>
            </div>
            <div class="footer-button">
                <i class="fas fa-truck"></i>
                <p>Shipment</p>
            </div>
            <div class="footer-button">
                <i class="fas fa-bell"></i>
                <p>Notifications</p>
            </div>
        </footer>
    </body>
</html>