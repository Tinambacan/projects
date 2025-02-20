$(document).ready(function () {
    // Disable the back button functionality
    window.history.pushState(null, "", window.location.href);
    $(window).on("popstate", function () {
        window.history.pushState(null, "", window.location.href);
    });
});
