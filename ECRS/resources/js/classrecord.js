$(document).ready(function () {
    function initializeDataTable(selector) {
        const $table = $(selector);
        if ($table.length) {
            $table.DataTable({
                responsive: true,
                pagingType: "simple",
                paging: true,
                order: [],
            });
        }
    }
    initializeDataTable("#myTable");

    var selectedView = localStorage.getItem("selectedView") || "grid";

    if (selectedView === "grid") {
        showGridView();
    } else {
        showTableView();
    }

    $("#grid-view-btn").on("click", function () {
        showGridView();
        localStorage.setItem("selectedView", "grid");
    });

    $("#table-view-btn").on("click", function () {
        showTableView();
        localStorage.setItem("selectedView", "list");
    });

    function showGridView() {
        $("#grid-view").removeClass("hidden");
        $(".grid-view-btn").removeClass("hidden");
        $(".table-view").addClass("hidden");
        $("#grid-view-btn")
            .addClass("text-red-900 dark:text-[#CCAA2C]")
            .removeClass("text-gray-300");
        $("#table-view-btn")
            .addClass("text-gray-500")
            .removeClass("text-red-900 dark:text-[#CCAA2C]");
    }

    function showTableView() {
        $(".table-view").removeClass("hidden");
        $(".grid-view-btn").addClass("hidden");
        $("#grid-view").addClass("hidden");
        $("#table-view-btn")
            .addClass("text-red-900 dark:text-[#CCAA2C]")
            .removeClass("text-gray-300");
        $("#grid-view-btn")
            .addClass("text-gray-500")
            .removeClass("text-red-900 dark:text-[#CCAA2C]");
    }

    $("#create-new").on("click", function () {
        $("#initial-options").addClass("hidden");
        $("#record-type-options").removeClass("hidden");
        $("#classrecord-information").addClass("hidden");
        $("#normal-grade-configuration").addClass("hidden");
        $("#header").removeClass("hidden");
        $("#p-add-classrecord").addClass("hidden");
        $("#p-type-classrecord").removeClass("hidden");
    });

    $("#create-btn, #create-btn2").on("click", function () {
        $("#modal").removeClass("hidden");
        // $("#initial-options").removeClass("hidden");
        // $("#record-type-options").addClass("hidden");
        $("#classrecord-information").removeClass("hidden");
        // $("#p-type-classrecord").addClass("hidden");
        // $("#p-add-classrecord").removeClass("hidden");
        $("#p-classrecord").removeClass("hidden");
        $("#normal-grade-configuration").addClass("hidden");
        $("#header").removeClass("hidden");
        $("body").addClass("no-scroll");
    });

    $("#normal-classrecord, #special-classrecord").on("click", function () {
        $("#classrecord-information").removeClass("hidden");
        $("#p-type-classrecord").addClass("hidden");
        $("#p-classrecord").removeClass("hidden");
        $("#record-type-options").addClass("hidden");
        $("#normal-grade-configuration").addClass("hidden");
        $("#header").addClass("hidden");
    });

    $("#close-btn, #header-close-btn").on("click", function () {
        $("#modal").addClass("hidden");
        $("body").removeClass("no-scroll");
    });

    $("#use-template").on("click", function () {
        $("#initial-options").addClass("hidden");
        $("#record-type-options").removeClass("hidden");
        $("#classrecord-information").addClass("hidden");
        $("#normal-grade-configuration").addClass("hidden");
    });

    $("#midterm, #final-back-btn").on("click", function () {
        // Hide final grade section and show midterm grade section
        $("#final-grade").addClass("hidden");
        $("#midterm-grade").removeClass("hidden");
        $("#finals").removeClass("hidden").removeClass("text-red-900");
        $("#midterm").addClass("text-red-900");
        $("#special-grade").addClass("hidden");

        // Update the stepper to deactivate step 4 and set styles
        const steps = document.querySelectorAll(".step-number");
        const borders = document.querySelectorAll(".step-border");

        if (steps.length >= 4 && borders.length >= 3) {
            // Deactivate step 4
            steps[3].classList.add("bg-gray-300", "text-gray-500");
            steps[3].classList.remove("bg-red-900", "text-white");

            // Gray out the border after step 3
            borders[2].classList.remove("bg-red-900");
            borders[2].classList.add("bg-gray-200");
        }
    });

    $("#finals").on("click", function () {
        $("#final-grade").removeClass("hidden");
        $("#midterm-grade").addClass("hidden");
        $("#finals").removeClass("hidden").addClass("text-red-900");
        $("#midterm").removeClass("text-red-900");
        $("#special-grade").addClass("hidden");
    });

    $("#edit-midterm, #edit-final-back-btn").on("click", function () {
        $("#edit-final-grade").addClass("hidden");
        $("#edit-midterm-grade").removeClass("hidden");
        $("#edit-finals").removeClass("hidden").removeClass("text-red-900");
        $("#edit-midterm").addClass("text-red-900");
        $("#edit-special-grade").addClass("hidden");
        const steps = document.querySelectorAll(".step-number");
        const borders = document.querySelectorAll(".step-border");

        if (steps.length >= 4 && borders.length >= 3) {
            // Deactivate step 4
            steps[3].classList.add("bg-gray-300", "text-gray-500");
            steps[3].classList.remove("bg-red-900", "text-white");

            // Gray out the border after step 3
            borders[2].classList.remove("bg-red-900");
            borders[2].classList.add("bg-gray-200");
        }
    });

    $("#edit-finals").on("click", function () {
        $("#edit-final-grade").removeClass("hidden");
        $("#edit-midterm-grade").addClass("hidden");
        $("#edit-finals").removeClass("hidden").addClass("text-red-900");
        $("#edit-midterm").removeClass("text-red-900");
        $("#edit-special-grade").addClass("hidden");
    });

    $(".modify-btn").on("click", function () {
        $(this).siblings(".modify-record").toggleClass("hidden");
    });

    $(document).on("click", function (event) {
        if (!$(event.target).closest(".modify-btn, .modify-record").length) {
            $(".modify-record").addClass("hidden");
        }
    });

    // $("#record-type-options div").on("click", function (event) {
    //     event.stopPropagation(); // Prevent event bubbling
    //     $("#record-type-options div").removeClass("bg-red-900 bg-selected");
    //     $(this).addClass("bg-selected"); // Mark the clicked option as selected

    //     // Log the clicked record type for debugging
    //     console.log("Clicked Record Type:", $(this).attr("data-value"));
    // });

    $("#back-btn").on("click", function () {
        $("#classrecord-information").addClass("hidden");
        $("#record-type-options").removeClass("hidden");
        $("#p-classrecord").addClass("hidden");
        $("#p-type-classrecord").removeClass("hidden");
    });

    // Back button click event for midterm
    $("#midterm-back-btn").on("click", function () {
        // Show the grade distribution section
        $("#grade-distribution").removeClass("hidden");

        // Hide the current sections
        $("#classrecord-information").addClass("hidden");
        $("#normal-grade-configuration").addClass("hidden");

        // Update the stepper to revert to step 2
        const steps = document.querySelectorAll(".step-number");
        const borders = document.querySelectorAll(".step-border");

        if (steps.length >= 3 && borders.length >= 2) {
            // Deactivate step 3
            steps[2].classList.add("bg-gray-300", "text-gray-500");
            steps[2].classList.remove("bg-red-900", "text-white");

            // Change the border back to default color
            borders[1].classList.remove("bg-red-900");
            borders[1].classList.add("bg-gray-200");

            // Reactivate step 2
            steps[1].classList.remove("bg-gray-300", "text-gray-500");
            steps[1].classList.add("bg-red-900", "text-white");
        }
    });

    $("#special-back-btn").on("click", function () {
        $("#final-grade").removeClass("hidden");
        $("#classrecord-information").addClass("hidden");
        $("#special-grade").addClass("hidden");
        const steps = document.querySelectorAll(".step-number");
        const borders = document.querySelectorAll(".step-border");

        if (steps.length >= 4 && borders.length >= 3) {
            // Deactivate step 5
            steps[4].classList.add("bg-gray-300", "text-gray-500");
            steps[4].classList.remove("bg-red-900", "text-white");

            // Gray out the border after step 3
            borders[3].classList.remove("bg-red-900");
            borders[3].classList.add("bg-gray-200");
        }
    });

    $("#edit-special-back-btn").on("click", function () {
        $("#edit-final-grade").removeClass("hidden");
        $("#classrecord-information").addClass("hidden");
        $("#edit-special-grade").addClass("hidden");
        const steps = document.querySelectorAll(".step-number");
        const borders = document.querySelectorAll(".step-border");

        if (steps.length >= 4 && borders.length >= 3) {
            // Deactivate step 5
            steps[4].classList.add("bg-gray-300", "text-gray-500");
            steps[4].classList.remove("bg-red-900", "text-white");

            // Gray out the border after step 3
            borders[3].classList.remove("bg-red-900");
            borders[3].classList.add("bg-gray-200");
        }
    });

    $("#edit-midterm-back-btn, #special-edit-back-btn").on(
        "click",
        function () {
            $("#edit-grade-distribution").removeClass("hidden");

            $("#edit-classrecord-information").addClass("hidden");
            $("#edit-normal-grade-configuration").addClass("hidden");
            const steps = document.querySelectorAll(".step-number");
            const borders = document.querySelectorAll(".step-border");

            if (steps.length >= 3 && borders.length >= 2) {
                // Deactivate step 3
                steps[2].classList.add("bg-gray-300", "text-gray-500");
                steps[2].classList.remove("bg-red-900", "text-white");

                // Change the border back to default color
                borders[1].classList.remove("bg-red-900");
                borders[1].classList.add("bg-gray-200");

                // Reactivate step 2
                steps[1].classList.remove("bg-gray-300", "text-gray-500");
                steps[1].classList.add("bg-red-900", "text-white");
            }
        }
    );

    $("#grade-config-back-btn").on("click", function () {
        $("#grade-distribution").addClass("hidden");
        $("#classrecord-information").removeClass("hidden");
        $("#next-btn").removeClass("hidden");

        // Stepper logic
        const step1 = $('[data-step="1"] .step-number');
        const step2 = $('[data-step="2"] .step-number');
        const stepBorder = $(".step-border");

        // Reset the border and step 2 appearance
        stepBorder.removeClass("bg-red-900").addClass("bg-gray-200"); // Reset the underline
        step2
            .removeClass("bg-red-900 text-white")
            .addClass("bg-gray-300 text-gray-500"); // Reset step 2
        step1
            .removeClass("bg-gray-300 text-gray-500")
            .addClass("bg-red-900 text-white"); // Make step 1 active
    });

    $("#edit-grade-config-back-btn").on("click", function () {
        $("#edit-grade-distribution").addClass("hidden");
        $("#edit-classrecord-information").removeClass("hidden");
        $("#edit-final-grade").removeClass("hidden");
        $("#edit-header-grade-configuration").removeClass("hidden");
        $("#editfinals").removeClass("hidden").removeClass("text-red-900");
        $("#edit-special-grade").addClass("hidden");
        $("#edit-next-btn").removeClass("hidden");
        const step1 = $('[data-step="1"] .step-number');
        const step2 = $('[data-step="2"] .step-number');
        const stepBorder = $(".step-border");

        // Reset the border and step 2 appearance
        stepBorder.removeClass("bg-red-900").addClass("bg-gray-200"); // Reset the underline
        step2
            .removeClass("bg-red-900 text-white")
            .addClass("bg-gray-300 text-gray-500"); // Reset step 2
        step1
            .removeClass("bg-gray-300 text-gray-500")
            .addClass("bg-red-900 text-white"); // Make step 1 active
    });

    // $("#final-next-btn").on("click", function () {
    //     $("#special-grade").removeClass("hidden");
    //     $("#midterm-grade").addClass("hidden");
    //     $("#final-grade").addClass("hidden");
    //     $("#classrecord-information").addClass("hidden");
    //     $("#grade-distribution").addClass("hidden");
    // });
});

//Grade Distribution:
function updateGradingNames(isEdit = false) {
    const prefix = isEdit ? "edit-" : "";
    const firstName = $(`.${prefix}first-grade-type-input`).val();
    const secondName = $(`.${prefix}second-grade-type-input`).val();
    const thirdName = $(`.${prefix}third-grade-type-input`).val();
    //console.log("Name: " + firstName, secondName, thirdName);

    // Set the text for each grading period
    $(`#${prefix}grading-period-1 p`).text(firstName || "1st Grading"); // Default text
    $(`#${prefix}grading-period-2 p`).text(secondName || "2nd Grading"); // Default text
    $(`#${prefix}grading-period-3 p`).text(thirdName || "3rd Grading"); // Default text

    $(`#${prefix}midterm-name`).text(
        (firstName || "Midterm") + " Grade Percentage"
    ); // Default text
    $(`#${prefix}final-name`).text(
        (secondName || "Finals") + " Grade Percentage"
    ); // Default text
    $(`#${prefix}special-name`).text(
        (thirdName || "Special") + " Grade Percentage"
    ); // Default text
}
$(
    ".first-grade-type-input, .second-grade-type-input, .third-grade-type-input"
).on("keyup change", function () {
    updateGradingNames(false);
});
$(
    ".edit-first-grade-type-input, .edit-second-grade-type-input, .edit-third-grade-type-input"
).on("keyup change", function () {
    updateGradingNames(true);
});

$(document).ready(function () {
    // Function to show/hide grading periods based on the selected grading period
    function handleGradingPeriodChange(isEdit = false) {
        const prefix = isEdit ? "edit-" : "";
        const selectedPeriod = $(`#${prefix}grading-period`).val();
        //console.log("Selected Period: " + selectedPeriod);
        // Hide all grading periods initially
        $(
            `#${prefix}first-grading, #${prefix}second-grading, #${prefix}third-grading`
        ).addClass("hidden");

        // Show relevant grading periods based on selection
        if (selectedPeriod === "1") {
            $(`#${prefix}first-grading`).removeClass("hidden");
            $(`#${prefix}grading-period-1`).removeClass("hidden");
        } else if (selectedPeriod === "2") {
            $(`#${prefix}first-grading, #${prefix}second-grading`).removeClass(
                "hidden"
            );
            $(
                `#${prefix}grading-period-1, #${prefix}grading-period-2`
            ).removeClass("hidden");
        } else if (selectedPeriod === "3") {
            $(
                `#${prefix}first-grading, #${prefix}second-grading, #${prefix}third-grading`
            ).removeClass("hidden");
            $(
                `#${prefix}grading-period-1, #${prefix}grading-period-2, #${prefix}grading-period-3`
            ).removeClass("hidden");
        }

        // Reset the total display
        $(`#${prefix}total-grade-distribution`).text("Total: 0%");
    }

    // Normal mode grading period change
    $("#grading-period").on("change", function () {
        handleGradingPeriodChange(false);
    });

    // Edit mode grading period change
    $("#edit-grading-period").on("change", function () {
        handleGradingPeriodChange(true);
    });

    // Function to calculate total grading percentage based on the selected period
    function calculateTotalPercentage(selectedPeriod, isEdit = false) {
        const prefix = isEdit ? "edit-" : "";
        const firstValue =
            parseInt($(`.${prefix}first-grade-distribution-input`).val()) || 0;
        const secondValue =
            parseInt($(`.${prefix}second-grade-distribution-input`).val()) || 0;
        const thirdValue =
            parseInt($(`.${prefix}third-grade-distribution-input`).val()) || 0;

        let total = 0;

        if (selectedPeriod === "1") {
            total = firstValue;
        } else if (selectedPeriod === "2") {
            total = firstValue + secondValue;
        } else if (selectedPeriod === "3") {
            total = firstValue + secondValue + thirdValue;
        }

        // Update the total grade distribution display
        $(`#${prefix}total-grade-distribution`).text("Total: " + total + "%");

        // Ensure the total doesn't exceed 100%
        if (total > 100) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Total Grading Distribution Percentage exceeds 100%. Please adjust the values!",
            });
        }

        return total;
    }

    // Function to validate grading input fields based on the selected period
    function validateGradingInputs(selectedPeriod, isEdit = false) {
        const prefix = isEdit ? "edit-" : "";
        const firstName = $(`.${prefix}first-grade-type-input`).val();
        const firstValue =
            parseInt($(`.${prefix}first-grade-distribution-input`).val()) || 0;
        const secondName = $(`.${prefix}second-grade-type-input`).val();
        const secondValue =
            parseInt($(`.${prefix}second-grade-distribution-input`).val()) || 0;
        const thirdName = $(`.${prefix}third-grade-type-input`).val();
        const thirdValue =
            parseInt($(`.${prefix}third-grade-distribution-input`).val()) || 0;

        let isValid = true;

        if (selectedPeriod === "1" && (!firstName || firstValue === 0)) {
            Swal.fire({
                icon: "error",
                title: "Input Error",
                text: "Please fill in the 1st Grading Name and Percentage!",
            });
            isValid = false;
        } else if (
            selectedPeriod === "2" &&
            (!firstName || firstValue === 0 || !secondName || secondValue === 0)
        ) {
            Swal.fire({
                icon: "error",
                title: "Input Error",
                text: "Please fill in the 1st and 2nd Grading Name and Percentage!",
            });
            isValid = false;
        } else if (
            selectedPeriod === "3" &&
            (!firstName ||
                firstValue === 0 ||
                !secondName ||
                secondValue === 0 ||
                !thirdName ||
                thirdValue === 0)
        ) {
            Swal.fire({
                icon: "error",
                title: "Input Error",
                text: "Please fill in the 1st, 2nd, and 3rd Grading Name and Percentage!",
            });
            isValid = false;
        }

        return isValid;
    }

    // Update total grading percentage on input changes (normal mode)
    $(
        ".first-grade-distribution-input, .second-grade-distribution-input, .third-grade-distribution-input"
    ).on("input", function () {
        const selectedPeriod = $("#grading-period").val();
        calculateTotalPercentage(selectedPeriod, false);
    });

    // Update total grading percentage on input changes (edit mode)
    $(
        ".edit-first-grade-distribution-input, .edit-second-grade-distribution-input, .edit-third-grade-distribution-input"
    ).on("input", function () {
        const selectedPeriod = $("#edit-grading-period").val();
        calculateTotalPercentage(selectedPeriod, true);
    });

    // Next button click event (normal mode)
    $("#grade-config-next-btn").on("click", function (e) {
        const selectedPeriod = $("#grading-period").val();
        const totalPercentage = calculateTotalPercentage(selectedPeriod, false);

        // Validate inputs and total percentage
        if (!validateGradingInputs(selectedPeriod, false)) {
            e.preventDefault(); // Stop the form from proceeding
            return;
        }

        if (totalPercentage !== 100) {
            Swal.fire({
                icon: "error",
                title: "Percentage Error",
                text: "The total grading percentage must equal 100%.",
            });
            e.preventDefault(); // Stop the form from proceeding
            return;
        }

        // If validation passes, show the grade configuration sections
        document
            .getElementById("header-grade-configuration")
            .classList.remove("hidden");
        document
            .getElementById("normal-grade-configuration")
            .classList.remove("hidden");
        document.getElementById("grade-distribution").classList.add("hidden");

        // Update the stepper to activate step 3
        const steps = document.querySelectorAll(".step-number");
        const borders = document.querySelectorAll(".step-border");

        if (steps.length >= 3 && borders.length >= 2) {
            // Activate step 2
            steps[1].classList.remove("bg-gray-300", "text-gray-500");
            steps[1].classList.add("bg-red-900", "text-white");

            // Change the border after step 2
            borders[1].classList.remove("bg-gray-200");
            borders[1].classList.add("bg-red-900");

            // Activate step 3
            steps[2].classList.remove("bg-gray-300", "text-gray-500");
            steps[2].classList.add("bg-red-900", "text-white");
        }
    });

    // Next button click event (edit mode)
    $("#edit-grade-config-next-btn").on("click", function (e) {
        const selectedPeriod = $("#edit-grading-period").val();
        const totalPercentage = calculateTotalPercentage(selectedPeriod, true);

        // Validate inputs and total percentage
        if (!validateGradingInputs(selectedPeriod, true)) {
            e.preventDefault(); // Stop the form from proceeding
            return;
        }

        if (totalPercentage !== 100) {
            Swal.fire({
                icon: "error",
                title: "Percentage Error",
                text: "The total grading percentage must equal 100%.",
            });
            e.preventDefault(); // Stop the form from proceeding
            return;
        }

        // If validation passes, show the grade configuration sections
        document
            .getElementById("edit-header-grade-configuration")
            .classList.remove("hidden");
        document
            .getElementById("edit-normal-grade-configuration")
            .classList.remove("hidden");
        document
            .getElementById("edit-grade-distribution")
            .classList.add("hidden");
        document.getElementById("edit-final-grade").classList.add("hidden");
        document.getElementById("edit-special-grade").classList.add("hidden");

        // Update the stepper to activate step 3
        const steps = document.querySelectorAll(".step-number");
        const borders = document.querySelectorAll(".step-border");

        if (steps.length >= 3 && borders.length >= 2) {
            // Activate step 2
            steps[1].classList.remove("bg-gray-300", "text-gray-500");
            steps[1].classList.add("bg-red-900", "text-white");

            // Change the border after step 2
            borders[1].classList.remove("bg-gray-200");
            borders[1].classList.add("bg-red-900");

            // Activate step 3
            steps[2].classList.remove("bg-gray-300", "text-gray-500");
            steps[2].classList.add("bg-red-900", "text-white");
        }
    });

    // Call initial functions to populate default text values
    updateGradingNames(false);
    updateGradingNames(true);
});

