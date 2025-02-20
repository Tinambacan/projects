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

    function validatePassword(password) {
        const lengthValid = password.length >= 8;
        const uppercaseValid = /[A-Z]/.test(password);
        const specialCharValid = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const numberValid = /\d/.test(password);

        // Update color based on the validity of each requirement
        $("#length-requirement").css("color", lengthValid ? "green" : "red");
        $("#uppercase-requirement").css(
            "color",
            uppercaseValid ? "green" : "red"
        );
        $("#special-char-requirement").css(
            "color",
            specialCharValid ? "green" : "red"
        );
        $("#number-requirement").css("color", numberValid ? "green" : "red");

        return lengthValid && uppercaseValid && specialCharValid && numberValid;
    }

    $(document).ready(function () {
        const passwordInputFa = $("#new-password");
        const passwordInputAd = $("#ad-new-password");
        const passwordInputSa = $("#sa-new-password");
        const passwordInputSt = $("#st-new-password");

        passwordInputFa.on("input", function () {
            const password = $(this).val();
            validatePassword(password);
        });

        passwordInputAd.on("input", function () {
            const password = $(this).val();
            validatePassword(password);
        });

        passwordInputSa.on("input", function () {
            const password = $(this).val();
            validatePassword(password);
        });

        passwordInputSt.on("input", function () {
            const password = $(this).val();
            validatePassword(password);
        });
    });

    $("#fa-update-btn").on("click", function (e) {
        e.preventDefault();

        const currentPassword = $("#current-password").val();
        const newPassword = $("#new-password").val();
        const confirmPassword = $("#confirm-password").val();
        const loginID = $("#loginID").val();

        if (newPassword !== confirmPassword) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "New Password and Confirm Password do not match.",
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

        var form = $(this).closest("form")[0];

        $.ajax({
            url: "update-password",
            method: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                loginID: loginID,
                currentPassword: currentPassword,
                newPassword: newPassword,
                confirmPassword: confirmPassword,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Password Updated",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        Swal.fire({
                            icon: "question",
                            title: "Do you want to keep logged in?",
                            showCancelButton: true,
                            confirmButtonText: "Yes, stay logged in",
                            cancelButtonText: "No, log me out",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.reset();
                            } else {
                                window.location.href = "/logout";
                            }
                        });
                    });
                } else {
                    form.reset();
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                form.reset();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";
                    for (let field in errors) {
                        errorMessages += errors[field].join(", ") + "\n";
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: errorMessages,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong. Please try again.",
                    });
                }
            },
        });
    });

    $("#st-update-btn").on("click", function (e) {
        e.preventDefault();

        const stCurrentPassword = $("#st-current-password").val();
        const stNewPassword = $("#st-new-password").val();
        const stConfirmPassword = $("#st-confirm-password").val();
        const stLoginID = $("#st-loginID").val();

        if (stNewPassword !== stConfirmPassword) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "New Password and Confirm Password do not match.",
            });
            return;
        }
        // console.log(stLoginID);

        if (!validatePassword(stNewPassword)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Password",
                text: "Password must be at least 8 characters long, contain one uppercase letter, one number, and one special character.",
            });
            return;
        }

        var form = $(this).closest("form")[0];

        $.ajax({
            url: "update-password",
            method: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                loginID: stLoginID,
                currentPassword: stCurrentPassword,
                newPassword: stNewPassword,
                confirmPassword: stConfirmPassword,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Password Updated",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        Swal.fire({
                            icon: "question",
                            title: "Do you want to keep logged in?",
                            showCancelButton: true,
                            confirmButtonText: "Yes, stay logged in",
                            cancelButtonText: "No, log me out",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.reset();
                            } else {
                                window.location.href = "/logout";
                            }
                        });
                    });
                } else {
                    form.reset();
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                form.reset();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";
                    for (let field in errors) {
                        errorMessages += errors[field].join(", ") + "\n";
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: errorMessages,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong. Please try again.",
                    });
                }
            },
        });
    });

    $("#ad-update-btn").on("click", function (e) {
        e.preventDefault();

        const adCurrentPassword = $("#ad-current-password").val();
        const adNewPassword = $("#ad-new-password").val();
        const adConfirmPassword = $("#ad-confirm-password").val();
        const adLoginID = $("#ad-loginID").val();

        if (adNewPassword !== adConfirmPassword) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "New Password and Confirm Password do not match.",
            });
            return;
        }
        // console.log(adLoginID);

        if (!validatePassword(adNewPassword)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Password",
                text: "Password must be at least 8 characters long, contain one uppercase letter, one number, and one special character.",
            });
            return;
        }

        var form = $(this).closest("form")[0];

        $.ajax({
            url: "update-password",
            method: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                loginID: adLoginID,
                currentPassword: adCurrentPassword,
                newPassword: adNewPassword,
                confirmPassword: adConfirmPassword,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Password Updated",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        Swal.fire({
                            icon: "question",
                            title: "Do you want to keep logged in?",
                            showCancelButton: true,
                            confirmButtonText: "Yes, stay logged in",
                            cancelButtonText: "No, log me out",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.reset();
                            } else {
                                window.location.href = "/logout";
                            }
                        });
                    });
                } else {
                    form.reset();
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                form.reset();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";
                    for (let field in errors) {
                        errorMessages += errors[field].join(", ") + "\n";
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: errorMessages,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong. Please try again.",
                    });
                }
            },
        });
    });

    $("#sa-update-btn").on("click", function (e) {
        e.preventDefault();

        const saCurrentPassword = $("#sa-current-password").val();
        const saNewPassword = $("#sa-new-password").val();
        const saConfirmPassword = $("#sa-confirm-password").val();
        const saLoginID = $("#sa-loginID").val();

        if (saNewPassword !== saConfirmPassword) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "New Password and Confirm Password do not match.",
            });
            return;
        }
        // console.log(saLoginID);

        if (!validatePassword(saNewPassword)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Password",
                text: "Password must be at least 8 characters long, contain one uppercase letter, one number, and one special character.",
            });
            return;
        }

        var form = $(this).closest("form")[0];

        $.ajax({
            url: "update-password",
            method: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                loginID: saLoginID,
                currentPassword: saCurrentPassword,
                newPassword: saNewPassword,
                confirmPassword: saConfirmPassword,
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Password Updated",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        Swal.fire({
                            icon: "question",
                            title: "Do you want to keep logged in?",
                            showCancelButton: true,
                            confirmButtonText: "Yes, stay logged in",
                            cancelButtonText: "No, log me out",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.reset();
                            } else {
                                window.location.href = "/logout";
                            }
                        });
                    });
                } else {
                    form.reset();
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    });
                }
            },
            error: function (xhr) {
                form.reset();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";
                    for (let field in errors) {
                        errorMessages += errors[field].join(", ") + "\n";
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: errorMessages,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong. Please try again.",
                    });
                }
            },
        });
    });
});

