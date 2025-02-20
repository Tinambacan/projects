document.addEventListener('DOMContentLoaded', () => {
    const deliveryData = document.getElementById('deliveryData');

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                populateTable(data);
            } else {
                console.error('Error fetching data:', xhr.status);
            }
        }
    };
  
    xhr.open('GET', 'controller/assign_delivery_api.php', true);
    xhr.send();
  
    deliveryData.addEventListener('click', function (event) {
        if (event.target.classList.contains('assign-btn')) {
            openAssignModal();
        }
    });
    
    function openAssignModal() {
        const modal = document.getElementById('assign_modal');
        const overlay = document.getElementById('assign_overlay');
    
        modal.style.display = 'block';
        overlay.style.display = 'block';
    }
    
    function populateTable(data) {
        let tableContent = '';
        let buttonIdCounter = 1;  // Initialize a counter for button IDs
    
        if (data.length > 0) {
            data.forEach(row => {
                tableContent += `
                    <tr>
                        <td>${row.del_reference_id}</td>
                        <td>${row.source_name}</td>
                        <td>${row.receiver_name}</td>
                        <td>pending</td>
                        <td>
                            <button class="assign-btn" id="${row.del_reference_id}">
                                Assign
                            </button>
                        </td>
                    </tr>
                `;
                buttonIdCounter++;  // Increment the button ID counter
            });
        } else {
            tableContent = '<tr><td colspan="5">No orders found</td></tr>';
        }
    
        deliveryData.innerHTML = tableContent;
    }
});

document.addEventListener('click', function (event) {
    if (event.target.classList.contains('assign-btn')) {
        const clickedId = event.target.id;
        console.log('Clicked Assign Button ID:', clickedId);
        // You can perform further actions here based on the clicked ID
    }
});



document.addEventListener('DOMContentLoaded', function () {
    let clickedAssignButtonId;
    let selectedRadioId;

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('assign-btn')) {
            // Store the clicked assign button ID
            clickedAssignButtonId = event.target.id;
            console.log('Clicked Assign Button ID:', clickedAssignButtonId);
        }
    });

    document.querySelector('.assign').addEventListener('click', function () {
        // Check if any radio button is selected
        const selectedRadio = document.querySelector('input[name="options"]:checked');
        
        if (selectedRadio) {
            // Store the selected radio button ID
            selectedRadioId = selectedRadio.value;
            console.log("Selected Radio Value: " + selectedRadioId);

            // Perform further actions or call the function to update the database
            updateDatabase(clickedAssignButtonId, selectedRadioId);
        } else {
            console.log("No radio button selected.");
        }
    });

    function updateDatabase(clickedAssignButtonId, selectedRadioId) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    const successModal = createSuccessModal(selectedRadioId);
                    document.body.appendChild(successModal);
                    
                    const assignModal = document.getElementById('assign_modal');
                    assignModal.style.display = 'none';
                    successModal.style.display = 'block';
                } else {
                    console.error('Error updating data:', xhr.status);
                }
            }
        };
    
        const phpScript = 'controller/update_delivery.php';
        const params = `assign_button_id=${clickedAssignButtonId}&radio_button_id=${selectedRadioId}`;
    
        xhr.open('POST', phpScript, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(params);
    }
    
    function createSuccessModal(deliveryNumber) {
        const modal = document.createElement('div');
        modal.classList.add('success-modal');
    
        modal.innerHTML = `
        <p>Delivery <span class="bold-text">${clickedAssignButtonId}</span> 
            successfully assigned and added to delivery list</p>
        <button class="confirm-button">Confirm</button>
        `;
    
        modal.querySelector('.confirm-button').addEventListener('click', function () {
            location.reload();
        });
    
        return modal;
    }
    
});
