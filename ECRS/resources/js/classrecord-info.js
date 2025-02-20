$(document).ready(function () {
    $("#add-stud-btn").on("click", function () {
        $("#add-student-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-add-stud").on("click", function () {
        $("#add-student-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#add-stud-list-btn").on("click", function () {
        $("#add-student-list-modal").show();
        $("body").addClass("no-scroll");
        // $("#loader-modal-import").show();
    });

    $("#close-btn-add-stud-list").on("click", function () {
        $("#add-student-list-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    const studInfoTable = document.querySelector("#studInfoTable");

    const isMobile = window.innerWidth < 768;

    const isArchived =
        document.getElementById("isArchived").textContent.trim() === "1";

    if (studInfoTable) {
        const dataTable = $(studInfoTable).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-stud-info",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), 
                },
            },
            columns: [
                // {
                //     data: null,
                //     render: function (data, type, row) {
                //         return `<input type="checkbox" class="stud_checkbox text-center" data-stud-id="${
                //             row.studentID
                //         }" ${row.isSentCredentials ? "disabled" : ""}>`;
                //     },
                // },
                { data: "studentNo" },
                { data: "studentLname" },
                { data: "studentFname" },
                { data: "studentMname" },
                { data: "Sname" },
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
                            <div class="flex justify-center items-center gap-1 text-2xl">
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-eye text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer text-xl"></i>
                                    <div
                                        class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                     <div
                                        class="flex justify-center items-center text-center transition-all duration-300 relative">
                                         <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">View Info</span>
                                        <div
                                            class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                        </div>
                                    </div>
                            </div>
                        </div>

                          ${
                              !isArchived
                                  ? `
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-button hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer text-xl" 
                                    data-student-id="${row.studentID}" data-remarks="${row.remarks}"
                                        data-mobile="${row.mobileNo}"></i>
                                    <div
                                class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                                </div>
                                
                            </div>
                               `
                                  : ""
                          }`;
                    },
                },
            ],
            scrollX: isMobile,
            pagingType: "simple",
            paging: true,
            order: [],
            // pageLength: 10,
            lengthMenu: [10, 25, 50],
            columnDefs: [
                {
                    targets: [0, 6],
                    orderable: false,
                },

                ...(isArchived ? [{ targets: [], visible: false }] : []),
            ],
        });

        $("#add-stud-form").on("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $("#add-cre-send-email-loader").removeClass("hidden");
            $("body").addClass("no-scroll");

            $.ajax({
                url: "/save-stud-info",
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
                                $("#add-stud-form")[0].reset();
                                $("#add-student-modal").fadeOut();
                                $("body").removeClass("no-scroll");
                                dataTable.ajax.reload();
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
                error: function (xhr, status, error) {
                    let errorMessage = "An error occurred: " + error;

                    // Check if custom error message is returned
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message; // Use the custom error message from the server
                    } else if (xhr.status === 422) {
                        // Handle validation errors
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors)
                            .map(function (err) {
                                return err.join(", "); // Display all error messages
                            })
                            .join("\n");
                    }

                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                    });

                    $("#add-cre-send-email-loader").removeClass("hidden");
                    $("body").addClass("no-scroll");
                },
                complete: function () {
                    $("#add-cre-send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                },
            });
        });

        $(document).on("submit", "#add-stud-list-form", function (event) {
            event.preventDefault();

            // Show the loader
            $("#loader-modal-import").removeClass("hidden");
            $("body").addClass("no-scroll");

            var formData = new FormData(this);

            $.ajax({
                url: "/import-students",
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
                    if (response.status === "success") {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("body").removeClass("no-scroll");
                                dataTable.ajax.reload();
                                $("#add-student-list-modal").fadeOut();
                            }
                        });
                    } else if (response.status === "error") {
                        let errorMessages = Array.isArray(response.messages)
                            ? response.messages
                            : [response.message];

                        Swal.fire({
                            title: "Error!",
                            text: errorMessages,
                            icon: "error",
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#add-student-list-modal").fadeOut();
                                $("body").removeClass("no-scroll");
                                dataTable.ajax.reload();
                            }
                        });
                    }
                },
                error: function (xhr, status, error) {
                    const errorMessage =
                        xhr.responseJSON?.message ||
                        "An unexpected error occurred.";
                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#add-student-list-modal").fadeOut();
                            $("body").removeClass("no-scroll");
                            dataTable.ajax.reload();
                        }
                    });
                },
                complete: function () {
                    $("#loader-modal-import").fadeOut();
                    $("body").removeClass("no-scroll");
                },
            });
        });

        $(document).ready(function () {
            $("#edit-stud-form").on("submit", function (e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: "/update-stud-info",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            title: "Success!",
                            text: "Student updated successfully!",
                            icon: "success",
                            confirmButtonText: "Ok",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#edit-student-modal").fadeOut();
                                $("body").removeClass("no-scroll");
                                dataTable.ajax.reload();
                            }
                        });
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Validation error
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = "";

                            if (errors.email) {
                                errorMessage += errors.email[0] + "\n";
                            }

                            if (errors.studentNo) {
                                errorMessage += errors.studentNo[0] + "\n";
                            }

                            Swal.fire({
                                title: "Error!",
                                text: errorMessage.trim(),
                                icon: "error",
                                confirmButtonText: "Ok",
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Error updating student!",
                                icon: "error",
                                confirmButtonText: "Ok",
                            });
                        }
                    },
                });
            });
        });

        $(".send-batch-stud-credentials").click(function () {
            const selectedStudIDs = getSelectedStudIDs();
            $("#selectedStudIDs").val(selectedStudIDs);

            if (selectedStudIDs.length === 0) {
                Swal.fire({
                    title: "Error!",
                    text: "Please select at least one student.",
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
                        url: "/send-student-credentials-batch",
                        method: "POST",
                        data: {
                            selectedStudIDs: selectedStudIDs,
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
                                    $("body").removeClass("no-scroll");
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
            var mname = $(this).data("mname");
            var lname = $(this).data("lname");
            var email = $(this).data("email");
            var studentno = $(this).data("studentno");

            Swal.fire({
                title: "Send Account Credentials?",
                text: `Send account credentials to ${fname} ${mname} ${lname} (${email})`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, send it!",
                cancelButtonText: "No, cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#send-email-loader").removeClass("hidden");
                    $("body").addClass("no-scroll");

                    $.ajax({
                        url: "/send-student-credentials",
                        method: "POST",
                        data: {
                            fname: fname,
                            lname: lname,
                            email: email,
                            mname: mname,
                            studentno: studentno,
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    Swal.fire({
                                        title: "Success!",
                                        text: response.message,
                                        icon: "success",
                                        confirmButtonText: "OK",
                                    }).then(() => {
                                        $("body").removeClass("no-scroll");
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
                } else if (result.isDismissed) {
                    $("#send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                }
            });
        });

        function getSelectedStudIDs() {
            const selectedStudIDs = [];
            dataTable.rows().every(function () {
                const $row = $(this.node());
                const $checkbox = $row.find(
                    'input[type="checkbox"].stud_checkbox'
                );

                if ($checkbox.prop("checked") && !$checkbox.prop("disabled")) {
                    const numberStudID = $checkbox.data("stud-id");
                    if (numberStudID) {
                        selectedStudIDs.push(numberStudID);
                    }
                }
            });

            const uniqueStudIDs = [...new Set(selectedStudIDs)];

            return uniqueStudIDs;
        }

        function toggleSendCredentialsButtonStud() {
            const selectedStudIDs = getSelectedStudIDs();
            // console.log("Selected student IDs:", selectedStudIDs);
            // console.log("Number of selected student:", selectedStudIDs.length);
            $("#selectedStudIDs").text(selectedStudIDs.join(", "));
        }

        $("#stud_select_all").on("click", function () {
            const isChecked = this.checked;
            if (isChecked) {
                dataTable.page.len(-1).draw();
            } else {
                dataTable.page.len(10).draw();
            }
            dataTable.rows().every(function () {
                const $row = $(this.node());
                $row.find(
                    'input[type="checkbox"].stud_checkbox:not(:disabled)'
                ).prop("checked", isChecked);
            });
            toggleSendCredentialsButtonStud();
        });

        $("#myTable").on(
            "change",
            'input[type="checkbox"].stud_checkbox',
            function () {
                toggleSendCredentialsButtonStud();

                const totalRows = dataTable.rows().count();
                const selectedRows = getSelectedStudIDs().length;

                const selectAllCheckbox = $("#stud_select_all").get(0);
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
});

//edit student info
$(document).ready(function () {
    $(document).on("click", ".fa-pen-to-square", function () {
        var $row = $(this).closest("tr");

        const studentID = $(this).data("student-id");
        var studentNo = $row.find("td:eq(0)").text().trim();
        var studentLname = $row.find("td:eq(1)").text();
        var studentFname = $row.find("td:eq(2)").text();
        var email = $row.find("td:eq(5)").text();
        var mobileNo = $row.data("mobile");
        var remarks = $row.data("remarks");

        // console.log("studentID:", studentID);

        $("#edit-studentID").val(studentID);
        $("#edit-studentNo").text(studentNo);
        $("#edit-studentLname").val(studentLname);
        $("#edit-studentFname").val(studentFname);
        $("#edit-email").val(email);
        $("#edit-remarks").val(remarks);
        $("#edit-mobileNo").val(mobileNo);

        $("#edit-student-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-edit-stud").on("click", function () {
        $("#edit-student-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });
});

//view student info:
$(document).ready(function () {
    $(document).on("click", ".fa-eye", function () {
        var $row = $(this).closest("tr");
        var studentID = $row.data("student-id");
        var studentNo = $row.find("td:eq(0)").text(); // Adjust the index based on visible columns
        var studentLname = $row.find("td:eq(1)").text();
        var studentFname = $row.find("td:eq(2)").text();
        var email = $row.find("td:eq(5)").text();
        var mobileNo = $row.data("mobile");
        var remarks = $row.data("remarks"); // Get the hidden "remarks" from data attributes

        // console.log("studentID:", studentID);

        $("#view-studentNo").text(studentNo);
        $("#view-studentFname").text(studentFname);
        $("#view-studentLname").text(studentLname);
        $("#view-email").text(email);
        $("#view-mobileNo").text(mobileNo);
        $("#view-remarks").text(remarks);

        // Show the modal
        $("#view-student-modal").show();
        $("body").addClass("no-scroll");
    });

    // Close button
    $("#close-btn-view-stud").on("click", function () {
        $("#view-student-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });
});
