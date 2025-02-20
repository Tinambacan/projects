
$(document).ready(function () {
    $('#classRecordsTable').DataTable({
        stateSave: true, // Retains table state across page reloads
        ordering: false, // Disable default column ordering
        responsive: true,
        pagingType: "simple",
        lengthMenu: [10, 25, 50],
        columnDefs: [
            {
                targets: '_all', // Apply to all columns
                orderable: false, // Disable ordering
            },
        ],
        initComplete: function () {
            // Remove initial focus if present
            $('#classRecordsTable tbody tr').removeClass('selected');
        },
    });
});


$(document).ready(function () {
    // const toVerifyTable = document.querySelector("#toVerifyTable");
    // if (toVerifyTable) {
    //     $(toVerifyTable).DataTable({
    //         responsive: true,
    //         pagingType: "simple",
    //         paging: true,
    //         order: [],
    //     });
    // }

    const toVerifyTable = document.querySelector("#toVerifyTable");
    if (toVerifyTable) {
        $(toVerifyTable).DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pagingType: "simple",
            paging: true,
            order: [],
            columnDefs: [
                {
                    targets: [3, 4],
                    orderable: false,
                },
            ],
            ajax: {
                url: "/get-admin-classrec-reports",
                method: "GET",
                dataSrc: "data",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            },
            columns: [
                {
                    data: "professorName",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "programTitle",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "courseTitle",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "status",
                    className: "text-center",
                    render: (data) => {
                        return data === "Submitted"
                            ? '<span class="bg-green-500 text-white p-2 rounded-md">Submitted</span>'
                            : '<span class="bg-red-500 text-white p-2 rounded-md">Unsubmitted</span>';
                    },
                },
                {
                    data: null,
                    className: "text-center",
                    render: (data) => {
                        const notifyIcon =
                            data.status === "Unsubmitted"
                                ? `
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-bell text-yellow-500 notif-prof hover:bg-gray-200 dark:hover:bg-[#161616] p-[5px]  rounded-md cursor-pointer text-xl" 
                                        data-prof-id="${data.profID}" 
                                        data-course="${data.courseTitle}" 
                                        data-prof-name="${data.professorName}" 
                                        data-class-record="${data.classRecordID}">
                                    </i>
                                    <div
                                        class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div
                                            class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Notify Professor</span>
                                            <div
                                                class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                            </div>
                                        </div>
                                    </div>
                                </div>`
                                : `<i class="fa-solid fa-bell text-gray-500 text-xl"></i>`;

                        const downloadIcon =
                            data.status === "Submitted"
                                ? `
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-download text-green-500 download-file hover:bg-gray-200 dark:hover:bg-[#161616] p-[5px]  rounded-md cursor-pointer text-xl" 
                                        data-fileID="${data.fileID}" 
                                        >
                                    </i>
                                    <div
                                        class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div
                                            class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Download File</span>
                                            <div
                                                class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    `
                                : `<i class="fa-solid fa-download  text-gray-500 text-xl"></i>`;
                        return `
                        <div class="flex justify-center items-center gap-2 text-2xl">
                            ${notifyIcon}
                            ${downloadIcon}
                        </div>
                        `;
                    },
                },
            ],
        });
    }

    const verifiedTable = document.querySelector("#verifiedTable");
    if (verifiedTable) {
        $(verifiedTable).DataTable({
            responsive: true,
            pagingType: "simple",
            paging: true,
            order: [],
        });
    }

    const submittedTable = document.querySelector("#submittedTable");
    if (submittedTable) {
        $(submittedTable).DataTable({
            responsive: true,
            pagingType: "simple",
            paging: true,
            order: [],
        });
    }

    const facultyVerifiedTable = document.querySelector(
        "#facultyVerifiedTable"
    );
    if (facultyVerifiedTable) {
        $(facultyVerifiedTable).DataTable({
            responsive: true,
            pagingType: "simple",
            paging: true,
            order: [],
        });
    }

    $(document).ready(function () {
        // Event delegation to handle dynamically added elements
        $(document).on("click", ".submit-button", function () {
            const fileID = $(this).data("file-id");
            submitForm(fileID);
        });
    });

    function submitForm(fileID) {
        const form = $("#report-form-" + fileID);
        if (form.length) {
            const formData = form.serialize(); // Serialize form data

            $.ajax({
                url: form.attr("action"),
                method: "POST",
                data: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                success: function (data) {
                    if (data.status === "success") {
                        window.location.href = data.redirect_url;
                    } else {
                        alert(data.message);
                    }
                },
                error: function (xhr) {
                    console.error("AJAX request failed:", xhr);
                },
            });
        } else {
            console.error("Form not found for fileID:", fileID);
        }
    }
});

$(document).ready(function () {
    $("#verify-print-form").on("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: $(this).serialize(),
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"), // Include CSRF token
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonColor: "#3085d6",
                    }).then(() => {
                        // Optionally, you can redirect or refresh the page
                        // location.reload(); // Refresh the page
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message,
                        icon: "error",
                        confirmButtonColor: "#3085d6",
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);

                Swal.fire({
                    title: "Error!",
                    text: "An error occurred  while updating the file.",
                    icon: "error",
                    confirmButtonColor: "#3085d6",
                });
            },
        });
    });
});

