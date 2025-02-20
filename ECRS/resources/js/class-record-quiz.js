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

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    function hideModal(modalId) {
        $("#" + modalId).fadeOut();
        $("body").removeClass("no-scroll");
    }
    function showModal(modalId, assessmentType) {
        $("#" + modalId).show();
        $("body").addClass("no-scroll");

        $("#add-assessment-form").attr("data-assessment-type", assessmentType);
    }

    $("[id^=add-]").on("click", function () {
        var assessmentType = $(this)
            .attr("id")
            .replace("add-", "")
            .replace("-btn", "");
        var modalId = "add-" + assessmentType + "-modal";
        showModal(modalId, assessmentType);
    });

    const assessInfoTable = document.querySelector("#assessInfoTable");

    const isMobile = window.innerWidth < 768;

    const isArchived =
        document.getElementById("isArchived").textContent.trim() === "1";

    if (assessInfoTable) {
        let gradingDistributionType = "";
        let storedAssessmentType = "";

        let dataTable;

        dataTable = $(assessInfoTable).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-assessment-info",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                dataSrc: function (json) {
                    storedAssessmentType = json.storedAssessmentType;
                    gradingDistributionType = json.gradingDistributionType;
                    return json.data;
                },
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="assess_checkbox text-center" data-assess-id="${
                            row.assessmentID
                        }" data-name="${row.assessmentName}" ${
                            row.isPublished ? "disabled" : ""
                        }>`;
                    },

                    orderable: false,
                },
                {
                    data: "assessmentName",
                },
                {
                    data: "totalItem",
                    render: function (data, type, row) {
                        if (storedAssessmentType !== "attendance") {
                            return `<div class="text-center">${data}</div>`;
                        }
                        return `<div class="text-center">${row.assessmentDate}</div>`;
                    },
                },

                {
                    data: "passingItem",
                    render: function (data, type, row) {
                        if (storedAssessmentType !== "attendance") {
                            return `<div class="text-center">${data}</div>`;
                        }
                        let statusContent = "";
                        if (row.isPublished == 1) {
                            statusContent = `<span class="bg-green-500 text-white p-2 rounded-md">Published</span>`;
                        } else {
                            statusContent = `
                                <div class="relative group flex justify-center items-center">
                                    <button class="send-stud-scores cursor-pointer" data-assessment-id="${row.assessmentID}">
                                         <span class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                    </button>
                                   
                              <div
                                class="absolute top-[-55px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Publish score</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                                </div>
                            `;
                        }
                        return `<div class="text-center">${statusContent}</div>`;
                    },
                },

                {
                    data: "assessmentDate",
                    render: function (data, type, row) {
                        if (storedAssessmentType !== "attendance" && data) {
                            return `<div class="text-center">${data}</div>`;
                        }
                        return `
                            <div class="text-center text-xl flex gap-1 justify-center items-center">

                                <!-- Form for assessment ID submission -->
                                <div class="relative group flex justify-center items-center">
                                    <form action="/store-assessment-id" method="POST">
                                        <input type="hidden" name="_token" value="${$(
                                            'meta[name="csrf-token"]'
                                        ).attr("content")}">
                                        <input type="hidden" name="assessmentID" value="${
                                            row.assessmentID
                                        }">
                                        <input type="hidden" name="gradingDistributionType" value="${gradingDistributionType}">
                                        <input type="hidden" name="assessmentType" value="${storedAssessmentType}">
                                        <button type="submit" class="text-white hover:bg-gray-200 hover:rounded-md p-1 text-center w-full flex justify-center">
                                            <i class="fa-solid fa-book text-blue-500"></i>
                                        </button>
                                    </form>

                                    <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">View Details</span>
                                            <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Button -->

                                ${
                                    !isArchived
                                        ? `
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                       data-assessment-id="${row.assessmentID}"
                                       data-assessment-name="${row.assessmentName}"
                                       data-assessment-date="${row.assessmentDate}"
                                       data-total-item="${row.totalItem}"
                                       data-passing-item="${row.passingItem}"
                                       data-assessment-type="${storedAssessmentType}">
                                    </i>

                                    <!-- Tooltip for "Edit Info" -->
                                    <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                                            <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Duplicate Button -->
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-copy text-yellow-500 duplicate-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                       data-assessment-id="${row.assessmentID}"
                                       data-assessment-name="${row.assessmentName}"
                                       data-assessment-date="${row.assessmentDate}"
                                       data-total-item="${row.totalItem}"
                                       data-passing-item="${row.passingItem}"
                                       data-assessment-type="${storedAssessmentType}">
                                    </i>

                                    <!-- Tooltip for "Duplicate Info" -->
                                    <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Duplicate Info</span>
                                            <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                        </div>
                                    </div>
                                </div>
                                `
                                        : ""
                                }
                            </div>
                        `;
                    },
                },

                {
                    data: "isPublished",
                    // visible: storedAssessmentType !== "attendance",
                    render: function (data, type, row) {
                        if (data == 1) {
                            return `<span class="bg-green-500 text-white p-2 rounded-md">Published</span>`;
                        } else {
                            return `
                                <div class="relative group flex justify-center items-center">
                                    <button class="send-stud-scores cursor-pointer" data-assessment-id="${row.assessmentID}">
                                        <span class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                    </button>
                                   
                               <div class="absolute bottom-full left-1/2 transform hidden group-hover:block -translate-x-1/2 z-40 mb-4">
    <div class="flex justify-center items-center text-center transition-all duration-300 relative ">
        <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Publish Score</span>
        <div
            class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
        </div>
    </div>
