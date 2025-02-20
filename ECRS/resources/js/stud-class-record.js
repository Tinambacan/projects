$(document).ready(function () {
    $("#filterForm").on("submit", function (e) {
        e.preventDefault();

        var semester = $("#semester").val();
        var schoolYear = $("#school_year").val();

        $.ajax({
            url: "/stud-filter-class-records",
            type: "GET",
            data: {
                semester: semester,
                school_year: schoolYear,
            },
            success: function (response) {
                $("#classRecordsContainer").html(
                    $(response).find("#classRecordsContainer").html()
                );
            },
        });
    });
});

//Sorting
document.addEventListener("DOMContentLoaded", function () {
    const startYear = 2023;
    const endYear = 2050;

    function populateAcademicYear(selectID) {
        const selectElement = document.getElementById(selectID);
        if (selectElement) {
            for (let year = startYear; year <= endYear; year++) {
                let option = document.createElement("option");
                option.value = `${year}-${year + 1}`;
                option.textContent = `${year}-${year + 1}`;
                selectElement.appendChild(option);
            }
        }
    }

    // Populate academic year dropdown
    populateAcademicYear("academic-year");

    // Handle filtering by Academic Year and Semester
    document
        .getElementById("academic-year")
        .addEventListener("change", filterClassRecords);
    document
        .getElementById("semester")
        .addEventListener("change", filterClassRecords);

    function filterClassRecords() {
        const selectedYear = document.getElementById("academic-year").value;
        const selectedSemester = document.getElementById("semester").value;
        const records = document.querySelectorAll(".class-record");
        const container = document.querySelector(".class-record-container");
        let visibleRecords = 0;

        // console.log("Selected Year:", selectedYear);
        // console.log("Selected Semester:", selectedSemester);

        records.forEach((record) => {
            const schoolYearElement = record.querySelector(".school-year");
            const semesterElement = record.querySelector(".semester");

            // console.log("School Year Element:", schoolYearElement);
            // console.log("Semester Element:", semesterElement);

            const recordYear = schoolYearElement
                ? schoolYearElement.textContent.trim().split(": ")[1]
                : "";
            const recordSemester = semesterElement
                ? semesterElement.textContent
                      .trim()
                      .replace(/^Semester:\s*/, "")
                      .trim()
                : "";

            // console.log("Record Year:", recordYear);
            // console.log("Record Semester:", recordSemester);

            const semesterMapping = {
                "1st Semester": "1",
                "2nd Semester": "2",
                "Summer Semester": "3",
            };

            const yearMatch =
                selectedYear === "" || recordYear === selectedYear;
            const semesterMatch =
                selectedSemester === "" ||
                semesterMapping[recordSemester] === selectedSemester;

            // console.log("Year Match:", yearMatch);
            // console.log("Semester Match:", semesterMatch);

            if (yearMatch && semesterMatch) {
                record.style.display = "block";
                visibleRecords++;
            } else {
                record.style.display = "none";
            }
        });

        // console.log("Visible Records:", visibleRecords);
        const noResultsMessage = document.querySelector(".no-results-message");

        if (visibleRecords === 0) {
            noResultsMessage.classList.remove("hidden");
        } else {
            noResultsMessage.classList.add("hidden");
        }
    }
});
