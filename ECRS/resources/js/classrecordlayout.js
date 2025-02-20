$(document).ready(function () {
    let currentTerm = null;
    let currentAssessmentType = null;

    // General URL update function
    // function updateUrl(gradingDistributionType, assessmentType) {
    //     const url = `/faculty/class-record/${gradingDistributionType}/${assessmentType}`;
    //     window.location.href = url;
    //     console.log("Redirecting to:", url);
    // }

    function updateUrl(gradingDistributionType, assessmentType) {
        // Replace spaces with hyphens to make it URL-safe
        const formattedType = gradingDistributionType.replace(/\s+/g, "-");

        const url = `/faculty/class-record/${formattedType}/${assessmentType}`;
        window.location.href = url;
        // console.log("Redirecting to:", url);
    }

    // Fetch assessments via AJAX and populate selector
    function fetchAssessments(classRecordID, term) {
        return new Promise((resolve, reject) => {
            const url = `/get-assessments/${classRecordID}/${term}`;
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    $("#section-selector").empty().data("term", term);

                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach((assessmentType) => {
                            const lowercaseAssessmentType =
                                assessmentType.toLowerCase();
                            $("#section-selector").append(
                                `<option value="${lowercaseAssessmentType}">${assessmentType}</option>`
                            );
                        });

                        // Always add "Grades" option
                        $("#section-selector").append(
                            '<option value="student-grades">Grades</option>'
                        );

                        // Resolve with the first assessment type
                        const firstAssessmentType = response[0].toLowerCase();
                        resolve(firstAssessmentType);
                    } else {
                        console.warn(
                            "No assessments found or invalid response."
                        );
                        $("#section-selector").append(
                            "<option disabled>No assessments available</option>"
                        );
                        resolve(null);
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching assessments:", xhr);
                    reject(xhr);
                },
            });
        });
    }

    // Initialize the assessment type and update the session
    function initializeAssessmentType(
        classRecordID,
        term,
        firstAssessmentType
    ) {
        if (currentTab !== "student-info") {
            $.ajax({
                url: "/get-assessment-type", // Route to get the assessment type from session
                type: "GET",
                success: function (response) {
                    const sessionAssessmentType = response.assessmentType;
                    const sessionGradingType = response.selectedTab;
                    const storedType = localStorage.getItem(
                        "currentAssessmentType"
                    );

                    const assessmentTypeToUse =
                        storedType ||
                        sessionAssessmentType ||
                        firstAssessmentType;
                    $("#section-selector").val(assessmentTypeToUse);
                    localStorage.setItem(
                        "currentAssessmentType",
                        assessmentTypeToUse
                    );

                    // updateUrl(sessionGradingType, assessmentTypeToUse);
                },
                error: function (xhr) {
                    console.error(
                        "Error fetching assessment type from session:",
                        xhr
                    );
                },
            });
        } else {
            // const defaultTab = $('[data-type="student-info"]');
            // defaultTab.addClass("bg-red-900 text-white font-bold");
            // const semGradeTab = $('[data-type="semester-grade"]');
            // semGradeTab.addClass("bg-red-900 text-white font-bold");
        }
    }

    const urlPath = window.location.pathname.split("/");
    const currentTab = urlPath[urlPath.length - 1];
    const currentTab2 = urlPath[urlPath.length - 2];

    // console.log(currentTab2);

    if (currentTab === "details") {
        const gradingDistributionType = urlPath[urlPath.length - 3]; 
        const activeTab = $(`[data-type="${gradingDistributionType}"]`);
        // $("#sem-grade-tab").addClass("hidden"); // Hide the sem-grade-tab for "details"

        if (activeTab.length) {
            activeTab.addClass(
                "bg-red-900 text-white  font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C]"
            );

            activeTab.removeClass(
                "bg-white"
            );
            const term = activeTab.data("term");
            const classRecordID = activeTab.data("class-record-id");

            fetchAssessments(classRecordID, term).then(
                (firstAssessmentType) => {
                    initializeAssessmentType(
                        classRecordID,
                        term,
                        firstAssessmentType
                    );
                }
            );
        }
    } else {
        const activeTab = $(`[data-type="${currentTab2}"]`);
        // $(".tab div").addClass("dark:bg-white");

        if (activeTab.length) {
            activeTab.addClass(
                "bg-red-900 text-white  font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C]"
            );
            activeTab.removeClass("bg-white");
            const term = activeTab.data("term");
            const classRecordID = activeTab.data("class-record-id");

            fetchAssessments(classRecordID, term).then(
                (firstAssessmentType) => {
                    initializeAssessmentType(
                        classRecordID,
                        term,
                        firstAssessmentType
                    );
                }
            );
        } else if (currentTab === "student-info") {
            const defaultTab = $('[data-type="student-info"]');
            defaultTab.addClass(
                "bg-red-900 text-white font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C]"
            );
            defaultTab.removeClass("bg-white");
        } else {
            $("#sem-grade-tab").removeClass("hidden");
            const semGradeTab = $('[data-type="semester-grade"]');
            semGradeTab.addClass(
                "bg-red-900 text-white  font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C]"
            );
            semGradeTab.removeClass("bg-white");
        }
    }

    if (currentTab === "grades") {
        const gradingDistributionType = urlPath[urlPath.length - 2];
        const activeTab = $(`[data-type="${gradingDistributionType}"]`);
        $("#sem-grade-tab").removeClass("hidden");

        if (activeTab.length) {
            activeTab.addClass(
                "bg-red-900 dark:text-white text-black font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C]"
            );
            const term = activeTab.data("term");
            const classRecordID = activeTab.data("class-record-id");

            fetchAssessments(classRecordID, term).then(
                (firstAssessmentType) => {
                    initializeAssessmentType(
                        classRecordID,
                        term,
                        firstAssessmentType
                    );
                }
            );
        }
    } else if (currentTab === "semester-grade") {
        $("#sem-grade-tab").removeClass("hidden");
        const semGradeTab = $('[data-type="semester-grade"]');
        semGradeTab.addClass(
            "bg-red-900 text-white  font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C]"
        );
    }

    $("#section-selector").on("change", function () {
        const selectedValue = $(this).val(); 
        const term = $(this).data("term");
        const urlPath = window.location.pathname.split("/");

        // console.log(selectedValue);

        const hasDetails = urlPath[urlPath.length - 1] === "details";
        const gradingDistributionType = hasDetails
            ? urlPath[urlPath.length - 3]
            : urlPath[urlPath.length - 2];

        if (selectedValue === "student-grades") {
            window.location.href = `/faculty/class-record/${gradingDistributionType}/grades`;

            currentAssessmentType = selectedValue;
            localStorage.setItem(
                "currentAssessmentType",
                currentAssessmentType
            );

            $.ajax({
                url: "/store-assessment-type",
                type: "POST",
                data: {
                    assessmentType: currentAssessmentType,
                    term: term,
                    selectedTab: gradingDistributionType,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
             
                },
                error: function (xhr) {
                    console.error("Error storing assessment type:", xhr);
                },
            });
        } else {
            currentAssessmentType = selectedValue;
            localStorage.setItem(
                "currentAssessmentType",
                currentAssessmentType
            );

            $.ajax({
                url: "/store-assessment-type",
                type: "POST",
                data: {
                    assessmentType: currentAssessmentType,
                    term: term,
                    selectedTab: gradingDistributionType,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    updateUrl(gradingDistributionType, currentAssessmentType);
                },
                error: function (xhr) {
                    console.error("Error storing assessment type:", xhr);
                },
            });
        }
    });

    $(".tab div").on("click", function (event) {
        event.preventDefault();
        $(".tab div").removeClass(
            "bg-red-900 text-white font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C] dark:bg-white"
        );

        $(".tab div").addClass("bg-white dark:bg-[#404040]");

        const selectedTab = $(this).data("type");
        const term = $(this).data("term");
        const classRecordID = $(this).data("class-record-id");

        $(this).addClass(
            "bg-red-900 text-white  font-bold border-red-900 dark:bg-[#CCAA2C] dark:border-[#CCAA2C]"
        );

        $(this).removeClass("bg-white dark:bg-[#404040]");

        // console.log(selectedTab);

        if (selectedTab === "student-info") {
            window.location.href = `/faculty/class-record/student-info`;
        } else if (selectedTab === "semester-grade") {
            window.location.href = `/faculty/class-record/semester-grade`;
        } else {
           
            fetchAssessments(classRecordID, term).then(
                (firstAssessmentType) => {
                    $("#section-selector").val(firstAssessmentType);
                    localStorage.setItem(
                        "currentAssessmentType",
                        firstAssessmentType
                    );

                    $.ajax({
                        url: "/store-assessment-type",
                        type: "POST",
                        data: {
                            assessmentType: firstAssessmentType,
                            term: term,
                            selectedTab: selectedTab,
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function () {
                            updateUrl(selectedTab, firstAssessmentType);
                        },
                        error: function (xhr) {
                            console.error(
                                "Error storing assessment type:",
                                xhr
                            );
                        },
                    });
                }
            );
        }
    });
});