</div>
                            `;
                        }
                    },
                },
                {
                    data: null,
                    visible: storedAssessmentType !== "attendance",
                    render: function (data, type, row) {
                        return `
    <div class="text-center text-xl flex gap-1 justify-center items-center">
        <div class="relative group flex justify-center items-center">
            <form action="/store-assessment-id" method="POST">
                <input type="hidden" name="_token" value="${$(
                    'meta[name="csrf-token"]'
                ).attr("content")}">
                <input type="hidden" name="assessmentID" value="${
                    row.assessmentID
                }">
                <input type="hidden" name="gradingDistributionType" value="${gradingDistributionType}">
                <input type="hidden" name="assessmentType" value="${storedAssessmentType}">
                <button type="submit" class="text-white hover:bg-gray-200 hover:rounded-md p-1 text-center w-full flex justify-center">
                    <i class="fa-solid fa-book text-blue-500"></i>
                </button>
            </form>

            <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                    <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">View Details</span>
                    <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                    </div>
                </div>
            </div>
        </div>

        <!-- Conditionally include Edit Info -->
        ${
            !isArchived
                ? `
        <div class="relative group flex justify-center items-center">
            <i class="fa-solid fa-pen-to-square text-green-500 edit-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
               data-assessment-id="${row.assessmentID}"
               data-assessment-name="${row.assessmentName}"
               data-assessment-date="${row.assessmentDate}"
               data-total-item="${row.totalItem}"
               data-passing-item="${row.passingItem}"
               data-assessment-type="${storedAssessmentType}">
            </i>

            <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                    <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                    <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                    </div>
                </div>
            </div>
        </div>
        `
                : ""
        }

        <!-- Conditionally include Duplicate Info -->
        ${
            !isArchived
                ? `
        <div class="relative group flex justify-center items-center">
            <i class="fa-solid fa-copy text-yellow-500 duplicate-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
               data-assessment-id="${row.assessmentID}"
               data-assessment-name="${row.assessmentName}"
               data-assessment-date="${row.assessmentDate}"
               data-total-item="${row.totalItem}"
               data-passing-item="${row.passingItem}"
               data-assessment-type="${storedAssessmentType}">
            </i>

            <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                    <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Duplicate Info</span>
                    <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                    </div>
                </div>
            </div>
        </div>
        `
                : ""
        }
    </div>
