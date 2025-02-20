$(document).ajaxStart(function () {
    $("#loader-get-update").show();
    $("body").addClass("no-scroll");
}).ajaxStop(function () {
    $("#loader-get-update").hide();
    $("body").removeClass("no-scroll");
});