$(document).ready(function () {
    // Function to handle button visibility based on the grading period
    function handleButtonVisibility(selectedPeriod, isEdit = false) {
        const prefix = isEdit ? "edit-" : ""; // Determine the prefix for edit mode

        //console.log("Selected period:", selectedPeriod);
        //console.log("Checking buttons exist:");
        // console.log(
        //     $(`#${prefix}midterm-next-btn`).length,
        //     $(`#${prefix}final-next-btn`).length,
        //     $(`#${prefix}special-next-btn`).length,
        //     $(`#${prefix}submit-btn`).length
        // );

        // Reset all button visibility
        $(`#${prefix}midterm-next-btn`).addClass("hidden");
        $(`#${prefix}final-next-btn`).addClass("hidden");
        $(`#${prefix}special-next-btn`).addClass("hidden");
        $(`#${prefix}submit-btn`).addClass("hidden"); // Hide all submit buttons initially

        // Show buttons based on the selected period
        if (selectedPeriod === "1") {
            // For 1st period
            $(`.${prefix}midterm-submit`).removeClass("hidden"); // Show next button for final
            $(`#${prefix}midterm-next-btn`).addClass("hidden"); // No next button for midterm
        } else if (selectedPeriod === "2") {
            // For 2nd period
            $(`#${prefix}midterm-next-btn`).removeClass("hidden"); // Show next button for midterm
            $(`.${prefix}midterm-submit`).addClass("hidden"); // Show next button for final
            $(`.${prefix}final-submit`).removeClass("hidden"); // Show next button for final
        } else if (selectedPeriod === "3") {
            // For 3rd period
            $(`.${prefix}midterm-submit`).addClass("hidden"); // Show next button for final
            $(`.${prefix}final-submit`).addClass("hidden"); // Show next button for final
            $(`#${prefix}midterm-next-btn`).removeClass("hidden"); // Show next button for midterm
            $(`#${prefix}final-next-btn`).removeClass("hidden"); // Show next button for final
            $(`#${prefix}special-submit`).removeClass("hidden"); // Hide submit button in this modal
        }
    }

    // Normal mode grading period change
    $("#grading-period").on("change", function () {
        const selectedPeriod = $(this).val();
        handleButtonVisibility(selectedPeriod, false);
        handleApplyToAllVisibility(selectedPeriod);
    });

    // Edit mode grading period change
    $("#edit-grading-period").on("change", function () {
        const selectedPeriod = $(this).val();
        handleButtonVisibility(selectedPeriod, true);
    });
    function handleApplyToAllVisibility(selectedPeriod) {
        const $applyAllCheckbox = $("#applyAll");
        if (selectedPeriod === "1") {
            $applyAllCheckbox.parent().hide();
        } else {
            $applyAllCheckbox.parent().show();
        }
    }

    // Ensure the event listener is only added once
    $("#applyAll").on("change", function () {
        const isChecked = $(this).prop("checked");
        const selectedPeriod = $("#grading-period").val();

        // Function to calculate total percentage for validation
        function calculateTotalPercentage() {
            let totalPercentage = 0;
            $(".class-standing-input, .examination-input").each(function () {
                const value = parseFloat($(this).val()) || 0; // Ensure it's a number
                totalPercentage += value;
            });
            return totalPercentage;
        }

        if (isChecked) {
            const totalPercentage = calculateTotalPercentage();

            if (totalPercentage !== 100) {
                // Show validation alert and uncheck the checkbox
                Swal.fire({
                    icon: "error",
                    title: "Invalid Percentage",
                    text: "The total percentage must equal 100. Please adjust the values.",
                });

                // Uncheck the checkbox
                $(this).prop("checked", false);
                return; // Prevent further execution
            }

            applyMidtermToOtherGrades(selectedPeriod); // Proceed if validation passes

            // Once checked, disable the checkbox to prevent unchecking
            $(this).prop("disabled", true);
        }
    });

    function addNewAssessmentToOtherGrades(type, name, selectedPeriod) {
        const newItem = `
            <div class="flex items-center space-x-1 mb-2 gap-2">
                <input type="text" 
                       class="md:w-1/3 w-1/2 border-b border-black focus:outline-none assessment-type-input text-black pl-2" 
                       value="${name}">
                <span>:</span>
                <input type="number" 
                       class="text-center border-b border-black focus:outline-none w-20 grade-input-finals text-black final-${type}-input ${type}-input" 
                       min="0" max="100" value="0">
                <span>%</span>
            </div>
        `;

        $(`#final-${type}-container`).append(newItem);
        updateTotals("final");

        if (selectedPeriod === "3") {
            const specialNewItem = `
                <div class="flex items-center space-x-1 mb-2 gap-2">
                    <input type="text" 
                           class=" border-b border-black focus:outline-none md:w-1/3 w-1/2 assessment-type-input text-black pl-2" 
                           value="${name}">
                    <span>:</span>
                    <input type="number" 
                           class="text-center border-b border-black focus:outline-none w-20 grade-input-special text-black special-${type}-input ${type}-input" 
                           min="0" max="100" value="0">
                    <span>%</span>
                </div>
            `;

            $(`#special-${type}-container`).append(specialNewItem);
            updateTotals("special");
        }
    }

    function applyMidtermToOtherGrades(selectedPeriod) {
        // Copy class standing percentages
        $("#final-class-standing-percentage").val(
            $("#class-standing-percentage").val()
        );
        $("#special-class-standing-percentage").val(
            $("#class-standing-percentage").val()
        );

        // Copy class standing inputs (values and assessment types)
        $("#class-standing-container .class-standing-input").each(function (
            index
        ) {
            const $this = $(this);
            const assessmentType = $this.prevAll("input[type='text']").val(); // Get assessment type input
            const value = $this.val();

            if (
                index >=
                $("#final-class-standing-container .final-class-standing-input")
                    .length
            ) {
                addNewAssessmentToOtherGrades(
                    "class-standing",
                    assessmentType,
                    selectedPeriod
                );
            }

            $("#final-class-standing-container .final-class-standing-input")
                .eq(index)
                .val(value);
            $("#final-class-standing-container input[type='text']")
                .eq(index)
                .val(assessmentType);

            if (selectedPeriod === "3") {
                $(
                    "#special-class-standing-container .special-class-standing-input"
                )
                    .eq(index)
                    .val(value);
                $("#special-class-standing-container input[type='text']")
                    .eq(index)
                    .val(assessmentType);
            }
        });

        // Copy examination percentages
        $("#final-examination-percentage").val(
            $("#examination-percentage").val()
        );
        $("#special-examination-percentage").val(
            $("#examination-percentage").val()
        );

        // Copy examination inputs (values and assessment types)
        $("#examination-container .examination-input").each(function (index) {
            const $this = $(this);
            const assessmentType = $this.prevAll("input[type='text']").val(); // Get assessment type input
            const value = $this.val();

            if (
                index >=
                $("#final-examination-container .final-examination-input")
                    .length
            ) {
                addNewAssessmentToOtherGrades(
                    "examination",
                    assessmentType,
                    selectedPeriod
                );
            }

            $("#final-examination-container .final-examination-input")
                .eq(index)
                .val(value);
            $("#final-examination-container input[type='text']")
                .eq(index)
                .val(assessmentType);

            if (selectedPeriod === "3") {
                $("#special-examination-container .special-examination-input")
                    .eq(index)
                    .val(value);
                $("#special-examination-container input[type='text']")
                    .eq(index)
                    .val(assessmentType);
            }
        });

        // Update totals
        updateTotals("final");
        if (selectedPeriod === "3") {
            updateTotals("special");
        }
    }

    function updateTotals(gradeType) {
        // Select elements for examination and class standing percentages
        const examinationPercentage = $(`#${gradeType}-examination-percentage`);
        const classStandingPercentage = $(
            `#${gradeType}-class-standing-percentage`
        );

        // Calculate class standing total
        let classStandingTotal = 0;
        $(`.${gradeType}-class-standing-input`).each(function () {
            classStandingTotal += parseFloat($(this).val()) || 0;
        });

        const classStandingTotalInput = $(
            `.total-class-standing-${gradeType}-input`
        );
        classStandingTotalInput.val(classStandingTotal);

        // Calculate examination total
        let examinationTotal = 0;
        $(`.${gradeType}-examination-input`).each(function () {
            examinationTotal += parseFloat($(this).val()) || 0;
        });

        const examinationTotalInput = $(
            `.total-${gradeType}-examination-input`
        );
        examinationTotalInput.val(examinationTotal);

        // Parse the percentage values for validation
        const classStandingPercentageValue =
            parseFloat(classStandingPercentage.val()) || 0;
        const examinationPercentageValue =
            parseFloat(examinationPercentage.val()) || 0;

        // Check if totals match the percentages
        const isValidClassStanding =
            Math.abs(classStandingTotal - classStandingPercentageValue) < 0.01;
        const isValidExamination =
            Math.abs(examinationTotal - examinationPercentageValue) < 0.01;

        // Apply visual feedback based on validity
        applyVisualFeedback(classStandingPercentage[0], isValidClassStanding);
        applyVisualFeedback(examinationPercentage[0], isValidExamination);
        applyVisualFeedback(classStandingTotalInput[0], isValidClassStanding);
        applyVisualFeedback(examinationTotalInput[0], isValidExamination);
    }

    $("#edit-applyAll").on("change", function () {
        const isChecked = $(this).prop("checked");
        const selectedPeriod = $("#edit-grading-period").val();

        // Function to calculate total percentage for validation
        function editCalculateTotalPercentage() {
            let totalPercentage = 0;
            $(".class-standing-input, .examination-input").each(function () {
                const value = parseFloat($(this).val()) || 0; // Ensure it's a number
                totalPercentage += value;
            });
            return totalPercentage;
        }

        if (isChecked) {
            const totalPercentage = editCalculateTotalPercentage();

            if (totalPercentage !== 100) {
                // Show validation alert and uncheck the checkbox
                Swal.fire({
                    icon: "error",
                    title: "Invalid Percentage",
                    text:
                        "The total percentage must equal 100. Please adjust the values." +
                        totalPercentage,
                });

                // Uncheck the checkbox
                $(this).prop("checked", false);
                return; // Prevent further execution
            }

            editApplyMidtermToOtherGrades(selectedPeriod); // Proceed if validation passes

            // Once checked, disable the checkbox to prevent unchecking
            $(this).prop("disabled", true);
        }
    });

    function editAddNewAssessmentToOtherGrades(type, name, selectedPeriod) {
        const newItem = `
            <div class="flex items-center space-x-1 mb-2 gap-2">
                <input type="text" 
                       class="md:w-1/3 w-1/2 border-b border-black focus:outline-none assessment-type-input text-black pl-2" 
                       value="${name}">
                <span>:</span>
                <input type="number" 
                       class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals text-black edit-final-${type}-input ${type}-input" 
                       min="0" max="100" value="0">
                <span>%</span>
            </div>
        `;

        // Append the new item to the appropriate container
        $(`#edit-final-${type}-container`).append(newItem);
        editUpdateTotals("final");

        // If selected period is "3", add a special item
        if (selectedPeriod === "3") {
            const specialNewItem = `
                <div class="flex items-center space-x-1 mb-2 gap-2">
                    <input type="text" 
                           class="border-b border-black focus:outline-none md:w-1/3 w-1/2 assessment-type-input text-black pl-2" 
                           value="${name}">
                    <span>:</span>
                    <input type="number" 
                           class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special text-black  edit-special-${type}-input ${type}-input" 
                           min="0" max="100" value="0">
                    <span>%</span>
                </div>
            `;

            // Append the special item
            $(`#edit-special-${type}-container`).append(specialNewItem);
            editUpdateTotals("special");
        }
    }

    function editApplyMidtermToOtherGrades(selectedPeriod) {
        // Copy class standing percentages
        $("#edit-final-class-standing-percentage").val(
            $("#edit-class-standing-percentage").val()
        );
        $("#edit-special-class-standing-percentage").val(
            $("#edit-class-standing-percentage").val()
        );

        // Copy class standing inputs (values and assessment types)
        $("#edit-class-standing-container .class-standing-input").each(
            function (index) {
                const $this = $(this);
                const assessmentType = $this
                    .prevAll("input[type='text']")
                    .val(); // Get assessment type input
                const value = $this.val();

                // Add new class standing input if needed
                if (
                    index >=
                    $(
                        "#edit-final-class-standing-container .edit-final-class-standing-input"
                    ).length
                ) {
                    editAddNewAssessmentToOtherGrades(
                        "class-standing",
                        assessmentType,
                        selectedPeriod
                    );
                }

                // Update class standing values
                $(
                    "#edit-final-class-standing-container .edit-final-class-standing-input"
                )
                    .eq(index)
                    .val(value);
                $("#edit-final-class-standing-container input[type='text']")
                    .eq(index)
                    .val(assessmentType);

                if (selectedPeriod === "3") {
                    $(
                        "#edit-special-class-standing-container .edit-special-class-standing-input"
                    )
                        .eq(index)
                        .val(value);
                    $(
                        "#edit-special-class-standing-container input[type='text']"
                    )
                        .eq(index)
                        .val(assessmentType);
                }
            }
        );

        // Copy examination percentages
        $("#edit-final-examination-percentage").val(
            $("#edit-examination-percentage").val()
        );
        $("#edit-special-examination-percentage").val(
            $("#edit-examination-percentage").val()
        );

        // Copy examination inputs (values and assessment types)
        $("#edit-examination-container .examination-input").each(function (
            index
        ) {
            const $this = $(this);
            const assessmentType = $this.prevAll("input[type='text']").val(); // Get assessment type input
            const value = $this.val();

            // Add new examination input if needed
            if (
                index >=
                $(
                    "#edit-final-examination-container .edit-final-examination-input"
                ).length
            ) {
                editAddNewAssessmentToOtherGrades(
                    "examination",
                    assessmentType,
                    selectedPeriod
                );
            }

            // Update examination values
            $("#edit-final-examination-container .edit-final-examination-input")
                .eq(index)
                .val(value);
            $("#edit-final-examination-container input[type='text']")
                .eq(index)
                .val(assessmentType);

            if (selectedPeriod === "3") {
                $(
                    "#edit-special-examination-container .edit-special-examination-input"
                )
                    .eq(index)
                    .val(value);
                $("#edit-special-examination-container input[type='text']")
                    .eq(index)
                    .val(assessmentType);
            }
        });

        // Update totals for both final and special periods
        editUpdateTotals("final");
        if (selectedPeriod === "3") {
            editUpdateTotals("special");
        }
    }

    function editUpdateTotals(gradeType) {
        // Select elements for examination and class standing percentages
        const examinationPercentage = $(
            `#edit-${gradeType}-examination-percentage`
        );
        const classStandingPercentage = $(`
            #edit-${gradeType}-class-standing-percentage
        `);

        // Calculate class standing total
        let classStandingTotal = 0;
        $(`.edit-${gradeType}-class-standing-input`).each(function () {
            classStandingTotal += parseFloat($(this).val()) || 0;
        });

        const classStandingTotalInput = $(`
            .edit-total-class-standing-${gradeType}-input
          
        `);
        classStandingTotalInput.val(classStandingTotal);

        // Calculate examination total
        let examinationTotal = 0;
        $(`.edit-${gradeType}-examination-input`).each(function () {
            examinationTotal += parseFloat($(this).val()) || 0;
        });

        const examinationTotalInput = $(`
            .edit-total-${gradeType}-examination-input
        `);
        examinationTotalInput.val(examinationTotal);

        // Parse the percentage values for validation
        const classStandingPercentageValue =
            parseFloat(classStandingPercentage.val()) || 0;
        const examinationPercentageValue =
            parseFloat(examinationPercentage.val()) || 0;

        // Check if totals match the percentages
        const isValidClassStanding =
            Math.abs(classStandingTotal - classStandingPercentageValue) < 0.01;
        const isValidExamination =
            Math.abs(examinationTotal - examinationPercentageValue) < 0.01;

        // Apply visual feedback based on validity
        applyVisualFeedback(classStandingPercentage[0], isValidClassStanding);
        applyVisualFeedback(examinationPercentage[0], isValidExamination);
        applyVisualFeedback(classStandingTotalInput[0], isValidClassStanding);
        applyVisualFeedback(examinationTotalInput[0], isValidExamination);
    }
});

