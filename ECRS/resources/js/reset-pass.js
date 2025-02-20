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

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();

        var email = $("#email").val();
        var form = $(this).closest("form")[0];

        $("#send-email-loader-fa").removeClass("hidden");
        $("#loader-title").text("Checking");

        $.ajax({
            url: "send-pass-link",
            method: "POST",
            data: {
                email: email,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#loader-title").text("Sending to email");

                    setTimeout(function () {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                        }).then(() => {
                            form.reset();
                            $("#send-email-loader-fa").addClass("hidden");
                            window.location.href = response.redirect;
                        });
                    }, 2000);
                } else {
                    $("#send-email-loader-fa").addClass("hidden");

                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: response.message,
                        confirmButtonText: "OK",
                    }).then(() => {
                        form.reset();
                        $("#send-email-loader-fa").addClass("hidden");
                    });
                }
            },
            error: function (response) {
                $("#send-email-loader-fa").addClass("hidden");

                if (response.responseJSON && response.responseJSON.errors) {
                    let errors = response.responseJSON.errors;
                    if (errors.email) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: errors.email[0],
                            confirmButtonText: "Try Again",
                            confirmButtonColor: "#d33",
                        });
                        form.reset();
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong",
                        text: "Please try again later.",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#d33",
                    });
                    form.reset();
                }
            },
        });
    });

    $("#submit-btn-stud").on("click", function (e) {
        e.preventDefault();

        var email = $("#email").val();
        var form = $(this).closest("form")[0];

        if (!form) {
            console.error(
                "Form not found. Please ensure the button is inside a form."
            );
            return;
        }

        $("#send-email-loader-st").removeClass("hidden");
        $("#loader-title").text("Checking");

        $.ajax({
            url: "send-pass-link",
            method: "POST",
            data: {
                email: email,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#loader-title").text("Sending to email");

                    setTimeout(function () {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                        }).then(() => {
                            form.reset();
                            $("#send-email-loader-st").addClass("hidden");
                            window.location.href = response.redirect;
                        });
                    }, 2000);
                } else {
                    $("#send-email-loader-st").addClass("hidden");

                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: response.message,
                        confirmButtonText: "OK",
                    }).then(() => {
                        form.reset();
                        $("#send-email-loader-st").addClass("hidden");
                    });
                }
            },
            error: function (response) {
                $("#send-email-loader-st").addClass("hidden");

                if (response.responseJSON && response.responseJSON.errors) {
                    let errors = response.responseJSON.errors;
                    if (errors.email) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: errors.email[0],
                            confirmButtonText: "Try Again",
                            confirmButtonColor: "#d33",
                        });
                        form.reset();
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong",
                        text: "Please try again later.",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#d33",
                    });
                    form.reset();
                }
            },
        });
    });

    $("#submit-btn-ad").on("click", function (e) {
        e.preventDefault();

        var email = $("#email").val();

        var form = $(this).closest("form")[0];

        if (!form) {
            console.error(
                "Form not found. Please ensure the button is inside a form."
            );
            return;
        }

        $("#send-email-loader-ad").removeClass("hidden");
        $("#loader-title").text("Checking");

        $.ajax({
            url: "send-pass-link",
            method: "POST",
            data: {
                email: email,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#loader-title").text("Sending to email");

                    setTimeout(function () {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                        }).then(() => {
                            form.reset();
                            $("#send-email-loader-ad").addClass("hidden");
                            window.location.href = response.redirect;
                        });
                    }, 2000);
                } else {
                    $("#send-email-loader-ad").addClass("hidden");

                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: response.message,
                        confirmButtonText: "OK",
                    }).then(() => {
                        form.reset();
                        $("#send-email-loader-ad").addClass("hidden");
                    });
                }
            },
            error: function (response) {
                $("#send-email-loader-ad").addClass("hidden");

                if (response.responseJSON && response.responseJSON.errors) {
                    let errors = response.responseJSON.errors;
                    if (errors.email) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: errors.email[0],
                            confirmButtonText: "Try Again",
                            confirmButtonColor: "#d33",
                        });
                        form.reset();
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong",
                        text: "Please try again later.",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#d33",
                    });
                    form.reset();
                }
            },
        });
    });
});

// $(document).ready(function () {
//     const token = $("#token").val();
//     const role = $("#role").val();

//     let saveButton;
//     let url;

//     if (role == 1) {
//         // Faculty
//         saveButton = $("#set-new-pass-btn");
//         url = `/faculty/reset-password/${token}`;
//     } else if (role == 2) {
//         // Admin
//         saveButton = $("#set-new-pass-btn-ad");
//         url = `/admin/reset-password/${token}`;
//     } else {
//         // Student
//         saveButton = $("#set-new-pass-btn-st");
//         url = `/student/reset-password/${token}`;
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
//                 newPassword: newPassword,
//                 confirmPassword: confirmPassword,
//             },
//             success: function (response) {
//                 if (response.success) {
//                     Swal.fire({
//                         icon: "success",
//                         title: "Password updated!",
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
    const token = $("#token").val();
    const role = $("#role").val();

    let saveButton;
    let url;

    if (role == 1) {
        // Faculty
        saveButton = $("#set-new-pass-btn");
        url = `/faculty/reset-password/${token}`;
    } else if (role == 2) {
        // Admin
        saveButton = $("#set-new-pass-btn-ad");
        url = `/admin/reset-password/${token}`;
    } else {
        // Student
        saveButton = $("#set-new-pass-btn-st");
        url = `/student/reset-password/${token}`;
    }

    // Password validation
    function validatePassword(password) {
        const lengthValid = password.length >= 8;
        const uppercaseValid = /[A-Z]/.test(password);
        const specialCharValid = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const numberValid = /\d/.test(password);

        // Update color based on validity of each requirement
        if (lengthValid) {
            $("#length-requirement").css("color", "green");
        } else {
            $("#length-requirement").css("color", "red");
        }

        if (uppercaseValid) {
            $("#uppercase-requirement").css("color", "green");
        } else {
            $("#uppercase-requirement").css("color", "red");
        }

        if (specialCharValid) {
            $("#special-char-requirement").css("color", "green");
        } else {
            $("#special-char-requirement").css("color", "red");
        }

        if (numberValid) {
            $("#number-requirement").css("color", "green");
        } else {
            $("#number-requirement").css("color", "red");
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
                newPassword: newPassword,
                confirmPassword: confirmPassword,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Password updated!",
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
                        text:
                            response.message ||
                            "There was an error updating the password.",
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
