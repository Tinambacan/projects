<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Fetch and Update Example</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Container to display API data */
        #apiData {
            text-align: center;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Button styles */
        button {
            padding: 10px;
            margin: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Style for the modal overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* Style for the modal content */
        .modal-content {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h1>Delivery History</h1>

    <!-- Container to display API data -->
    <div id="apiData"></div>

    <!-- Button to trigger API fetch -->
    <button onclick="fetchData()">Fetch Data</button>
    <button onclick="openUpdateModal()">Update</button>

    <!-- Modal overlay -->
    <div id="updateModal" class="modal-overlay">
        <!-- Modal content -->
        <div class="modal-content">
            <h2>Update Delivery</h2>
            <form id="updateForm">
                <label for="checkpointLocation">Checkpoint Location:</label>
                <input type="text" id="checkpointLocation" name="checkpointLocation" required>

                <label for="description">Description:</label>
                <input type="text" id="description" name="description" required>

                <label for="status">Status:</label>
                <input type="text" id="status" name="status" required>

                <button type="button" onclick="submitUpdate()">Submit</button>
                <button type="button" onclick="closeUpdateModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // Function to fetch data from the API
        function fetchData() {
            const partnerId = prompt('Enter Partner ID (or leave empty for all):');
            const apiUrl = `controller/get-delivery-list.php${partnerId ? `?partner_id=${partnerId}` : ''}`;

            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Display the fetched data
                    displayData(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle errors or display an error message
                    document.getElementById('apiData').innerHTML = 'Error fetching data from the API';
                });
        }

        // Function to open the update modal
        function openUpdateModal() {
            document.getElementById('updateModal').style.display = 'flex';
        }

        // Function to close the update modal
        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        // Function to submit the update form
        function submitUpdate() {
            // Collect form data
            const deliveryReferenceNumber = prompt('Enter Delivery Reference Number:');
            const timestamp = new Date().toISOString(); // You can adjust this as per your requirement
            const checkpointLocation = document.getElementById('checkpointLocation').value;
            const description = document.getElementById('description').value;
            const status = document.getElementById('status').value;

            // Prepare the data for the POST request
            const formData = new FormData();
            formData.append('delivery_reference_number', deliveryReferenceNumber);
            formData.append('timestamp', timestamp);
            formData.append('checkpoint_location', checkpointLocation);
            formData.append('description', description);
            formData.append('delivery_status', status);

            fetch('controller/update-delivery-api.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    if (result && result.update_delivery_success === 'success') {
                        alert('Delivery update successful!');
                        // Fetch and display updated data
                        fetchData();
                        // Close the update modal
                        closeUpdateModal();
                    } else {
                        alert('Delivery update failed or unexpected response from the server.');
                    }
                })
                .catch(error => {
                    console.error('Error updating delivery:', error);
                    alert('Error updating delivery. Check the console for details.');
                });
        }

        // Function to display data on the web page
        function displayData(data) {
            const container = document.getElementById('apiData');
            container.innerHTML = '<h2>Delivery Data:</h2>';

            if (data.status && data.status === 'error') {
                container.innerHTML += `<p>${data.message}</p>`;
            } else if (data.length === 0) {
                container.innerHTML += '<p>No delivery history found.</p>';
            } else {
                // Create a table to display the fetched data
                const table = document.createElement('table');
                table.innerHTML = `
            <tr>
                <th>Order ID</th>
                <th>Destination Address</th>
                <th>Status</th>
                <th>Rider Name</th>
                <th>Partner ID</th>
            </tr>
        `;
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>${item.order_id}</td>
                <td>${item.destination_address}</td>
                <td>${item.status}</td>
                <td>${item.rider_name}</td>
                <td>${item.partner_id}</td>
            `;
                    table.appendChild(row);
                });

                container.appendChild(table);
            }
        }
    </script>
</body>

</html>