//Filter and Search Bar:
$(document).ready(function () {
    $("#program-dropdown-toggle").on("click", function () {
        $("#filter-dropdown").toggleClass("hidden");
    });

    $("#filter-dropdown li").on("click", function () {
        let selectedFilter = $(this).data("filter");
        $("#filter-label").text(selectedFilter);
        $("#filter-dropdown").addClass("hidden");
    });

    $("#search-input").on("input", function () {
        let searchText = $(this).val().toLowerCase();
        let filterType = $("#filter-label").text();

        // Hide or show the div based on whether the search input is empty or not
        if (searchText === "") {
            $("#new-class-record-container").show();
        } else {
            $("#new-class-record-container").hide();
        }

        $(".record-item").each(function () {
            let courseTitle = $(this).data("title").toLowerCase();
            let courseCode = $(this).data("code").toLowerCase();
            let programTitle = $(this).data("program-title").toLowerCase();
            let programCode = $(this).data("program-code").toLowerCase();

            let isVisible = false;

            if (filterType === "All") {
                isVisible =
                    courseTitle.includes(searchText) ||
                    courseCode.includes(searchText) ||
                    programTitle.includes(searchText) ||
                    programCode.includes(searchText);
            } else if (filterType === "Program") {
                isVisible =
                    programTitle.includes(searchText) ||
                    programCode.includes(searchText);
            } else if (filterType === "Course") {
                isVisible =
                    courseTitle.includes(searchText) ||
                    courseCode.includes(searchText);
            }

            $(this).toggle(isVisible);
        });
    });
});


//Image upload
$(document).ready(function () {
    function handleImagePreview(uploadID, previewID) {
        $(uploadID).on("change", function (event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById(previewID);
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    }

    handleImagePreview("#image-upload", "image-preview");
    handleImagePreview("#edit-image-upload", "edit-image-preview");
});

//Academic Year
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
    populateAcademicYear("academic-year");
    populateAcademicYear("edit-academic-year");
});

