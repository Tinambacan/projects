function downloadPDF(url) {
    window.location.href = url;
}
$(document).ready(function () {
    $("#submit-grades-form").on("submit", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Submit Grades",
            text: "Are you sure you want to submit grades?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, submit it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $("#loader-modal-submit").removeClass("hidden");
                $("#loader-title").text("Generating excel");
                $("body").addClass("no-scroll");

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $("#loader-title").text("Submitting");

                            setTimeout(function () {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                }).then(() => {
                                    $("#loader-modal-submit").addClass(
                                        "hidden"
                                    );
                                    $("body").removeClass("no-scroll");
                                });
                            }, 2000);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                                confirmButtonColor: "#3085d6",
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: "Error!",
                            text: "An error occurred while submitting the grades.",
                            icon: "error",
                            confirmButtonColor: "#3085d6",
                        });
                    },
                });
            }
        });
    });

    const semesterTable = document.querySelector("#semesterTable");
    if (semesterTable) {
        $(semesterTable).DataTable({
            // responsive: true,
            scrollX: true,
            pagingType: "simple",
            paging: true,
            order: [],
        });
    }

    

    $(document).ready(function () {
        $("#download-excel-btn").on("click", function (e) {
            const courseTitle = $(this).data("course-title");
            const programTitle = $(this).data("program-title");
            const yearLevel = $(this).data("year");

            e.preventDefault();

            Swal.fire({
                title: "Confirmation",
                html: `
        <p>Download this excel file for semester grades of class record: <strong>${courseTitle} (${programTitle} ${yearLevel})</strong>?</p>`,
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Download",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#download-excel").removeClass("hidden");
                    $.ajax({
                        url: "/export-semester-grade", 
                        method: "GET",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        processData: false,
                        contentType: false,
                        xhrFields: {
                            responseType: "blob",
                        },
                        success: function (data, status, xhr) {
                            const disposition = xhr.getResponseHeader(
                                "Content-Disposition"
                            );
                            let filename = "exported-scores.xlsx"; // Default filename
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

                            $("#download-excel").addClass("hidden");

                            Swal.fire({
                                title: "Success!",
                                text: "Semester grades file successfully downloaded",
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                            });
                        },
                        error: function () {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong while downloading the file",
                                icon: "error",
                                confirmButtonColor: "#d33",
                            });
                        },
                    });
                }
            });
        });
    });
});
