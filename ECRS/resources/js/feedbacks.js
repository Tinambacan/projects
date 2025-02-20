$(document).ready(function () {
    $("#select-all").on("change", function () {
        $(".feedback-checkbox").prop("checked", $(this).prop("checked"));
        logSelectedFeedbackIDs();
    });

    $(".feedback-checkbox").on("change", function () {
        logSelectedFeedbackIDs();
    });

    function logSelectedFeedbackIDs() {
        var selectedFeedbackIDs = [];
        $(".feedback-checkbox:checked").each(function () {
            var feedbackID = $(this).data("feedback-id");
            selectedFeedbackIDs.push(feedbackID);
        });
        // console.log("Selected Feedback IDs:", selectedFeedbackIDs);
    }

    $(".fa-envelope").on("click", function () {
        var selectedFeedbackIDs = getSelectedFeedbackIDs();

        if (selectedFeedbackIDs.length > 0) {
            $.ajax({
                url: "/feedback/mark-as-read",
                method: "POST",
                data: {
                    feedback_ids: selectedFeedbackIDs,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: xhr.responseJSON.message,
                    });
                },
            });
        } else {
            Swal.fire({
                icon: "warning",
                title: "No Feedback Selected",
                text: "Please select at least one feedback to mark as read.",
            });
        }
    });

    $(".fa-trash").on("click", function () {
        var selectedFeedbackIDs = getSelectedFeedbackIDs();

        if (selectedFeedbackIDs.length > 0) {
            $.ajax({
                url: "/feedback/delete",
                method: "POST",
                data: {
                    feedback_ids: selectedFeedbackIDs,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: xhr.responseJSON.message,
                    });
                },
            });
        } else {
            Swal.fire({
                icon: "warning",
                title: "No Feedback Selected",
                text: "Please select at least one feedback to delete.",
            });
        }
    });

    function getSelectedFeedbackIDs() {
        var selectedFeedbackIDs = [];
        $(".feedback-checkbox:checked").each(function () {
            selectedFeedbackIDs.push($(this).data("feedback-id"));
        });
        return selectedFeedbackIDs;
    }

    $(".feed-btn").on("click", function (event) {
        event.stopPropagation();
        const feedbackId = $(this).data("feedback-id");

        $(".feedback-modal-container").addClass("hidden");

        const modalContainer = $(
            `.feedback-modal-container[data-feedback-id="${feedbackId}"]`
        );
        modalContainer.toggleClass("hidden");
    });

    $(document).on("click", function (event) {
        if (
            !$(event.target).closest(".feed-btn").length &&
            !$(event.target).closest(".feedback-modal-container").length
        ) {
            $(".feedback-modal-container").addClass("hidden");
        }
    });

    $(".delete-btn").on("click", function () {
        const feedbackId = $(this)
            .closest(".feedback-modal-container")
            .data("feedback-id");

        // console.log(
        //     `Mark as read action triggered for feedback ID: ${feedbackId}`
        // );

        $.ajax({
            url: "/feedback/delete",
            type: "POST",
            data: {
                feedback_ids: [feedbackId],
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // console.log(response.message);
                window.location.reload();
            },
            error: function (xhr) {
                // console.log(
                //     "Error marking feedback as read: ",
                //     xhr.responseText
                // );
            },
        });
        $(this).closest(".feedback-modal-container").addClass("hidden");
    });

    $(".read-btn").on("click", function () {
        const feedbackId = $(this)
            .closest(".feedback-modal-container")
            .data("feedback-id");

        // console.log(
        //     `Mark as read action triggered for feedback ID: ${feedbackId}`
        // );

        $.ajax({
            url: "/feedback/mark-as-read",
            type: "POST",
            data: {
                feedback_ids: [feedbackId],
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // console.log(response.message);
                window.location.reload();
            },
            error: function (xhr) {
                // console.log(
                //     "Error marking feedback as read: ",
                //     xhr.responseText
                // );
            },
        });
        $(this).closest(".feedback-modal-container").addClass("hidden");
    });
});
