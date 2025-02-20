<?php require_once('admin_header.php'); ?>
<title> Accounts </title>
<link rel="stylesheet" href="css/account_rider_style1.css">


<div class="rider-userlist">
    <div class="header-container">
        <h2>Rider Users List</h2>
        <button class="add-user-button">Add User</button>
    </div>

    <div class="table-userlist">
        <table>
            <thead>
                <tr>
                    <th>Rider Name</th>
                    <th>Vehicle</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>

            <tbody>

                <?php require_once('display_delivery_partner.php');

                foreach ($deliveryPartners as $partner) {
                    echo '<tr>';
                    echo '<td>' . $partner['rider_name'] . '</td>';
                    echo '<td>' . $partner['vehicle'] . '</td>';
                    echo '<td>' . $partner['username'] . '</td>';
                    echo '<td>' . $partner['email'] . '</td>';
                    echo '</tr>';
                }
                ?>

            </tbody>
        </table>
    </div>
</div>

<div id="add_user_modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add Rider User</h2>
        <hr>
        <form id="add_user_form">
            <label for="riderName">Rider Name:</label>
            <input type="text" id="rider_name" name="riderName" required>

            <label for="vehicle">Vehicle:</label>
            <input type="text" id="vehicle" name="vehicle" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="username">Email:</label>
            <input type="text" id="email" name="email" required>

            <button type="button" id="add_user_btn">
                Add Rider
            </button>
        </form>
    </div>
</div>

<div id="accounts_alerts_modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAlertModal()">&times;</span>
        <h2>Alert</h2>
        <p id="alertMessage"></p>
        <button onclick="closeAlertModal()" class="accounts-close-modal">OK</button>
    </div>
</div>

<script src="js/account_rider.js"></script>
<?php require_once('admin_footer.php'); ?>