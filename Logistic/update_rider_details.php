<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Rider Details</title>
    <link rel="stylesheet" href="css/logistics_update_rider.css">
</head>
<body>
    <div class="update-rider-container">
        <h2>Update Rider Details</h2>
        <form onsubmit="updateRiderDetails(event)">
            <div class="form-group">
                <label for="rider_name">Rider Name:</label>
                <input type="text" 
                        id="rider_name" 
                        name="rider_name" 
                        required>
            </div>

            <br>

            <div class="form-group">
                <label for="newRiderName">New Rider Name:</label>
                <input type="text" 
                        id="new_rider_name" 
                        name="newRiderName" 
                        required>
            </div>

            <div class="form-group">
                <label for="newVehicle">New Vehicle:</label>
                <input type="text" 
                        id="new_vehicle" 
                        name="newVehicle" 
                        required>
            </div>

            <div class="form-group">
                <label for="newUsername">New Username:</label>
                <input type="text" 
                        id="new_username" 
                        name="newUsername" 
                        required>
            </div>

            <div class="form-group">
                <label for="newPassword">New Password:</label>
                <input type="password" 
                        id="new_password" 
                        name="newPassword" 
                        required>
            </div>

            <div class="form-group">
                <button type="submit">Update Details</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/update_rider_details.js"></script>
</body>
</html>
