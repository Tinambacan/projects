async function searchTracking() {
    const trackingNumber = document.getElementById('delivery_reference_input').value;
    try {
        const response = await fetch(`get_homepage_info.php?trackingNumber=${trackingNumber}`);

        if (response.ok) {
            const data = await response.json();

            const trackingMessage = document.getElementById('trackingMessage');

            if (data.length > 0) {
                const firstEntry = data[0];

                const trackingHistory = document.querySelector('.tracking-history');
                trackingHistory.innerHTML = `
                    <h2>Tracking History</h2>
                    <div class="shop">
                        <img src="images/shop_location.png" alt="" class="shop-location">
                        <div class="shop-info">
                            <p>${firstEntry.source_name}</p>
                            <p>Tracking #: ${firstEntry.del_reference_id}</p>
                        </div>
                    </div>
                `;

                const information = document.querySelector('.information');
                information.innerHTML = `
                    <div class="info-heading">
                        <h4>INFORMATION</h4>
                    </div>
                    <div class="shipper">
                        <h4><i class="fas fa-box"></i> Shipper Details</h4>
                        <hr>
                        <p>
                            <h5>
                                <strong id="shipper_name">Shipper Name: ${firstEntry.source_name}</strong> <br>
                                <strong id="address">Address: ${firstEntry.src_address}</strong> <br>
                            </h5>
                        </p>
                    </div>
                    <div class="receiver">
                        <h4><i class="fas fa-box-open"></i> Receiver Details</h4>
                        <hr>
                        <p>
                            <h5>
                                <strong id="receiver_name">Receiver Name: ${firstEntry.receiver_name}</strong> <br>
                                <strong id="address">Address: ${firstEntry.destination_address}</strong> <br>
                            </h5>
                        </p>
                    </div>
                `;

                trackingMessage.style.display = 'none'; 
                trackingHistory.style.display = 'block';
                information.style.display = 'block';
            } else {
                alert('No tracking history found for the provided tracking number.');
            }
        } else {
            alert('Error fetching tracking data. Please try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}