$(document).ready(function () {
    const $steps = $(".step");
    const $stepContents = $(".step-content");
    const $gradeConfigBackBtn = $("#grade-config-back-btn");
    const $nextBtn = $("#next-btn");
    const $gradeConfigNextBtn = $("#grade-config-next-btn"); // Add this button
    const $stepBorder = $("#step-border");
    let currentStep = 1;
    let maxStep = 2; // Default to 2 steps
    const $termSelect = $("#grading-period"); // The term select dropdown

    // Step content elements based on your description
    const $midtermGrade = $("#midterm-grade");
    const $finalGrade = $("#final-grade");
    const $specialGrade = $("#special-grade");

    // Function to update the step UI
    function updateStep() {
        $(".step").each(function () {
            const stepNumber = parseInt($(this).attr("data-step"));
            const $stepNumberDiv = $(this).find(".step-number");

            if (stepNumber === currentStep) {
                $stepNumberDiv
                    .removeClass("bg-gray-300 text-gray-500")
                    .addClass("bg-red-900 text-white dark:bg-[#CCAA2C]");
            } else if (stepNumber < currentStep) {
                $stepNumberDiv
                    .removeClass("bg-gray-300 text-gray-500")
                    .addClass("bg-green-500 text-white");
            } else {
                $stepNumberDiv
                    .removeClass("bg-red-900 text-white dark:bg-[#CCAA2C] bg-green-500")
                    .addClass("bg-gray-300 text-gray-500");
            }
        });

        // Show the correct content based on the current step
        $(".step-content").addClass("hidden");
        $(`.step-content[data-step="${currentStep}"]`).removeClass("hidden");

        // Update sections visibility
        $("#classrecord-information").toggle(currentStep === 1);
        $("#grade-distribution").toggle(currentStep === 2);
        $("#grade-config-back-btn, #grade-config-next-btn").toggle(currentStep > 1 && currentStep < maxStep);
        $("#next-btn").toggle(currentStep === 1);
    }

    // Function to update the stepper HTML
    function updateStepperHTML(steps) {
        const $stepper = $("#stepper");
        $stepper.empty();

        for (let i = 1; i <= steps; i++) {
            const stepHtml = `
                <div class="step-container flex items-center">
                    <div class="step z-20" data-step="${i}">
                        <div class="step-number flex justify-center items-center w-8 h-8 rounded-full bg-gray-300 text-gray-500 font-bold transition-all duration-300">${i}</div>
                    </div>
                    ${i < steps ? '<div class="step-border w-12 h-2 bg-gray-200"></div>' : ''}
                </div>
            `;
            $stepper.append(stepHtml);
        }
    }

    // Handle term selection change
    $termSelect.on("change", function () {
        const selectedTerm = $(this).val();
        switch (selectedTerm) {
            case "1":
                maxStep = 3; // Show steps 1 to 3 (Midterm only)
                break;
            case "2":
                maxStep = 4; // Show steps 1 to 4 (Midterm and Final)
                break;
            case "3":
                maxStep = 5; // Show steps 1 to 5 (Midterm, Final, and Special)
                break;
            default:
                maxStep = 2; // Reset to only 2 steps if no valid term is selected
        }
        currentStep = 1; // Reset to step 1 when changing the number of terms
        updateStepperHTML(maxStep);
        updateStep(); // Update the stepper UI

        // Ensure grade distribution is visible when on step 2
        if (currentStep === 2) {
            $("#grade-distribution").show();
        }
    });

    // Next button click (moving to the next step)
    function handleNextStep() {
        const requiredFields = document.querySelectorAll("#classrecord-information [required]");
        let allFieldsFilled = true;
        let emptyFields = [];

        requiredFields.forEach((field) => {
            if (!field.value) {
                allFieldsFilled = false;
                field.classList.add("border-red-500");
                emptyFields.push(field.previousElementSibling.textContent.replace("*", "").trim());
            } else {
                field.classList.remove("border-red-500");
            }
        });

        const courseSelect = document.getElementById("course-select");
        if (courseSelect.value === "" || courseSelect.value === "Select Course") {
            allFieldsFilled = false;
            courseSelect.classList.add("border-red-500");
            emptyFields.push("Course");
        } else {
            courseSelect.classList.remove("border-red-500");
        }

        if (allFieldsFilled && currentStep < maxStep) {
            currentStep++;
            updateStep();

            if (currentStep === 2) {
                $("#grade-distribution").show();
            }

            $("#classrecord-information").toggle(currentStep === 1);
            $("#grade-distribution").toggle(currentStep === 2);
            $("#grade-config-back-btn").show();
            $("#grade-config-next-btn").show();
            if (currentStep === maxStep) {
                $("#next-btn").hide();
            }
        } else if (!allFieldsFilled) {
            Swal.fire({
                title: "Incomplete Form",
                html: `Please fill in the following required fields:<br><br>${emptyFields.join("<br>")}`,
                icon: "warning",
                confirmButtonText: "OK",
                confirmButtonColor: "#dc2626",
            });
        }
    }

    // Next button click handlers
    $nextBtn.on("click", handleNextStep); // Attach the handler to #next-btn
    $gradeConfigNextBtn.on("click", handleNextStep); // Attach the handler to #grade-config-next-btn

    // Back button click
    $gradeConfigBackBtn.on("click", function () {
        if (currentStep > 1) {
            currentStep--;
            updateStep();
            $("#classrecord-information").toggle(currentStep === 1);
            $("#grade-distribution").toggle(currentStep === 2);
            $("#grade-config-back-btn").toggle(currentStep > 1);
            $("#next-btn").show();
        }
    });

    // Initialize the step UI
    updateStep();
});
