$(document).ready(function () {
    $("#add-branch-btn").on("click", function () {
        $("#add-branch-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-add-branch").on("click", function () {
        $("#add-branch-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    $("#add-branch-list-btn").on("click", function () {
        $("#add-branch-list-modal").show();
        $("body").addClass("no-scroll");
        // $("#loader-modal-import").show();
    });

    $("#close-btn-add-branch-list").on("click", function () {
        $("#add-branch-list-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });

    const branchesdata = document.querySelector("#myTable");

    if (branchesdata) {
        const dataTable = $(branchesdata).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-branches-sa",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<div class="text-center">${row.id}</div>`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<div class="text-center">${row.branchDescription}</div>`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                             <div class="flex justify-center items-center">
                                        <div class="relative group flex justify-center items-center">
                                            <div class="flex justify-center items-center ">
                                                <i class="fa-solid fa-pen-to-square text-green-500 text-xl hover:bg-gray-200 hover:rounded-md cursor-pointer p-1 edit-branch-btn"
                                                    data-branchDescription="${row.branchDescription}"
                                                    data-branchid="${row.id}"
                                                   >
                                                </i>
                                            </div>
                                             <div class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                         <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                             <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">View Info</span>
                                             <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
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
            columnDefs: [
                {
                    targets: [2],
                    orderable: false,
                },
            ],
        });

        $("#add-branch-form").on("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: "/save-branch-info",
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
                                $("#add-branch-form")[0].reset();
                                $("#add-branch-modal").fadeOut();
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

        $("#edit-branch-form").on("submit", function (e) {
            e.preventDefault(); // Prevent form from submitting the traditional way

            // const formData = new FormData(this);

            const formData = {
                branchID: $("#edit-branchID").val(), 
                branchDescription: $("#edit-branchDescription").val(),
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            $.ajax({
                url: "/update-branch",
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
                                $("#edit-branch-modal").fadeOut();
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

    $(document).on("click", ".edit-branch-btn", function () {
        const branchData = $(this).data();

        $("#edit-branchID").val(branchData.branchid);
        $("#edit-branchDescription").val(branchData.branchdescription);

        $("#edit-branch-modal").show();
        $("body").addClass("no-scroll");
    });

    $("#close-btn-edit-branch").on("click", function () {
        $("#edit-branch-modal").fadeOut();
        $("body").removeClass("no-scroll");
    });
});
