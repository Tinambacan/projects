function fetchDeliveryHistory(deliveryReferenceNumber) {
    const timeline = document.getElementById('timeline');
    
    timeline.innerHTML = '';
    const inlineErrorMessage = document.getElementById('inlineErrorMessage');

    fetch(`controller/get-delivery-history-api.php?delivery_reference_number=${deliveryReferenceNumber}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Tracking history not found');
            }
            return response.json();
        })
        .then(data => {
            if (data.length > 0) {
                const statusMessage = "Status: ";
                const status = document.createElement('p');
                status.id = 'status';
                timeline.appendChild(status);
                data.reverse().forEach(item => {
                    const eventLi = document.createElement('li');
                    console.log(item.status);
                    if (item.status === 0) {
                        status.textContent = statusMessage + 'Pending';
                    } else if (item.status === 1) {
                        status.textContent = statusMessage + 'In Transit';
                    } else if (item.status === 2) {
                        status.textContent = statusMessage + 'Shipped Out';
                    } else if (item.status === 3) {
                        status.textContent = statusMessage + 'Delivered';
                    } else {
                        status.textContent = statusMessage + 'Unknown Status';
                    }
                    eventLi.innerHTML = `
                        <time datetime="${item.timestamp}">${item.timestamp}</time>
                        <span><strong>${item.description}</strong> ${item.checkpoint_location}</span>
                    `;
                    timeline.appendChild(eventLi);
                });

                const trackingMessage = document.getElementById('trackingMessage');
                trackingMessage.style.display = 'none';
                inlineErrorMessage.style.display = 'none';
            } else {
                inlineErrorMessage.textContent = 'Tracking number not found.';
                inlineErrorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching delivery history:', error);
            inlineErrorMessage.textContent = 'Error fetching delivery history. Please try again later.';
            inlineErrorMessage.style.display = 'block';
        });
}

function searchDeliveryHistory() {
    const deliveryReferenceInput = document.getElementById('deliveryReferenceInput');
    const deliveryReferenceNumber = deliveryReferenceInput.value;

    const searchButton = document.querySelector('.search-tracking');
    const inlineErrorMessage = document.getElementById('inlineErrorMessage');

    searchButton.disabled = false;

    if (deliveryReferenceNumber !== '' && !isNaN(deliveryReferenceNumber)) {
        fetchDeliveryHistory(deliveryReferenceNumber);

        document.querySelector('.reset-search').style.display = 'inline-block';
    } else {
        inlineErrorMessage.textContent = 'Please enter a valid 5-digit tracking number.';
        inlineErrorMessage.style.display = 'block';

        document.querySelector('.reset-search').style.display = 'none';
    }
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchDeliveryHistory();
    }
}

function resetSearch() {
    document.getElementById('deliveryReferenceInput').value = '';
    document.getElementById('inlineErrorMessage').style.display = 'none';
    document.getElementById('trackingMessage').style.display = 'block';
    document.getElementById('timeline').innerHTML = '';
    document.querySelector('.reset-search').style.display = 'none';
}