$(document).ready(function () {
    // Attach a click event listener to the button
    $("#verify-print-btn").on("click", function (e) {
        e.preventDefault(); // Prevent the form from submitting immediately

        // Show SweetAlert confirmation
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to verify and print this file?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, verify it!",
            cancelButtonText: "No, cancel!",
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirmed, submit the form via AJAX
                $.ajax({
                    url: $("#verify-print-form").attr("action"), // Use the form's action URL
                    type: "POST",
                    data: $("#verify-print-form").serialize(), // Serialize the form data
                    success: function (response) {
                        Swal.fire(
                            "Verified!",
                            "The file has been verified and printed.",
                            "success"
                        ).then(() => {
                            window.location.href = "/admin/reports/verified";
                        });
                    },
                    error: function (xhr) {
                        Swal.fire(
                            "Error!",
                            "There was an error verifying the file.",
                            "error"
                        );
                    },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    "Cancelled",
                    "Your action has been cancelled :)",
                    "info"
                );
            }
        });
    });

    $(document).on("click", ".notif-prof", function (e) {
        const profID = $(this).data("prof-id");
        const course = $(this).data("course");
        const classRecordID = $(this).data("class-record");
        const professorName = $(this).data("prof-name");

        Swal.fire({
            title: "Notify Professor",
            html: `Do you want to send a notice to <strong>${professorName}</strong> for the course: <strong>${course}</strong>?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/notify-professor",
                    type: "POST",
                    data: {
                        professor_id: profID,
                        course: course,
                        classRecordID: classRecordID,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        // console.log(response.message);
                        Swal.fire({
                            icon: "success",
                            title: "Notification Sent",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Failed to notify the professor!",
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", ".download-file", function () {
        const fileID = $(this).data("fileid");

        Swal.fire({
            title: "Download File",
            text: "Are you sure you want to download this file?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Download",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/download-file/${fileID}`,
                    type: "GET",
                    xhrFields: {
                        responseType: "blob",
                    },
                    success: function (data, status, xhr) {
                        const filename = xhr.getResponseHeader(
                            "Content-Disposition"
                        )
                            ? xhr
                                  .getResponseHeader("Content-Disposition")
                                  .split("filename=")[1]
                                  .replace(/"/g, "")
                            : "downloaded_file";

                        // Create a temporary link to download the file
                        const link = document.createElement("a");
                        link.href = window.URL.createObjectURL(data);
                        link.download = filename;
                        link.click();
                        window.URL.revokeObjectURL(link.href); // Clean up
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Failed to download the file!",
                        });
                    },
                });
            }
        });
    });
});

// document
//     .getElementById("sendJson")
//     .addEventListener("click", async function () {
//         document
//             .getElementById("loader-modal-submit")
//             .classList.remove("hidden");
//         document.getElementById("getJson").classList.remove("hidden");
//         $("body").addClass("no-scroll");

//         try {
//             const response = await fetch("/fetch-pupt-faculty-schedules", {
//                 method: "GET",
//                 headers: {
//                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
//                         "content"
//                     ),
//                 },
//             });

//             if (!response.ok) {
//                 throw new Error("Network response was not ok");
//             }

//             const jsonData = await response.json();

//             const sendResponse = await fetch("/store-classrecord-integration", {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/json",
//                     "X-CSRF-TOKEN": document
//                         .querySelector('meta[name="csrf-token"]')
//                         .getAttribute("content"),
//                 },
//                 body: JSON.stringify({ pupt_faculty_schedules: jsonData }),
//             });

//             const sendResult = await sendResponse.json();

//             if (sendResponse.ok) {
//                 Swal.fire({
//                     icon: "success",
//                     title: "Success",
//                     text: sendResult.message || "Data sent successfully!",
//                 }).then(() => {
//                     window.location.reload();
//                 });
//             } else {
//                 Swal.fire({
//                     icon: "error",
//                     title: "Error",
//                     text:
//                         sendResult.error ||
//                         "Error occurred while processing your request.",
//                 }).then(() => {
//                     window.location.reload();
//                 });
//             }
//         } catch (error) {
//             console.error("Error:", error);
//             Swal.fire({
//                 icon: "error",
//                 title: "Unexpected Error",
//                 text:
//                     error.message ||
//                     "An error occurred while processing your request.",
//             }).then(() => {
//                 window.location.reload();
//             });
//         } finally {
//             document
//                 .getElementById("loader-modal-submit")
//                 .classList.add("hidden");
//             document.getElementById("getJson").classList.add("hidden");
//             $("body").removeClass("no-scroll");
//         }
//     });

// $(document).ready(function () {
//     $("#send-notification-btn").on("click", function () {
//         $.ajax({
//             url: "https://api-ecrs.puptcapstone.com/send-notice", // Your route
//             type: "POST",
//             data: {
//                 _token: $('meta[name="csrf-token"]').attr("content"), // Include CSRF token
//             },
//             success: function () {
//                 console.log("Job dispatched successfully!");
//             },
//             error: function (xhr) {
//                 alert("Error: " + xhr.statusText);
//             },
//         });
//     });
// });
// $(document).ready(function () {
//     $.ajaxSetup({
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//     });

//     $("#send-notification-btn").on("click", function () {
//         $.ajax({
//             url: "/test-send-notice", // your route
//             type: "POST",
//             data: {
//                 data: "Hi", // data you want to send
//             },
//             success: function (response) {
//                 console.log("Response: ", response);
//                 alert("Job dispatched successfully!");
//             },
//             error: function (xhr) {
//                 console.log("Error Status: ", xhr.status);
//                 console.log("Error Response: ", xhr.responseText);
//                 alert("Error: " + xhr.statusText);
//             },
//         });
//     });
// });

// document.addEventListener('DOMContentLoaded', function () {
//     const button = document.getElementById('showJson');

//     button.addEventListener('click', function () {
//         // Redirect to the new page with the faculty load data
//         window.location.href = "/admin-faculty-loads";
//     });
// });





