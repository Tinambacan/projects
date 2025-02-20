document.addEventListener('DOMContentLoaded', function () {
    const addUserModal = document.getElementById('add_user_modal');
    const addUserBtn = document.querySelector('.add-user-button');
    const closeAddUserBtn = document.querySelector('.close');
    const addUserSubmitBtn = document.getElementById('add_user_btn');
    const alertModal = document.getElementById('accounts_alerts_modal');
    const alertMessage = document.getElementById('alertMessage');

    function clearAddUserForm() {
        document.getElementById('rider_name').value = '';
        document.getElementById('vehicle').value = '';
        document.getElementById('username').value = '';
        document.getElementById('email').value = '';
    }

    addUserBtn.onclick = function () {
        addUserModal.style.display = 'block';
        clearAddUserForm();
    };

    closeAddUserBtn.onclick = function () {
        addUserModal.style.display = 'none';
        clearAddUserForm();
    };

    addUserSubmitBtn.onclick = function () {
        const riderName = document.getElementById('rider_name').value;
        const vehicle = document.getElementById('vehicle').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;

        if (!riderName || !vehicle || !username || !email) {
            openAlertModal('Please fill in all required fields.');
            return;
        }

        console.log('Sending data:', { riderName, vehicle, username, email });

        // Additional code for sending data to the server and handling the response
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                console.log('Received response:', xhr.responseText);
                if (xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        openAlertModal('Rider added successfully!');
                        addRowToTable(riderName, vehicle, username, email);
                    } else {
                        openAlertModal('Failed to add rider. Please try again.');
                    }
                } else {
                    openAlertModal('Error in the request. Please check the console for details.');
                    console.error('Error in the request:', xhr.status);
                }
            }
        };

        xhr.open('POST', 'controller/account_add_user.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(
            `riderName=${encodeURIComponent(riderName)}&` +
            `vehicle=${encodeURIComponent(vehicle)}&` +
            `username=${encodeURIComponent(username)}&` +
            `email=${encodeURIComponent(email)}`
        );

        // Close the modal after adding the user
        addUserModal.style.display = 'none';
        clearAddUserForm();
    };

    window.onclick = function (event) {
        if (event.target === addUserModal) {
            addUserModal.style.display = 'none';
            clearAddUserForm();
        }
    };

    function openAlertModal(message) {
        alertMessage.textContent = message;
        alertModal.style.display = 'block';
    }

    function closeAlertModal() {
        alertModal.style.display = 'none';
    }

    // Function to close the alert modal
    window.closeAlertModal = function () {
        closeAlertModal();
    };

    function addRowToTable(riderName, vehicle, username, email) {
        const table = document.querySelector('.table-userlist tbody');
        const newRow = table.insertRow(table.rows.length);
        const cell1 = newRow.insertCell(0);
        const cell2 = newRow.insertCell(1);
        const cell3 = newRow.insertCell(2);
        const cell4 = newRow.insertCell(3);

        cell1.innerHTML = riderName;
        cell2.innerHTML = vehicle;
        cell3.innerHTML = username;
        cell4.innerHTML = email;
    }
});