//Program and Course and Branches
$(document).ready(function () {
    // Function to populate branch select
    function populateBranchSelect() {
        $.ajax({
            url: "/get-branches", // Fetch branches from backend
            method: "GET",
            success: function (data) {
                const branchSelect = $("#branch-select");
                branchSelect.empty(); // Clear existing options
                branchSelect.append(
                    "<option value=''>Select PUP Branch</option>"
                ); // Default option

                // Populate branches
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

    // Function to populate program select based on branch
    function populateProgramSelect(branchID) {
        if (branchID) {
            $.ajax({
                url: `/get-program/${branchID}`, // Pass the branchID to the endpoint
                method: "GET",
                success: function (data) {
                    const programSelect = $("#program-select");
                    programSelect.empty();
                    programSelect.append(
                        "<option value=''>Select Program</option>"
                    ); // Default option

                    data.programs.forEach(function (program) {
                        programSelect.append(
                            `<option value="${program.programID}">${program.programTitle}</option>`
                        );
                    });
                },
                error: function (err) {
                    console.error("Error fetching programs:", err);
                },
            });
        } else {
            // Clear the program select if no branch is selected
            $("#program-select")
                .empty()
                .append("<option value=''>Select Program</option>");
        }
    }

    // Function to handle program change and populate course select
    function handleProgramChange(selectID, courseSelectID) {
        $(selectID).on("change", function () {
            const programID = $(this).val();

            $.ajax({
                url: `/get-courses/${programID}`,
                method: "GET",
                success: function (data) {
                    const courseSelect = $(courseSelectID);
                    courseSelect.empty();
                    courseSelect.append(
                        "<option value=''>Select Course</option>"
                    );

                    data.courses.forEach(function (course) {
                        courseSelect.append(
                            `<option value="${course.courseID}" data-course-code="${course.courseCode}">${course.courseTitle}</option>`
                        );
                    });
                },
                error: function (err) {
                    console.error("Error fetching courses:", err);
                },
            });
        });

        // Display the `courseCode` in `#course-description` when a course is selected
        $(courseSelectID).on("change", function () {
            const selectedOption = $(this).find(":selected");
            const courseCode = selectedOption.data("course-code");
            $("#course-description").text(courseCode || "Select a course");
        });
    }

    // Populate the branch select on page load
    populateBranchSelect();

    // Handle branch selection change
    $("#branch-select").on("change", function () {
        const branchID = $(this).val();
        populateProgramSelect(branchID); // Fetch programs based on branch
    });

    // Populate program and course selects on page load for editing
    handleProgramChange("#program-select", "#course-select");
});

$(document).ready(function () {
    // Function to set up event listeners for midterm
    function setupMidtermNextButton(prefix) {
        $(`#${prefix}midterm-next-btn`).on("click", function (e) {
            const isEditMode = prefix === "edit-";
            const updatePrefix = isEditMode ? "edit-" : "";
            const isValid = updateTotal(updatePrefix, false, isEditMode);

            const classStandingPercentage =
                parseFloat(
                    $(`#${updatePrefix}class-standing-percentage`).val()
                ) || 0;
            const examinationPercentage =
                parseFloat($(`#${updatePrefix}examination-percentage`).val()) ||
                0;

            if (
                !isValid ||
                classStandingPercentage === 0 ||
                examinationPercentage === 0
            ) {
                e.preventDefault();
                let errorMessage =
                    "Please ensure the total percentage adds up to 100% before proceeding.";
                if (
                    classStandingPercentage === 0 ||
                    examinationPercentage === 0
                ) {
                    errorMessage =
                        "Class Standing and Examination percentages must both be greater than 0.";
                }
                Swal.fire({
                    title: "Invalid Midterm Total",
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return false;
            }

            // Show the final grade section
            $(`#${prefix}final-grade`).removeClass("hidden");
            $(`#${prefix}midterm-grade`).addClass("hidden");
            $(`#${prefix}finals`)
                .removeClass("hidden")
                .addClass("text-red-900");
            $(`#${prefix}midterm`).removeClass("text-red-900");
            $(`#${prefix}special-grade`).addClass("hidden");

            // Update the stepper to activate step 4
            const steps = document.querySelectorAll(".step-number");
            const borders = document.querySelectorAll(".step-border");

            if (steps.length >= 4 && borders.length >= 3) {
                steps[2].classList.remove("bg-gray-300", "text-gray-500");
                steps[2].classList.add("bg-red-900", "text-white");

                // Activate step 4
                steps[3].classList.remove("bg-gray-300", "text-gray-500");
                steps[3].classList.add("bg-red-900", "text-white");

                // Change the border after step 3 to active color
                borders[2].classList.remove("bg-gray-200");
                borders[2].classList.add("bg-red-900");
            }
        });
    }

    // Function to handle the next button for final grade
    function setupFinalNextButton(prefix) {
        $(`#${prefix}final-next-btn`).on("click", function (e) {
            const isEditMode = prefix === "edit-";
            const updatePrefix = isEditMode ? "edit-" : "";
            const isValid = updateFinalTotal(updatePrefix, false, isEditMode); // true for final grade

            const finalClassStandingPercentage =
                parseFloat(
                    $(`#${updatePrefix}final-class-standing-percentage`).val()
                ) || 0;
            const finalExaminationPercentage =
                parseFloat(
                    $(`#${updatePrefix}final-examination-percentage`).val()
                ) || 0;
            //console.log("isValid: ", isValid);
            //console.log("Final Class Standing: ", finalClassStandingPercentage);
            //console.log("Final Examination: ", finalExaminationPercentage);

            if (
                !isValid ||
                finalClassStandingPercentage === 0 ||
                finalExaminationPercentage === 0
            ) {
                e.preventDefault();
                let errorMessage =
                    "Please ensure the total percentage adds up to 100% before proceeding.";
                if (
                    finalClassStandingPercentage === 0 ||
                    finalExaminationPercentage === 0
                ) {
                    errorMessage =
                        "Class Standing and Examination percentages must both be greater than 0.";
                }
                Swal.fire({
                    title: "Invalid Final Total",
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return false; // Ensure the event fully halts here
            }
            $(`#${prefix}special-grade`).removeClass("hidden");
            $(`#${prefix}final-grade`).addClass("hidden");
            $(`#${prefix}midterm-grade`).addClass("hidden");
            $(`#${prefix}grade-distribution`).addClass("hidden");
            // Update the stepper to activate step 4
            const steps = document.querySelectorAll(".step-number");
            const borders = document.querySelectorAll(".step-border");

            if (steps.length >= 5 && borders.length >= 4) {
                steps[3].classList.remove("bg-gray-300", "text-gray-500");
                steps[3].classList.add("bg-red-900", "text-white");

                // Activate step 5
                steps[4].classList.remove("bg-gray-300", "text-gray-500");
                steps[4].classList.add("bg-red-900", "text-white");

                // Change the border after step 3 to active color
                borders[3].classList.remove("bg-gray-200");
                borders[3].classList.add("bg-red-900");
            }
        });
    }

    // Set up for edit mode midterm
    setupMidtermNextButton("edit-");
    // Set up for creation mode midterm
    setupMidtermNextButton("");

    // Set up for edit mode final
    setupFinalNextButton("edit-");
    // Set up for creation mode final (if needed)
    setupFinalNextButton("");
});
//midterm grade
let isValidMidterm = false;
function updateTotal(
    prefix,
    manualPercentageUpdate = false,
    isEditMode = false
) {
    const totalDisplay = document.getElementById(`${prefix}midterm-total`);
    const classStandingContainer = document.getElementById(
        `${prefix}class-standing-container`
    );
    const examinationContainer = document.getElementById(
        `${prefix}examination-container`
    );
    const totalGradeClassStanding = document.querySelector(
        `.${prefix}total-grade-class-standing`
    );
    const totalExaminationInput = document.querySelector(
        `.${prefix}total-examination-input`
    );
    const classStandingPercentage = document.getElementById(
        `${prefix}class-standing-percentage`
    );
    const examinationPercentage = document.getElementById(
        `${prefix}examination-percentage`
    );

    let classStandingTotal = 0;
    let examinationTotal = 0;
    const maxTotal = 100;

    classStandingContainer
        .querySelectorAll(`.${prefix}grade-input:not(.examination-input)`)
        .forEach((input) => {
            classStandingTotal += parseFloat(input.value) || 0;
        });

    examinationContainer
        .querySelectorAll(`.${prefix}grade-input.examination-input`)
        .forEach((input) => {
            examinationTotal += parseFloat(input.value) || 0;
        });

    // Helper function to format numbers dynamically
    function formatNumber(value) {
        return value % 1 === 0 ? value.toString() : value.toFixed(2);
    }

    // Update total class standing and examination inputs without unnecessary decimals
    totalGradeClassStanding.value = formatNumber(classStandingTotal);
    totalExaminationInput.value = formatNumber(examinationTotal);

    // Get the current percentage values
    let classStandingPercentageValue =
        parseFloat(classStandingPercentage.value) || 0;
    let examinationPercentageValue =
        parseFloat(examinationPercentage.value) || 0;

    if (!manualPercentageUpdate) {
        // Only update percentages if they haven't been manually changed
        if (classStandingPercentageValue === 0) {
            classStandingPercentage.value = formatNumber(classStandingTotal);
            classStandingPercentageValue = classStandingTotal;
        }
        if (examinationPercentageValue === 0) {
            examinationPercentage.value = formatNumber(examinationTotal);
            examinationPercentageValue = examinationTotal;
        }
    }

    const totalPercentage =
        classStandingPercentageValue + examinationPercentageValue;
    const midtermTotal = maxTotal - totalPercentage;

    // Update the total display without unnecessary decimals
    totalDisplay.textContent = `Total: ${formatNumber(midtermTotal)}%`;

    const isValidMidterm = Math.abs(totalPercentage - maxTotal) < 0.01;
    const isValidClassStanding =
        Math.abs(classStandingTotal - classStandingPercentageValue) < 0.01;
    const isValidExamination =
        Math.abs(examinationTotal - examinationPercentageValue) < 0.01;

    // Check if total class standing exceeds class standing percentage
    if (classStandingTotal > classStandingPercentageValue) {
        Swal.fire({
            title: "Error",
            text: `Total Class Standing (${formatNumber(
                classStandingTotal
            )}) exceeds the assigned percentage (${formatNumber(
                classStandingPercentageValue
            )}).`,
            icon: "error",
            confirmButtonText: "OK",
        });
        return false;
    }

    // Check if total examination exceeds examination percentage
    if (examinationTotal > examinationPercentageValue) {
        Swal.fire({
            title: "Error",
            text: `Total Examination (${formatNumber(
                examinationTotal
            )}) exceeds the assigned percentage (${formatNumber(
                examinationPercentageValue
            )}).`,
            icon: "error",
            confirmButtonText: "OK",
        });
        return false;
    }

    // Visual feedback
    applyVisualFeedback(classStandingPercentage, isValidClassStanding);
    applyVisualFeedback(examinationPercentage, isValidExamination);
    applyVisualFeedback(totalDisplay, isValidMidterm);
    applyVisualFeedback(totalGradeClassStanding, isValidClassStanding);
    applyVisualFeedback(totalExaminationInput, isValidExamination);

    // Return the validation state
    return isValidMidterm && isValidClassStanding && isValidExamination;
}

function applyVisualFeedback(element, isValid) {
    if (isValid) {
        element.classList.remove("border-red-500");
        element.classList.add("border-green-500");
    } else {
        element.classList.remove("border-green-500");
        element.classList.add("border-red-500");
    }
}

function setupGradeSection(prefix, isEditMode = false) {
    const classStandingContainer = document.getElementById(
        `${prefix}class-standing-container`
    );
    const examinationContainer = document.getElementById(
        `${prefix}examination-container`
    );
    const classStandingPercentage = document.getElementById(
        `${prefix}class-standing-percentage`
    );
    const examinationPercentage = document.getElementById(
        `${prefix}examination-percentage`
    );
    const addClassStandingBtn = document.getElementById(
        `${prefix}add-class-standing`
    );
    const addExaminationBtn = document.getElementById(
        `${prefix}add-examination`
    );
    const newClassStandingNameInput = document.getElementById(
        `${prefix}new-class-standing-name`
    );
    const newExaminationNameInput = document.getElementById(
        `${prefix}new-examination-name`
    );

    // Check if the section has already been initialized
    if (classStandingContainer.dataset.initialized === "true") {
        return;
    }

    function addAssessment(container, nameInput, inputClass, isExamination) {
        //console.log("Adding assessment. Name input value:", nameInput.value);

        const assessmentName = nameInput.value.trim();
        //console.log("Trimmed assessment name:", assessmentName);

        if (!assessmentName) {
            //console.log("Assessment name is empty after trim");
            Swal.fire({
                title: "Error",
                text: "Assessment name cannot be empty",
                icon: "error",
                confirmButtonText: "OK",
            });
            return;
        }

        //console.log("Creating new assessment element");

        const newAssessment = document.createElement("div");
        newAssessment.className = "flex items-center space-x-1 mb-2 gap-2";

        newAssessment.innerHTML = ` 
            <input 
                type="text" 
                class="assessment-type-input text-left border-b border-black focus:outline-none w-1/3 text-black pl-2" 
                value="${assessmentName}" 
            />
            <span>:</span>
            <input 
                type="number" 
                class="text-center border-b border-black focus:outline-none w-20 text-black ${prefix}grade-input ${inputClass} ${
            isExamination ? "examination-input" : "class-standing-input"
        }" 
                min="0" 
                max="100" 
                value="0"
            />
            <span>%</span>
        `;

        container.appendChild(newAssessment);

        // Clear the input value after successful addition
        nameInput.value = "";

        const newInput = newAssessment.querySelector("input[type='number']");
        newInput.addEventListener("input", () =>
            updateTotal(prefix, false, isEditMode)
        );
        updateTotal(prefix, false, isEditMode);

        //console.log("Assessment added successfully");
    }

    // Use event delegation for add buttons
    document.addEventListener("click", function (event) {
        if (event.target === addClassStandingBtn) {
            event.preventDefault();
            //console.log("Add Class Standing button clicked");
            addAssessment(
                classStandingContainer,
                newClassStandingNameInput,
                `${prefix}class-standing-input`,
                false
            );
        } else if (event.target === addExaminationBtn) {
            event.preventDefault();
            //console.log("Add Examination button clicked");
            addAssessment(
                examinationContainer,
                newExaminationNameInput,
                `${prefix}examination-input`,
                true
            );
        }
    });

    function addInputListeners(container) {
        container.addEventListener("input", function (event) {
            if (event.target.classList.contains(`${prefix}grade-input`)) {
                updateTotal(prefix, false, isEditMode);
            }
        });
    }

    function addPercentageListener(input) {
        input.addEventListener("input", () => {
            updateTotal(prefix, true, isEditMode);
        });
    }

    // Add event listeners for the existing assessments
    addInputListeners(classStandingContainer);
    addInputListeners(examinationContainer);

    addPercentageListener(classStandingPercentage);
    addPercentageListener(examinationPercentage);

    // Initial update
    updateTotal(prefix, false, isEditMode);

    // Mark the section as initialized
    classStandingContainer.dataset.initialized = "true";
}

$(document).ready(function () {
    setupGradeSection("", false); // Creation mode
    setupGradeSection("edit-", true); // Edit mode
});

//Final Grade
let isValidFinal = false;
function updateFinalTotal(
    prefix,
    manualPercentageUpdate = false,
    isEditMode = false
) {
    const finalTotalDisplay = document.getElementById(`${prefix}final-total`);
    const finalClassStandingContainer = document.getElementById(
        `${prefix}final-class-standing-container`
    );
    const finalExaminationContainer = document.getElementById(
        `${prefix}final-examination-container`
    );
    const totalClassStandingFinalInput = document.querySelector(
        `.${prefix}total-class-standing-final-input`
    );
    const totalFinalExaminationInput = document.querySelector(
        `.${prefix}total-final-examination-input`
    );
    const finalClassStandingPercentage = document.getElementById(
        `${prefix}final-class-standing-percentage`
    );
    const finalExaminationPercentage = document.getElementById(
        `${prefix}final-examination-percentage`
    );

    let finalClassStandingTotal = 0;
    let finalExaminationTotal = 0;
    const maxTotal = 100;

    // Calculate totals for both existing and new inputs
    finalClassStandingContainer
        .querySelectorAll(
            `.${prefix}grade-input-finals:not(.examination-input)`
        )
        .forEach((input) => {
            finalClassStandingTotal += parseFloat(input.value) || 0;
        });

    finalExaminationContainer
        .querySelectorAll(`.${prefix}grade-input-finals.examination-input`)
        .forEach((input) => {
            finalExaminationTotal += parseFloat(input.value) || 0;
        });

    function formatNumber(value) {
        return value % 1 === 0 ? value.toString() : value.toFixed(2);
    }

    // Update total class standing and examination inputs without unnecessary decimals
    totalClassStandingFinalInput.value = formatNumber(finalClassStandingTotal);
    totalFinalExaminationInput.value = formatNumber(finalExaminationTotal);

    // Get the current percentage values
    let finalClassStandingPercentageValue =
        parseFloat(finalClassStandingPercentage.value) || 0;
    let finalExaminationPercentageValue =
        parseFloat(finalExaminationPercentage.value) || 0;

    if (!manualPercentageUpdate) {
        // Only update percentages if they haven't been manually changed
        if (finalClassStandingPercentageValue === 0) {
            finalClassStandingPercentage.value = formatNumber(
                finalClassStandingTotal
            );
            finalClassStandingPercentageValue = finalClassStandingTotal;
        }
        if (finalExaminationPercentageValue === 0) {
            finalExaminationPercentage.value = formatNumber(
                finalExaminationTotal
            );
            finalExaminationPercentageValue = finalExaminationTotal;
        }
    }

    const totalPercentage =
        finalClassStandingPercentageValue + finalExaminationPercentageValue;
    const finalTotal = maxTotal - totalPercentage;

    // Update the total display
    finalTotalDisplay.textContent = `Total: ${finalTotal}%`;

    const isValidFinal = Math.abs(totalPercentage - maxTotal) < 0.01;
    const isValidClassStanding =
        Math.abs(finalClassStandingTotal - finalClassStandingPercentageValue) <
        0.01;
    const isValidExamination =
        Math.abs(finalExaminationTotal - finalExaminationPercentageValue) <
        0.01;

    // Visual feedback
    applyVisualFeedback(finalClassStandingPercentage, isValidClassStanding);
    applyVisualFeedback(finalExaminationPercentage, isValidExamination);
    applyVisualFeedback(finalTotalDisplay, isValidFinal);
    applyVisualFeedback(totalClassStandingFinalInput, isValidClassStanding);
    applyVisualFeedback(totalFinalExaminationInput, isValidExamination);

    // Validation checks
    if (finalClassStandingTotal > finalClassStandingPercentageValue) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: `Total Class Standing (${finalClassStandingTotal.toFixed(
                2
            )}) exceeds the assigned percentage (${finalClassStandingPercentageValue.toFixed(
                2
            )}).`,
        });
        return false;
    }

    if (finalExaminationTotal > finalExaminationPercentageValue) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: `Total Examination (${finalExaminationTotal.toFixed(
                2
            )}) exceeds the assigned percentage (${finalExaminationPercentageValue.toFixed(
                2
            )}).`,
        });
        return false;
    }

    // Return the validation state
    return isValidFinal && isValidClassStanding && isValidExamination;
}

function setupFinalGradeSection(prefix, isEditMode = false) {
    const finalClassStandingContainer = document.getElementById(
        `${prefix}final-class-standing-container`
    );
    const finalExaminationContainer = document.getElementById(
        `${prefix}final-examination-container`
    );
    const finalClassStandingPercentage = document.getElementById(
        `${prefix}final-class-standing-percentage`
    );
    const finalExaminationPercentage = document.getElementById(
        `${prefix}final-examination-percentage`
    );
    const finalAddClassStandingButton = document.getElementById(
        `${prefix}final-add-class-standing`
    );
    const finalAddExaminationButton = document.getElementById(
        `${prefix}final-add-examination`
    );
    const finalNewClassStandingNameInput = document.getElementById(
        `${prefix}final-new-class-standing-name`
    );
    const finalNewExaminationNameInput = document.getElementById(
        `${prefix}final-new-examination-name`
    );

    // Check if the section has already been initialized
    if (finalClassStandingContainer.dataset.initialized === "true") {
        return;
    }

    function addFinalAssessment(
        container,
        nameInput,
        inputClass,
        isExamination
    ) {
        // console.log(
        //     "Adding final assessment. Name input value:",
        //     nameInput.value
        // );

        const assessmentName = nameInput.value.trim();
        //console.log("Trimmed final assessment name:", assessmentName);

        if (!assessmentName) {
            //console.log("Final assessment name is empty after trim");
            Swal.fire({
                title: "Error",
                text: "Final assessment name cannot be empty",
                icon: "error",
                confirmButtonText: "OK",
            });
            return;
        }

        // Validation: Check if the sum of Final Class Standing and Examination percentages will exceed 100% after adding this assessment
        const finalClassStandingPercentage =
            parseFloat(
                document.getElementById(
                    `${prefix}final-class-standing-percentage`
                ).value
            ) || 0;
        const finalExaminationPercentage =
            parseFloat(
                document.getElementById(`${prefix}final-examination-percentage`)
                    .value
            ) || 0;

        // if (finalClassStandingPercentage + finalExaminationPercentage >= 100) {
        //     Swal.fire({
        //         title: "Error",
        //         text: "Adding this final assessment will exceed the total allowed percentage of 100%.",
        //         icon: "error",
        //         confirmButtonText: "OK",
        //     });
        //     return;
        // }

        //console.log("Creating new final assessment element");

        const newAssessment = document.createElement("div");
        newAssessment.className = "flex items-center space-x-1 mb-2 gap-2";
        newAssessment.innerHTML = `
            <input 
                type="text" 
                class="assessment-type-input text-left border-b border-black focus:outline-none w-1/3 text-black pl-2" 
                value="${assessmentName}" 
            />
            <span>:</span>
            <input type="number" class="text-center border-b border-black focus:outline-none w-20 text-black ${prefix}grade-input-finals ${inputClass} ${
            isExamination ? "examination-input" : ""
        }" min="0" max="100" value="0">
            <span>%</span>
        `;
        container.appendChild(newAssessment);

        // Clear the input value after successful addition
        nameInput.value = "";

        const newInput = newAssessment.querySelector("input[type='number']");
        newInput.addEventListener("input", () =>
            updateFinalTotal(prefix, false, isEditMode)
        );
        updateFinalTotal(prefix, false, isEditMode);

        //console.log("Final assessment added successfully");
    }

    // Use event delegation for add buttons
    document.addEventListener("click", function (event) {
        if (event.target === finalAddClassStandingButton) {
            event.preventDefault();
            //console.log("Add Final Class Standing button clicked");
            addFinalAssessment(
                finalClassStandingContainer,
                finalNewClassStandingNameInput,
                `${prefix}final-class-standing-input`,
                false
            );
        } else if (event.target === finalAddExaminationButton) {
            event.preventDefault();
            //console.log("Add Final Examination button clicked");
            addFinalAssessment(
                finalExaminationContainer,
                finalNewExaminationNameInput,
                `${prefix}final-examination-input`,
                true
            );
        }
    });

    function addInputListeners(container) {
        container.addEventListener("input", function (event) {
            if (
                event.target.classList.contains(`${prefix}grade-input-finals`)
            ) {
                updateFinalTotal(prefix, false, isEditMode);
            }
        });
    }

    function addPercentageListener(input) {
        input.addEventListener("input", () => {
            updateFinalTotal(prefix, true, isEditMode);
        });
    }

    // Add event listeners for the existing assessments
    addInputListeners(finalClassStandingContainer);
    addInputListeners(finalExaminationContainer);

    addPercentageListener(finalClassStandingPercentage);
    addPercentageListener(finalExaminationPercentage);

    // Initial update
    updateFinalTotal(prefix, false, isEditMode);

    // Mark the section as initialized
    finalClassStandingContainer.dataset.initialized = "true";
}

$(document).ready(function () {
    setupFinalGradeSection("", false); // Creation mode
    setupFinalGradeSection("edit-", true); // Edit mode
});

//Special Grade:
let isValidSpecial = false;
function updateSpecialTotal(
    prefix,
    manualPercentageUpdate = false,
    isEditMode = false
) {
    const specialTotalDisplay = document.getElementById(
        `${prefix}special-total`
    );
    const specialClassStandingContainer = document.getElementById(
        `${prefix}special-class-standing-container`
    );
    const specialExaminationContainer = document.getElementById(
        `${prefix}special-examination-container`
    );
    const totalClassStandingSpecialInput = document.querySelector(
        `.${prefix}total-class-standing-special-input`
    );
    const totalSpecialExaminationInput = document.querySelector(
        `.${prefix}total-special-examination-input`
    );
    const specialClassStandingPercentage = document.getElementById(
        `${prefix}special-class-standing-percentage`
    );
    const specialExaminationPercentage = document.getElementById(
        `${prefix}special-examination-percentage`
    );

    let specialClassStandingTotal = 0;
    let specialExaminationTotal = 0;
    const maxTotal = 100;

    // Calculate totals for both existing and new inputs
    specialClassStandingContainer
        .querySelectorAll(
            `.${prefix}grade-input-special:not(.examination-input)`
        )
        .forEach((input) => {
            specialClassStandingTotal += parseFloat(input.value) || 0;
        });

    specialExaminationContainer
        .querySelectorAll(`.${prefix}grade-input-special.examination-input`)
        .forEach((input) => {
            specialExaminationTotal += parseFloat(input.value) || 0;
        });

    function formatNumber(value) {
        return value % 1 === 0 ? value.toString() : value.toFixed(2);
    }

    // Update total class standing and examination inputs without unnecessary decimals
    totalClassStandingSpecialInput.value = formatNumber(
        specialClassStandingTotal
    );
    totalSpecialExaminationInput.value = formatNumber(specialExaminationTotal);

    // Get the current percentage values
    let specialClassStandingPercentageValue =
        parseFloat(specialClassStandingPercentage.value) || 0;
    let specialExaminationPercentageValue =
        parseFloat(specialExaminationPercentage.value) || 0;

    if (!manualPercentageUpdate) {
        // Only update percentages if they haven't been manually changed
        if (specialClassStandingPercentageValue === 0) {
            specialClassStandingPercentage.value = formatNumber(
                specialClassStandingTotal
            );
            specialClassStandingPercentageValue = specialClassStandingTotal;
        }
        if (specialExaminationPercentageValue === 0) {
            specialExaminationPercentage.value = formatNumber(
                specialExaminationTotal
            );
            specialExaminationPercentageValue = specialExaminationTotal;
        }
    }

    const totalPercentage =
        specialClassStandingPercentageValue + specialExaminationPercentageValue;
    const specialTotal = maxTotal - totalPercentage;

    // Update the total display
    specialTotalDisplay.textContent = `Total: ${specialTotal}%`;

    const isValidSpecial = Math.abs(totalPercentage - maxTotal) < 0.01;
    const isValidClassStanding =
        Math.abs(
            specialClassStandingTotal - specialClassStandingPercentageValue
        ) < 0.01;
    const isValidExamination =
        Math.abs(specialExaminationTotal - specialExaminationPercentageValue) <
        0.01;

    // Visual feedback
    applyVisualFeedback(specialClassStandingPercentage, isValidClassStanding);
    applyVisualFeedback(specialExaminationPercentage, isValidExamination);
    applyVisualFeedback(specialTotalDisplay, isValidSpecial);
    applyVisualFeedback(totalClassStandingSpecialInput, isValidClassStanding);
    applyVisualFeedback(totalSpecialExaminationInput, isValidExamination);

    // Validation checks
    if (specialClassStandingTotal > specialClassStandingPercentageValue) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: `Total Class Standing (${specialClassStandingTotal.toFixed(
                2
            )}) exceeds the assigned percentage (${specialClassStandingPercentageValue.toFixed(
                2
            )}).`,
        });
        return false;
    }

    if (specialExaminationTotal > specialExaminationPercentageValue) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: `Total Examination (${specialExaminationTotal.toFixed(
                2
            )}) exceeds the assigned percentage (${specialExaminationPercentageValue.toFixed(
                2
            )}).`,
        });
        return false;
    }

    // Return the validation state
    return isValidSpecial && isValidClassStanding && isValidExamination;
}
function setupSpecialGradeSection(prefix, isEditMode = false) {
    const specialClassStandingContainer = document.getElementById(
        `${prefix}special-class-standing-container`
    );
    const specialExaminationContainer = document.getElementById(
        `${prefix}special-examination-container`
    );
    const specialClassStandingPercentage = document.getElementById(
        `${prefix}special-class-standing-percentage`
    );
    const specialExaminationPercentage = document.getElementById(
        `${prefix}special-examination-percentage`
    );
    const specialAddClassStandingButton = document.getElementById(
        `${prefix}special-add-class-standing`
    );
    const specialAddExaminationButton = document.getElementById(
        `${prefix}special-add-examination`
    );
    const specialNewClassStandingNameInput = document.getElementById(
        `${prefix}special-new-class-standing-name`
    );
    const specialNewExaminationNameInput = document.getElementById(
        `${prefix}special-new-examination-name`
    );

    // Check if the section has already been initialized
    if (specialClassStandingContainer.dataset.initialized === "true") {
        return;
    }

    function addSpecialAssessment(
        container,
        nameInput,
        inputClass,
        isExamination
    ) {
        // console.log(
        //     "Adding special assessment. Name input value:",
        //     nameInput.value
        // );

        const assessmentName = nameInput.value.trim();
        //console.log("Trimmed special assessment name:", assessmentName);

        if (!assessmentName) {
            Swal.fire({
                title: "Error",
                text: "Special assessment name cannot be empty",
                icon: "error",
                confirmButtonText: "OK",
            });
            return;
        }

        //console.log("Creating new special assessment element");

        const newAssessment = document.createElement("div");
        newAssessment.className = "flex items-center space-x-1 mb-2 gap-2";
        newAssessment.innerHTML = `
            <input 
                type="text" 
                class="assessment-type-input text-left border-b border-black focus:outline-none w-1/3 text-black pl-2" 
                value="${assessmentName}" 
            />
            <span>:</span>
            <input type="number" class="text-center border-b border-black focus:outline-none w-20 text-black  ${prefix}grade-input-special ${inputClass} ${
            isExamination ? "examination-input" : ""
        }" min="0" max="100" value="0">
            <span>%<span>
        `;
        container.appendChild(newAssessment);

        nameInput.value = ""; // Clear the input value

        const newInput = newAssessment.querySelector("input[type='number']");
        newInput.addEventListener("input", () =>
            updateSpecialTotal(prefix, false, isEditMode)
        );
        updateSpecialTotal(prefix, false, isEditMode);

        //console.log("Special assessment added successfully");
    }

    // Event listeners for adding new assessments
    document.addEventListener("click", function (event) {
        if (event.target === specialAddClassStandingButton) {
            event.preventDefault();
            addSpecialAssessment(
                specialClassStandingContainer,
                specialNewClassStandingNameInput,
                `${prefix}special-class-standing-input`,
                false
            );
        } else if (event.target === specialAddExaminationButton) {
            event.preventDefault();
            addSpecialAssessment(
                specialExaminationContainer,
                specialNewExaminationNameInput,
                `${prefix}special-examination-input`,
                true
            );
        }
    });

    // Event listeners for existing assessments
    function addInputListeners(container) {
        container.addEventListener("input", function (event) {
            if (
                event.target.classList.contains(`${prefix}grade-input-special`)
            ) {
                updateSpecialTotal(prefix, false, isEditMode);
            }
        });
    }

    function addPercentageListener(input) {
        input.addEventListener("input", () => {
            updateSpecialTotal(prefix, true, isEditMode);
        });
    }

    addInputListeners(specialClassStandingContainer);
    addInputListeners(specialExaminationContainer);
    addPercentageListener(specialClassStandingPercentage);
    addPercentageListener(specialExaminationPercentage);

    // Initial update
    updateSpecialTotal(prefix, false, isEditMode);

    // Mark the section as initialized
    specialClassStandingContainer.dataset.initialized = "true";
}

