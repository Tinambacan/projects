$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const assessmentDetailsTable = document.querySelector(
        "#assessmentDetailsTable"
    );

    const isMobile = window.innerWidth < 768;

    const isArchived =
        document.getElementById("isArchived").textContent.trim() === "1";

    if (assessmentDetailsTable) {
        let storedAssessmentType = "";
        let assessmentID = "";
        let totalItem = "";
        let classRecordID = "";
        let gradingDistributionType = "";
        let term = "";
        let passingItem = "";
        const dataTable = $(assessmentDetailsTable).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-assessment-details-info",
                type: "GET",
                dataType: "json",
                dataSrc: function (json) {
                    storedAssessmentType = json.storedAssessmentType;
                    assessmentID = json.assessmentID;
                    totalItem = json.totalItem;
                    passingItem = json.passingItem;
                    classRecordID = json.classRecordID;
                    gradingDistributionType = json.gradingDistributionType;
                    term = json.term;
                    return json.data;
                },
            },
            columns: [
                {
                    data: "studentID",
                    render: function (data, type, row) {
                        if (storedAssessmentType !== "attendance") {
                            return `<input type="checkbox" class="score_checkbox text-center" data-student-id="${
                                row.studentID
                            }" ${row.isRawScoreViewable ? "disabled" : ""}>`;
                        }
                        return `<input type="checkbox" class="score_checkbox text-center" data-student-id="${row.studentID}">`;
                    },
                    orderable: false,
                    className: "text-center",
                },
                { data: "studentNo", className: "text-center" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<span class=" font-bold"> 
                        ${row.studentLname},
                        </span> 
                        ${row.studentFname}`;
                    },
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        let formHTML = "";
                        if (storedAssessmentType === "attendance") {
                            formHTML = `
                                <form id="assessmentFormAttendance-${
                                    row.studentID
                                }">
                                    <input type="hidden" name="assessmentID" value="${assessmentID}">
                                    <input type="hidden" name="classRecordID" value="${classRecordID}">
                                    <select name="scores[${
                                        row.studentID
                                    }]" class="attendance-selector p-2 border-2 border-gray-300 rounded shadow-lg" data-student-id="${
                                row.studentID
                            }">
                                        <option value="" ${
                                            !row.score ? "selected" : ""
                                        }>Not set</option>
                                        <option value="1.0" ${
                                            row.score === "1.0"
                                                ? "selected"
                                                : ""
                                        }>Present</option>
                                        <option value="0.0" ${
                                            row.score === "0.0"
                                                ? "selected"
                                                : ""
                                        }>Absent</option>
                                        <option value="0.75" ${
                                            row.score === "0.75"
                                                ? "selected"
                                                : ""
                                        }>Late</option>
                                        <option value="N/A" ${
                                            row.score === "N/A"
                                                ? "selected"
                                                : ""
                                        }>Excuse</option>
                                    </select>
                                </form>
                            `;
                        } else {
                            const scoreBgColor =
                                row.score === "" || row.score === null
                                    ? "bg-gray-100 dark:bg-gray-500"
                                    : row.score >= passingItem &&
                                      row.score <= totalItem
                                    ? "bg-[#78DC82] dark:bg-green-500 text-white"
                                    : "bg-[#f87171]  dark:bg-red-500  text-white ";

                            formHTML = `
                                    <input type="hidden" name="assessmentID" value="${assessmentID}">
                                    <input type="hidden" name="classRecordIDScores" value="${classRecordID}">
                                
                                    <div class="score-container rounded-xl flex items-center justify-center md:w-full w-24 ${scoreBgColor} [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
    ${
        isArchived
            ? `<span class="font-semibold text-white">${
                  row.score || `<div class="text-gray-700">No score</div`
              }</span> `
            : `<input type="number" name="scores[${row.studentID}]" 
                     value="${row.score || ""}" 
                     class="score-input font-semibold w-10 text-center bg-gray-300 text-gray-700" 
                     data-student-id="${row.studentID}">`
    }
    <span class="font-semibold">/${totalItem}</span>