`;
                    },
                    orderable: false,
                },
            ],
            scrollX: isMobile,
            pagingType: "simple",
            order: [],
            columnDefs: [
                {
                    targets: [0],
                    orderable: false,
                },
                ...(isArchived ? [{ targets: [0, 5, 6], visible: false }] : []),
            ],
            initComplete: function () {
                const isPublishedColumnIndex = 5;
                const isActionsColumnIndex = 6;

                if (isArchived === 1) {
                    dataTable.column(isPublishedColumnIndex).visible(false);
                    dataTable.column(isActionsColumnIndex).visible(false);
                } else {
                    dataTable
                        .column(isPublishedColumnIndex)
                        .visible(storedAssessmentType !== "attendance");

                    dataTable
                        .column(isActionsColumnIndex)
                        .visible(storedAssessmentType !== "attendance");
                }
            },
        });

        $("#add-assessment-form").on("submit", function (e) {
            e.preventDefault();

            var formAction = $(this).attr("action");
            var assessmentType = $(this).data("assessment-type");
            var modalId =
                "add-" + assessmentType.toLowerCase().replace(/ /g, "-");

            $.ajax({
                url: formAction,
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("body").removeClass("no-scroll");
                            dataTable.ajax.reload();
                            $("#add-assessment-form")[0].reset();
                            $("#" + modalId).fadeOut();

                            const maxLength = 20;
                            const countDisplaySelector = "#char-count";
                            $(countDisplaySelector).text(maxLength);
                        }
                    });
                },
                error: function (xhr) {
                    // Check if the response is a 409 Conflict
                    if (xhr.status === 409) {
                        var errorMessage = xhr.responseJSON.message; // Get the custom message from PHP response

                        Swal.fire({
                            title: "Error!",
                            text: errorMessage,
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    } else {
                        // For other errors, handle them as usual
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = "Please fix the following errors:\n";

                        $.each(errors, function (field, messages) {
                            errorMessage += messages.join(" ") + "\n";
                        });

                        Swal.fire({
                            title: "Error!",
                            text: errorMessage,
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    }
                },
            });
        });

        // jQuery for form submission
        $("#import-assessment-form").on("submit", function (e) {
            e.preventDefault();

            var formAction = $(this).attr("action");
            var modalId = "import-assessment-modal";
            var formData = new FormData(this);

            $.ajax({
                url: formAction,
                method: "POST",
                data: formData,
                processData: false, // Required for file uploads
                contentType: false, // Required for file uploads
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Close the modal
                            $("#" + modalId).fadeOut();
                            $("body").removeClass("no-scroll");
                            $("#import-assessment-form")[0].reset();
                            dataTable.ajax.reload();
                        }
                    });
                },
                error: function (xhr) {
                    // Handle errors
                    var errorMessage =
                        xhr.responseJSON.message ||
                        "An unexpected error occurred.";

                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                },
                complete: function () {
                    // Close the modal even after errors (if needed)
                    $("#" + modalId).fadeOut();
                },
            });
        });

        $("#edit-assessment-form").on("submit", function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr("action"),
                type: "POST", // Use POST method
                data: formData, // Send the form data
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then(() => {
                        $(
                            "#edit-" +
                                response.assessmentType
                                    .toLowerCase()
                                    .replace(/\s+/g, "-") +
                                "-modal"
                        ).fadeOut();
                        $("body").removeClass("no-scroll");
                        dataTable.ajax.reload();
                    });
                },
                error: function (xhr) {
                    var response = xhr.responseJSON;
                    var errorMessage = response.message || "An error occurred.";

                    if (
                        response.invalidStudentAssessments &&
                        response.invalidStudentAssessments.length > 0
                    ) {
                        errorMessage +=
                            "\n\nTotal score cannot be less than the score of the following students:\n";
                        response.invalidStudentAssessments.forEach(function (
                            assessment
                        ) {
                            errorMessage += `${assessment.studentLname} (Score: ${assessment.studentScore})\n`;
                        });
                    }

                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                },
            });
        });

        $("#duplicate-assessment-form").on("submit", function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr("action"),
                type: "POST", // Use POST for creation
                data: formData,
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then(() => {
                        $(
                            "#duplicate-" +
                                response.assessmentType
                                    .toLowerCase()
                                    .replace(/\s+/g, "-") +
                                "-modal"
                        ).fadeOut();
                        $("body").removeClass("no-scroll");
                        dataTable.ajax.reload();
                    });
                },
                error: function (xhr) {
                    var response = xhr.responseJSON;
                    var errorMessage = response.message || "An error occurred.";
                    Swal.fire({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                },
            });
        });

        $(document).on("click", ".send-stud-scores", function () {
            const selectedAssessIDs = [$(this).data("assessment-id")];
            const classRecordID = $('input[name="classRecordIDScore"]').val();
            const gradingType = $('input[name="gradingType"]').val();
            const gradingTerm = $('input[name="gradingTerm"]').val();

            if (selectedAssessIDs.length === 0) {
                Swal.fire({
                    title: "Error!",
                    text: "Please select at least one assessment",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return;
            }
            $("body").addClass("no-scroll");

            Swal.fire({
                title: "Publish scores?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Publish",
                cancelButtonText: "No, cancel",
            }).then((result) => {
                $("#send-scores-loader").removeClass("hidden");

                if (result.isConfirmed) {
                    $.ajax({
                        url: "/notify-students-publish",
                        method: "POST",
                        data: {
                            selectedAssessIDs: selectedAssessIDs,
                            classRecordID: classRecordID,
                            gradingType: gradingType,
                            gradingTerm: gradingTerm,
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
                                }).then(function () {
                                    dataTable.ajax.reload();
                                });
                            }, 500);
                        },
                        error: function (xhr) {
                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.invalidStudentAssessments
                            ) {
                                let invalidAssessmentsList = "";
                                xhr.responseJSON.invalidStudentAssessments.forEach(
                                    function (item) {
                                        invalidAssessmentsList += `Assessment: <strong>${item.assessmentName}</strong>, Student: <strong>${item.studentFname} ${item.studentLname}</strong><br>`;
                                    }
                                );

                                Swal.fire({
                                    title: "Publish scores failed!",
                                    html: `<strong>Some students have no scores:</strong><br><br>${invalidAssessmentsList}`,
                                    icon: "warning",
                                    confirmButtonText: "OK",
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: xhr.responseJSON
                                        ? xhr.responseJSON.message
                                        : "An error occurred while sending the notification.",
                                    icon: "error",
                                    confirmButtonText: "OK",
                                });
                            }
                        },
                        complete: function () {
                            $("#send-scores-loader").addClass("hidden");
                            $("body").removeClass("no-scroll");
                        },
                    });
                } else {
                    $("#send-scores-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                }
            });
        });

        $(".send-batch-stud-scores").click(function () {
            const selectedAssessIDs = getSelectedAssessmentIDs();
            const classRecordID = $('input[name="classRecordIDScore"]').val();
            $("#selectedAssessIDs").val(selectedAssessIDs);
            const gradingType = $('input[name="gradingType"]').val();
            const gradingTerm = $('input[name="gradingTerm"]').val();

            if (selectedAssessIDs.length === 0) {
                Swal.fire({
                    title: "Error!",
                    text: "Please select at least one assessment",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return;
            }

            $("body").addClass("no-scroll");

            Swal.fire({
                title: "Publish scores?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Publish",
                cancelButtonText: "No, cancel",
            }).then((result) => {
                $("#send-scores-loader").removeClass("hidden");

                if (result.isConfirmed) {
                    $.ajax({
                        url: "/notify-students-batch",
                        method: "POST",
                        data: {
                            selectedAssessIDs: selectedAssessIDs,
                            classRecordID: classRecordID,
                            gradingType: gradingType,
                            gradingTerm: gradingTerm,
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ), // Ensure CSRF token
                        },
                        success: function (response) {
                            setTimeout(function () {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function () {
                                    // location.reload();
                                    dataTable.ajax.reload();
                                });
                            }, 500);
                        },
                        error: function (xhr) {
                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.invalidStudentAssessments
                            ) {
                                let invalidAssessmentsList = "";
                                xhr.responseJSON.invalidStudentAssessments.forEach(
                                    function (item) {
                                        invalidAssessmentsList += `Assessment: <strong>${item.assessmentName}</strong>, Student: <strong>${item.studentFname} ${item.studentLname}</strong><br>`;
                                    }
                                );

                                Swal.fire({
                                    title: "Publish scores failed!",
                                    html: `<strong>Some students have no scores:</strong><br><br>${invalidAssessmentsList}`,
                                    icon: "warning",
                                    confirmButtonText: "OK",
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: xhr.responseJSON
                                        ? xhr.responseJSON.message
                                        : "An error occurred while sending the notification.",
                                    icon: "error",
                                    confirmButtonText: "OK",
                                });
                            }
                        },
                        complete: function () {
                            $("#send-scores-loader").addClass("hidden");
                            $("body").removeClass("no-scroll");
                        },
                    });
                } else {
                    $("#send-scores-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                }
            });
        });

        function togglePublishScoresButton() {
            const selectedAssessIDs = getSelectedAssessmentIDs();
            // console.log("Selected Assessment IDs:", selectedAssessIDs);
            // console.log(
            //     "Number of selected assessment:",
            //     selectedAssessIDs.length
            // );

            $("#selectedAssessIDs").text(selectedAssessIDs.join(", "));
        }

        // $("#assess_select_all").on("click", function () {

        //     dataTable.rows().every(function () {
        //         const $row = $(this.node());
        //         $row.find(
        //             'input[type="checkbox"].assess_checkbox:not(:disabled)'
        //         ).prop("checked", isChecked);
        //     });
        //     togglePublishScoresButton();
        // });

        $("#assess_select_all").on("click", function () {
            const isChecked = $(this).prop("checked");
            // console.log("Select All Checkbox State:", isChecked);

            dataTable.rows({ page: "current" }).every(function () {
                const $row = $(this.node());

                $row.find(
                    'input[type="checkbox"].assess_checkbox:not(:disabled)'
                ).prop("checked", isChecked);

                // console.log(
                //     "Row Checkboxes:",
                //     $row
                //         .find('input[type="checkbox"].assess_checkbox')
                //         .map(function () {
                //             return {
                //                 id: $(this).data("assess-id"),
                //                 name: $(this).data("name"),
                //                 checked: this.checked,
                //             };
                //         })
                //         .get()
                // );
            });

            togglePublishScoresButton();
        });

        // const isChecked = this.checked;
        // dataTable.rows().every(function () {
        //     const $row = $(this.node());
        //     $row.find('input[type="checkbox"].assess_checkbox').prop(
        //         "checked",
        //         isChecked
        //     );
        // });

        // const isChecked = this.checked;
        // if (isChecked) {
        //     dataTable.page.len(-1).draw();
        // } else {
        //     dataTable.page.len(10).draw();
        // }

        $("#myTable").on(
            "change",
            'input[type="checkbox"].assess_checkbox',
            function () {
                togglePublishScoresButton();

                const totalRows = dataTable.rows().count();
                const selectedRows = getSelectedAssessmentIDs().length;

                const selectAllCheckbox = $("#assess_select_all").get(0);
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

        function getSelectedAssessmentIDs() {
            const selectedAssessIDs = [];
            dataTable.rows().every(function () {
                const $row = $(this.node());
                const $checkbox = $row.find(
                    'input[type="checkbox"].assess_checkbox'
                );

                if ($checkbox.prop("checked")) {
                    const numberAssessID = $row
                        .find('input[type="checkbox"]')
                        .data("assess-id");
                    if (numberAssessID) {
                        selectedAssessIDs.push(numberAssessID);
                    }
                }
            });

            const uniqueAssessIDs = [...new Set(selectedAssessIDs)];

            return uniqueAssessIDs;
        }
    }

    $(document).on("click", "[id^=close-btn-]", function () {
        var closeBtnId = $(this).attr("id");
        var modalId = closeBtnId.replace("close-btn-", "") + "-modal";
        hideModal(modalId);
    });

    $(document).ready(function () {
        $(".import-assessment-btn").on("click", function () {
            $("#import-assessment-modal").show();
            $("body").addClass("no-scroll");
        });

        $("#close-btn-import-assessment").on("click", function () {
            $("#import-assessment-modal").fadeOut(); // Smoothly hide the modal
            $("body").removeClass("no-scroll"); // Allow background scroll
        });
    });
});

function handleEditCharacterCount(
    inputSelector,
    countDisplaySelector,
    maxChars
) {
    $(inputSelector).on("input", function () {
        const currentLength = $(this).val().length;
        const charsLeft = maxChars - currentLength;

        $(countDisplaySelector).text(`${charsLeft}`);

        // Enforce max length (in case maxlength is bypassed)
        if (currentLength > maxChars) {
            $(this).val($(this).val().substring(0, maxChars));
            $(countDisplaySelector).text(0); // Ensure it shows 0 if max exceeded
        }
    });

    // Initialize character count when the modal is opened or page loads
    const initialLength = $(inputSelector).val().length;
    const initialCharsLeft = maxChars - initialLength;
    $(countDisplaySelector).text(`${initialCharsLeft}`);
}

$(document).on("click", ".edit-assessment", function () {
    var assessmentID = $(this).data("assessment-id");
    var assessmentName = $(this).data("assessment-name");
    var totalItem = $(this).data("total-item");
    var passingItem = $(this).data("passing-item");

    var passingPercentage = (passingItem / totalItem) * 100;
    var assessmentDate = $(this).data("assessment-date");
    var assessmentType = $(this).data("assessment-type");

    $("#edit-assessment-id").val(assessmentID);
    $("#edit-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-name").val(
        assessmentName
    );
    $("#edit-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-date").val(
        assessmentDate
    );
    $(
        "#edit-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-total"
    ).val(totalItem);
    $(
        "#edit-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-passing"
    ).val(passingPercentage);

    $(
        "#edit-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-modal"
    ).show();

    $("body").addClass("no-scroll");

    handleEditCharacterCount(
        "#edit-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-name", // Selector for the input field
        "#edit-char-count",
        20
    );
});

function handleDuplicateCharacterCount(
    inputSelector,
    countDisplaySelector,
    maxChars
) {
    $(inputSelector).on("input", function () {
        const currentLength = $(this).val().length;
        const charsLeft = maxChars - currentLength;

        $(countDisplaySelector).text(`${charsLeft}`);

        // Enforce max length (in case maxlength is bypassed)
        if (currentLength > maxChars) {
            $(this).val($(this).val().substring(0, maxChars));
            $(countDisplaySelector).text(0); // Ensure it shows 0 if max exceeded
        }
    });

    // Initialize character count when the modal is opened or page loads
    const initialLength = $(inputSelector).val().length;
    const initialCharsLeft = maxChars - initialLength;
    $(countDisplaySelector).text(`${initialCharsLeft}`);
}

$(document).on("click", ".duplicate-assessment", function () {
    var assessmentName = $(this).data("assessment-name");
    var totalItem = $(this).data("total-item");
    var passingItem = $(this).data("passing-item");
    var assessmentDate = $(this).data("assessment-date");
    var assessmentType = $(this).data("assessment-type");

    // Populate the duplicate modal fields
    $(
        "#duplicate-" +
            assessmentType.toLowerCase().replace(/ /g, "-") +
            "-name"
    ).val(assessmentName);
    $(
        "#duplicate-" +
            assessmentType.toLowerCase().replace(/ /g, "-") +
            "-date"
    ).val(assessmentDate);
    $(
        "#duplicate-" +
            assessmentType.toLowerCase().replace(/ /g, "-") +
            "-total"
    ).val(totalItem);
    $(
        "#duplicate-" +
            assessmentType.toLowerCase().replace(/ /g, "-") +
            "-passing"
    ).val(passingItem);

    // Show the duplicate modal
    $(
        "#duplicate-" +
            assessmentType.toLowerCase().replace(/ /g, "-") +
            "-modal"
    ).show();
    $("body").addClass("no-scroll");

    handleDuplicateCharacterCount(
        "#duplicate-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-name", // Selector for the input field
        "#duplicate-char-count",
        20
    );
});

function formatDate(dateString) {
    var parts = dateString.split("-");
    return parts[2] + "-" + parts[0] + "-" + parts[1];
}

$(".close-btn").on("click", function () {
    var assessmentType = $(this).data("assessment-type");
    $(
        "#edit-" + assessmentType.toLowerCase().replace(/ /g, "-") + "-modal"
    ).fadeOut();
    $("body").removeClass("no-scroll");
});

$(document).ready(function () {
    $(".export-template-btn").on("submit", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Confirmation",
            text: "You are about to export a template",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Export",
        }).then((result) => {
            if (result.isConfirmed) {
                $("#export-template").removeClass("hidden");
                const formData = new FormData(this); // Get form data
                $.ajax({
                    url: "/export-assessment-template", // Your URL for export
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhrFields: {
                        responseType: "blob", // Expect binary response (file)
                    },
                    success: function (data, status, xhr) {
                        const disposition = xhr.getResponseHeader(
                            "Content-Disposition"
                        );
                        let filename = "exported-scores.xlsx"; // Default filename
                        if (disposition) {
                            const matches = /filename="([^"]*)"/.exec(
                                disposition
                            );
                            if (matches != null && matches[1])
                                filename = matches[1];
                        }

                        const blob = new Blob([data], {
                            type: xhr.getResponseHeader("Content-Type"),
                        });
                        const link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        $("#export-template").addClass("hidden");

                        Swal.fire({
                            title: "Success!",
                            text: "The template has been exported successfully.",
                            icon: "success",
                            confirmButtonColor: "#3085d6",
                        });
                    },
                    error: function () {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong while exporting the template.",
                            icon: "error",
                            confirmButtonColor: "#d33",
                        });
                    },
                });
            }
        });
    });
});

$(document).ready(function () {
    const maxLength = 20;
    const inputSelector = "#assessmentName";
    const countDisplaySelector = "#char-count";

    // Use event delegation to handle dynamically loaded inputs
    $(document).on("input", inputSelector, function () {
        const currentLength = $(this).val().length;
        const remainingChars = maxLength - currentLength;

        console.log(inputSelector);

        // Update the countdown text
        $(countDisplaySelector).text(remainingChars);

        // Enforce max length (just in case maxlength is bypassed)
        if (remainingChars < 0) {
            $(this).val($(this).val().substring(0, maxLength));
            $(countDisplaySelector).text(0);
        }
    });
});

// document.addEventListener("DOMContentLoaded", function () {
//     // const modal = document.getElementById("import-assessment-modal");
//     const btnOpenModal = document.querySelector(
//         '[data-modal-toggle="import-assessment-modal"]'
//     ); // Trigger button for opening modal
//     const btnCloseModal = document.getElementById(
//         "close-btn-import-assessment"
//     );
//     const form = document.getElementById("import-assessment-form");

//     // Function to open the modal
//     function openModal() {
//         // modal.classList.show();
//         $("#import-assessment-modal").show();
//         $("body").addClass("no-scroll");
//         // modal.setAttribute("aria-hidden", "false");
//     }

//     // Function to close the modal
//     function closeModal() {
//         // modal.classList.fadeOut();
//         $("#import-assessment-modal").fadeOut();
//         $("body").removeClass("no-scroll");
//         // modal.setAttribute("aria-hidden", "true");
//         // Reset the form when the modal is closed
//         form.reset();
//     }

//     // Handle open modal button
//     // if (btnOpenModal) {
//     //     btnOpenModal.addEventListener("click", openModal);
//     // }

//     // Handle modal close button
//     // if (btnCloseModal) {
//     //     btnCloseModal.addEventListener("click", closeModal);
//     // }

//     // window.addEventListener("click", function (event) {
//     //     if (event.target === modal) {
//     //         closeModal();
//     //     }
//     // });
// });
