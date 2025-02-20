$(document).ready(function () {
    const facultyAcc = document.querySelector("#myTable");

    if (facultyAcc) {
        const dataTable = $(facultyAcc).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-faculty-acc",
            },
            type: "GET",
            dataType: "json",
            columns: [
                // {
                //     data: "id",
                //     render: function (data, type, row) {
                //         return `<input type="checkbox" class="prof_checkbox text-center" data-prof-id="${
                //             row.id
                //         }" ${row.isSentCredentials ? "disabled" : ""}>`;
                //     },
                // },
                { data: "schoolIDNo" },
                { data: "Lname" },
                { data: "Fname" },
                { data: "email" },
                // {
                //     data: "status",
                //     render: function (data) {
                //         return data === "Active"
                //             ? '<span class="bg-green-500 text-white p-2 rounded-md">Active</span>'
                //             : '<span class="bg-red-500 text-white p-2 rounded-md">Inactive</span>';
                //     },
                // },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div class="flex justify-center items-center gap-1 text-xl">
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-eye text-blue-500 hover:bg-gray-200 dark:hover:bg-[#161616] p-[5px] hover:rounded-md cursor-pointer" data-salutation="${row.salutation}" data-schoolID="${row.schoolIDNo}" data-fname="${row.Fname}" data-id="${row.id}" data-lname="${row.Lname}" data-mname="${row.Mname}" data-sname="${row.Sname}"  
                         data-email="${row.email}"></i>
                            <div
                                class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">View Info</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                        </div>
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-button hover:bg-gray-200 dark:hover:bg-[#161616] p-[5px] hover:rounded-md cursor-pointer" data-id="${row.id}" data-salutation="${row.salutation}" data-schoolID="${row.schoolIDNo}" data-fname="${row.Fname}" data-lname="${row.Lname}" data-mname="${row.Mname}" data-sname="${row.Sname}" data-email="${row.email}"></i>
                                    <div
                                class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                                </div>
                               
                            </div>`;
                    },
                },
            ],
            responsive: true,
            pagingType: "simple",
            paging: true,
            order: [],
            lengthMenu: [10, 25, 50],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                },
            ],
        });

        $("#add-prof-form").on("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            $("#add-cre-send-email-loader").removeClass("hidden");
            $("body").addClass("no-scroll");

            $.ajax({
                url: "/save-prof-info",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // window.location.reload();
                                dataTable.ajax.reload();
                                $("#add-prof-form")[0].reset();
                                $("#add-prof-modal").fadeOut();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = "An error occurred.";

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) {
                            errorMessage = errors.email[0];
                        }
                    } else if (xhr.status === 400) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                    });

                    $("#add-prof-form")[0].reset();
                    $("#add-cre-send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                },
                complete: function () {
                    // Hide the loader and allow scrolling after the request completes
                    $("#add-cre-send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                },
            });
        });

        $("#edit-prof-form").on("submit", function (e) {
            e.preventDefault();

            // Collect form data
            const formData = {
                registrationID: $("#edit-registrationID").val(),
                schoolIDNo: $("#edit-schoolIDNo").val(),
                Fname: $("#edit-Fname").val(),
                Lname: $("#edit-Lname").val(),
                Mname: $("#edit-Mname").val(),
                Sname: $("#edit-Sname").val(),
                email: $("#edit-email").val(),
                salutation: $("#edit-salutation").val(),
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            $.ajax({
                url: "/professor-update",
                method: "PUT",
                data: formData,
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#edit-prof-modal").fadeOut();
                            dataTable.ajax.reload(); 
                        }
                    });
                },
                error: function (xhr) {
                    let errorMessages = "An unexpected error occurred.";
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        errorMessages = Object.values(errors)
                            .map((errorArray) => errorArray.join(" "))
                            .join("\n");
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessages = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: "Error!",
                        text: errorMessages,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                },
            });
        });

        $(document).on("submit", "#add-prof-list-form", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Show the loader
            $("#loader-modal-import").removeClass("hidden");
            $("body").addClass("no-scroll");

            var formData = new FormData(this); // Collect form data including file

            $.ajax({
                url: "/import-professor", // Update this route to your import handling route
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // window.location.reload(); // Reload the page to reflect changes
                            dataTable.ajax.reload();
                            $("#add-prof-list-form")[0].reset();
                            $("#add-prof-list-modal").fadeOut();
                        }
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred: " + error,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                },
                complete: function () {
                    // Hide the loader and allow scrolling after the request completes
                    $("#loader-modal-import").addClass("hidden");
                    $("body").removeClass("no-scroll");
                },
            });
        });

        $(document).on("click", ".send-credentials", function () {
            var fname = $(this).data("fname");
            var salutation = $(this).data("salutation");
            var lname = $(this).data("lname");
            var email = $(this).data("email");

            Swal.fire({
                title: "Send Account Credentials?",
                text: `Send account credentials to ${salutation} ${fname} ${lname} (${email})`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, send it!",
                cancelButtonText: "No, cancel",
            }).then((result) => {
                $("#send-email-loader").removeClass("hidden");
                $("body").addClass("no-scroll");
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/send-faculty-credentials",
                        method: "POST",
                        data: {
                            fname: fname,
                            lname: lname,
                            email: email,
                            salutation: salutation,
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ), // Ensure CSRF token
                        },
                        success: function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: response.message,
                                        icon: "success",
                                        confirmButtonText: "OK",
                                    }).then(() => {
                                        // window.location.reload();
                                        dataTable.ajax.reload();
                                    });
                                }, 500);
                            } else {
                                Swal.fire(
                                    "Error",
                                    "There was an issue sending the credentials.",
                                    "error"
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                "Error",
                                "There was an issue with the request.",
                                "error"
                            );
                        },
                        complete: function () {
                            // Hide the loader and allow scrolling after the request completes
                            $("#send-email-loader").addClass("hidden");
                            $("body").removeClass("no-scroll");
                        },
                    });
                } else {
                    $("#send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                }
            });
        });

        $(".send-batch-prof-credentials").click(function () {
            const selectedProfIDs = getSelectedProfIDs();

            $("#selectedProfIDs").val(selectedProfIDs);

            if (selectedProfIDs.length === 0) {
                Swal.fire({
                    title: "Error!",
                    text: "Please select at least one professor.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return;
            }

            Swal.fire({
                title: "Send Account Credentials?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, send it!",
                cancelButtonText: "No, cancel",
            }).then((result) => {
                $("#send-email-loader").removeClass("hidden");
                $("body").addClass("no-scroll");

                if (result.isConfirmed) {
                    $.ajax({
                        url: "/send-faculty-credentials-batch",
                        method: "POST",
                        data: {
                            selectedProfIDs: selectedProfIDs,
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (response) {
                            setTimeout(function () {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(() => {
                                    // window.location.reload();
                                    dataTable.ajax.reload();
                                });
                            }, 500);
                        },
                        error: function (xhr) {
                            console.error("Error sending notification:", xhr);
                            setTimeout(function () {
                                Swal.fire({
                                    title: "Error!",
                                    text: xhr.responseJSON
                                        ? xhr.responseJSON.message
                                        : "An error occurred while sending the notification.",
                                    icon: "error",
                                    confirmButtonText: "OK",
                                });
                            }, 500);
                        },
                        complete: function () {
                            $("#send-email-loader").addClass("hidden");
                            $("body").removeClass("no-scroll");
                        },
                    });
                } else {
                    $("#send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                }
            });
        });

        function getSelectedProfIDs() {
            const selectedProfIDs = [];
            dataTable.rows().every(function () {
                const $row = $(this.node());
                const $checkbox = $row.find(
                    'input[type="checkbox"].prof_checkbox'
                );

                if ($checkbox.prop("checked") && !$checkbox.prop("disabled")) {
                    const numberProfID = $row
                        .find('input[type="checkbox"]')
                        .data("prof-id");
                    if (numberProfID) {
                        selectedProfIDs.push(numberProfID);
                    }
                }
            });

            const uniqueProfIDs = [...new Set(selectedProfIDs)];

            return uniqueProfIDs;
        }

        function toggleSendCredentialsButton() {
            const selectedProfIDs = getSelectedProfIDs();
            // console.log("Selected Prof IDs:", selectedProfIDs);
            // console.log("Number of selected prof:", selectedProfIDs.length);
            $("#selectedProfIDs").text(selectedProfIDs.join(", "));
        }

        $("#prof_select_all").on("click", function () {
            const isChecked = this.checked;

            if (isChecked) {
                dataTable.page.len(-1).draw();
            } else {
                dataTable.page.len(10).draw();
            }

            dataTable.rows().every(function () {
                const $row = $(this.node());
                $row.find(
                    'input[type="checkbox"].prof_checkbox:not(:disabled)'
                ).prop("checked", isChecked);
            });
            toggleSendCredentialsButton();
        });

        $("#myTable").on(
            "change",
            'input[type="checkbox"].prof_checkbox',
            function () {
                toggleSendCredentialsButton();

                const totalRows = dataTable.rows().count();
                const selectedRows = getSelectedProfIDs().length;

                const selectAllCheckbox = $("#prof_select_all").get(0);
                if (selectedRows === totalRows) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (selectedRows === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.indeterminate = true;
                }
            }
        );
    }

    $("#add-prof-btn").on("click", function () {
        $("#add-prof-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-add-prof").on("click", function () {
        $("#add-prof-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#add-prof-list-btn").on("click", function () {
        $("#add-prof-list-modal").show();
        $("body").addClass("no-scroll");
        // $("#loader-modal-import").show();
    });

    $("#close-btn-add-prof-list").on("click", function () {
        $("#add-prof-list-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });
});

$(document).on("click", ".fa-eye", function () {
    const salutation = this.getAttribute("data-salutation");
    const fname = this.getAttribute("data-fname");
    const lname = this.getAttribute("data-lname");
    const mname = this.getAttribute("data-mname");
    const sname = this.getAttribute("data-sname");
    const email = this.getAttribute("data-email");
    const schoolID = this.getAttribute("data-schoolID");
    // console.log(salutation + fname + lname);

    document.getElementById("view-salutation").textContent = salutation;
    document.getElementById("view-Fname").textContent = fname;
    document.getElementById("view-Lname").textContent = lname;
    document.getElementById("view-Mname").textContent ? mname : "";
    document.getElementById("view-Sname").textContent ? sname : "";
    document.getElementById("view-email").textContent = email;
    document.getElementById("view-schoolIDNo").textContent = schoolID;

    // const modal = document.getElementById("view-prof-modal");
    // modal.classList.remove("hidden");

    $("#view-prof-modal").show();
    $("body").addClass("no-scroll");
});

// Function to close the modal
// document
//     .getElementById("close-btn-view-prof")
//     .addEventListener("click", function () {
//         document.getElementById("view-prof-modal").classList.add("hidden");
//     });

$("#close-btn-view-prof").on("click", function () {
    $("#view-prof-modal").fadeOut();
    $("body").removeClass("no-scroll");
});

// document
//     .getElementById("close-btn-edit-prof")
//     .addEventListener("click", function () {
//         document.getElementById("edit-prof-modal").classList.add("hidden");
//     });

$("#close-btn-edit-prof").on("click", function () {
    $("#edit-prof-modal").fadeOut();
    $("body").removeClass("no-scroll");
});

$(document).on("click", ".edit-button", function () {
    const professorData = $(this).data();


    $("#edit-prof-modal #edit-registrationID").val(professorData.id); // Lowercase 'registrationid'
    $("#edit-prof-modal #edit-Fname").val(professorData.fname);
    $("#edit-prof-modal #edit-Lname").val(professorData.lname);
    $("#edit-prof-modal #edit-Mname").val(professorData.mname);
    $("#edit-prof-modal #edit-Sname").val(professorData.sname);
    $("#edit-prof-modal #edit-schoolIDNo").val(professorData.schoolid);
    $("#edit-prof-modal #edit-email").val(professorData.email);
    $("#edit-prof-modal #edit-salutation").val(professorData.salutation);

    // Show the modal
    // $("#edit-prof-modal").removeClass("hidden");
    $("#edit-prof-modal").show();
    $("body").addClass("no-scroll");
});