</div>
                                    <input type="hidden" name="totalItem" value="${totalItem}" readonly>
                                    <input type="hidden" name="passingItem" value="${passingItem}" readonly>      
                                `;
                        }

                        return formHTML;
                    },
                    className: "text-center",
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        if (storedAssessmentType === "attendance") {
                            return "";
                        }

                        const remarkValue = row.remarks || "";
                        return `
  <div class="remark-container flex items-center justify-center">
    ${
        isArchived
            ? `<span class="font-semibold text-gray-500">${
                  remarkValue || " "
              }</span>`
            : `<input 
                   type="text" 
                   name="remarks[${row.studentID}]" 
                   value="${remarkValue}" 
                   maxlength="20" 
                   class="remark-input font-semibold text-center bg-gray-300 text-gray-700 w-3/4" 
                   data-student-id="${row.studentID}">
              `
    }
</div>

`;
                    },
                    className: "text-center",
                    visible: storedAssessmentType !== "attendance",
                },

                {
                    data: null,
                    render: function (data, type, row) {
                        let viewableHTML = "";
                        if (row.isRawScoreViewable === 1) {
                            viewableHTML =
                                '<span class="bg-green-500 text-white p-2 rounded-md">Published</span>';
                        } else if (row.isRawScoreViewable === 0) {
                            viewableHTML = `
                                <div class="relative group flex justify-center items-center">
                                    <button class="publish-indiv cursor-pointer" data-assessment-id="${assessmentID}" data-class-record-id="${classRecordID}" data-student-id="${row.studentID}">
                                    <input type="hidden" name="gradingType"
                                                            value="${gradingDistributionType}" />
                                                        <input type="hidden" name="gradingTerm"
                                                            value="${term}" />
                                        <span class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                      
                                    
                                    </button>
                                   
                            <div class="absolute bottom-full left-1/2 transform hidden group-hover:block -translate-x-1/2 z-40 mb-4">
    <div class="flex justify-center items-center text-center transition-all duration-300 relative ">
        <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Publish Score</span>
        <div
            class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
        </div>
    </div>
