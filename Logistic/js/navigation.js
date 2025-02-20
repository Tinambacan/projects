document.addEventListener('DOMContentLoaded', function () {
    const homeButton = document.getElementById('homeButton');
    const shipmentButton = document.getElementById('shipmentButton');

    if (homeButton) {
        homeButton.style.pointerEvents = 'none';
    }

    if (shipmentButton) {
        shipmentButton.style.pointerEvents = 'none';
    }
});