$(document).ready(function () {
    setupSpecialGradeSection("", false); // Creation mode for special grades
    setupSpecialGradeSection("edit-", true); // Edit mode for special grades
});

//Creation of classrecord
$(document).ready(function () {
    $("#submit-btn, #special-submit-btn").on("click", function () {
        // Get the selected grading type
        const recordType = $("#grading-period").val();
        //console.log("Selected Record Type:", recordType);

        if (!recordType) {
            console.error("No record type selected or it is undefined.");
            return;
        }

        // Initialize totals
        let midtermTotal = 0;
        let finalsTotal = 0;
        let specialTotal = 0;

        // Grading distribution data
        const gradingDistributions = [];

        if (recordType === "1") {
            // 1st Grading Period: Only Midterm
            midtermTotal = calculateTotal(".first-grade-distribution-input");
            if (midtermTotal !== 100) {
                Swal.fire({
                    title: "Invalid Grading Distribution",
                    html: `The total percentage for Midterm must be 100%.<br><br>
                           Current total: ${midtermTotal}%`,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Stop form submission
            }

            // Capture grading distribution
            gradingDistributions.push({
                gradingDistributionType: $(".first-grade-type-input").val(),
                gradingDistributionPercentage: midtermTotal,
                term: 1,
            });
        } else if (recordType === "2") {
            // 2nd Grading Period: Midterm and Finals
            midtermTotal = calculateTotal(".first-grade-distribution-input");
            finalsTotal = calculateTotal(".second-grade-distribution-input");

            if (midtermTotal + finalsTotal !== 100) {
                Swal.fire({
                    title: "Invalid Grading Distribution",
                    html: `The total percentage for Midterm and Finals must be 100%.<br><br>
                           Current totals:<br>
                           Midterm: ${midtermTotal}%<br>
                           Finals: ${finalsTotal}%`,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Stop form submission
            }

            // Capture grading distribution
            gradingDistributions.push(
                {
                    gradingDistributionType: $(".first-grade-type-input").val(),
                    gradingDistributionPercentage: midtermTotal,
                    term: 1,
                },
                {
                    gradingDistributionType: $(
                        ".second-grade-type-input"
                    ).val(),
                    gradingDistributionPercentage: finalsTotal,
                    term: 2,
                }
            );
        } else if (recordType === "3") {
            // 3rd Grading Period: Midterm, Finals, and Special
            midtermTotal = calculateTotal(".first-grade-distribution-input");
            finalsTotal = calculateTotal(".second-grade-distribution-input");
            specialTotal = calculateTotal(".third-grade-distribution-input");

            if (midtermTotal + finalsTotal + specialTotal !== 100) {
                Swal.fire({
                    title: "Invalid Grading Distribution",
                    html: `The total percentage for Midterm, Finals, and Special must be 100%.<br><br>
                           Current totals:<br>
                           Midterm: ${midtermTotal}%<br>
                           Finals: ${finalsTotal}%<br>
                           Special: ${specialTotal}%`,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Stop form submission
            }

            // Capture grading distribution
            gradingDistributions.push(
                {
                    gradingDistributionType: $(".first-grade-type-input").val(),
                    gradingDistributionPercentage: midtermTotal,
                    term: 1,
                },
                {
                    gradingDistributionType: $(
                        ".second-grade-type-input"
                    ).val(),
                    gradingDistributionPercentage: finalsTotal,
                    term: 2,
                },
                {
                    gradingDistributionType: $(".third-grade-type-input").val(),
                    gradingDistributionPercentage: specialTotal,
                    term: 3,
                }
            );
        }

        // Prepare form data for submission
        const formData = new FormData();
        formData.append("schoolYear", $("#academic-year").val());

        const schedules = getSelectedSchedules();
        formData.append("schedules", JSON.stringify(schedules));

        formData.append("semester", $('select[name="semester"]').val());
        formData.append("yearLevel", $("#year-level").val());
        formData.append("classImg", $("#image-upload")[0].files[0]);
        formData.append("template", $("#template-field").val() || "");
        formData.append("recordType", recordType);
        formData.append("programID", $("#program-select").val());
        formData.append("courseID", $("#course-select").val());
        formData.append("branch", $("#branch-select").val());
        // Append grading distributions to the form data
        gradingDistributions.forEach((item, index) => {
            formData.append(
                `gradingDistributions[${index}][gradingDistributionType]`,
                item.gradingDistributionType
            );
            formData.append(
                `gradingDistributions[${index}][gradingDistributionPercentage]`,
                item.gradingDistributionPercentage
            );
            formData.append(`gradingDistributions[${index}][term]`, item.term);
        });

        // Grading Data Processing
        const gradingData = [];

        // Validation before processing grades
        if (recordType === "1") {
            // Midterm only
            const midtermTotal = calculateTotal(".grade-input");

            if (midtermTotal !== 100) {
                Swal.fire({
                    title: "Error!",
                    text: "total percentage must equal 100%.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Prevent submission
            }
            processGrades(
                ".grade-input",
                1,
                "#class-standing-container",
                "#examination-container"
            );
        } else if (recordType === "2") {
            // Midterm and Finals
            const midtermTotal = calculateTotal(".grade-input");
            const finalsTotal = calculateTotal(".grade-input-finals");

            if (midtermTotal !== 100 || finalsTotal !== 100) {
                Swal.fire({
                    title: "Error!",
                    text: "total percentages must equal 100%.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Prevent submission
            }
            processGrades(
                ".grade-input",
                1,
                "#class-standing-container",
                "#examination-container"
            );
            processGrades(
                ".grade-input-finals",
                2,
                "#final-class-standing-container",
                "#final-examination-container"
            );
        } else if (recordType === "3") {
            // Midterm, Finals, and Special
            const midtermTotal = calculateTotal(".grade-input");
            const finalsTotal = calculateTotal(".grade-input-finals");
            const specialTotal = calculateTotal(".grade-input-special");

            if (
                midtermTotal !== 100 ||
                finalsTotal !== 100 ||
                specialTotal !== 100
            ) {
                Swal.fire({
                    title: "Error!",
                    text: "total percentages must equal 100%.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Prevent submission
            }
            processGrades(
                ".grade-input",
                1,
                "#class-standing-container",
                "#examination-container"
            );
            processGrades(
                ".grade-input-finals",
                2,
                "#final-class-standing-container",
                "#final-examination-container"
            );
            processGrades(
                ".grade-input-special",
                3,
                "#special-class-standing-container",
                "#special-examination-container"
            );
        }

        function processGrades(
            inputSelector,
            term,
            classStandingSelector,
            examinationSelector
        ) {
            $(inputSelector).each(function () {
                const percentage = $(this).val();
                if (percentage && percentage != "0") {
                    const assessmentType = $(this)
                        .closest("div")
                        .find("input.assessment-type-input")
                        .val()
                        .trim();
                    const isExamination =
                        $(this).closest(examinationSelector).length > 0;

                    gradingData.push({
                        assessmentType: assessmentType,
                        term: term,
                        percentage: percentage,
                        isExamination: isExamination,
                    });
                }
            });
        }

        // Append grading data to form data
        gradingData.forEach((item, index) => {
            formData.append(
                `grading[${index}][assessmentType]`,
                item.assessmentType
            );
            formData.append(`grading[${index}][term]`, item.term);
            formData.append(`grading[${index}][percentage]`, item.percentage);
            formData.append(
                `grading[${index}][isExamination]`,
                item.isExamination
            );
        });

        //console.log("Filtered Grading Data:", JSON.stringify(gradingData));

        $("#loader-modal-create").removeClass("hidden");
        $("body").addClass("no-scroll");

        $.ajax({
            url: "/insert-classrecord",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    setTimeout(function () {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    }, 500);
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
                Swal.fire({
                    title: "Error!",
                    text: "An error occurred: " + error,
                    icon: "error",
                    confirmButtonText: "OK",
                });
            },
            complete: function () {
                $("#loader-modal-create").addClass("hidden");
                $("body").removeClass("no-scroll");
            },
        });
    });

    // function getSelectedDays() {
    //     let selectedDays = [];
    //     $(".day-button.bg-red-900").each(function () {
    //         selectedDays.push($(this).text());
    //     });
    //     return selectedDays.join("/");
    // }

    function calculateTotal(inputSelector) {
        let total = 0;
        $(inputSelector).each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        return total;
    }
    // function getSelectedSchedules() {
    //     let schedules = [];
    //     $(".day-container.bg-red-900").each(function () {
    //         const day = $(this).find(".day-button").text().trim();
    //         const timeInputs = $(this)
    //             .closest(".day-row")
    //             .find('.time-inputs input[type="time"]');
    //         let times = [];

    //         for (let i = 0; i < timeInputs.length; i += 2) {
    //             const startTime = $(timeInputs[i]).val();
    //             const endTime = $(timeInputs[i + 1]).val();
    //             if (startTime && endTime) {
    //                 times.push(`${startTime}-${endTime}`);
    //             }
    //         }

    //         if (times.length > 0) {
    //             schedules.push({
    //                 day: day,
    //                 time: times.join(" / "),
    //             });
    //         }
    //     });
    //     return schedules;
    // }
    function getSelectedSchedules() {
        let schedules = [];
        $(".day-row").each(function () {
            const dayContainer = $(this).find(".day-container");

            // Check if the select element has a selected day and the background color is bg-red-900
            if (dayContainer.hasClass("bg-red-900")) {
                const day = dayContainer.val(); // Get the selected day from the <select> tag
                const timeInputs = $(this).find(
                    '.time-inputs input[type="time"]'
                );
                let times = [];

                for (let i = 0; i < timeInputs.length; i += 2) {
                    const startTime = $(timeInputs[i]).val();
                    const endTime = $(timeInputs[i + 1]).val();
                    if (startTime && endTime) {
                        times.push(`${startTime}-${endTime}`);
                    }
                }

                if (times.length > 0) {
                    schedules.push({
                        day: day,
                        time: times.join(" / "),
                    });
                }
            }
        });
        return schedules;
    }
});

//creation validation
document.addEventListener("DOMContentLoaded", function () {
    const nextBtn = document.getElementById("next-btn");

    nextBtn.addEventListener("click", function (e) {
        e.preventDefault();

        // Check if all required fields are filled
        const requiredFields = document.querySelectorAll(
            "#classrecord-information [required]:not(:disabled)"
        );
        let allFieldsFilled = true;
        let emptyFields = [];

        requiredFields.forEach((field) => {
            if (!field.value) {
                allFieldsFilled = false;
                field.classList.add("border-red-500");
                emptyFields.push(
                    field.previousElementSibling.textContent
                        .replace("*", "")
                        .trim()
                );
            } else {
                field.classList.remove("border-red-500");
            }
        });

        const courseSelect = document.getElementById("course-select");
        if (
            courseSelect.value === "" ||
            courseSelect.value === "Select Course"
        ) {
            allFieldsFilled = false;
            courseSelect.classList.add("border-red-500");
            emptyFields.push("Course");
        } else {
            courseSelect.classList.remove("border-red-500");
        }

        // Check if at least one day is selected
        const selectedDays = document.querySelectorAll(
            ".day-container.bg-red-900"
        );
        if (selectedDays.length === 0) {
            allFieldsFilled = false;
            emptyFields.push("At least one day must be selected");
        }

        // Check if selected days have both start and end times
        selectedDays.forEach((day) => {
            const dayRow = day.closest(".day-row");
            const startTime = dayRow.querySelector(".start-time");
            const endTime = dayRow.querySelector(".end-time");

            if (!startTime.value || !endTime.value) {
                allFieldsFilled = false;
                startTime.classList.add("border-red-500");
                endTime.classList.add("border-red-500");
                emptyFields.push(`${day.textContent.trim()} times`);
            } else {
                startTime.classList.remove("border-red-500");
                endTime.classList.remove("border-red-500");
            }
        });

        if (allFieldsFilled) {
            // Stepper logic
            const step1 = document.querySelector(
                '[data-step="1"] .step-number'
            );
            const step2 = document.querySelector(
                '[data-step="2"] .step-number'
            );
            const stepBorder = document.querySelector(".step-border");

            // Change the border and step 2 appearance
            stepBorder.classList.remove("bg-gray-200");
            stepBorder.classList.add("bg-red-900");
            step2.classList.remove("bg-gray-300", "text-gray-500");
            step2.classList.add("bg-red-900", "text-white");

            // Hide current section, show next section
            document
                .getElementById("grade-distribution")
                .classList.remove("hidden");
            document
                .getElementById("grade-config-next-btn")
                .classList.remove("hidden");
            document
                .getElementById("grade-config-back-btn")
                .classList.remove("hidden");
            document
                .getElementById("classrecord-information")
                .classList.add("hidden");
            document.getElementById("next-btn").classList.add("hidden");
        } else {
            Swal.fire({
                title: "Incomplete Form",
                html: `Please address the following:<br><br>${emptyFields.join(
                    "<br>"
                )}`,
                icon: "warning",
                confirmButtonText: "OK",
                confirmButtonColor: "#dc2626",
            });
        }
    });
});

//Search bar
// document.addEventListener("DOMContentLoaded", function () {
//     const searchInput = document.getElementById("search-input");
//     const records = document.querySelectorAll(".record-item");

//     searchInput.addEventListener("input", function () {
//         const query = searchInput.value.toLowerCase();

//         records.forEach((record) => {
//             const title = record.getAttribute("data-title").toLowerCase();
//             if (title.includes(query)) {
//                 record.style.display = ""; // Show record
//             } else {
//                 record.style.display = "none"; // Hide record
//             }
//         });
//     });
// });

//Update Classrecord

// Updation validateForm function
function validateForm() {
    // Check if all required fields are filled
    const requiredFields = document.querySelectorAll(
        "#edit-classrecord-information [required]:not(:disabled)"
    );
    let allFieldsFilled = true;
    let emptyFields = [];

    requiredFields.forEach((field) => {
        if (!field.value) {
            allFieldsFilled = false;
            field.classList.add("border-red-500");
            emptyFields.push(
                field.previousElementSibling.textContent.replace("*", "").trim()
            );
        } else {
            field.classList.remove("border-red-500");
        }
    });

    const courseSelect = document.getElementById("edit-course-select");
    if (!courseSelect.value || courseSelect.value === "Select Course") {
        allFieldsFilled = false;
        courseSelect.classList.add("border-red-500");
        emptyFields.push("Course");
    } else {
        courseSelect.classList.remove("border-red-500");
    }

    // Check if at least one day is selected
    const selectedDays = document.querySelectorAll(
        ".edit-day-row .edit-day-container.bg-red-900"
    );

    if (selectedDays.length === 0) {
        allFieldsFilled = false;
        emptyFields.push("At least one day must be selected");
    }

    // Check if selected days have both start and end times
    selectedDays.forEach((day) => {
        const dayRow = day.closest(".edit-day-row");
        const timeInputs = dayRow.querySelectorAll(
            '.edit-time-inputs input[type="time"]'
        );

        // Validate that both start and end time are provided
        if (
            !timeInputs[0].value || // Check if start time is empty
            !timeInputs[1].value // Check if end time is empty
        ) {
            allFieldsFilled = false;

            // Highlight the time input fields in red if empty
            timeInputs.forEach((input) => {
                input.classList.add("border-red-500");
            });

            emptyFields.push(`${day.value.trim()} times`); // Use the day value, not the text content
        } else {
            // Remove red border if both times are filled
            timeInputs.forEach((input) => {
                input.classList.remove("border-red-500");
            });
        }
    });

    if (allFieldsFilled) {
        let recordType;

        //Check if global recordType exists
        if (
            typeof window.recordType !== "undefined" &&
            window.recordType !== null
        ) {
            recordType = window.recordType.toString(); // Ensure it's treated as a string
            $(
                ".assessment-cs, .assessment-exam, .assessment-cs-final, .assessment-exam-final, .assessment-cs-special, .assessment-exam-special"
            ).each(function () {
                const inputValue = $(this).find("input").val(); // Get the value of the input inside
                if (!inputValue || parseInt(inputValue) === 0) {
                    // Remove if the input is empty or equals 0
                    $(this).remove();
                }
            });
            $("#checkbox-container").addClass("hidden");

            $(
                ".new-assessment-cs, .new-assessment-exam, .new-assessment-exam-final, .new-assessment-cs-final, .new-assessment-cs-special, .new-assessment-exam-special"
            ).addClass("hidden");
        } else {
            // Fetch value from the dropdown if global recordType is not set
            recordType = $("#edit-grading-period").val();
            $("#edit-grading-period").prop("disabled", false);
            if (
                recordType === null ||
                recordType === undefined ||
                recordType === ""
            ) {
                //console.log("No valid recordType selected.");
                return; // Exit if no valid selection is made
            }
            recordType = recordType.toString(); // Ensure it's a string
            $(".assessment-cs").each(function () {
                const labelText = $(this)
                    .find("p")
                    .text()
                    .replace(":", "")
                    .trim(); // Extract the text label
                $(this).replaceWith(`
                    <div class="flex items-center space-x-1 mb-2 gap-2">
                        <input type="text" class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2" value="${labelText}">
                        <span>:</span>
                        <input type="number" class="text-center border-b border-black focus:outline-none w-20 text-black edit-grade-input class-standing-input" min="0" max="100" value="0">
                        <span>%</span>
                    </div>
                `);
            });

            $(".assessment-exam").each(function () {
                const labelText = $(this)
                    .find("p")
                    .text()
                    .replace(":", "")
                    .trim(); // Extract the text label
                $(this).replaceWith(`
                    <div class="flex items-center space-x-1 mb-2 gap-2">
                        <input type="text" class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2" value="${labelText}">
                        <span>:</span>
                        <input type="number" class="text-center border-b border-black focus:outline-none w-20 edit-grade-input examination-input text-black" min="0" max="100" value="0">
                        <span>%</span>
                    </div>
                `);
            });
            $(".assessment-cs-final").each(function () {
                const labelText = $(this)
                    .find("p")
                    .text()
                    .replace(":", "")
                    .trim(); // Extract the text label
                $(this).replaceWith(`
                    <div class="flex items-center space-x-1 mb-2 gap-2">
                        <input type="text" class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2" value="${labelText}">
                        <span>:</span>
                        <input type="number" class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-class-standing-input text-black" min="0" max="100" value="0">
                        <span>%</span>
                    </div>
                `);
            });
            $(".assessment-exam-final").each(function () {
                const labelText = $(this)
                    .find("p")
                    .text()
                    .replace(":", "")
                    .trim(); // Extract the text label
                $(this).replaceWith(`
                    <div class="flex items-center space-x-1 mb-2 gap-2">
                        <input type="text" class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2" value="${labelText}">
                        <span>:</span>
                        <input type="number" class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-examination-input examination-input text-black" min="0" max="100" value="0">
                        <span>%</span>
                    </div>
                `);
            });
            $(".assessment-exam-special").each(function () {
                const labelText = $(this)
                    .find("p")
                    .text()
                    .replace(":", "")
                    .trim(); // Extract the text label
                $(this).replaceWith(`
                    <div class="flex items-center space-x-1 mb-2 gap-2">
                        <input type="text" class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2" value="${labelText}">
                        <span>:</span>
                        <input type="number" class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-examination-input examination-input text-black" min="0" max="100" value="0">
                        <span>%</span>
                    </div>
                `);
            });
            $(".assessment-cs-special").each(function () {
                const labelText = $(this)
                    .find("p")
                    .text()
                    .replace(":", "")
                    .trim(); // Extract the text label
                $(this).replaceWith(`
                    <div class="flex items-center space-x-1 mb-2 gap-2">
                        <input type="text" class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2" value="${labelText}">
                        <span>:</span>
                        <input type="number" class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-class-standing-input text-black" min="0" max="100" value="0">
                        <span>%</span>
                    </div>
                `);
            });

            $("#edit-class-standing-percentage").val(70);
            $("#edit-examination-percentage").val(30);
            $("#edit-final-class-standing-percentage").val(70);
            $("#edit-final-examination-percentage").val(30);
            $("#edit-special-class-standing-percentage").val(70);
            $("#edit-special-examination-percentage").val(30);
        }

        //console.log("recordTypersd: " + recordType);

        // Hide all grading periods and gradings initially
        $(
            "#edit-first-grading, #edit-second-grading, #edit-third-grading"
        ).addClass("hidden");
        $(
            "#edit-grading-period-1, #edit-grading-period-2, #edit-grading-period-3"
        ).addClass("hidden");
        $("#edit-classrecord-information").addClass("hidden");

        // Show appropriate grading periods based on recordType
        if (recordType === "1") {
            // For recordType '1' (First grading only)
            $("#edit-grade-distribution").removeClass("hidden");
            $("#edit-first-grading").removeClass("hidden"); // Show only first grading
            $("#edit-grading-period-1").removeClass("hidden"); // Show only first grading period
            $("#edit-next-btn").addClass("hidden");
        } else if (recordType === "2") {
            // For recordType '2' (First and second grading)
            $("#edit-grade-distribution").removeClass("hidden");
            $("#edit-first-grading, #edit-second-grading").removeClass(
                "hidden"
            ); // Show first and second grading
            $("#edit-grading-period-1, #edit-grading-period-2").removeClass(
                "hidden"
            ); // Show first and second grading periods
            $("#edit-next-btn").addClass("hidden");
        } else if (recordType === "3") {
            // For recordType '3' (First, second, and third grading)
            $("#edit-grade-distribution").removeClass("hidden");
            $(
                "#edit-first-grading, #edit-second-grading, #edit-third-grading"
            ).removeClass("hidden"); // Show all gradings
            $(
                "#edit-grading-period-1, #edit-grading-period-2, #edit-grading-period-3"
            ).removeClass("hidden"); // Show all grading periods
            $("#edit-next-btn").addClass("hidden");
        } else {
            //console.log("Undefined Record");
        }
    } else {
        Swal.fire({
            title: "Incomplete Form",
            html: `Please address the following:<br><br>${emptyFields.join(
                "<br>"
            )}`,
            icon: "warning",
            confirmButtonText: "OK",
            confirmButtonColor: "#dc2626",
        });
    }

    return allFieldsFilled;
}

// Modal Get Data
$(document).ready(function () {
    $(document).on("click", ".edit-btn", function () {
        var classRecordID = $(this).data("class-record-id");
        //console.log("Class Record ID:", classRecordID);
        // Redirect to the update class record page with the classRecordID
        window.location.href = `update-class-record/${classRecordID}`;
    });
});

// Initialization and validation
document.addEventListener("DOMContentLoaded", function () {
    const nextBtn = document.getElementById("edit-next-btn");

    nextBtn.addEventListener("click", function (e) {
        e.preventDefault();
        validateForm(); // Call validateForm on click
        const step1 = document.querySelector('[data-step="1"] .step-number');
        const step2 = document.querySelector('[data-step="2"] .step-number');
        const stepBorder = document.querySelector(".step-border");

        // Change the border and step 2 appearance
        stepBorder.classList.remove("bg-gray-200");
        stepBorder.classList.add("bg-red-900");
        step2.classList.remove("bg-gray-300", "text-gray-500");
        step2.classList.add("bg-red-900", "text-white");

        $("#edit-grade-config-back-btn").removeClass("hidden");
        $("#edit-grade-config-next-btn").removeClass("hidden");
        // $("#edit-next-btn").addClass("hidden");
    });
});

//Update ClassRecord:
$(document).ready(function () {
    let classRecordID = null;

    // Capture classRecordID when edit-btn is clicked
    $(document).on("click", ".edit-btn", function (e) {
        classRecordID = $(this).data("class-record-id");
        //console.log("Class Record ID captured:", classRecordID);
        $("#edit-submit-btn, #special-edit-submit-btn").data(
            "class-record-id",
            classRecordID
        );
    });

    // Handle submit button click
    $("#edit-submit-btn, #special-edit-submit-btn").on("click", function (e) {
        e.preventDefault();

        function getClassRecordIDFromUrl() {
            const url = window.location.href;
            const parts = url.split("/");
            return parts[parts.length - 1]; // Assuming the classRecordID is the last segment in the URL
        }

        const classRecordID = getClassRecordIDFromUrl();
        // console.log("Class Record ID from URL:", classRecordID);

        if (classRecordID === null) {
            console.error("Class Record ID is missing");
            Swal.fire({
                title: "Error",
                text: "Class Record ID is missing",
                icon: "error",
                confirmButtonText: "OK",
            });
            return;
        }

        // console.log("Updating Class Record ID:", classRecordID);
        // const recordType = String(window.recordType); // Ensure recordType is a string
        const recordType = $("#edit-grading-period").val();
        // console.log("Record Type:", recordType);

        // Gather form data using FormData
        const formData = new FormData();
        let midtermTotal = 0;
        let finalsTotal = 0;
        let specialTotal = 0;

        // Grading distribution data
        const gradingDistributions = [];

        if (recordType === "1") {
            // 1st Grading Period: Only Midterm
            midtermTotal = calculateTotal(
                ".edit-first-grade-distribution-input"
            );
            if (midtermTotal !== 100) {
                Swal.fire({
                    title: "Invalid Grading Distribution",
                    html: `The total percentage for Midterm must be 100%.<br><br>
                           Current total: ${midtermTotal}%`,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Stop form submission
            }

            // Capture grading distribution
            gradingDistributions.push({
                gradingDistributionType: $(
                    ".edit-first-grade-type-input"
                ).val(),
                gradingDistributionPercentage: midtermTotal,
                term: 1,
            });
        } else if (recordType === "2") {
            // 2nd Grading Period: Midterm and Finals
            midtermTotal = calculateTotal(
                ".edit-first-grade-distribution-input"
            );
            finalsTotal = calculateTotal(
                ".edit-second-grade-distribution-input"
            );

            if (midtermTotal + finalsTotal !== 100) {
                Swal.fire({
                    title: "Invalid Grading Distribution",
                    html: `The total percentage for Midterm and Finals must be 100%.<br><br>
                           Current totals:<br>
                           Midterm: ${midtermTotal}%<br>
                           Finals: ${finalsTotal}%`,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Stop form submission
            }

            // Capture grading distribution
            gradingDistributions.push(
                {
                    gradingDistributionType: $(
                        ".edit-first-grade-type-input"
                    ).val(),
                    gradingDistributionPercentage: midtermTotal,
                    term: 1,
                },
                {
                    gradingDistributionType: $(
                        ".edit-second-grade-type-input"
                    ).val(),
                    gradingDistributionPercentage: finalsTotal,
                    term: 2,
                }
            );
        } else if (recordType === "3") {
            // 3rd Grading Period: Midterm, Finals, and Special
            midtermTotal = calculateTotal(
                ".edit-first-grade-distribution-input"
            );
            finalsTotal = calculateTotal(
                ".edit-second-grade-distribution-input"
            );
            specialTotal = calculateTotal(
                ".edit-third-grade-distribution-input"
            );

            if (midtermTotal + finalsTotal + specialTotal !== 100) {
                Swal.fire({
                    title: "Invalid Grading Distribution",
                    html: `The total percentage for Midterm, Finals, and Special must be 100%.<br><br>
                           Current totals:<br>
                           Midterm: ${midtermTotal}%<br>
                           Finals: ${finalsTotal}%<br>
                           Special: ${specialTotal}%`,
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Stop form submission
            }

            // Capture grading distribution
            gradingDistributions.push(
                {
                    gradingDistributionType: $(
                        ".edit-first-grade-type-input"
                    ).val(),
                    gradingDistributionPercentage: midtermTotal,
                    term: 1,
                },
                {
                    gradingDistributionType: $(
                        ".edit-second-grade-type-input"
                    ).val(),
                    gradingDistributionPercentage: finalsTotal,
                    term: 2,
                },
                {
                    gradingDistributionType: $(
                        ".edit-third-grade-type-input"
                    ).val(),
                    gradingDistributionPercentage: specialTotal,
                    term: 3,
                }
            );
        }
        gradingDistributions.forEach((item, index) => {
            formData.append(
                `gradingDistributions[${index}][gradingDistributionType]`,
                item.gradingDistributionType
            );
            formData.append(
                `gradingDistributions[${index}][gradingDistributionPercentage]`,
                item.gradingDistributionPercentage
            );
            formData.append(`gradingDistributions[${index}][term]`, item.term);
        });

        const schedules = [];
        // Loop through each "edit-day-row" to gather day and time data
        $(".edit-day-row").each(function () {
            const dayContainer = $(this).find(".edit-day-container"); // The select dropdown for the day
            if (dayContainer.hasClass("bg-red-900")) {
                // Ensure the day is selected (has the bg-red-900 class)
                const day = dayContainer.val(); // Get the selected day value from the dropdown
                const timeInputs = $(this).find(
                    '.edit-time-inputs input[type="time"]'
                ); // Get all time input fields
                let times = [];

                // Loop through the time inputs in pairs (start time and end time)
                for (let i = 0; i < timeInputs.length; i += 2) {
                    const startTime = timeInputs[i].value;
                    const endTime = timeInputs[i + 1].value;
                    // Only push valid time slots (both start and end times must be filled)
                    if (startTime && endTime) {
                        times.push(`${startTime}-${endTime}`);
                    }
                }

                // Only add to schedules if there are valid times for the selected day
                if (times.length > 0) {
                    schedules.push({
                        day: day, // Store the selected day
                        times: times, // Store the array of times for that day
                    });
                }
            }
        });

        formData.append("schoolYear", $("#edit-academic-year").val());
        formData.append("schedules", JSON.stringify(schedules));
        formData.append("semester", $("#edit-semester").val());
        formData.append("yearLevel", $("#edit-year-level").val());
        formData.append("recordType", recordType);
        formData.append("branch", $("#edit-branch-select").val());
        formData.append("programID", $("#edit-program-select").val());
        formData.append("courseID", $("#edit-course-select").val());

        // Grading Data Processing
        const gradingData = [];

        // Validation before processing grades
        if (recordType === "1") {
            // Midterm only
            const midtermTotal = calculateTotal(".edit-grade-input");

            if (midtermTotal !== 100) {
                Swal.fire({
                    title: "Error!",
                    text: "total percentage must equal 100%.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Prevent submission
            }
            processGrades(
                ".edit-grade-input",
                1,
                "#edit-class-standing-container",
                "#edit-examination-container"
            );
        } else if (recordType === "2") {
            // Midterm and Finals
            const midtermTotal = calculateTotal(".edit-grade-input");
            const finalsTotal = calculateTotal(".edit-grade-input-finals");
            // console.log("1st grading: " + midtermTotal);
            // console.log("2nd grading: " + finalsTotal);
            if (midtermTotal !== 100 || finalsTotal !== 100) {
                Swal.fire({
                    title: "Error!",
                    text: "total percentages must equal 100%.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Prevent submission
            }
            processGrades(
                ".edit-grade-input",
                1,
                "#edit-class-standing-container",
                "#edit-examination-container"
            );
            processGrades(
                ".edit-grade-input-finals",
                2,
                "#edit-final-class-standing-container",
                "#edit-final-examination-container"
            );
        } else if (recordType === "3") {
            // Midterm, Finals, and Special
            const midtermTotal = calculateTotal(".edit-grade-input");
            const finalsTotal = calculateTotal(".edit-grade-input-finals");
            const specialTotal = calculateTotal(".edit-grade-input-special");

            if (
                midtermTotal !== 100 ||
                finalsTotal !== 100 ||
                specialTotal !== 100
            ) {
                Swal.fire({
                    title: "Error!",
                    text: "total percentages must equal 100%.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return; // Prevent submission
            }
            processGrades(
                ".edit-grade-input",
                1,
                "#edit-class-standing-container",
                "#edit-examination-container"
            );
            processGrades(
                ".edit-grade-input-finals",
                2,
                "#edit-final-class-standing-container",
                "#edit-final-examination-container"
            );
            processGrades(
                ".edit-grade-input-special",
                3,
                "#edit-special-class-standing-container",
                "#edit-special-examination-container"
            );
        }

        function processGrades(
            inputSelector,
            term,
            classStandingSelector,
            examinationSelector
        ) {
            $(inputSelector).each(function () {
                const percentage = $(this).val();
                if (percentage && percentage !== "0") {
                    // Check for input.assessment-type-input; fallback to p tag if not found
                    const assessmentType =
                        $(this)
                            .closest("div")
                            .find("input.assessment-type-input")
                            .val() || // Try input value first
                        $(this)
                            .closest("div")
                            .find("p")
                            .text()
                            .replace(":", "")
                            .trim(); // Fallback to p text

                    const isExamination =
                        $(this).closest(examinationSelector).length > 0;

                    gradingData.push({
                        assessmentType: assessmentType, // Use dynamic input or fallback p text
                        term: term,
                        percentage: percentage,
                        isExamination: isExamination,
                    });
                }
            });
        }

        // Append grading data to form data
        gradingData.forEach((item, index) => {
            formData.append(
                `grading[${index}][assessmentType]`,
                item.assessmentType
            );
            formData.append(`grading[${index}][term]`, item.term);
            formData.append(`grading[${index}][percentage]`, item.percentage);
            formData.append(
                `grading[${index}][isExamination]`,
                item.isExamination
            );
        });

        // console.log("Filtered Grading Data:", JSON.stringify(gradingData));

        // Append the image file if it exists
        const imageFile = $("#edit-image-upload")[0].files[0];
        if (imageFile) {
            formData.append("classImg", imageFile);
        }

        // Get CSRF token from meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $("#loader-modal-update").removeClass("hidden");
        $("body").addClass("no-scroll");

        // Send update request
        $.ajax({
            url: `/update-class-record/${classRecordID}`,
            method: "POST", // Using POST method with PUT override
            data: formData,
            contentType: false, // Needed to send FormData
            processData: false, // Needed to send FormData
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-HTTP-Method-Override": "PUT", // Override method to PUT
            },
            success: function (response) {
                // console.log(response.message);
                // Swal.fire({
                //     title: "Success",
                //     text: "Class record updated successfully!",
                //     icon: "success",
                //     confirmButtonText: "OK",
                // }).then(() => {
                //     window.location.reload();
                // });

                Swal.fire({
                    title: "Success!",
                    text: response.message,
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = response.redirect_url;
                });
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.errors);
                Swal.fire({
                    title: "Error",
                    text: xhr.responseJSON.errors
                        ? Object.values(xhr.responseJSON.errors)
                              .flat()
                              .join("\n")
                        : "An error occurred while updating the class record.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            },
            complete: function () {
                $("#loader-modal-update").addClass("hidden");
                $("body").removeClass("no-scroll");
            },
        });
    });

    function calculateTotal(inputSelector) {
        let total = 0;
        $(inputSelector).each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        return total;
    }

    // function getSelectedDays() {
    //     let selectedDays = [];
    //     $(".edit-day-button.bg-red-900").each(function () {
    //         selectedDays.push($(this).text().trim());
    //     });
    //     return selectedDays.join("/");
    // }
});

//stepper
document.addEventListener("DOMContentLoaded", function () {
    const gradingPeriodSelect = document.getElementById("grading-period");
    const editGradingPeriodSelect = document.getElementById(
        "edit-grading-period"
    );
    const stepper = document.getElementById("stepper");

    // console.log(
    //     "Edit Mode Grading Period Value:",
    //     editGradingPeriodSelect ? editGradingPeriodSelect.value : "Not found"
    // );

    function updateStepper(terms) {
        stepper.innerHTML = "";
        for (let i = 1; i <= terms + 2; i++) {
            const stepContainer = document.createElement("div");
            stepContainer.className = "step-container flex items-center";

            const step = document.createElement("div");
            step.className = "step z-20";
            step.setAttribute("data-step", i);

            const stepNumber = document.createElement("div");
            stepNumber.className = `step-number flex justify-center items-center md:w-8 md:h-8 h-7 w-7 rounded-full ${
                i === 1 ? "bg-red-900 text-white" : "bg-gray-300 text-gray-500"
            } font-bold transition-all duration-300`;
            stepNumber.textContent = i;

            step.appendChild(stepNumber);
            stepContainer.appendChild(step);
            stepper.appendChild(stepContainer);

            // Add step border with the specified class for edit mode
            if (i < terms + 2) {
                const stepBorder = document.createElement("div");
                stepBorder.className =
                    "step-border md:w-14 w-7 h-2 z-10 bg-gray-200 transition-all duration-300"; // Set class for step border
                stepContainer.appendChild(stepBorder);
            }
        }
    }

    function handleGradingPeriodChange(selectElement) {
        const selectedValue = parseInt(selectElement.value);
        // console.log("Selected grading period:", selectedValue);
        if (selectedValue) {
            updateStepper(selectedValue);
        } else {
            stepper.innerHTML = ""; // Clear stepper if no value is selected
        }
    }

    function initializeStepper(selectElement) {
        if (selectElement) {
            // console.log("Initializing stepper for:", selectElement.id);
            const selectedValue = parseInt(selectElement.value);
            if (selectedValue) {
                updateStepper(selectedValue);
            }

            selectElement.addEventListener("change", function () {
                handleGradingPeriodChange(this);
            });
        }
    }

    // Initialize stepper for normal mode
    initializeStepper(gradingPeriodSelect);

    // For edit mode, we'll initialize the stepper after fetching the data
    if (editGradingPeriodSelect) {
        // console.log("Edit mode detected, waiting for AJAX call");

        $(document).ajaxComplete(function (event, xhr, settings) {
            if (settings.url.includes("/get-grading-distribution/")) {
                // console.log("Grading distribution AJAX call completed");
                const recordType = $("#edit-grading-period").val();
                // console.log("Record Type from AJAX:", recordType);

                // Initialize the stepper based on the fetched recordType
                initializeStepper(editGradingPeriodSelect);

                // Trigger the change event to update stepper
                $("#edit-grading-period").trigger("change");
            }
        });
    }
});



//Get ClassRecord Data
$(document).ready(function () {
    // Function to get classRecordID from the URL
    function getClassRecordIDFromUrl() {
        const url = window.location.href;
        const parts = url.split("/");
        return parts[parts.length - 1]; // Assuming the classRecordID is the last segment in the URL
    }

    const classRecordID = getClassRecordIDFromUrl();
    // console.log("Class Record ID from URL:", classRecordID);

    // Check if classRecordID exists, then populate the data

    $("#edit-classrecord-information").removeClass("hidden");
    $("#edit-normal-grade-configuration").addClass("hidden");

    let activeAjaxCalls = 0;

    function showLoader() {
        $("#loader-get-update").show();
    }

    function hideLoader() {
        if (activeAjaxCalls === 0) {
            $("#loader-get-update").hide();
        }
    }
    

    $.ajax({
        url: `/get-class-record`,
        method: "POST",
        data: { id: classRecordID },
        
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            // On edit load
            // console.log("Class Record Data:", data);

            // Function to fetch and populate branches
            function fetchBranches(currentBranchID) {
                $.ajax({
                    url: "/get-branches", // Adjust the URL to your endpoint that returns branches
                    method: "GET",
                    success: function (response) {
                        const branchSelect = $("#edit-branch-select");
                        branchSelect
                            .empty()
                            .append('<option value="">Select Branch</option>'); // Clear previous options

                        if (Array.isArray(response.branches)) {
                            response.branches.forEach(function (branch) {
                                branchSelect.append(
                                    `<option value="${branch.branchID}">${branch.branchDescription}</option>`
                                );
                            });

                            // Set the current branch as selected
                            if (currentBranchID) {
                                branchSelect.val(currentBranchID).change(); // Set branch value and trigger change
                            }
                        } else {
                            console.error(
                                "Unexpected branches data format:",
                                response.branches
                            );
                        }
                    },
                    error: function (err) {
                        console.error("Error fetching branches:", err);
                    },
                });
            }

            // Set the initial branch value
            if (data.branch && data.branch.branchID) {
                fetchBranches(data.branch.branchID); // Fetch branches and set the current branch
            }

            // Set program value if available
            if (data.program && data.program.programID) {
                $("#edit-program-select").val(data.program.programID); // Set program value
            }

            // Set course value if available
            if (data.course && data.course.courseID) {
                $("#edit-course-select").val(data.course.courseID); // Set course value
            }

            // Fetch programs based on the initial branch
            function fetchPrograms(branchID) {
                $.ajax({
                    url: `/get-program/${branchID}`,
                    method: "GET",
                    success: function (response) {
                        const programSelect = $("#edit-program-select");
                        programSelect
                            .empty()
                            .append('<option value="">Select Program</option>'); // Clear previous options

                        if (Array.isArray(response.programs)) {
                            response.programs.forEach(function (program) {
                                programSelect.append(
                                    `<option value="${program.programID}">${program.programTitle}</option>`
                                );
                            });

                            // Set the program value if it exists
                            if (data.program && data.program.programID) {
                                programSelect.val(data.program.programID); // Keep the current program value
                            }

                            // Trigger change event to fetch courses
                            programSelect.trigger("change");
                        } else {
                            console.error(
                                "Unexpected programs data format:",
                                response.programs
                            );
                        }
                    },
                    error: function (err) {
                        console.error("Error fetching programs:", err);
                    },
                });
            }

            // Fetch courses based on the selected program
            function fetchCourses(programID) {
                $.ajax({
                    url: `/get-courses/${programID}`,
                    method: "GET",
                    success: function (coursesData) {
                        const courseSelect = $("#edit-course-select");
                        courseSelect
                            .empty()
                            .append('<option value="">Select Course</option>'); // Clear previous options

                        if (Array.isArray(coursesData.courses)) {
                            coursesData.courses.forEach(function (course) {
                                courseSelect.append(
                                    `<option value="${course.courseID}" data-course-code="${course.courseCode}">${course.courseTitle}</option>`
                                );
                            });

                            // Set the selected course if it exists
                            if (data.course && data.course.courseID) {
                                courseSelect.val(data.course.courseID); // Keep the current course value

                                // Display the courseCode of the currently selected course
                                const selectedCourseCode = courseSelect
                                    .find(":selected")
                                    .data("course-code");
                                $("#edit-course-description").text(
                                    selectedCourseCode || "Select a course"
                                );
                            }
                        } else {
                            console.warn(
                                "No courses data available for the selected program"
                            );
                        }
                    },
                    error: function (err) {
                        console.error("Error fetching courses:", err);
                    },
                });
            }

            // Update the displayed courseCode when a new course is selected
            $("#edit-course-select").on("change", function () {
                const selectedOption = $(this).find(":selected");
                const courseCode = selectedOption.data("course-code");
                $("#edit-course-description").text(
                    courseCode || "Select a course"
                );
            });

            // Initial program and course load based on branch
            if (data.branch && data.branch.branchID) {
                fetchPrograms(data.branch.branchID); // Fetch programs for the current branch
            }

            // Event listener for branch changes
            $("#edit-branch-select").on("change", function () {
                const newBranchID = $(this).val(); // Get the new branch ID from the select element

                // Clear course selection when changing branches
                $("#edit-course-select")
                    .empty()
                    .append('<option value="">Select Course</option>');

                // Fetch programs for the new branch ID
                if (newBranchID) {
                    fetchPrograms(newBranchID);
                }
            });

            // Event listener for program changes
            $("#edit-program-select").on("change", function () {
                const selectedProgramID = $(this).val(); // Get the selected program ID

                // Clear course selection when changing programs
                $("#edit-course-select")
                    .empty()
                    .append('<option value="">Select Course</option>');

                // Fetch courses for the selected program
                if (selectedProgramID) {
                    fetchCourses(selectedProgramID);
                }
            });

            // Populate other fields
            $("#edit-year-level").val(data.yearLevel);

            $("#edit-academic-year").val(data.schoolYear);
            $("#edit-semester").val(data.semester);
            $("#edit-sched-time").val(data.schedTime);

            // Handle the image preview if available
            if (data.classImgUrl) {
                $("#edit-image-preview").attr("src", data.classImgUrl);
            } else {
                $("#edit-image-preview").attr(
                    "src",
                    "https://via.placeholder.com/150"
                );
            }

            // Update the day buttons based on schedDay
            // const schedDays = data.schedDay ? data.schedDay.split("/") : []; // Split the schedDay value
            // initializeDayButtons(schedDays);
            $("#edit-sched-day").val(data.schedDay);

            const recordType = data.recordType;
            // console.log("Record Type:", recordType);
            window.recordType = recordType;
            if (
                recordType === null ||
                recordType === undefined ||
                recordType === ""
            ) {
                $("#edit-grading-period").prop("disabled", false); // Remove 'disabled' using jQuery
                $("#edit-grading-period").css("border", "2px solid red"); // Add red border
         
            } else {
                $("#edit-grading-period").css("border", ""); // Reset border if condition is false
                $("#edit-grading-period").css("background-color", ""); // Reset background color if condition is false
            }
            
            $("#edit-grading-period").on("change", function () {
                if ($(this).val()) { // If the value is not empty
                    $(this).css("border", ""); // Remove the red border
                    $(this).css("background-color", ""); // Remove the red background color
                }
            });
            

            // Fetch grading data
            $.ajax({
                url: `/get-class-record-grading/${classRecordID}`,
                method: "GET",
                success: function (gradingData) {
                    // console.log("Grading Data:", gradingData);

                    if (Array.isArray(gradingData)) {
                        // console.log("Before forEach:", gradingData);

                        // Populate grades
                        gradingData.forEach(function (grade) {
                            // console.log("Processing grade:", grade);

                            let container, inputClass, subContainer;

                            if (grade.term === 1) {
                                container = $("#edit-midterm-grade");
                                inputClass = "edit-grade-input";
                            } else if (grade.term === 2) {
                                container = $("#edit-final-grade");
                                inputClass = "edit-grade-input-finals";
                            } else if (grade.term === 3) {
                                container = $("#edit-special-grade");
                                inputClass = "edit-grade-input-special";
                            }

                            // console.log("Container:", container);

                            if (container && container.length) {
                                if (grade.isExamination) {
                                    subContainer = container.find(
                                        "[id$='examination-container']"
                                    );
                                    inputClass += " examination-input";
                                } else {
                                    subContainer = container.find(
                                        "[id$='class-standing-container']"
                                    );
                                }

                                // console.log("SubContainer:", subContainer);

                                let existingInput = subContainer
                                    .find(
                                        `p:contains("${grade.assessmentType}")`
                                    )
                                    .closest("div")
                                    .find("input");

                                // console.log("Existing input:", existingInput);

                                // Helper function to remove unnecessary decimals

                                if (existingInput.length) {
                                    // console.log("Updating existing input");
                                    existingInput.val(
                                        formatPercentage(grade.percentage)
                                    );
                                    // console.log(
                                    //     "Value set to:",
                                    //     existingInput.val()
                                    // );
                                    if (grade.isExamination) {
                                        existingInput.addClass(
                                            "examination-input"
                                        );
                                    } else {
                                        existingInput.removeClass(
                                            "examination-input"
                                        );
                                    }
                                    existingInput.trigger("input");
                                } else {
                                    // console.log("Creating new assessment");
                                    let newAssessment = `
                                            <div class="flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">${
                                                    grade.assessmentType
                                                }:</p>
                                                <input type="number" class="text-center border-b border-black focus:outline-none w-20 ${inputClass}" min="0" max="100" value="${formatPercentage(
                                        grade.percentage
                                    )}">
                                            <span>%</span>
                                            </div>
                                        `;
                                    subContainer.append(newAssessment);
                                    let newInput = subContainer
                                        .find("input")
                                        .last();
                                    // console.log("New input created:", newInput);
                                    // console.log(
                                    //     "Value set to:",
                                    //     newInput.val()
                                    // );
                                    newInput.trigger("input");
                                }
                            } else {
                                console.error(
                                    "Container not found for term:",
                                    grade.term
                                );
                            }
                        });

                        // console.log("After populating grades");

                        if (typeof updateTotal === "function") {
                            // console.log("Calling updateTotal");
                            updateTotal("edit-", false, true);
                        } else {
                            console.error(
                                "updateTotal function is not defined"
                            );
                        }
                        if (typeof updateFinalTotal === "function") {
                            // console.log("Calling updateFinalTotal");
                            updateFinalTotal("edit-", false, true);
                        } else {
                            console.error(
                                "updateFinalTotal function is not defined"
                            );
                        }
                        if (typeof updateSpecialTotal === "function") {
                            // console.log("Calling updateFinalTotal");
                            updateSpecialTotal("edit-", false, true);
                        } else {
                            console.error(
                                "updateFinalTotal function is not defined"
                            );
                        }
                    } else {
                        console.error(
                            "Grading data is not an array:",
                            gradingData
                        );
                    }

                    // Call setupGradeSection and setupFinalGradeSection after populating the data
                    // console.log("Calling setupGradeSection");
                    setupGradeSection("edit-", true);
                    // console.log("Calling setupFinalGradeSection");
                    setupFinalGradeSection("edit-", true);
                    // console.log("Calling setupFinalGradeSection");
                    setupSpecialGradeSection("edit-", true);
                },
                error: function (err) {
                    console.error("Error fetching grading data:", err);
                },
            });

            function formatPercentage(value) {
                // Check if the value is a whole number
                if (value % 1 === 0) {
                    return parseInt(value); // Remove the decimal part
                } else {
                    return value.toFixed(2); // Keep two decimal places for non-integers
                }
            }

            // Assuming you're retrieving data and have the correct structure
            $.ajax({
                url: `/get-grading-distribution/${classRecordID}`,
                method: "GET",
                success: function (distributionData) {
                    // Assuming data.recordType is set correctly
                    // console.log("Grading Distribution Data:", distributionData);
                    $("#edit-grading-period").val(data.recordType); // Set the grading period

                    // Trigger the change event to update button visibility
                    $("#edit-grading-period").trigger("change");

                    // Your existing code to handle distribution data
                    if (Array.isArray(distributionData)) {
                        distributionData.forEach(function (item) {
                            const formattedPercentage = formatPercentage(
                                item.gradingDistributionPercentage
                            );

                            // Check the term and populate the corresponding inputs
                            if (item.term === 1) {
                                $(".edit-first-grade-type-input").val(
                                    item.gradingDistributionType
                                );
                                $(".edit-first-grade-distribution-input").val(
                                    formattedPercentage
                                );
                            } else if (item.term === 2) {
                                $(".edit-second-grade-type-input").val(
                                    item.gradingDistributionType
                                );
                                $(".edit-second-grade-distribution-input").val(
                                    formattedPercentage
                                );
                            } else if (item.term === 3) {
                                $(".edit-third-grade-type-input").val(
                                    item.gradingDistributionType
                                );
                                $(".edit-third-grade-distribution-input").val(
                                    formattedPercentage
                                );
                            }
                        });
                        updateGradingNames(true); // Pass `true` for the 'edit-' prefix
                    } else {
                        console.error(
                            "Expected an array of grading distribution data but got:",
                            distributionData
                        );
                    }
                },
                error: function (err) {
                    console.error(
                        "Error fetching grading distribution data:",
                        err
                    );
                },
            });

            $.ajax({
                url: `/get-schedule/${classRecordID}`,
                method: "GET",
                success: function (scheduleData) {
                    // console.log("Schedule Data:", scheduleData);
                    if (scheduleData.success && scheduleData.schedules) {
                        initializeEditMode(scheduleData.schedules);
                    } else {
                        console.error(
                            "Error fetching schedule data:",
                            scheduleData.message
                        );
                    }
                },
                error: function (err) {
                    console.error("Error fetching schedule:", err);
                },
            });

            // Show the modal
            $("#update-modal").removeClass("hidden");
        },
        error: function (err) {
            console.error("Error fetching class record:", err);
        },
        
    });
    function initializeEditMode(schedules) {
        schedules.forEach((schedule) => {
            const times = schedule.scheduleTime.split(" / "); // Split multiple time slots

            // Loop through each time slot for a given day
            times.forEach((time, index) => {
                let dayRow;

                // Check if a row for this day exists already
                dayRow = $(".edit-day-row").filter(function () {
                    return (
                        $(this).find(".edit-day-container").val() ===
                        schedule.scheduleDay
                    );
                });

                if (dayRow.length === 0) {
                    // If no row exists, create a new row
                    $(".edit-add-schedule-button").before(`
                        <div class="edit-day-row flex flex-col gap-2 mb-2">
                            <div class=" edit-day-row flex md:flex-row flex-col gap-5 items-center -mb-2">
                                                        <div class="flex gap-2 flex-col">
            
                                                            <select name="" id=""
                                                                class="edit-day-container bg-red-900 flex flex-col gap-2 rounded-2xl p-2 shadow-md border border-gray-300  day-container text-gray-700">
                                                                <option value="">Select Day</option>
                                                                <option value="Monday">Monday</option>
                                                                <option value="Tuesday">Tuesday</option>
                                                                <option value="Wednesday">Wednesday</option>
                                                                <option value="Thursday">Thursday</option>
                                                                <option value="Friday">Friday</option>
                                                                <option value="Saturday">Saturday</option>
                                                                <option value="Sunday">Sunday</option>
                                                            </select>
                                                        </div>

                                                        <div class="flex gap-2 flex-col">
                                                         
                                                            <div class="flex flex-row gap-2 px-4">
                                                                <div class="edit-time-inputs flex flex-col md:gap-2 gap-1 ">
                                                                    <div class="flex md:gap-2 gap-1">
                                                                        <input type="time"
                                                                            class="edit-start-time border border-gray-300 rounded-2xl md:p-2 p-2  md:w-full w-24 text-gray-700"
                                                                            required >
                                                                        <span class="self-center">to</span>
                                                                        <input type="time"
                                                                            class="edit-end-time border border-gray-300 rounded-2xl md:p-2 p-2 md:w-full w-24 text-gray-700"
                                                                            required >
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-center items-center">
                                                                    <i
                                                                        class="edit-add-time  fa-solid fa-plus cursor-pointer text-gray-400 text-lg dark:text-[#CCAA2C] bg-white p-1 rounded-md shadow-lg border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                        </div>
                    `);
                    // Select the newly created row
                    dayRow = $(".edit-day-row").last();
                }

                // Set the selected day in the dropdown and apply styling
                const dayContainer = dayRow.find(".edit-day-container");
                dayContainer
                    .val(schedule.scheduleDay)
                    .addClass("bg-red-900 text-white");

                // Add the time slot to the row if it's not the first time slot
                if (index > 0) {
                    // Add a new time input row for the additional time slot
                    const timeInputsContainer =
                        dayRow.find(".edit-time-inputs");
                    timeInputsContainer.append(`
                        <div class="flex gap-2">
                            <input type="time" class="edit-start-time border border-gray-300 rounded-2xl md:p-2 p-2  md:w-full w-24" required>
                            <span class="self-center">to</span>
                            <input type="time" class="edit-end-time border border-gray-300 rounded-2xl md:p-2 p-2  md:w-full w-24" required>
                        </div>
                    `);
                }

                // Parse and set the start and end times for the current time slot
                const [start, end] = time.split("-");
                dayRow
                    .find(".edit-time-inputs .edit-start-time")
                    .last()
                    .val(start); // Set the start time for this slot
                dayRow.find(".edit-time-inputs .edit-end-time").last().val(end); // Set the end time for this slot
            });
        });
    }
});

$(document).ready(function () {
    // When a day is selected, change background color, reset time inputs, and disable them
    $(document).on(
        "change",
        ".day-container, .edit-day-container",
        function () {
            const dayRow = $(this).closest(".day-row, .edit-day-row");
            const timeInputs = dayRow.find('input[type="time"]');
            const addTimeButton = dayRow.find(".add-time, .edit-add-time");

            // Check if a valid day is selected
            if ($(this).val()) {
                // Change background and text color for the selected day
                $(this)
                    .addClass("bg-red-900 text-white")
                    .removeClass("bg-white text-black");

                // Enable time inputs and the add time button
                timeInputs.prop("disabled", false); // Enable time inputs
                addTimeButton.removeClass("disabled"); // Enable add time button
            } else {
                // Reset background and text color to default (white background and black text)
                $(this)
                    .removeClass("bg-red-900 text-white")
                    .addClass("bg-white text-black");

                // Disable time inputs when no day is selected
                timeInputs.prop("disabled", true).val(""); // Clear any existing time values

                // Disable the add time button
                addTimeButton.addClass("disabled");
            }

            // Optionally, reset the time inputs
            // dayRow.find(".start-time, .end-time").val("");
        }
    );

    // Use event delegation to add new time input fields when the "+" icon is clicked (both regular and edit modes)
    // Event delegation for adding new time input fields
    $(document).on(
        "click",
        ".add-time:not(.disabled), .edit-add-time:not(.disabled), .remove-time",
        function () {
            const dayRow = $(this).closest(".day-row, .edit-day-row");
            const timeContainer = dayRow.find(
                ".time-inputs, .edit-time-inputs"
            );
            const daySelected = dayRow
                .find(".day-container, .edit-day-container")
                .val();

            // Check if a day is selected before any action
            if (!daySelected) {
                Swal.fire({
                    icon: "error",
                    title: "No Day Selected",
                    text: "Please select a day before adding or removing a time.",
                });
                return; // Prevent any action if no day is selected
            }

            if (
                $(this).hasClass("add-time") ||
                $(this).hasClass("edit-add-time")
            ) {
                // Check if a time input already exists
                if (timeContainer.find(".time-row").length > 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Limit Reached",
                        text: "You can only add time once for this day.",
                    });
                    return; // Prevent adding more than one time input
                }

                // Add new time input
                const newTimeInput = `
                <div class="flex gap-2 mt-2 time-row">
                    <input type="time" class="start-time border border-gray-300 rounded-2xl md:p-2 p-2  md:w-full w-24" required >
                    <span class="self-center">to</span>
                    <input type="time" class="end-time border border-gray-300 rounded-2xl md:p-2 p-2 md:w-full w-24" required>
                </div>
            `;

                timeContainer.append(newTimeInput);

                // Change Add-Time to Remove-Time
                $(this)
                    .removeClass("add-time fa-plus edit-add-time")
                    .addClass("remove-time fa-minus")
                    .attr("title", "Remove Time");
            } else if ($(this).hasClass("remove-time")) {
                // Remove the time input
                const timeRow = timeContainer.find(".time-row");
                if (timeRow.length > 0) {
                    timeRow.remove();

                    // Swal.fire({
                    //     icon: "success",
                    //     title: "Removed",
                    //     text: "The time input has been removed successfully.",
                    // });

                    // Change Remove-Time back to Add-Time
                    $(this)
                        .removeClass("remove-time fa-minus")
                        .addClass("add-time fa-plus")
                        .attr("title", "Add Time");
                }
            }
        }
    );

    // Prevent interaction with disabled time inputs and add buttons

    // Add new schedule row when "Add Schedule" button is clicked (both regular and edit modes)
    $(document).on(
        "click",
        ".add-schedule-button, .edit-add-schedule-button",
        function () {
            const newDayRow = `
            <div class="day-row flex flex-col gap-2 mb-2">
                                                    <div class="flex md:flex-row flex-col gap-5 items-center -mb-2">
                                                        <div class="flex gap-2 flex-col">
                                                            <div class="inline sm:hidden">
                                                                <div class="flex items-center justify-center font-bold  ">
                                                                    <span>Day</span>
                                                            </div>
                                                            </div>
                                                            <select name="" id=""
                                                                class="flex flex-col gap-2 rounded-2xl p-2 shadow-md border border-gray-300  day-container text-gray-700">
                                                                <option value="">Select Day</option>
                                                                <option value="Monday">Monday</option>
                                                                <option value="Tuesday">Tuesday</option>
                                                                <option value="Wednesday">Wednesday</option>
                                                                <option value="Thursday">Thursday</option>
                                                                <option value="Friday">Friday</option>
                                                                <option value="Saturday">Saturday</option>
                                                                <option value="Sunday">Sunday</option>
                                                            </select>
                                                        </div>

                                                        <div class="flex gap-2 flex-col">
                                                            <div class="inline sm:hidden">
                                                                <div class="flex items-center justify-center font-bold ">
                                                                    <span>Time</span>
                                                            </div>
                                                            </div>
                                                            <div class="flex flex-row gap-2 px-4">
                                                                <div class="time-inputs flex flex-col md:gap-2 gap-1 ">
                                                                    <div class="flex md:gap-2 gap-1">
                                                                        <input type="time"
                                                                            class="start-time border border-gray-300 rounded-2xl p-2  md:w-full w-24 text-gray-700"
                                                                            required disabled>
                                                                        <span class="self-center">to</span>
                                                                        <input type="time"
                                                                            class="end-time border border-gray-300 rounded-2xl p-2  md:w-full w-24 text-gray-700"
                                                                            required disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-center items-center">
                                                                    <i
                                                                        class="add-time fa-solid fa-plus cursor-pointer text-gray-400 text-lg dark:text-[#CCAA2C] bg-white p-1 rounded-md shadow-lg border border-gray-300"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;

            const newEditDayRow = `
            <div class="edit-day-row flex flex-col gap-2 mb-2">
                                                    <div class="flex md:flex-row flex-col gap-5 items-center -mb-2">
                                                        <div class="flex gap-2 flex-col">
                                                    
                                                            <select name="" id=""
                                                                class="edit-day-container flex flex-col gap-2 rounded-2xl p-2 shadow-md border border-gray-300  day-container text-gray-700">
                                                                <option value="">Select Day</option>
                                                                <option value="Monday">Monday</option>
                                                                <option value="Tuesday">Tuesday</option>
                                                                <option value="Wednesday">Wednesday</option>
                                                                <option value="Thursday">Thursday</option>
                                                                <option value="Friday">Friday</option>
                                                                <option value="Saturday">Saturday</option>
                                                                <option value="Sunday">Sunday</option>
                                                            </select>
                                                        </div>

                                                        <div class="flex gap-2 flex-col">
                                
                                                            <div class="flex flex-row gap-2 px-4">
                                                                <div class="edit-time-inputs flex flex-col md:gap-2 gap-1 text-gray-700">
                                                                    <div class="flex md:gap-2 gap-1">
                                                                        <input type="time"
                                                                            class="edit-start-time border border-gray-300 rounded-2xl p-2  md:w-full w-24"
                                                                            required disabled>
                                                                        <span class="self-center">to</span>
                                                                        <input type="time"
                                                                            class="edit-end-time border border-gray-300 rounded-2xl p-2  md:w-full w-24"
                                                                            required disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-center items-center">
                                                                    <i
                                                                        class="edit-add-time fa-solid fa-plus cursor-pointer text-gray-400 text-lg dark:text-[#CCAA2C] bg-white p-1 rounded-md shadow-lg border border-gray-300 hover:bg-gray-200"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
            
            `;

            if ($(this).hasClass("edit-add-schedule-button")) {
                $(this).before(newEditDayRow); // Insert new edit schedule row before the edit add schedule button
            } else {
                $(this).before(newDayRow); // Insert new regular schedule row before the add schedule button
            }
        }
    );
});

//old day and time
// $(document).ready(function () {
//     // Toggle background and text color for the div and button on click (for both create and edit modes)
//     $(document).on("click", ".day-container", function () {
//         const isEditMode = $(this).closest(".edit-day-row").length > 0;
//         const dayRow = $(this).closest(
//             isEditMode ? ".edit-day-row" : ".day-row"
//         );
//         const dayButton = $(this).find(
//             isEditMode ? ".edit-day-button" : ".day-button"
//         );
//         const timeInputs = dayRow.find(
//             isEditMode
//                 ? '.edit-time-inputs input[type="time"]'
//                 : 'input[type="time"]'
//         );
//         const addTimeButton = dayRow.find(
//             isEditMode ? ".edit-add-time" : ".add-time"
//         );

//         if ($(this).hasClass("bg-red-900")) {
//             // Deselect the day
//             $(this)
//                 .removeClass("bg-red-900 text-white")
//                 .addClass("bg-white text-black");
//             dayButton.removeClass("text-white").addClass("text-black");
//             // Disable time inputs when day is deselected
//             timeInputs.prop("disabled", true).val(""); // Reset time inputs
//             addTimeButton.addClass("disabled");
//             // Remove additional time inputs
//             dayRow
//                 .find(
//                     (isEditMode ? ".edit-time-inputs" : ".time-inputs") +
//                         " .flex.gap-2.mt-2"
//                 )
//                 .not(":first")
//                 .remove();
//         } else {
//             // Select the day
//             $(this)
//                 .addClass("bg-red-900 text-white")
//                 .removeClass("bg-white text-black");
//             dayButton.addClass("text-white").removeClass("text-black");
//             // Enable time inputs when day is selected
//             timeInputs.prop("disabled", false);
//             addTimeButton.removeClass("disabled");
//         }
//     });

//     // Use event delegation to add new time input fields when the "+" icon is clicked (for both create and edit modes)
//     $(document).on(
//         "click",
//         ".add-time:not(.disabled), .edit-add-time:not(.disabled)",
//         function () {
//             const isEditMode = $(this).hasClass("edit-add-time");
//             const timeContainer = $(this)
//                 .closest(isEditMode ? ".edit-day-row" : ".day-row")
//                 .find(isEditMode ? ".edit-time-inputs" : ".time-inputs");

//             // Check if a time input already exists
//             if (timeContainer.find(".flex.gap-2.mt-2").length > 0) {
//                 Swal.fire({
//                     icon: "warning",
//                     title: "Limit Reached",
//                     text: "You can only add time once for this day.",
//                 });
//                 return; // Prevent adding more than one time input
//             }

//             const newTimeInput = `
//             <div class="flex gap-2 mt-2">
//                 <input type="time" class="${
//                     isEditMode ? "edit-start-time" : "start-time"
//                 } border border-gray-300 rounded-2xl p-2" required>
//                 <span class="self-center">to</span>
//                 <input type="time" class="${
//                     isEditMode ? "edit-end-time" : "end-time"
//                 } border border-gray-300 rounded-2xl p-2" required>
//             </div>`;

//             timeContainer.append(newTimeInput);
//         }
//     );

//     // Prevent interaction with disabled time inputs and add buttons (for both create and edit modes)
//     $(document).on(
//         "click",
//         "input[type='time']:disabled, .add-time.disabled, .edit-add-time.disabled",
//         function (e) {
//             e.preventDefault();
//             Swal.fire({
//                 icon: "error",
//                 title: "Oops...",
//                 text: "Please Select the Day First",
//             });
//         }
//     );

//     // Function to initialize edit mode (call this when loading existing data)
//     function initializeEditMode(scheduleData) {
//         scheduleData.forEach((schedule) => {
//             const dayRow = $(`.edit-day-row:contains("${schedule.day}")`);
//             if (dayRow.length) {
//                 const dayContainer = dayRow.find(".day-container");
//                 dayContainer.click(); // Select the day

//                 const timeInputs = dayRow.find(".edit-time-inputs");
//                 schedule.times.forEach((time, index) => {
//                     if (index > 0) {
//                         dayRow.find(".edit-add-time").click(); // Add new time input if needed
//                     }
//                     const [start, end] = time.split("-");
//                     timeInputs.find(".edit-start-time").eq(index).val(start);
//                     timeInputs.find(".edit-end-time").eq(index).val(end);
//                 });
//             }
//         });
//     }
// });

$(document).ready(function () {
    // Function to filter both grid and table views based on selected academic year and semester
    function filterRecords() {
        var selectedYear = $("#academic-year").val();
        var selectedSemester = $("#semester").val();
        var visibleGridItems = 0;
        var visibleTableItems = 0;

        // Filter grid view
        $(".record-item").each(function () {
            var recordYear = $(this).data("school-year");
            var recordSemester = $(this).data("semester");

            if (
                (selectedYear === "" || selectedYear == recordYear) &&
                (selectedSemester === "" || selectedSemester == recordSemester)
            ) {
                $(this).show();
                visibleGridItems++;
            } else {
                $(this).hide();
            }
        });

        // Filter table view
        $(".record-item").each(function () {
            var recordYear = $(this).data("school-year");
            var recordSemester = $(this).data("semester");

            if (
                (selectedYear === "" || selectedYear == recordYear) &&
                (selectedSemester === "" || selectedSemester == recordSemester)
            ) {
                $(this).show();
                visibleTableItems++;
            } else {
                $(this).hide();
            }
        });

        // Display "No class record found" if no records are visible in both views
        if (visibleGridItems === 0 && visibleTableItems === 0) {
            $("#no-record-message").show(); // Show the no-record message
            $("#new-class-record").hide(); // Hide the "New class record" button
        } else {
            $("#no-record-message").hide(); // Hide the no-record message
            $("#new-class-record").show(); // Show the "New class record" button
        }
    }

    // Trigger filtering when the academic year or semester dropdown changes
    $("#academic-year, #semester").on("change", function () {
        filterRecords();
    });

    // Initial call to display records correctly based on the default dropdown values
    filterRecords();
});

$(document).on("click", ".archive-btn", function () {
    const classRecordID = $(this).data("class-record-id");
    const courseTitle = $(this).data("course-title");
    const programTitle = $(this).data("program-title");

    Swal.fire({
        title: "Confirmation",
        html: `
        <p>Do you want to archive this class record: <strong>${courseTitle} (${programTitle})</strong>?</p>
        <p><strong><small style="color: #d33;">This record will be marked as archived. You cannot modify, add, or edit it once archived.</small></strong></p>
    `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/archive-record",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    classRecordID: classRecordID,
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            "Archived!",
                            response.message,
                            "success"
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function (xhr) {
                    const errorMessage =
                        xhr.responseJSON?.message ||
                        "An error occurred while processing your request.";
                    Swal.fire("Warning!", errorMessage, "warning");
                },
            });
        }
    });
});

// document.getElementById('sendJson').addEventListener('click', async function () {
//     try {
//         // Fetch faculty schedules
//         const response = await fetch('/fetch-pupt-faculty-schedules', {
//             method: 'GET'
//         });

//         if (!response.ok) {
//             throw new Error('Network response was not ok');
//         }

//         const jsonData = await response.json();

//         // Send schedules to store-classrecord-integration
//         const sendResponse = await fetch('/store-classrecord-integration', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             },
//             body: JSON.stringify({ pupt_faculty_schedules: jsonData })
//         });

//         const sendResult = await sendResponse.json();

//         if (sendResponse.ok) {
//             Swal.fire({
//                 icon: 'success',
//                 title: 'Success',
//                 text: sendResult.message || 'Data sent successfully!',
//             });
//         } else {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: sendResult.error || 'Error occurred while processing your request.',
//             });
//         }

//     } catch (error) {
//         console.error('Error:', error);
//         Swal.fire({
//             icon: 'error',
//             title: 'Unexpected Error',
//             text: error.message || 'An error occurred while processing your request.',
//         });
//     }
// });

$(document).ready(function () {
    // $(".record-item").each(function () {
    //     var recordType = $(this).data("record-type");

    //     if (!recordType) {
    //         $(this).find(".modify-btn").closest("div").hide();
    //     }
    // });

    $(".record-item").on("click", function (event) {
        event.preventDefault();

        var recordType = $(this).data("record-type");
        var classRecordID = $(this).find("input[name='classRecordID']").val();

        if (!recordType) {
            Swal.fire({
                icon: "warning",
                title: "Incomplete Record",
                text: "The grade configuration for this class record is missing. Please update it before proceeding.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "Go to Update",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `update-class-record/${classRecordID}`;
                }
            });
        } else {
            if ($(event.target).closest(".modify-btn").length > 0) {
                return;
            }
            if ($(event.target).closest(".fa-box-archive").length > 0) {
                return;
            }
            $(this).find("form").submit();
        }
    });
});
