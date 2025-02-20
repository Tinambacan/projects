<?php require_once('admin_header.php'); ?>

<head>
    <title>Delivery List </title>
    <link rel="stylesheet" href="css/style_list.css">
</head>

<div class="partner-box">
    <h3> Delivery List</h3>
    <input type="text" class="input-tracking" placeholder="Enter Order ID" id="deliveryReferenceInput">

</div>

<table>
    <thead>
        <tr>
            <th>Tracking No.</th>
            <th>Receiver Name</th>
            <th>Receiver Address</th>
            <th>Source Name</th>
            <th>Source Address</th>
            <th>Status</th>
            <th>Rider Name</th>
        </tr>
    </thead>
    <tbody id="delivery_table">
        <!-- Delivery history data will be displayed here -->
    </tbody>
</table>
<div id="paginationContainer">
</div>
<script src="js/delivery_list.js"></script>


<?php require_once('admin_footer.php'); ?>