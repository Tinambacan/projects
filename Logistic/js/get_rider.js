function close_modal(modalClass, overlayClass) {
    const modals = document.querySelectorAll(`.${modalClass}`);
    const overlays = document.querySelectorAll(`.${overlayClass}`);

    modals.forEach(modal => modal.style.display = 'none');
    overlays.forEach(overlay => overlay.style.display = 'none');
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const assignButton = document.querySelector('.assign');

    document.querySelector('.assign-table-container')
        .addEventListener('change', function (event) {
        // Directly target radio buttons inside the container
        const radioButtons = document
            .querySelectorAll('.assign-table-container input[type="radio"]');
        const atLeastOneChecked = Array.from(radioButtons)
            .some(radio => radio.checked);
        assignButton.disabled = !atLeastOneChecked;
    });

    // Check which rider is selected
    assignButton.addEventListener('click', function () {
        // Check if any radio button is selected
        if (selectedRadio) {
            console.log("Selected Radio Value: " + selectedRadio.id);
            // Perform further actions with the selected radio button value
        } else {
            console.log("No radio button selected.");
        }
    });

    // Event listener for dynamically added radio buttons
    document.querySelector('.assign-table-container')
        .addEventListener('change', function (event) {
        const clickedRadio = event.target;
        if (clickedRadio.type === 'radio') {
            selectedRadio = clickedRadio;
        }
    });
});

const assignButtons = document.getElementsByClassName('assign-btn');

// Event listeners to each 'Assign' button
for (let i = 0; i < assignButtons.length; i++) {
    assignButtons[i].addEventListener('click', handleAssignButtonClick);
}

function handleAssignButtonClick() {
    // Access the id of the clicked "Assign" button directly
    let buttonId = this.id;
    console.log(`HI Assign button with ID ${buttonId} clicked`);
    check = 1;
    console.log("hello"+check);
}
let check = 0;
let selectedPartnerId = null; // Variable to store the selected partner_id

// Initial fetch and display
fetchAndDisplayRiders();

// Fetch and display every 5 seconds (adjust the interval as needed)
setInterval(fetchAndDisplayRiders, 1000);

// Function to fetch and display riders
function fetchAndDisplayRiders() {
    const deliveryTableBody = document.querySelector('#delivery_table tbody');
    
    fetch('controller/controller_get_rider.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Get the currently selected radio button
            const currentlySelected = document.querySelector(`input[name="options"]:checked`);

            // Clear existing rows
            deliveryTableBody.innerHTML = '';

            if (data.length === 0) {
                // Display a message when there are no riders available
                const noRidersRow = document.createElement('tr');
                noRidersRow.innerHTML = '<td colspan="4">No Riders Available</td>';
                deliveryTableBody.appendChild(noRidersRow);
            } else {
                // Iterate over the data and append rows to the table
                data.forEach(function (row) {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td class="rider_name">${row.rider_name}</td>
                        <td class="vehicle">${row.vehicle}</td>
                        <td><input type="radio" id="${row.partner_id}" 
                            name="options" value="${row.partner_id}"></td>
                    `;

                    deliveryTableBody.appendChild(newRow);
                });

                // Restore the checked state after updating the table
                const assignModal = document.querySelector('.assign-modal');
                if (currentlySelected && assignModal.style.display !== 'none') {
                    document.getElementById(currentlySelected.id).checked = true;
                    check = 0;
                }
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

