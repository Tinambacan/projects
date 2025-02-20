document.addEventListener('DOMContentLoaded', function () {
    // Get the modal for logout
    var logoutModal = document.getElementById('logoutModal');

    // Get the button that opens the logout modal
    var logoutButton = document.getElementById('confirmLogout');

    // Get the <span> element that closes the logout modal
    var spanLogout = document.getElementsByClassName('logout-close')[0];

    // When the user clicks the logout button, open the logout modal
    logoutButton.onclick = function () {
        logoutModal.style.display = 'block';
    };

    // When the user clicks on Yes in the logout modal, close the modal and initiate logout
    document.getElementById('confirmYes').onclick = function () {
        logoutModal.style.display = 'none';
        window.location.href = 'log-out.php';
    };

    // When the user clicks on No in the logout modal, close the modal
    document.getElementById('confirmNo').onclick = function () {
        logoutModal.style.display = 'none';
    };

    // When the user clicks on <span> (x) in the logout modal, close the modal
    spanLogout.onclick = function () {
        logoutModal.style.display = 'none';
    };

    // When the user clicks anywhere outside of the logout modal, close it
    window.onclick = function (event) {
        if (event.target == logoutModal) {
            logoutModal.style.display = 'none';
        }
    };

    // Call setActive function when DOM content is loaded
    document.addEventListener('DOMContentLoaded', setActive);

    // Add setActive function to highlight active link
    function setActive() {
        var currentURL = window.location.href;
        var links = document.querySelectorAll('.list-group-item');
        links.forEach(function (link) {
            if (link.href === currentURL) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
});