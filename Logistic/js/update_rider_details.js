function updateRiderDetails(event) {
    event.preventDefault();

    let riderName = document.getElementById('rider_name').value; 
    let newRiderName = document.getElementById('new_rider_name').value;
    let newVehicle = document.getElementById('new_vehicle').value;
    let newUsername = document.getElementById('new_username').value;
    let newPassword = document.getElementById('new_password').value;

    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    formData.append('rider_name', riderName); 
    formData.append('new_rider_name', newRiderName);
    formData.append('new_vehicle', newVehicle);
    formData.append('new_username', newUsername);
    formData.append('new_password', newPassword);

    xhr.open('POST', 'controller/update_rider_info_api.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            console.log(response);
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Details Updated!',
                    text: 'Your details have been updated successfully.'
                });
            } else {
 
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        } else {
            console.log("Error updating rider details");
        }
    }

    xhr.send(formData);
}
