// $(document).ready(function () {
//     $(".toggle-password").click(function () {
//         $(this).toggleClass("fa-eye fa-eye-slash");
//         var input = $($(this).attr("toggle"));
//         if (input.attr("type") == "password") {
//             input.attr("type", "text");
//         } else {
//             input.attr("type", "password");
//         }
//     });

//     const email = $("#email").val();
//     const role = $("#role").val();

//     let saveButton;
//     let url;

//     if (role == 1) {
//         // Faculty
//         saveButton = $("#set-new-temp-pass-btn");
//         url = `/faculty/reset-temp-password`;
//     } else if (role == 2) {
//         // Admin
//         saveButton = $("#set-new-temp-pass-btn-ad");
//         url = `/admin/reset-temp-password`;
//     } else {
//         // Student
//         saveButton = $("#set-new-temp-pass-btn-st");
//         url = `/student/reset-temp-password`;
//     }

//     saveButton.on("click", function (e) {
//         e.preventDefault();

//         var form = $(this).closest("form")[0];

//         const newPassword = $("#new-password").val();
//         const confirmPassword = $("#confirm-password").val();

//         if (newPassword !== confirmPassword) {
//             Swal.fire({
//                 icon: "error",
//                 title: "Passwords do not match",
//                 text: "Please make sure the passwords are the same.",
//             });
//             return;
//         }

//         if (newPassword.length < 8) {
//             Swal.fire({
//                 icon: "error",
//                 title: "Password too short",
//                 text: "Password must be at least 8 characters long.",
//             });
//             return;
//         }

//         $.ajax({
//             url: url,
//             type: "POST",
//             data: {
//                 _token: $("input[name=_token]").val(),
//                 email: email, 
//                 newPassword: newPassword,
//                 confirmPassword: confirmPassword,
//                 role: role
//             },
//             success: function (response) {
//                 if (response.success) {
//                     Swal.fire({
//                         icon: "success",
//                         title: "Success!",
//                         text: response.message,
//                         confirmButtonText: "OK",
//                     }).then((result) => {
//                         if (result.isConfirmed) {
//                             form.reset();
//                             setTimeout(() => {
//                                 window.location.href = response.redirect;
//                             }, 1000);
//                         }
//                     });
//                 } else {
//                     Swal.fire({
//                         icon: "error",
//                         title: "Error",
//                         text:
//                             response.message ||
//                             "There was an error updating the password.",
//                     });
//                     form.reset();
//                 }
//             },
//             error: function (response) {
//                 Swal.fire({
//                     icon: "error",
//                     title: "Error",
//                     text: "An error occurred while updating your password. Please try again.",
//                 });
//                 form.reset();
//             },
//         });
//     });
// });

$(document).ready(function () {
    // Toggle password visibility
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    // Password validation function to check requirements
    function validatePassword(password) {
        const lengthValid = password.length >= 8;
        const uppercaseValid = /[A-Z]/.test(password);
        const specialCharValid = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const numberValid = /\d/.test(password);

        // Update color based on validity of each requirement
        if (lengthValid) {
            $("#length-requirement").css('color', 'green');
        } else {
            $("#length-requirement").css('color', 'red');
        }

        if (uppercaseValid) {
            $("#uppercase-requirement").css('color', 'green');
        } else {
            $("#uppercase-requirement").css('color', 'red');
        }

        if (specialCharValid) {
            $("#special-char-requirement").css('color', 'green');
        } else {
            $("#special-char-requirement").css('color', 'red');
        }

        if (numberValid) {
            $("#number-requirement").css('color', 'green');
        } else {
            $("#number-requirement").css('color', 'red');
        }

        return lengthValid && uppercaseValid && specialCharValid && numberValid;
    }

    // Show password requirements when focused
    const passwordInput = $("#new-password");

    // Check password as the user types
    passwordInput.on("input", function () {
        const password = $(this).val();
        validatePassword(password);
    });

    const email = $("#email").val();
    const role = $("#role").val();

    let saveButton;
    let url;

    if (role == 1) {
        // Faculty
        saveButton = $("#set-new-temp-pass-btn");
        url = `/faculty/reset-temp-password`;
    } else if (role == 2) {
        // Admin
        saveButton = $("#set-new-temp-pass-btn-ad");
        url = `/admin/reset-temp-password`;
    } else {
        // Student
        saveButton = $("#set-new-temp-pass-btn-st");
        url = `/student/reset-temp-password`;
    }

    saveButton.on("click", function (e) {
        e.preventDefault();

        var form = $(this).closest("form")[0];

        const newPassword = $("#new-password").val();
        const confirmPassword = $("#confirm-password").val();

        if (newPassword !== confirmPassword) {
            Swal.fire({
                icon: "error",
                title: "Passwords do not match",
                text: "Please make sure the passwords are the same.",
            });
            return;
        }

        if (!validatePassword(newPassword)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Password",
                text: "Password must be at least 8 characters long, contain one uppercase letter, one number, and one special character.",
            });
            return;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: $("input[name=_token]").val(),
                email: email, 
                newPassword: newPassword,
                confirmPassword: confirmPassword,
                role: role
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: response.message,
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.reset();
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 1000);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message || "There was an error updating the password.",
                    });
                    form.reset();
                }
            },
            error: function (response) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An error occurred while updating your password. Please try again.",
                });
                form.reset();
            },
        });
    });
});

