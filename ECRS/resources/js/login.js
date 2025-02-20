$(document).ready(function () {
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
});

$(document).on("click", "#myLoginFaculty", function (event) {
    event.preventDefault();

    var form = $(this).closest("form")[0];
    var $button = $(this);

    if (!form) {
        console.error("Form not found.");
        return;
    }

    $button.prop("disabled", true).html(`
       <div class="flex items-center text-center justify-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-xl text-red-900 dark:text-[#CCAA2C]"></i>
            </div>
    `).removeClass("hover:bg-gray-200 cursor-pointer");

    $.ajax({
        url: "/sign-in-faculty",
        type: "POST",
        data: new FormData(form),
        processData: false,
        contentType: false,
        success: function (response) {
            // console.log("Response received:", response);

            if (response.status === "success") {
                form.reset();
                Swal.fire({
                    title: "Success!",
                    text: response.message || "Login successful!",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = response.redirect_url;
                });
            } else {
                $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
                handleLoginError(response.message || "An error occurred.");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", status, error);
            $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
            handleLoginError(
                "An error occurred while processing your request."
            );
        },
    });

    function handleLoginError(message) {
        $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
        form.reset();
        Swal.fire({
            title: "Error!",
            text: message,
            icon: "error",
        });
    }
});

$(document).on("click", "#myLoginAdmin", function (event) {
    event.preventDefault();

    var form = $(this).closest("form")[0];
    var $button = $(this);

    if (!form) {
        console.error("Form not found.");
        return;
    }

    $button.prop("disabled", true).html(`
       <div class="flex items-center text-center justify-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-xl text-red-900 dark:text-[#CCAA2C]"></i>
            </div>
    `).removeClass("hover:bg-gray-200 cursor-pointer");

    $.ajax({
        url: "/sign-in-admin",
        type: "POST",
        data: new FormData(form),
        processData: false,
        contentType: false,
        success: function (response) {
            // console.log("Response received:", response);

            if (response.status === "success") {
                form.reset();
                Swal.fire({
                    title: "Success!",
                    text: response.message || "Login successful!",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = response.redirect_url;
                    $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
                });
            } else if (response.status === "error") {
                $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
                console.error("Error:", response.message);
                form.reset();
                Swal.fire({
                    title: "Error!",
                    text: response.message || "An error occurred.",
                    icon: "error",
                });
            } else {
                $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
                console.error("Invalid response format:", response);
                form.reset();
                Swal.fire({
                    title: "Error!",
                    text: "Unexpected response format.",
                    icon: "error",
                });
            }
        },
        error: function (xhr, status, error) {
            form.reset();
            $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
            console.error("AJAX error:", status, error);
        },
    });
});

$(document).on("click", "#myLoginStudent", function (event) {
    event.preventDefault();

    var form = $(this).closest("form")[0];
    var $button = $(this);

    if (!form) {
        console.error("Form not found.");
        return;
    }

    $button.prop("disabled", true).html(`
        <div class="flex items-center text-center justify-center gap-2">
                 <i class="fa-solid fa-spinner fa-spin text-xl text-red-900 dark:text-[#CCAA2C]"></i>
             </div>
     `).removeClass("hover:bg-gray-200 cursor-pointer");

    $.ajax({
        url: "/sign-in-student",
        type: "POST",
        data: new FormData(form),
        processData: false,
        contentType: false,
        success: function (response) {
            // console.log("Response received:", response);

            if (response.status === "success") {
                form.reset();

                Swal.fire({
                    title: "Success!",
                    text: response.message || "Login successful!",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = response.redirect_url;
                });
            } else if (response.status === "error") {
                $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
                console.error("Error:", response.message);
                form.reset();
                Swal.fire({
                    title: "Error!",
                    text: response.message || "An error occurred.",
                    icon: "error",
                });
            } else {
                $button.prop("disabled", false).html("Login").addClass("hover:bg-gray-200 cursor-pointer");
                console.error("Invalid response format:", response);
                form.reset();
                Swal.fire({
                    title: "Error!",
                    text: "Unexpected response format.",
                    icon: "error",
                });
            }
        },
        error: function (xhr, status, error) {
            form.reset();
            console.error("AJAX error:", status, error);
        },
    });
});
