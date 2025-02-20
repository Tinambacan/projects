const deliveryTableBody = document.getElementById('delivery_table');
const itemsPerPage = 10;
let currentPage = 1;

document.getElementById('deliveryReferenceInput').addEventListener('input', function () {
    const searchTerm = this.value.toLowerCase();
    fetchDeliveryList(searchTerm);
});

function fetchDeliveryList(searchTerm = '') {
    deliveryTableBody.innerHTML = '';

    fetch('controller/get-delivery-list.php')
        .then(response => response.json())
        .then(data => {
            const filteredData = data.filter(entry => {
                const orderId = entry.order_id !== null ? entry.order_id.toString() : 'N/A';
                const receiver = entry.receiver_name !== null ? entry.receiver_name.toString() : 'N/A';
                const srcName = entry.source_name !== null ? entry.source_name.toString() : 'N/A';
                const destinationAddress = entry.destination_address !== null ? entry.destination_address.toString() : 'N/A';
                const srcAddress = entry.src_address !== null ? entry.src_address.toString() : 'N/A';

                let statusLabel;
                let statusClass = '';

                switch (entry.status) {
                    case 0:
                        statusLabel = 'Pending';
                        statusClass = 'pending-status';
                        break;
                    case 1:
                        statusLabel = 'In Transit';
                        statusClass = 'in-transit-status';
                        break;
                    case 2:
                        statusLabel = 'Shipped Out';
                        statusClass = 'shipped-out-status';
                        break;
                    case 3:
                        statusLabel = 'Delivered';
                        statusClass = 'delivered-status';
                        break;
                }

                const riderName = entry.rider_name !== null ? entry.rider_name.toString() : 'Not Assigned';

                return (
                    searchTerm === '' ||
                    orderId.toLowerCase().includes(searchTerm) ||
                    receiver.toLowerCase().includes(searchTerm) ||
                    destinationAddress.toLowerCase().includes(searchTerm) ||
                    srcName.toLowerCase().includes(searchTerm) ||
                    srcAddress.toLowerCase().includes(searchTerm) ||
                    statusLabel.toLowerCase().includes(searchTerm) ||
                    riderName.toLowerCase().includes(searchTerm)
                );
            });

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const dataSlice = filteredData.slice(startIndex, endIndex);

            let dataFound = false;
            dataSlice.forEach(entry => {
                const orderId = entry.order_id !== null ? entry.order_id.toString() : 'N/A';
                const receiver = entry.receiver_name !== null ? entry.receiver_name.toString() : 'N/A';
                const destinationAddress = entry.destination_address !== null ? entry.destination_address.toString() : 'N/A';
                const srcName = entry.source_name !== null ? entry.source_name.toString() : 'N/A';
                const srcAddress = entry.src_address !== null ? entry.src_address.toString() : 'N/A';

                let statusLabel;
                let statusClass = '';

                switch (entry.status) {
                    case 0:
                        statusLabel = 'Pending';
                        statusClass = 'pending-status';
                        break;
                    case 1:
                        statusLabel = 'In Transit';
                        statusClass = 'in-transit-status';
                        break;
                    case 2:
                        statusLabel = 'Shipped Out';
                        statusClass = 'shipped-out-status';
                        break;
                    case 3:
                        statusLabel = 'Delivered';
                        statusClass = 'delivered-status';
                        break;
                }

                const riderName = entry.rider_name !== null ? entry.rider_name.toString() : 'Not Assigned';
                const titleAttribute = 'Click to see the shipment details history';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${orderId}</td>
                    <td>${receiver}</td>
                    <td>${destinationAddress}</td>
                    <td>${srcName}</td>
                    <td>${srcAddress}</td>
                    <td class="${statusClass}" title="${titleAttribute}">${statusLabel}</td>
                    <td>${riderName}</td>
                `;

                deliveryTableBody.appendChild(row);
                dataFound = true;
            });

            if (!dataFound && searchTerm !== '') {
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = '<td colspan="7">No data found</td>';
                deliveryTableBody.appendChild(noDataRow);
            }

            addPaginationControls(filteredData.length);
        })
        .catch(error => console.error('Error:', error));
}

function addPaginationControls(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationContainerElement = document.getElementById('paginationContainer');
    paginationContainerElement.innerHTML = ''; // Clear existing pagination
    paginationContainerElement.appendChild(createPagination(totalPages));
}

function createPagination(totalPages) {
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'pagination';

    // Create Previous button
    const prevButton = document.createElement('button');
    prevButton.textContent = 'Previous';
    prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchDeliveryList();
        }
    });
    paginationContainer.appendChild(prevButton);

    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.addEventListener('click', () => {
            currentPage = i;
            fetchDeliveryList();
        });

        if (i === currentPage) {
            pageButton.classList.add('active'); // Highlight the active page
        }

        paginationContainer.appendChild(pageButton);
    }

    // Create Next button
    const nextButton = document.createElement('button');
    nextButton.textContent = 'Next';
    nextButton.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            fetchDeliveryList();
        }
    });
    paginationContainer.appendChild(nextButton);

    return paginationContainer;
}


function displayDeliveryHistoryModal(deliveryReferenceNumber, deliveryHistory) {
  // Create a modal
  const modals = document.createElement('div');
  modals.classList.add('modals');

  // Create content for the modal
  const content = document.createElement('div');
  content.classList.add('modal-contlist');

  // Create a close button for the modal
  const closeButton = document.createElement('span');
  closeButton.innerHTML = '&times;';
  closeButton.classList.add('close');

  // Add click event listener to close the modal when the close button is clicked
  closeButton.addEventListener('click', function () {
    modals.style.display = 'none';
  });

  // Append the close button to the content
  content.appendChild(closeButton);

  const title = document.createElement('h3');
  title.textContent = `Shipment History for Order ID: ${deliveryReferenceNumber}`;
  content.appendChild(title);

  // Check if there is no record for the order ID
  if (deliveryHistory.length === 0) {
    const noRecordMessage = document.createElement('p');
    noRecordMessage.textContent = 'No record found for this Tracking';
    content.appendChild(noRecordMessage);
  } else {
    // Create and append delivery history information to the content
    const historyList = document.createElement('ul');
    deliveryHistory.forEach(historyEntry => {
      const historyItem = document.createElement('li');
      historyItem.textContent = `${historyEntry.timestamp} - ${historyEntry.description}`;
      historyList.appendChild(historyItem);
    });

    content.appendChild(historyList);
  }

  // Append the content to the modal
  modals.appendChild(content);

  // Append the modal to the body
  document.body.appendChild(modals);

  // Display the modal
  modals.style.display = 'block';
}


function showDeliveryHistory(deliveryReferenceNumber) {
  fetch('controller/get-delivery-history-api.php?delivery_reference_number=' + deliveryReferenceNumber)
    .then(response => response.json())
    .then(data => {
      // Display the delivery history in a modal
      displayDeliveryHistoryModal(deliveryReferenceNumber, data);
    })
    .catch(error => console.error('Error fetching delivery history:', error));
}


// Add click event listener to each row in the table
document.getElementById('delivery_table').addEventListener('click', function (event) {
  // Check if the clicked element is a table cell
  if (event.target.tagName === 'TD' && event.target.parentNode.tagName === 'TR') {
    // Get the delivery reference number from the first cell in the clicked row
    const deliveryReferenceNumber = event.target.parentNode.firstElementChild.textContent;

    // Show the delivery history for the clicked row
    showDeliveryHistory(deliveryReferenceNumber);
    
  }
});

fetchDeliveryList();