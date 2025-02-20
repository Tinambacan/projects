$(document).ready(function () {
    // Function to update the total percentage
    function updateTotalPercentage() {
        let total = 0;

        $("tbody tr").each(function () {
            let percentage = $(this).find(".percentage-input").val();
            if (percentage) {
                total += parseFloat(percentage);
            }
        });

        // Update the total in the table
        $("tr.total-row td.total-percentage").text(total + "%");

        // Only show validation if total exceeds 100
        if (total > 100) {
            Swal.fire({
                title: "Invalid Total",
                text: "Total percentage cannot exceed 100%.",
                icon: "warning",
                button: "OK",
            });
            return false; // Return false to indicate invalid state
        }

        return total === 100; // Return true if total equals 100, false otherwise
    }

    // Listen to changes in the percentage input fields
    $(".percentage-input").on("input", function () {
        updateTotalPercentage(); // Update the total when input changes
    });

    // Handle save button click
    $("#save-btn").on("click", function () {
        let gradingData = [];

        // Calculate the total before submission
        if (!updateTotalPercentage()) {
            Swal.fire({
                title: "Invalid Total",
                text: "Total percentage must equal 100% to proceed.",
                icon: "warning",
                button: "OK",
            });
            return; // If total is invalid, prevent submission
        }

        // Collect data from the table
        $("tbody tr").each(function () {
            let gradingID = $(this).data("grading-id");
            let percentage = $(this).find(".percentage-input").val();

            gradingData.push({
                gradingID: gradingID,
                percentage: percentage,
            });
        });

        // Send the data to the server if valid
        $.ajax({
            url: "/update-grading-percentages", // The route to update the percentages
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                gradingData: gradingData,
            },
            success: function (response) {
                Swal.fire({
                    title: "Success",
                    text: "Percentages updated successfully!",
                    icon: "success",
                    button: "OK",
                }).then(() => {
                    location.reload(); // Reload the page after the alert is closed
                });
            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    title: "Error",
                    text: "Error updating percentages. Please try again.",
                    icon: "error",
                    button: "OK",
                });
            },
        });
    });
});

$(document).ready(function () {
    // console.log("Document is ready"); // Check if jQuery is loaded properly

    $(".grade-percentage-btn").on("click", function () {
        $(".card").toggleClass("open"); // Toggle card open state
        $(".arrow-icon").toggleClass("arrow-left arrow-right"); // Toggle arrow direction
    });

    $(".close-card-icon").on("click", function () {
        // console.log("Close icon clicked"); // Check if the close icon click is detected
        $(this).closest(".card").removeClass("open"); // Hide the card when close icon is clicked
    });
});

// $(document).ready(function () {
//     $(".publish-grade-btn").on("click", function () {
//         let term = $(this).data("term");
//         let classRecordID = $(this).data("class-record-id");
//         let gradingType = $(this).data("grading-type"); // Get grading type dynamically

//         Swal.fire({
//             title: "Confirmation",
//             text: `Publish ${gradingType} grades?`,
//             icon: "info",
//             showCancelButton: true,
//             confirmButtonColor: "#3085d6",
//             cancelButtonColor: "#d33",
//             confirmButtonText: "Yes",
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 $.ajax({
//                     url: "/publish-grades",
//                     type: "POST",
//                     data: {
//                         _token: $('meta[name="csrf-token"]').attr("content"),
//                         term: term,
//                         classRecordID: classRecordID,
//                     },
//                     success: function (response) {
//                         if (response.success) {
//                             Swal.fire({
//                                 title: "Success",
//                                 text: response.message,
//                                 icon: "success",
//                                 confirmButtonText: "OK",
//                             }).then(() => {
//                                 location.reload();
//                             });
//                         } else {
//                             Swal.fire({
//                                 title: "Publish failed",
//                                 text:
//                                     response.message,
//                                 icon: "error",
//                                 confirmButtonText: "OK",
//                             }).then(() => {
//                                 if (typeof dataTable !== "undefined") {
//                                     dataTable.ajax.reload();
//                                 }
//                             });
//                         }
//                     },
//                     error: function (xhr) {
//                         const errorMessage =
//                             xhr.responseJSON?.message ||
//                             "An unexpected error occurred.";
//                         Swal.fire({
//                             title: "Error!",
//                             text: errorMessage,
//                             icon: "error",
//                             confirmButtonText: "OK",
//                         });
//                     },
//                 });
//             }
//         });
//     });
// });

$(document).ready(function () {
    $(".toggle-grade-btn").on("click", function () {
        let term = $(this).data("term");
        let classRecordID = $(this).data("class-record-id");
        let isPublished = $(this).data("is-published"); // 1 = Published, 0 = Not Published
        let gradingType = $(this).data("grading-type");
        let actionText = isPublished ? "Unpublish" : "Publish";

        Swal.fire({
            title: "Confirmation",
            text: `${actionText} ${gradingType} grades?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: isPublished ? "#d33" : "#3085d6",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Yes`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/toggle-grades", 
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        term: term,
                        classRecordID: classRecordID,
                        isPublished: isPublished ? 0 : 1, 
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK",
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Action Failed",
                                text: response.message,
                                icon: "error",
                                confirmButtonText: "OK",
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: "Error!",
                            text:
                                xhr.responseJSON?.message ||
                                "An unexpected error occurred.",
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    },
                });
            }
        });
    });
});
