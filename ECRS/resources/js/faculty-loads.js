document
    .getElementById("insertJSON")
    .addEventListener("click", async function () {
        document
            .getElementById("loader-modal-submit")
            .classList.remove("hidden");
        document.getElementById("getJson").classList.remove("hidden");
        $("body").addClass("no-scroll");

        try {
            const response = await fetch("/fetch-pupt-faculty-schedules", {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const jsonData = await response.json();

            const sendResponse = await fetch("/store-classrecord-integration", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ pupt_faculty_schedules: jsonData }),
            });

            const sendResult = await sendResponse.json();

            if (sendResponse.ok) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: sendResult.message || "Data sent successfully!",
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text:
                        sendResult.error ||
                        "Error occurred while processing your request.",
                }).then(() => {
                    window.location.reload();
                });
            }
        } catch (error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Unexpected Error",
                text:
                    error.message ||
                    "An error occurred while processing your request.",
            }).then(() => {
                window.location.reload();
            });
        } finally {
            document
                .getElementById("loader-modal-submit")
                .classList.add("hidden");
            document.getElementById("getJson").classList.add("hidden");
            $("body").removeClass("no-scroll");
        }
    });

$(document).ready(function () {
    const facultySchedulesTable = document.querySelector(
        "#facultySchedulesTable"
    );

    if (facultySchedulesTable) {
        $(facultySchedulesTable).DataTable({
            responsive: true,
            pagingType: "simple",
            paging: true,
            order: [],
            columnDefs: [
                {
                    targets: [],
                    orderable: false,
                },
            ],
            ajax: {
                url: "/get-faculty-schedules",
                method: "GET",
                dataType: "json",
                dataSrc: function (json) {
                    // console.log(json); // Verify the structure of the returned data

                    // Extract semester and academic year
                    const semester = json.semester;
                    const academicYearStart = json.academic_year_start;
                    const academicYearEnd = json.academic_year_end;

                    // Log values to check
                    // console.log("Semester:", semester);
                    // console.log("Academic Year Start:", academicYearStart);
                    // console.log("Academic Year End:", academicYearEnd);

                    // Construct the semester and academic year display
                    if (semester && academicYearStart && academicYearEnd) {
                        let semesterText = "N/A";
                        if (semester === 1) {
                            semesterText = "1st Semester";
                        } else if (semester === 2) {
                            semesterText = "2nd Semester";
                        } else if (semester === 3) {
                            semesterText = "Summer Semester";
                        }

                        // Select the elements in the details-section
                        const semesterSyText = `${semesterText} SY ${academicYearStart} - ${academicYearEnd}`;
                        $("#semester-sy-text").text(semesterSyText);
                    }

                    // Process and return faculty data for DataTable
                    const rows = [];
                    json.faculties.forEach((faculty) => {
                        faculty.schedules.forEach((schedule) => {
                            rows.push({
                                full_name: `${faculty.first_name} ${
                                    faculty.middle_name || ""
                                } ${faculty.last_name}`,
                                faculty_code: faculty.faculty_code,
                                program_code: schedule.program_code,
                                course_title: schedule.course_details.course_title,
                                course_code: schedule.course_details.course_code,
                                room_code: schedule.room_code,
                                day: schedule.day,
                                start_time: schedule.start_time,
                                end_time: schedule.end_time,
                                year_level: schedule.year_level,
                                section_name: schedule.section_name,
                            });
                        });
                    });

                    return rows;
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            },
            columns: [
                {
                    data: "full_name",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "faculty_code",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "course_code",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "course_title",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "program_code",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: "day",
                    className: "text-center",
                    render: (data) => data || "N/A",
                },
                {
                    data: null,
                    className: "text-center",
                    render: (data, type, row) => {
                        const startTime = row.start_time || "N/A"; 
                        const endTime = row.end_time || "N/A"; 
                        return `${startTime} - ${endTime}`; 
                    }
                },

                {
                    data: null,
                    className: "text-center",
                    render: (data, type, row) => {
                        const year = row.year_level || "N/A"; 
                        const section = row.section_name || "N/A"; 
                        return `${year} - ${section}`; 
                    }
                }
            ]
        });
    }
});