$(document).ready(function () {
    $("#save-btn").on("click", function (e) {
        e.preventDefault();

        var registrationID = $("#registrationID").val();
        var Fname = $("#edit-facultyFname").val();
        var Mname = $("#edit-facultyMname").val();
        var Lname = $("#edit-facultyLname").val();
        var Sname = $("#edit-facultySname").val();
        var salutation = $("#edit-salutation").val();
        var adminID = $("#adminID").val(); // Get adminID value
        var loginID = $("#ad-loginID").val();
        var email = $("#edit-adminEmail").val();

        $.ajax({
            url: "update-personalInfo",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                registrationID: registrationID,
                Fname: Fname,
                Mname: Mname,
                Lname: Lname,
                Sname: Sname,
                adminID: adminID,
                loginID: loginID,
                salutation: salutation,
                email: email,
                salutation: salutation,
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Something went wrong, please try again.",
                });
            },
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const startYear = 2023;
    const endYear = 2050;

    // Fetch current school year via AJAX
    $.ajax({
        url: "/get-school-year", // The new route for fetching the school year
        method: "GET",
        success: function (response) {
            const currentSchoolYear = response.schoolYear;
            $("#edit-semester").val(response.semester);
            // Populate the academic years dropdown
            populateAcademicYear("edit-schoolYear", currentSchoolYear);
        },
        error: function () {
            // console.log("Failed to fetch school year");
        },
    });

    function populateAcademicYear(selectID, currentSchoolYear) {
        const selectElement = document.getElementById(selectID);
        if (selectElement) {
            for (let year = startYear; year <= endYear; year++) {
                let option = document.createElement("option");
                option.value = `${year}-${year + 1}`;
                option.textContent = `${year}-${year + 1}`;

                // Set the current value as selected
                if (option.value === currentSchoolYear) {
                    option.selected = true;
                }

                selectElement.appendChild(option);
            }
        }
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const saveButton = document.getElementById("save-schoolSem-btn");

    saveButton.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default form submission

        const adminID = document.getElementById("adminID").value;
        const schoolYear = document.getElementById("edit-schoolYear").value;
        const semester = document.getElementById("edit-semester").value;

        // Validate inputs
        if (!schoolYear || !semester) {
            alert("Please select both School Year and Semester");
            return;
        }

        // Make AJAX request to update school year and semester
        $.ajax({
            url: "/update-school-year-semester", // The route for updating
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                adminID: adminID,
                schoolYear: schoolYear,
                semester: semester,
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr, status, error) {
                // console.log(error);
                alert(
                    "An error occurred while updating the school year and semester."
                );
            },
        });
    });
});
