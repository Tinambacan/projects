$(document).ready(function () {
    // const table = document.querySelector("#myTable");
    // let dataTable;

    // if (table) {
    //     dataTable = $(table).DataTable({
    //         responsive: true,
    //         pagingType: "simple",
    //         paging: true,
    //         order: [],
    //         columnDefs: [
    //             {
    //                 targets: 0,
    //                 orderable: false,
    //             },
    //         ],
    //     });
    // }

    const adminAcc = document.querySelector("#myTable");

    if (adminAcc) {
        const dataTable = $(adminAcc).DataTable({
            processing: true,
            serverSide: true,
            // ajax: {
            //     url: "/get-admin-acc",
            // },
            // headers: {
            //     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            // },
            // type: "GET",
            // dataType: "json",
            ajax: {
                url: "/get-admin-acc",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            },
            columns: [
                // {
                //     data: "id",
                //     render: function (data, type, row) {
                //         return `<input type="checkbox" class="admin_checkbox text-center" data-admin-id="${
                //             row.id
                //         }" ${row.isSentCredentials ? "disabled" : ""}>`;
                //     },
                // },
                { data: "Fname" },
                { data: "Lname" },
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
                                    <i class="fa-solid fa-eye text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer" data-salutation="${row.salutation}" data-schoolID="${row.schoolIDNo}" data-fname="${row.Fname}" data-id="${row.id}" data-lname="${row.Lname}" data-mname="${row.Mname}" data-sname="${row.Sname}" data-branch="${row.branch}" 
                        data-branch-description="${row.branchDescription}" data-email="${row.email}"></i>
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
                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-button hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer" data-adminID="${row.id}" data-salutation="${row.salutation}" data-schoolID="${row.schoolIDNo}" data-fname="${row.Fname}" data-lname="${row.Lname}" data-mname="${row.Mname}" data-sname="${row.Sname}" data-email="${row.email}"></i>
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
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                },
            ],
        });

        $('input[type="checkbox"].admin_checkbox').prop("checked", false);
        $("#admin_select_all")
            .prop("checked", false)
            .prop("indeterminate", false);

        function getSelectedAdminIDs() {
            const selectedAdminIDs = [];
            dataTable.rows().every(function () {
                const $row = $(this.node());
                const $checkbox = $row.find(
                    'input[type="checkbox"].admin_checkbox'
                );

                if ($checkbox.prop("checked") && !$checkbox.prop("disabled")) {
                    const numberAdminID = $row
                        .find('input[type="checkbox"]')
                        .data("admin-id");
                    if (numberAdminID) {
                        selectedAdminIDs.push(numberAdminID);
                    }
                }
            });

            const uniqueAdminIDs = [...new Set(selectedAdminIDs)];

            return uniqueAdminIDs;
        }

        function toggleSendCredentialsButton() {
            const selectedAdminIDs = getSelectedAdminIDs();
            // console.log("Selected Admin IDs:", selectedAdminIDs);
            // console.log("Number of selected admin:", selectedAdminIDs.length);
            $("#selectedAdminIDs").text(selectedAdminIDs.join(", "));
        }

        $("#admin_select_all").on("click", function () {
            const isChecked = this.checked;

            if (isChecked) {
                dataTable.page.len(-1).draw();
            } else {
                dataTable.page.len(10).draw();
            }

            dataTable.rows().every(function () {
                const $row = $(this.node());
                $row.find(
                    'input[type="checkbox"].admin_checkbox:not(:disabled)'
                ).prop("checked", isChecked);
            });
            toggleSendCredentialsButton();
        });

        $("#myTable").on(
            "change",
            'input[type="checkbox"].admin_checkbox',
            function () {
                toggleSendCredentialsButton();

                const totalRows = dataTable.rows().count();
                const selectedRows = getSelectedAdminIDs().length;

                const selectAllCheckbox = $("#admin_select_all").get(0);
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

        $(document).on("submit", "#add-admin-list-form", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Show the loader
            $("#loader-modal-import").removeClass("hidden");
            $("body").addClass("no-scroll");

            var formData = new FormData(this); // Collect form data including file

            $.ajax({
                url: "/import-admin", // Update this route to your import handling route
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
                    $("#loader-modal-import").fadeOut();
                    $("body").removeClass("no-scroll");
                },
            });
        });

        $("#edit-admin-form").on("submit", function (e) {
            e.preventDefault();

            const formData = $(this).serialize(); // Serialize the form data


            $.ajax({
                url: "admin-update", // Update route
                method: "POST",
                data: formData,
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Close the modal and reload the page after confirmation
                            $("#edit-admin-modal").fadeOut();
                            // window.location.reload(); // Reload or update the data via Ajax
                            dataTable.ajax.reload();
                        }
                    });
                },

                error: function (xhr) {
                    alert("Error: " + xhr.responseText);
                },
            });
        });

        $("#add-admin-form").on("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $("#add-cre-send-email-loader").removeClass("hidden");
            $("body").addClass("no-scroll");

            // const $submitButton = $("#add-admin-btn-form");

            // // Disable the button and show loader
            // disableWithLoader($submitButton[0]);


            $.ajax({
                url: "/save-admin-info",
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
                                $("#add-admin-form")[0].reset();
                                $("#add-admin-modal").fadeOut();
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
                        // Validation error
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

                    $("#add-admin-form")[0].reset();
                    $("#add-cre-send-email-loader").fadeOut();
                    $("body").removeClass("no-scroll");
                },
                complete: function () {
                    $("#add-cre-send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                    // $submitButton.prop("disabled", false).html(`<span>Add Admin</span>`);
                },
            });
        });

        $(".send-batch-admin-credentials").click(function () {
            const selectedAdminIDs = getSelectedAdminIDs();

            $("#selectedAdminIDs").val(selectedAdminIDs);

            if (selectedAdminIDs.length === 0) {
                Swal.fire({
                    title: "Error!",
                    text: "Please select at least one admin.",
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
                        url: "/send-admin-credentials-batch",
                        method: "POST",
                        data: {
                            selectedAdminIDs: selectedAdminIDs,
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
                        url: "/send-admin-credentials",
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
    }

    // dataTable.on("draw", function () {
    //     $('input[type="checkbox"].admin_checkbox').prop("checked", false);
    //     $("#admin_select_all")
    //         .prop("checked", false)
    //         .prop("indeterminate", false);
    //     toggleSendCredentialsButton();
    // });

    $("#add-admin-btn").on("click", function () {
        $("#add-admin-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-add-admin").on("click", function () {
        $("#add-admin-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#add-admin-list-btn").on("click", function () {
        $("#add-admin-list-modal").show();
        $("body").addClass("no-scroll");
        // $("#loader-modal-import").show();
    });

    $("#close-btn-add-admin-list").on("click", function () {
        $("#add-admin-list-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    function populateBranchSelect() {
        $.ajax({
            url: "/get-branches",
            method: "GET",
            success: function (data) {
                const branchSelect = $("#branch");
                branchSelect.empty();
                branchSelect.append(
                    "<option value=''>Select PUP Branch</option>"
                );

                data.branches.forEach(function (branch) {
                    branchSelect.append(
                        `<option value="${branch.branchID}">${branch.branchDescription}</option>`
                    );
                });
            },
            error: function (err) {
                console.error("Error fetching branches:", err);
            },
        });
    }

    populateBranchSelect();

    // $(".send-credentials").click(function () {
});
$(document).on("click", ".fa-eye", function () {
    const salutation = this.getAttribute("data-salutation");
    const fname = this.getAttribute("data-fname");
    const lname = this.getAttribute("data-lname");
    const mname = this.getAttribute("data-mname");
    const sname = this.getAttribute("data-sname");
    const email = this.getAttribute("data-email");
    const schoolID = this.getAttribute("data-schoolID");
    // const adminID = this.getAttribute("data-id");

    const branch = this.getAttribute("data-branch");
    // const branchDescript = this.getAttribute("data-branch-description");

    // console.log(salutation + fname + lname);

    document.getElementById("view-salutation").textContent = salutation;
    document.getElementById("view-Fname").textContent = fname;
    document.getElementById("view-Lname").textContent = lname;
    document.getElementById("view-Mname").textContent ? mname : "";
    document.getElementById("view-Sname").textContent ? sname : "";
    document.getElementById("view-email").textContent = email;
    document.getElementById("view-schoolIDNo").textContent ? schoolID : "";
    document.getElementById("view-branch").textContent = branch;
    // document.getElementById("view-id").textContent = adminID;

    // const modal = document.getElementById("view-admin-modal");
    // modal.classList.show();

    $("#view-admin-modal").show();
    $("body").addClass("no-scroll");
});

// document
//     .getElementById("close-btn-view-admin")
//     .addEventListener("click", function () {
//         document.getElementById("view-admin-modal").classList.fadeOut();
//     });

$("#close-btn-view-admin").on("click", function () {
    $("#view-admin-modal").fadeOut();
    $("body").removeClass("no-scroll");
});

// document
//     .getElementById("close-btn-edit-admin")
//     .addEventListener("click", function () {
//         document.getElementById("edit-admin-modal").classList.fadeOut();
//     });

$("#close-btn-edit-admin").on("click", function () {
    $("#edit-admin-modal").fadeOut();
    $("body").removeClass("no-scroll");
});

$(document).on("click", ".edit-button", function () {
    const adminData = $(this).data();

    $("#edit-admin-modal #edit-adminID").val(adminData.adminid);
    $("#edit-admin-modal #edit-Fname").val(adminData.fname);
    $("#edit-admin-modal #edit-Lname").val(adminData.lname);
    $("#edit-admin-modal #edit-Mname").val(adminData.mname);
    $("#edit-admin-modal #edit-Sname").val(adminData.sname);
    $("#edit-admin-modal #edit-schoolIDNo").val(adminData.schoolid);
    $("#edit-admin-modal #edit-email").val(adminData.email);
    $("#edit-admin-modal #edit-salutation").val(adminData.salutation);

    // $("#edit-admin-modal").show();

    $("#edit-admin-modal").show();
    $("body").addClass("no-scroll");
});
