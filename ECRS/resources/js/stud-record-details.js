$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        url: "/get-distribution-type",
        method: "GET",
        success: function (data) {
            const { gradingTerm, selectedTab } = data;

            if (gradingTerm && selectedTab) {
                $("#section-selector option").each(function () {
                    const optionTerm = $(this).data("term");
                    const optionType = $(this).val();

                    // console.log("fasdf",optionTerm);

                    if (
                        optionTerm == gradingTerm &&
                        optionType == selectedTab
                    ) {
                        $(this).prop("selected", true);
                    }
                });
            }
        },
        error: function (xhr) {
            console.error("Error fetching session data:", xhr);
        },
    });

    function updateUrl(gradingDistributionType) {
        const formattedType = gradingDistributionType.replace(/\s+/g, "-");
        const url = `/student/class-record/${formattedType}`.toLowerCase();
        window.location.href = url;
        // console.log("Redirecting to:", url);
    }

    $("#section-selector").on("change", function () {
        const selectedOption = $(this).find(":selected");
        const selectedValue = selectedOption.val();
        const term = selectedOption.data("term");

        // console.log(term);

        $.ajax({
            url: "/store-distribution-type",
            type: "POST",
            data: {
                term: term,
                selectedTab: selectedValue,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // console.log("Session data saved.");
                updateUrl(selectedValue);

                // console.log(selectedValue);
            },
            error: function (xhr) {
                console.error("Error storing assessment type:", xhr);
            },
        });
    });

    $("#feedback-btn").on("click", function () {
        let studentAssessmentsCount = $(this).data("assessments");
        if (studentAssessmentsCount === 0) {
            Swal.fire({
                icon: "warning",
                title: "No assessment found",
                text: "No assessment found to feedback.",
                confirmButtonColor: "#CCAA2C",
            });
        } else {
            $("#feedback-modal").show();
            $("body").addClass("no-scroll");
        }
    });

    $("#close-btn-feedback").on("click", function () {
        $("#feedback-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#send-request-btn").click(function () {
        var professorId = $("#professor-id").val();
        // console.log(professorId);
        $.ajax({
            url: "/send-notification",
            type: "POST",
            data: {
                professor_id: professorId,
            },
            success: function (response) {
                // console.log("Notification sent successfully.");
            },
            error: function (xhr, status, error) {
                console.error("Error sending notification:", error);
            },
        });
    });

    // function handleCharacterCount(
    //     inputSelector,
    //     countDisplaySelector,
    //     maxChars
    // ) {
    //     $(inputSelector).on("input", function () {
    //         const currentLength = $(this).val().length;
    //         const charsLeft = maxChars - currentLength;

    //         // Update the character count display
    //         $(countDisplaySelector).text(charsLeft);

    //         // Enforce max length (in case maxlength is bypassed)
    //         if (currentLength > maxChars) {
    //             $(this).val($(this).val().substring(0, maxChars));
    //             $(countDisplaySelector).text(0); // Ensure it shows 0 if max exceeded
    //         }
    //     });
    // }

    // // Usage
    // handleCharacterCount(
    //     '#{{ strtolower(str_replace(" ", "-", $storedAssessmentType)) }}-name',
    //     '#{{ strtolower(str_replace(" ", "-", $storedAssessmentType)) }}-char-count',
    //     20
    // );

    function handleCharacterCount(
        textareaSelector,
        countDisplaySelector,
        maxChars
    ) {
        $(textareaSelector).on("input", function () {
            const currentLength = $(this).val().length;
            const charsLeft = maxChars - currentLength;

            $(countDisplaySelector).text(charsLeft);

            if (currentLength >= maxChars) {
                $(this).val($(this).val().substring(0, maxChars));
            }
        });
    }

    handleCharacterCount("#body", "#char-count", 50);

    $("#feedback-form").on("submit", function (e) {
        $("#send-feedback-st").show();
        e.preventDefault();

        const subject = $("#subject").val();
        const body = $("#body").val();
        const loginID = $("#professor-id").val();
        const csrfToken = $('input[name="_token"]').val();

        $.ajax({
            url: "/store-feedback",
            type: "POST",
            data: {
                _token: csrfToken,
                subject: subject,
                body: body,
                loginID: loginID,
            },
            success: function (response) {
                $("#send-feedback-st").fadeOut();
                $("#feedback-modal").fadeOut();
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000,
                }).then(() => {
                    $("#feedback-form")[0].reset();
                    $("#char-count").text(50);
                    $("body").removeClass("no-scroll");
                });
            },
            error: function (xhr) {
                $("#send-feedback-st").fadeOut();

                if (xhr.status === 422) {
                    const response = xhr.responseJSON;
                    const errorMessages = Object.values(response.errors)
                        .map((messages) => messages[0])
                        .join("\n");

                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: errorMessages,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "There was an error submitting your feedback. Please try again.",
                    });
                }
            },
        });
    });

    $(document).ready(function () {
        $("#print-scores-btn").on("click", function (e) {
            let studentAssessmentsCount = $(this).data("assessments");
            if (studentAssessmentsCount === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "No assessment found",
                    text: "No assessment found to print",
                    confirmButtonColor: "#CCAA2C",
                });
            } else {
                e.preventDefault();

                Swal.fire({
                    title: "Confirmation",
                    text: "You are about to print the scores.",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#print-score-st").removeClass("hidden");
                        $.ajax({
                            url: "/export-student-assessments",
                            method: "GET",
                            xhrFields: {
                                responseType: "blob",
                            },
                            success: function (data, status, xhr) {
                                const disposition = xhr.getResponseHeader(
                                    "Content-Disposition"
                                );
                                let filename = "exported-scores.pdf";
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

                                $("#print-score-st").addClass("hidden");

                                Swal.fire({
                                    title: "Success!",
                                    text: "The scores have been printed successfully.",
                                    icon: "success",
                                    confirmButtonColor: "#3085d6",
                                });
                            },
                            error: function () {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Something went wrong while printing the scores.",
                                    icon: "error",
                                    confirmButtonColor: "#d33",
                                });
                            },
                        });
                    }
                });
            }
        });
    });
});
