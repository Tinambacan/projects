$(document).ready(function () {
    var ctx = document.getElementById("students-chart").getContext("2d");
    var studentsChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Passed", "Failed"],
            datasets: [
                {
                    data: [0, 0],
                    backgroundColor: ["#4CAF50", "#F44336"],
                    borderColor: ["#fff", "#fff"],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "top",
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.label + ": " + tooltipItem.raw;
                        },
                    },
                },
            },
        },
    });

    var ctx2 = document.getElementById("students-chart-bar").getContext("2d");
    var studentsChartBar = new Chart(ctx2, {
        type: "bar", // Change type to "bar"
        data: {
            labels: ["Passed", "Failed"],
            datasets: [
                {
                    label: "Number of Students",
                    data: [0, 0], // Placeholder data
                    backgroundColor: ["#4CAF50", "#F44336"],
                    borderColor: ["#fff", "#fff"],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "top",
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.label + ": " + tooltipItem.raw;
                        },
                    },
                },
            },
            scales: {
                x: {
                    stacked: true, // Stack bars for better comparison
                },
                y: {
                    beginAtZero: true,
                    stacked: true, // Stack bars for better comparison
                },
            },
        },
    });

    function fetchClassRecords(professorId) {
        $.ajax({
            url: "/get-class-records-dashboard/" + professorId,
            type: "GET",
            dataType: "json",
            success: function (data) {
                // Clear all selectors and reset total students count
                $("#course-selector")
                    .empty()
                    .append('<option value="">Select Course</option>');
                $("#acad-selector")
                    .empty()
                    .append('<option value="">Select Academic Year</option>');
                $("#sem-selector")
                    .empty()
                    .append('<option value="">Select Semester</option>');
                $("#program-selector")
                    .empty()
                    .append('<option value="">Select Program</option>');
                $("#total-students").text("0");

                if (data.classRecords) {
                    // Use a Set to store unique course names
                    const uniqueCourses = new Map();

                    $.each(data.classRecords, function (key, value) {
                        if (!uniqueCourses.has(value.courseId)) {
                            uniqueCourses.set(value.courseId, value.courseName);
                        }
                    });

                    // Append unique courses to the dropdown
                    uniqueCourses.forEach((courseName, courseId) => {
                        $("#course-selector").append(
                            '<option value="' +
                                courseId +
                                '">' +
                                courseName +
                                "</option>"
                        );
                    });
                }
            },
            error: function () {
                // Handle errors if needed
            },
        });
    }

    function populateAcademicYears(years) {
        $("#acad-selector")
            .empty()
            .append('<option value="">Select Academic Year</option>');
        years.forEach(function (year) {
            $("#acad-selector").append(
                '<option value="' + year + '">' + year + "</option>"
            );
        });
    }

    function populateSemesters(semesters) {
        $("#sem-selector")
            .empty()
            .append('<option value="">Select Semester</option>');
        semesters.forEach(function (sem) {
            $("#sem-selector").append(
                '<option value="' +
                    sem +
                    '">' +
                    (sem == 1 ? "First" : sem == 2 ? "Second" : "Summer") +
                    "</option>"
            );
        });
    }

    function populatePrograms(programs) {
        $("#program-selector")
            .empty()
            .append('<option value="">Select Program</option>');
        programs.forEach(function (program) {
            $("#program-selector").append(
                '<option value="' +
                    program.programId +
                    '">' +
                    program.programName +
                    "</option>"
            );
        });
    }

    function fetchAcademicYearsAndSemesters(courseId) {
        var professorId = $("#prof-selector").val();
        if (professorId && courseId) {
            $.ajax({
                url: "/get-class-records-by-course-and-semester",
                type: "GET",
                dataType: "json",
                data: { courseId: courseId, professorId: professorId },
                success: function (data) {
                    var academicYears = new Set();
                    var semesters = new Set();

                    $.each(data.classRecords, function (key, value) {
                        academicYears.add(value.schoolYear);
                        semesters.add(value.semester);
                    });

                    populateAcademicYears(Array.from(academicYears));
                    populateSemesters(Array.from(semesters));

                    // Reset the total students count
                    $("#total-students").text("0");
                },
            });
        } else {
            $("#acad-selector")
                .empty()
                .append('<option value="">Select Academic Year</option>');
            $("#sem-selector")
                .empty()
                .append('<option value="">Select Semester</option>');

            // Reset the total students count
            $("#total-students").text("0");
        }
    }

    // function fetchPrograms(courseId, semester, schoolYear) {
    //     var professorId = $("#prof-selector").val();
    //     $.ajax({
    //         url: "/get-class-records-by-course-semester-school-year",
    //         type: "GET",
    //         dataType: "json",
    //         data: {
    //             courseId: courseId,
    //             semester: semester,
    //             schoolYear: schoolYear,
    //             professorId: professorId,
    //         },
    //         success: function (data) {
    //             if (data.classRecords) {
    //                 var programs = data.classRecords.map(function (record) {
    //                     return {
    //                         programId: record.programId,
    //                         programName: record.programName,
    //                     };
    //                 });
    //                 populatePrograms(programs);

    //                 // Reset the total students count
    //                 $("#total-students").text("0");
    //             }
    //         },
    //     });
    // }

    function fetchPrograms(courseId, semester, schoolYear) {
        var professorId = $("#prof-selector").val();
        $.ajax({
            url: "/get-class-records-by-course-semester-school-year",
            type: "GET",
            dataType: "json",
            data: {
                courseId: courseId,
                semester: semester,
                schoolYear: schoolYear,
                professorId: professorId,
            },
            success: function (data) {
                if (data.classRecords) {
                    var programs = data.classRecords.map(function (record) {
                        return {
                            programId: record.programId,
                            programName: record.programName,
                        };
                    });
                    populatePrograms(programs);

                    // Calculate passed and failed counts
                    var totalStudents = 0;
                    var passedStudents = 0;
                    var failedStudents = 0;

                    data.classRecords.forEach(function (record) {
                        totalStudents += record.totalStudents;
                        passedStudents += record.passed;
                        failedStudents += record.failed;
                    });

                    // Update the UI with the counts
                    $("#total-students").text(totalStudents || "0");
                    $("#passed-count").text(passedStudents || "0");
                    $("#failed-count").text(failedStudents || "0");

                    // Update the charts with the new data
                    studentsChart.data.datasets[0].data = [
                        passedStudents,
                        failedStudents,
                    ];
                    studentsChart.update();

                    studentsChartBar.data.datasets[0].data = [
                        passedStudents,
                        failedStudents,
                    ];
                    studentsChartBar.update();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown);
                $("#total-students").text("0");
                $("#passed-count").text("0");
                $("#failed-count").text("0");
            },
        });
    }

    // function updateTotalStudents(
    //     professorId,
    //     courseId,
    //     academicYear,
    //     semester,
    //     programId
    // ) {
    //     if (professorId && courseId && academicYear && semester && programId) {
    //         $.ajax({
    //             url: "/get-class-records-dashboard/" + professorId,
    //             type: "GET",
    //             dataType: "json",
    //             data: {
    //                 courseId: courseId,
    //                 academicYear: academicYear,
    //                 semester: semester,
    //                 programId: programId,
    //             },
    //             success: function (data) {
    //                 // Find the total number of students for the selected filters
    //                 var totalStudents = 0;
    //                 if (data.classRecords) {
    //                     data.classRecords.forEach(function (record) {
    //                         totalStudents += record.totalStudents;
    //                     });
    //                 }
    //                 $("#total-students").text(totalStudents || "0");
    //             },
    //             error: function () {
    //                 $("#total-students").text("0");
    //             },
    //         });
    //     } else {
    //         $("#total-students").text("0");
    //     }
    // }

    function updateTotalStudents(
        professorId,
        courseId,
        academicYear,
        semester,
        programId
    ) {
        if (professorId && courseId && academicYear && semester && programId) {
            $.ajax({
                url: "/get-class-records-dashboard/" + professorId,
                type: "GET",
                dataType: "json",
                data: {
                    courseId: courseId,
                    academicYear: academicYear,
                    semester: semester,
                    programId: programId,
                },
                success: function (data) {
                    var totalStudents = 0;
                    var passedStudents = 0;
                    var failedStudents = 0;

                    if (data.classRecords) {
                        data.classRecords.forEach(function (record) {
                            totalStudents += record.totalStudents;
                            passedStudents += record.passed;
                            failedStudents += record.failed;
                        });
                    }

                    $("#total-students").text(totalStudents || "0");
                    $("#passed-count").text(passedStudents || "0");
                    $("#failed-count").text(failedStudents || "0");

                    studentsChart.data.datasets[0].data = [
                        passedStudents,
                        failedStudents,
                    ];
                    studentsChart.update();

                    studentsChartBar.data.datasets[0].data = [
                        passedStudents,
                        failedStudents,
                    ];
                    studentsChartBar.update();
                },
                error: function () {
                    $("#total-students").text("0");
                    $("#passed-count").text("0");
                    $("#failed-count").text("0");
                },
            });
        } else {
            $("#total-students").text("0");
            $("#passed-count").text("0");
            $("#failed-count").text("0");
        }
    }

    $("#course-selector").on("change", function () {
        var courseId = $(this).val();

        fetchAcademicYearsAndSemesters(courseId);
        // Reset the total students count
        $("#total-students").text("0");
        $("#passed-count").text("0");
        $("#failed-count").text("0");
    });

    $("#prof-selector").on("change", function () {
        var professorId = $(this).val();
        if (professorId) {
            fetchClassRecords(professorId);
        } else {
            $("#course-selector")
                .empty()
                .append('<option value="">Select Course</option>');
            $("#acad-selector")
                .empty()
                .append('<option value="">Select Academic Year</option>');
            $("#sem-selector")
                .empty()
                .append('<option value="">Select Semester</option>');
            $("#program-selector")
                .empty()
                .append('<option value="">Select Program</option>');
            $("#total-students").text("0");
            $("#passed-count").text("0");
            $("#failed-count").text("0");
        }
    });

    $("#acad-selector, #sem-selector").on("change", function () {
        var courseId = $("#course-selector").val();
        var semester = $("#sem-selector").val();
        var schoolYear = $("#acad-selector").val();
        fetchPrograms(courseId, semester, schoolYear);
    });

    $("#acad-selector, #sem-selector, #program-selector").on(
        "change",
        function () {
            var professorId = $("#prof-selector").val();
            var courseId = $("#course-selector").val();
            var academicYear = $("#acad-selector").val();
            var semester = $("#sem-selector").val();
            var programId = $("#program-selector").val();
            updateTotalStudents(
                professorId,
                courseId,
                academicYear,
                semester,
                programId
            );
        }
    );
});