</div>
                               
                            `;
                        }
                        return viewableHTML;
                    },
                    className: "text-center",
                },
            ],
            // responsive: true,
            scrollX: isMobile,
            pagingType: "simple",
            paging: true,
            order: [],
            columnDefs: [
                {
                    targets: [0],
                    orderable: false,
                },
            ],
            initComplete: function () {
                if (storedAssessmentType === "attendance") {
                    dataTable.column(4).visible(false);
                } else {
                    dataTable.column(0).visible(true);
                }
            },
        });

        $(".publish-score-btn").on("click", function (event) {
            event.preventDefault();

            let selectedStudentIDs = [];
            var assessmentID = $(this).data("assessment-id");
            var classRecordID = $(this).data("class-record-id");

            $('input[type="checkbox"].score_checkbox:checked').each(
                function () {
                    const studentID = $(this).data("student-id");
                    selectedStudentIDs.push(studentID);
                }
            );

            $("#selectedStudentIDs").val(JSON.stringify(selectedStudentIDs));

            if (!classRecordID || !assessmentID) {
                Swal.fire({
                    title: "Error!",
                    text: "Missing class record or assessment information.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return;
            }

            if (selectedStudentIDs.length > 0) {
                $.ajax({
                    url: "/notify-students-scores-batch",
                    type: "POST",
                    data: {
                        classRecordID: classRecordID,
                        selectedStudentIDs: selectedStudentIDs,
                        selectedAssessIDs: [assessmentID],
                        gradingType: $('input[name="gradingType"]').val(),
                        gradingTerm: $('input[name="gradingTerm"]').val(),
                        _token: $('meta[name="csrf-token"]').attr("content"), // Ensure CSRF token
                    },
                    beforeSend: function () {
                        $("#send-email-loader").removeClass("hidden");
                        $("body").addClass("no-scroll");
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
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: "Error!",
                            text: xhr.responseJSON
                                ? xhr.responseJSON.message
                                : "An error occurred while sending the notification.",
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    },
                    complete: function () {
                        $("#send-email-loader").addClass("hidden");
                        $("body").removeClass("no-scroll");
                    },
                });
            } else {
                Swal.fire({
                    title: "Error!",
                    text: "No student selected.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            }
        });

        $(document).on("click", ".publish-indiv", function () {
            var assessmentID = $(this).data("assessment-id");
            var classRecordID = $(this).data("class-record-id");
            var studentID = $(this).data("student-id");
            const gradingType = $('input[name="gradingType"]').val();
            const gradingTerm = $('input[name="gradingTerm"]').val();

            // Show confirmation dialog
            Swal.fire({
                title: "Publish score?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Publish",
                cancelButtonText: "No, cancel",
            }).then((result) => {
                $("#send-email-loader").removeClass("hidden");
                $("body").addClass("no-scroll");

                if (result.isConfirmed) {
                    $.ajax({
                        url: "/notify-students-scores-individual",
                        method: "POST",
                        data: {
                            selectedAssessIDs: assessmentID,
                            classRecordID: classRecordID,
                            studentID: studentID,
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
                            $("#send-email-loader").addClass("hidden");
                            $("body").removeClass("no-scroll");
                        },
                    });
                } else {
                    // Reset loader if action was canceled
                    $("#send-email-loader").addClass("hidden");
                    $("body").removeClass("no-scroll");
                }
            });
        });

        // $(document).ready(function () {
        //     let savedStudents = new Set();

        //     $("#assessmentDetailsTable").on(
        //         "change",
        //         'input[name^="score"]',
        //         function () {
        //             const $input = $(this);
        //             const studentID = $input.data("student-id");

        //             if (savedStudents.has(studentID)) {
        //                 return;
        //             }

        //             const totalItem = parseInt($("#total-item").text());
        //             let score = $input.val();
        //             let isValid = true;

        //             score = score.trim() === "" ? null : parseInt(score);

        //             if (score !== null && score > totalItem) {
        //                 score = 0;
        //                 $input.val(score);
        //                 $input.addClass("border-red-500");
        //                 isValid = false;
        //             } else {
        //                 $input.removeClass("border-red-500");
        //             }

        //             if (!isValid) {
        //                 Swal.fire({
        //                     icon: "error",
        //                     title: `The score for Student ID: ${studentID} was adjusted to 0 as it exceeded ${totalItem}.`,
        //                     toast: true,
        //                     position: "top-end",
        //                     showConfirmButton: false,
        //                     timer: 4000,
        //                 });
        //                 return;
        //             }

        //             const scores = {};
        //             scores[studentID] = score;

        //             const assessmentID = $('input[name="assessmentID"]').val();
        //             const classRecordID = $(
        //                 'input[name="classRecordIDScores"]'
        //             ).val();

        //             const data = {
        //                 _token: $('meta[name="csrf-token"]').attr("content"),
        //                 assessmentID: assessmentID,
        //                 classRecordID: classRecordID,
        //                 scores: scores,
        //             };

        //             $.ajax({
        //                 url: "/store-scores",
        //                 type: "POST",
        //                 data: data,
        //                 success: function (response) {
        //                     Swal.fire({
        //                         icon: "success",
        //                         title: response.message,
        //                         toast: true,
        //                         position: "top-end",
        //                         showConfirmButton: false,
        //                         timer: 4000,
        //                     });

        //                     savedStudents.add(studentID);

        //                     const $nextInput = $input
        //                         .closest("tr")
        //                         .next()
        //                         .find('input[name^="score"]');
        //                     const nextStudentID = $nextInput.data("student-id");

        //                     dataTable.ajax.reload(() => {
        //                         if (nextStudentID) {
        //                             const $restoredInput = $(
        //                                 `input[name^="score"][data-student-id="${nextStudentID}"]`
        //                             );
        //                             if ($restoredInput.length > 0) {
        //                                 $restoredInput[0].focus();
        //                             }
        //                         }
        //                     });
        //                 },
        //                 error: function (xhr) {
        //                     console.error("AJAX Error:", xhr.responseText);
        //                     Swal.fire({
        //                         icon: "error",
        //                         title:
        //                             xhr.responseJSON?.message ||
        //                             "An error occurred.",
        //                         toast: true,
        //                         position: "top-end",
        //                         showConfirmButton: false,
        //                         timer: 4000,
        //                     });
        //                 },
        //             });
        //         }
        //     );

        //     $("#assessmentDetailsTable").on(
        //         "keydown",
        //         'input[name^="score"]',
        //         function (e) {
        //             const $input = $(this);

        //             if (
        //                 (e.key === "ArrowDown" ||
        //                     e.key === "ArrowUp" ||
        //                     e.key === "Enter") &&
        //                 !savedStudents.has($input.data("student-id"))
        //             ) {
        //                 $input.trigger("change");
        //                 e.preventDefault();
        //             }
        //         }
        //     );
        // });

        // $(document).ready(function () {
        //     $("#assessmentDetailsTable").on(
        //         "change",
        //         'input[name^="scores"]',
        //         function () {
        //             const $input = $(this);
        //             const studentID = $input.data("student-id");

        //             const totalItem = parseInt(
        //                 $('input[name="totalItem"]').val()
        //             );
        //             let score = $input.val().trim();
        //             score = score === "" ? null : parseInt(score);

        //             if (score !== null && score > totalItem) {
        //                 score = 0;
        //                 $input.val(score).addClass("border-red-500");
        //                 Swal.fire({
        //                     icon: "error",
        //                     title: `The score for Student ID: ${studentID} was adjusted to 0 as it exceeded ${totalItem}.`,
        //                     toast: true,
        //                     position: "top-end",
        //                     showConfirmButton: false,
        //                     timer: 4000,
        //                 });
        //                 return;
        //             } else {
        //                 $input.removeClass("border-red-500");
        //             }

        //             const scores = {};
        //             scores[studentID] = score;

        //             const assessmentID = $('input[name="assessmentID"]').val();
        //             const classRecordID = $(
        //                 'input[name="classRecordIDScores"]'
        //             ).val();

        //             if (!assessmentID || !classRecordID) {
        //                 console.error("Missing required parameters.");
        //                 return;
        //             }

        //             const data = {
        //                 _token: $('meta[name="csrf-token"]').attr("content"),
        //                 assessmentID,
        //                 classRecordID,
        //                 scores,
        //             };

        //             $.ajax({
        //                 url: "/store-scores",
        //                 type: "POST",
        //                 data: data,
        //                 success: function (response) {
        //                     Swal.fire({
        //                         icon: "success",
        //                         title: response.message,
        //                         toast: true,
        //                         position: "top-end",
        //                         showConfirmButton: false,
        //                         timer: 4000,
        //                     });

        //                     const $nextInput = $input
        //                         .closest("tr")
        //                         .next()
        //                         .find('input[name^="score"]');
        //                     const nextStudentID = $nextInput.data("student-id");

        //                     dataTable.ajax.reload(() => {
        //                         if (nextStudentID) {
        //                             const $restoredInput = $(
        //                                 `input[name^="scores"][data-student-id="${nextStudentID}"]`
        //                             );
        //                             if ($restoredInput.length > 0) {
        //                                 $restoredInput[0].focus();
        //                             }
        //                         }
        //                     });
        //                 },
        //                 error: function (xhr) {
        //                     console.error("AJAX Error:", xhr.responseText);
        //                     Swal.fire({
        //                         icon: "error",
        //                         title:
        //                             xhr.responseJSON?.message ||
        //                             "An error occurred.",
        //                         toast: true,
        //                         position: "top-end",
        //                         showConfirmButton: false,
        //                         timer: 4000,
        //                     });
        //                 },
        //             });
        //         }
        //     );

        //     $("#assessmentDetailsTable").on(
        //         "keydown",
        //         'input[name^="scores"]',
        //         function (e) {
        //             const $input = $(this);
        //             let nextInput;
        //             let nextRow;

        //             if (
        //                 e.key === "ArrowDown" ||
        //                 e.key === "ArrowUp" ||
        //                 e.key === "Enter"
        //             ) {
        //                 e.preventDefault();

        //                 // Determine the next row and input
        //                 if (e.key === "ArrowDown" || e.key === "Enter") {
        //                     nextRow = $input.closest("tr").next("tr");
        //                 } else if (e.key === "ArrowUp") {
        //                     nextRow = $input.closest("tr").prev("tr");
        //                 }

        //                 if (nextRow && nextRow.length) {
        //                     nextInput = nextRow.find('input[name^="scores"]');
        //                 }

        //                 if (nextInput && nextInput.length) {
        //                     const nextStudentID = nextInput.data("student-id");

        //                     // Store the next input's student ID
        //                     dataTable.ajax.reload(() => {
        //                         if (nextStudentID) {
        //                             const $restoredInput = $(
        //                                 `input[name^="scores"][data-student-id="${nextStudentID}"]`
        //                             );
        //                             if ($restoredInput.length > 0) {
        //                                 $restoredInput.focus();
        //                             }
        //                         }
        //                     });
        //                 }
        //             }
        //         }
        //     );
        // });

        $(document).ready(function () {
            let focusedStudentID = null; // Track the currently focused student ID

            const focusInputByStudentID = (studentID) => {
                if (studentID) {
                    const $inputToFocus = $(
                        `input[name^="scores"][data-student-id="${studentID}"]`
                    );
                    if ($inputToFocus.length > 0) {
                        $inputToFocus.focus();
                    }
                }
            };

            const navigateToNextInput = ($currentInput, direction) => {
                let nextRow;
                if (direction === "down" || direction === "enter") {
                    nextRow = $currentInput.closest("tr").next("tr");
                } else if (direction === "up") {
                    nextRow = $currentInput.closest("tr").prev("tr");
                }

                if (nextRow && nextRow.length) {
                    const $nextInput = nextRow.find('input[name^="scores"]');
                    if ($nextInput.length > 0) {
                        focusedStudentID = $nextInput.data("student-id"); // Update focused student ID
                        return focusedStudentID;
                    }
                }
                return null;
            };

            // Handle score changes
            $("#assessmentDetailsTable").on(
                "change",
                'input[name^="scores"]',
                function () {
                    const $input = $(this);
                    const studentID = $input.data("student-id");
                    const totalItem = parseInt(
                        $('input[name="totalItem"]').val()
                    );
                    let score = $input.val().trim();
                    score = score === "" ? null : parseInt(score);

                    if (score !== null && score > totalItem) {
                        score = 0;
                        $input.val(score).addClass("border-red-500");
                        Swal.fire({
                            icon: "error",
                            title: `The score for Student ID: ${studentID} was adjusted to 0 as it exceeded ${totalItem}.`,
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 4000,
                        });
                        return;
                    } else {
                        $input.removeClass("border-red-500");
                    }

                    const scores = {};
                    scores[studentID] = score;

                    const assessmentID = $('input[name="assessmentID"]').val();
                    const classRecordID = $(
                        'input[name="classRecordIDScores"]'
                    ).val();

                    if (!assessmentID || !classRecordID) {
                        console.error("Missing required parameters.");
                        return;
                    }

                    const data = {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        assessmentID,
                        classRecordID,
                        scores,
                    };

                    // Save current focus for restoration
                    focusedStudentID = navigateToNextInput($input, "down");

                    $.ajax({
                        url: "/store-scores",
                        type: "POST",
                        data: data,
                        success: function (response) {
                            Swal.fire({
                                icon: "success",
                                title: response.message,
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 4000,
                            });

                            // Reload the table and restore focus
                            dataTable.ajax.reload(() => {
                                focusInputByStudentID(focusedStudentID);
                            });
                        },
                        error: function (xhr) {
                            console.error("AJAX Error:", xhr.responseText);
                            Swal.fire({
                                icon: "error",
                                title:
                                    xhr.responseJSON?.message ||
                                    "An error occurred.",
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 4000,
                            });
                        },
                    });
                }
            );

            // Handle navigation with Arrow keys and Enter
            $("#assessmentDetailsTable").on(
                "keydown",
                'input[name^="scores"]',
                function (e) {
                    const $input = $(this);

                    if (
                        e.key === "ArrowDown" ||
                        e.key === "ArrowUp" ||
                        e.key === "Enter"
                    ) {
                        e.preventDefault();

                        const direction =
                            e.key === "ArrowDown" || e.key === "Enter"
                                ? "down"
                                : "up";

                        focusedStudentID = navigateToNextInput(
                            $input,
                            direction
                        );

                        dataTable.ajax.reload(() => {
                            focusInputByStudentID(focusedStudentID);
                        });
                    }
                }
            );
        });

        $(document).ready(function () {
            $("#assessmentDetailsTable").on(
                "change",
                'input[name^="remarks"]',
                function () {
                    const $input = $(this);
                    const studentID = $input.data("student-id");
                    const remarkValue = $input.val().trim();
                    const scoreInput = $input
                        .closest("tr")
                        .find('input[name^="scores"]');
                    const score = scoreInput.val()
                        ? parseInt(scoreInput.val())
                        : null;

                    if (remarkValue.length > 20) {
                        Swal.fire({
                            icon: "error",
                            title: "Remark Too Long",
                            text: "Remarks cannot exceed 20 characters.",
                        });
                        $input.val("");
                        return;
                    }

                    const assessmentID = $('input[name="assessmentID"]').val();
                    const classRecordID = $(
                        'input[name="classRecordIDScores"]'
                    ).val();

                    const data = {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        assessmentID: assessmentID,
                        classRecordID: classRecordID,
                        scores: {
                            [studentID]: score,
                        },
                        remarks: {
                            [studentID]: remarkValue,
                        },
                    };

                    $.ajax({
                        url: "/save-remarks",
                        type: "POST",
                        data: data,
                        success: function (response) {
                            Swal.fire({
                                icon: "success",
                                title: response.message,
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 4000,
                            });
                        },
                        error: function (xhr, status, error) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                },
                            });
                            Toast.fire({
                                icon: "error",
                                title:
                                    xhr.responseJSON?.message ||
                                    "An error occurred.",
                            });
                        },
                    });
                }
            );

            $("#assessmentDetailsTable").on(
                "keydown",
                'input[name^="remarks"]',
                function (e) {
                    const $input = $(this);

                    if (
                        e.key === "ArrowDown" ||
                        e.key === "ArrowUp" ||
                        e.key === "Enter"
                    ) {
                        let nextInput;
                        let nextRow;

                        if (e.key === "ArrowDown") {
                            nextRow = $input.closest("tr").next("tr");
                        } else if (e.key === "ArrowUp") {
                            nextRow = $input.closest("tr").prev("tr");
                        }

                        nextInput = nextRow.find('input[name^="remarks"]');

                        if (nextInput.length) {
                            nextInput.focus();
                        }

                        $input.trigger("change");
                        e.preventDefault();
                    }
                }
            );
        });

        $(document).on("change", ".attendance-selector", function () {
            const studentID = $(this).data("student-id");
            const selectedScore = $(this).val();
            const assessmentID = $("input[name='assessmentID']").val();
            const classRecordID = $("input[name='classRecordID']").val();

            const data = {
                assessmentID: assessmentID,
                classRecordID: classRecordID,
                scores: {},
            };
            data.scores[studentID] = selectedScore;

            $.ajax({
                url: "/store-score-attendance",
                method: "POST",
                data: data,
                success: function (response) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                    });
                    Toast.fire({
                        icon: "success",
                        title: response.message,
                    });
                    dataTable.ajax.reload();
                },
                error: function (xhr) {
                    console.error("Error saving score:", xhr.responseText);
                },
            });
        });

        $("#assessmentFormAttendance button").on("click", function (e) {
            e.preventDefault();

            const classRecordID = $('input[name="classRecordID"]').val();
            const assessmentID = $('input[name="assessmentID"]').val();

            let selectedStudentIDs = getSelectedStudentIDs();
            let scores = {};
            const scoreValue = $(this).val();

            selectedStudentIDs.forEach((studentID) => {
                scores[studentID] = scoreValue;
            });

            // console.log("Selected Student IDs:", selectedStudentIDs);

            var data = {
                assessmentID: assessmentID,
                classRecordID: classRecordID,
                scores: scores,
            };

            $("#selectedStudentIDs").val(JSON.stringify(selectedStudentIDs));

            if (selectedStudentIDs.length > 0) {
                $.ajax({
                    url: "/store-score-attendance",
                    method: "POST",
                    data: data,
                    success: function (response) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            },
                        });
                        Toast.fire({
                            icon: "success",
                            title: response.message,
                        });
                        dataTable.ajax.reload();
                    },
                    error: function (error) {
                        console.error("Error updating attendance:", error);
                    },
                });
            } else {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });
                Toast.fire({
                    icon: "errror",
                    title: "Please select at least one student.",
                });
            }
        });

        function getSelectedStudentIDs() {
            const selectedStudentIDs = [];
            dataTable.rows().every(function () {
                const $row = $(this.node());
                const $checkbox = $row.find(
                    'input[type="checkbox"].score_checkbox'
                );

                if ($checkbox.prop("checked") && !$checkbox.prop("disabled")) {
                    const numberStudentID = $row
                        .find('input[type="number"]')
                        .data("student-id");
                    const attendanceStudentID = $row
                        .find(".attendance-selector")
                        .data("student-id");

                    if (numberStudentID) {
                        selectedStudentIDs.push(numberStudentID);
                    }
                    if (attendanceStudentID) {
                        selectedStudentIDs.push(attendanceStudentID);
                    }
                }
            });

            const uniqueStudentIDs = [...new Set(selectedStudentIDs)];

            $("#selectedStudentIDs, #selectedStudentIDsAttendance").val(
                uniqueStudentIDs.join(",")
            );
            return uniqueStudentIDs;
        }

        function togglePublishButton() {
            const storedAssessmentType = $("#type").text().trim();
            const selectedCount = getSelectedStudentIDs().length;
            const publishButton = $(".publish-score-btn");

            // Apply the logic when storedAssessmentType is attendance
            // if (storedAssessmentType !== "attendance" && selectedCount > 0) {
            //     publishButton.removeClass("hidden").addClass("block");
            // } else {
            //     publishButton.removeClass("block").addClass("hidden");
            // }

            // Log selected student IDs
            const classRecordID = $('input[name="classRecordID"]').val();
            const selectedStudentIDs = getSelectedStudentIDs();
            // console.log("Class Record ID:", classRecordID);
            // console.log("Selected Student IDs:", selectedStudentIDs);
            // console.log(
            //     "Number of selected students:",
            //     selectedStudentIDs.length
            // );
        }

        // Event listener for "Select All" checkbox
        // $("#score_select_all").on("click", function () {
        //     const isChecked = this.checked;

        //     if (isChecked) {
        //         dataTable.page.len(-1).draw();
        //     } else {
        //         dataTable.page.len(10).draw();
        //     }
        //     dataTable.rows().every(function () {
        //         const $row = $(this.node());
        //         $row.find(
        //             'input[type="checkbox"].score_checkbox:not(:disabled)'
        //         ).prop("checked", isChecked);
        //     });
        //     togglePublishButton();
        // });

        $("#score_select_all").on("click", function () {
            const isChecked = $(this).prop("checked");
            // console.log("Select All Checkbox State:", isChecked);

            dataTable.rows({ page: "current" }).every(function () {
                const $row = $(this.node());

                $row.find(
                    'input[type="checkbox"].score_checkbox:not(:disabled)'
                ).prop("checked", isChecked);

                // console.log(
                //     "Row Checkboxes:",
                //     $row
                //         .find('input[type="checkbox"].score_checkbox')
                //         .map(function () {
                //             return {
                //                 id: $(this).data("student-id"),
                //                 checked: this.checked,
                //             };
                //         })
                //         .get()
                // );
            });

            togglePublishButton();
        });

        $("#assessmentDetailsTable").on(
            "change",
            'input[type="checkbox"].score_checkbox',
            function () {
                togglePublishButton();

                const totalRows = dataTable.rows().count();
                const selectedRows = getSelectedStudentIDs().length;

                const selectAllCheckbox = $("#score_select_all").get(0);
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

        $(".attendance-selector").on("change", function () {
            var form = $("#assessmentFormAttendance");
            $.ajax({
                url: form.attr("action"),
                method: form.attr("method"),
                data: form.serialize(),
                success: function (response) {},
                error: function (xhr) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Please select at least one student.",
                    });
                },
            });
        });

        let isPublishedUpdated = false;

        function checkAllPublishedAndUpdate() {
            if (isPublishedUpdated) return;

            const rowsData = dataTable.rows().data().toArray();

            const allPublished = rowsData.every(
                (row) => row.isRawScoreViewable === 1
            );

            if (allPublished) {
                isPublishedUpdated = true;

                $.ajax({
                    url: "/update-assessment-status",
                    type: "POST",
                    data: {
                        assessmentID: assessmentID,
                        classRecordID: classRecordID,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        // console.log(
                        //     "Assessment status updated successfully:",
                        //     response
                        // );
                        dataTable.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        console.error(
                            "Error updating assessment status:",
                            xhr.responseText
                        );
                        isPublishedUpdated = false;
                    },
                });
            }
        }

        dataTable.on("draw", checkAllPublishedAndUpdate);
    }
});
