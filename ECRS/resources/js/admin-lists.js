$(document).ready(function () {
    $("#add-program-btn").on("click", function () {
        $("#add-program-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-add-program").on("click", function () {
        $("#add-program-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#add-program-list-btn").on("click", function () {
        $("#add-program-list-modal").show();
        $("body").addClass("no-scroll");
        // $("#loader-modal-import").show();
    });

    $("#close-btn-add-program-list").on("click", function () {
        $("#add-program-list-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#add-course-btn").on("click", function () {
        $("#add-course-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-add-course").on("click", function () {
        $("#add-course-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#add-course-list-btn").on("click", function () {
        $("#add-course-list-modal").show();
        $("body").addClass("no-scroll");
        // $("#loader-modal-import").show();
    });

    $("#close-btn-add-course-list").on("click", function () {
        $("#add-course-list-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    const programTbl = document.querySelector("#prgTable");

    if (programTbl) {
        const dataTable = $(programTbl).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-admin-program",
            },
            type: "GET",
            dataType: "json",
            columns: [
                { data: "programCode" },
                { data: "programTitle" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                             <div class="flex justify-center items-center">
                                        <div class="relative group flex justify-center items-center">
                                            <div class="flex justify-center items-center ">
                                                <i class="fa-solid fa-pen-to-square text-green-500 text-xl dark:hover:bg-[#161616] p-[5px] hover:rounded-md cursor-pointer edit-program-btn"
                                                    data-programcode="${row.programCode}"
                                                    data-programtitle="${row.programTitle}"
                                                    data-programid="${row.programID}"
                                                   >
                                                </i>
                                            </div>
                                            <div
                                class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                                        </div>
                                    </div>
                        `;
                    },
                },
            ],
            responsive: true,
            pagingType: "simple",
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            order: [],
            columnDefs: [
                {
                    targets: 2,
                    orderable: false,
                },
            ],
        });

        $("#add-program-form").on("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: "/save-program-info",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                dataTable.ajax.reload();
                                $("#add-program-form")[0].reset();
                                $("#add-program-modal").fadeOut();
                                $("body").removeClass("no-scroll");
                            }
                        });
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
            });
        });

        $(document).on("submit", "#add-program-list-form", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Show the loader
            $("#loader-modal-import").removeClass("hidden");
            $("body").addClass("no-scroll");

            var formData = new FormData(this);

            $.ajax({
                url: "/import-programs",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#add-program-list-form")[0].reset();
                            $("#add-program-list-modal").fadeOut();
                            $("body").removeClass("no-scroll");
                            dataTable.ajax.reload();
                        }
                    });
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
                    $("#loader-modal-import").addClass("hidden");
                    $("body").removeClass("no-scroll");
                },
            });
        });

        $(document).on("click", ".edit-program-btn", function () {
            const programData = $(this).data();

            $("#edit-programID").val(programData.programid);
            $("#edit-programCode").val(programData.programcode);
            $("#edit-programTitle").val(programData.programtitle);

            $("#edit-program-modal").show();
            $("body").addClass("no-scroll");
        });

        $("#close-btn-edit-program").on("click", function () {
            $("#edit-program-modal").fadeOut();
            $("body").removeClass("no-scroll");
        });

        $("#edit-program-form").on("submit", function (e) {
            e.preventDefault();

            // const formData = new FormData(this);

            const formData = {
                programID: $("#edit-programID").val(),
                programCode: $("#edit-programCode").val(),
                programTitle: $("#edit-programTitle").val(),
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            $.ajax({
                url: "/update-program",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "PUT",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                dataTable.ajax.reload();
                                $("#edit-program-modal").fadeOut();
                                $("body").removeClass("no-scroll");
                            }
                        });
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
            });
        });
    }

    const courseTbl = document.querySelector("#crsTable");

    if (courseTbl) {
        const dataTable = $(courseTbl).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-admin-course",
            },
            type: "GET",
            dataType: "json",
            columns: [
                { data: "courseCode" },
                { data: "courseTitle" },
                { data: "programCode" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <div class="flex justify-center items-center">
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center ">
                                                    <i class="fa-solid fa-pen-to-square text-green-500 text-xl edit-button dark:hover:bg-[#161616] p-[5px] hover:rounded-md cursor-pointer  edit-course-btn"
                                                        data-course-id="${row.courseID}"
                                                        data-course-code="${row.courseCode}"
                                                        data-course-title="${row.courseTitle}"
                                                         data-program-id="${row.programID}"
                                                        data-program-code="${row.programCode}"></i>
                                                </div>
                                               <div
                                class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                                            </div>
                                        </div>
                        `;
                    },
                },
            ],
            responsive: true,
            pagingType: "simple",
            paging: true,
            order: [],
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            columnDefs: [
                {
                    targets: 3,
                    orderable: false,
                },
            ],
        });

        $("#add-course-form").on("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: "/save-course-info",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                dataTable.ajax.reload();
                                $("body").removeClass("no-scroll");
                                $("#add-course-form")[0].reset();
                                $("#add-course-modal").fadeOut();
                            }
                        });
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
            });
        });

        $(document).on("submit", "#add-course-list-form", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Show the loader
            $("#loader-modal-import").removeClass("hidden");
            $("body").addClass("no-scroll");

            var formData = new FormData(this); // Collect form data including file

            $.ajax({
                url: "/import-courses", // Update this route to your import handling route
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#add-course-list-form")[0].reset();
                            $("#add-course-list-modal").fadeOut();
                            dataTable.ajax.reload();
                            $("body").removeClass("no-scroll");
                        }
                    });
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
                    $("#loader-modal-import").addClass("hidden");
                    $("body").removeClass("no-scroll");
                },
            });
        });

        $(document).on("click", ".edit-course-btn", function () {
            const courseID = $(this).data("course-id");
            const courseCode = $(this).data("course-code");
            const courseTitle = $(this).data("course-title");
            const programID = $(this).data("program-id");
            // const programCode = $(this).data("program-code");

            $("#edit-courseID").val(courseID);
            $("#edit-courseCode").val(courseCode);
            $("#edit-courseTitle").val(courseTitle);
            $("#edit-programCodeCourse").val(programID);

            $("#edit-course-modal").show();
            $("body").addClass("no-scroll");
        });

        $("#edit-course-form").on("submit", function (e) {
            e.preventDefault();

            // const formData = new FormData(this);

            const formData = {
                courseID: $("#edit-courseID").val(), // Make sure this is populated
                courseCode: $("#edit-courseCode").val(),
                courseTitle: $("#edit-courseTitle").val(),
                programID: $("#edit-programCodeCourse").val(),
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            $.ajax({
                url: "/update-course",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "PUT",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                dataTable.ajax.reload();
                                $("#edit-course-modal").fadeOut();
                                $("body").removeClass("no-scroll");
                            }
                        });
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
            });
        });
    }

    $("#close-btn-edit-course").on("click", function () {
        $("#edit-course-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });
});
