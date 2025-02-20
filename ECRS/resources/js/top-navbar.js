$(document).ready(function () {
    let role;

    $(document).ready(function () {
        $.ajax({
            url: "/get-role",
            type: "GET",
            dataType: "json",
            success: function (data) {
                role = data.roleNum;
            },
            error: function (xhr, status, error) {
                console.error("Error fetching roleNum:", error);
            },
        });
    });

    // $("#prof-profile").on("click", function () {
    //     $("#profile-modal").removeClass("hidden");
    //     $("body").addClass("no-scroll");
    // });

    // $("#close-btn-profile").on("click", function () {
    //     $("#profile-modal").addClass("hidden");
    //     $("body").removeClass("no-scroll");
    // });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).ready(function () {
        const profileButtons = $("[data-profile]");

        profileButtons.on("click", function (event) {
            event.stopPropagation();
            const targetModalId = $(this).data("profile");
            const targetModal = $(`#${targetModalId}`);

            if (targetModal.hasClass("hidden")) {
                targetModal.removeClass("hidden");
                $(this).addClass("bg-red-800 dark:bg-[#1E1E1E]");
            } else {
                targetModal.addClass("hidden");
                $(this).removeClass("bg-red-800 dark:bg-[#1E1E1E]");
            }
        });

        $(document).on("click", function (event) {
            profileButtons.each(function () {
                const targetModalId = $(this).data("profile");
                const targetModal = $(`#${targetModalId}`);

                if (
                    !$(this).is(event.target) &&
                    !targetModal.is(event.target) &&
                    targetModal.has(event.target).length === 0
                ) {
                    targetModal.addClass("hidden");
                    $(this).removeClass("bg-red-800 dark:bg-[#1E1E1E]");
                }
            });
        });
    });

    // $("#student-profile").on("click", function () {
    //     $("#student-modal").toggleClass("hidden");
    //     // $("body").addClass("no-scroll");
    // });

    // $("#close-btn-profile-stud").on("click", function () {
    //     $("#student-modal").addClass("hidden");
    //     $("body").removeClass("no-scroll");
    // });

    // $("#admin-profile").on("click", function () {
    //     $("#admin-modal").toggleClass("hidden");
    //     // $("body").addClass("no-scroll");
    // });

    $("#close-btn-profile-admin").on("click", function () {
        $("#admin-modal").addClass("hidden");
        $("body").removeClass("no-scroll");
    });

    // $("#superadmin-profile").on("click", function () {
    //     $("#superadmin-modal").toggleClass("hidden");
    //     // $("body").addClass("no-scroll");
    // });

    // $("#close-btn-profile-superadmin").on("click", function () {
    //     $("#superadmin-modal").addClass("hidden");
    //     $("body").removeClass("no-scroll");
    // });

    $(document).ready(function () {
        const notifButton = $("#notif-button");
        const notifContainer = $(".notif-container");

        notifButton.on("click", function (event) {
            event.stopPropagation();

            if (notifContainer.hasClass("hidden")) {
                fetchNotifications();
                updateUnreadCount();
                notifContainer.removeClass("hidden");
                notifButton.addClass("bg-red-800 dark:bg-[#1E1E1E]");
            } else {
                notifContainer.addClass("hidden");
                notifButton.removeClass("bg-red-800 dark:bg-[#1E1E1E]");
            }
        });

        $(document).on("click", function (event) {
            if (
                !notifButton.is(event.target) &&
                !notifContainer.is(event.target) &&
                notifContainer.has(event.target).length === 0
            ) {
                notifContainer.addClass("hidden");
                notifButton.removeClass("bg-red-800 dark:bg-[#1E1E1E]");
            }
        });
    });

    $(document).on("click", "#myLogout", function (event) {
        $("#loader-modal").removeClass("hidden");
        $("body").addClass("no-scroll");
        // $.ajax({
        //     url: "/logout",
        //     type: "GET",
        //     success: function (response) {
        //         console.log("Response received:", response);
        //         if (response.status === "success") {
        //             window.location.href = "/student"; // Redirect to the login page
        //         } else if (response.status === "error") {
        //             console.error("Error:", response.message);
        //         } else {
        //             console.error("Invalid response format:", response);
        //         }
        //     },
        //     error: function (xhr, status, error) {
        //         console.error("AJAX error:", status, error);
        //     },
        // });
    });

    $(document).on("click", "#submitBtn", function (event) {
        event.preventDefault(); // Prevent the default form submission
        let formData = new FormData(document.getElementById("esignatureForm"));
        let esign = document.getElementById("esign").files[0];

        if (!esign) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Please select an e-signature image.",
                confirmButtonText: "OK",
            });
            return;
        }

        formData.append("esign", esign);

        $.ajax({
            url: "/store-esignature-faculty",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                        confirmButtonText: "OK",
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                        confirmButtonText: "OK",
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "There was an error submitting the form. Please try again.",
                    confirmButtonText: "OK",
                });
            },
        });
    });

    $(document).on("click", "#submitBtnAdmin", function (event) {
        event.preventDefault(); // Prevent the default form submission
        let formData = new FormData(
            document.getElementById("esignatureFormAdmin")
        );
        let esignAdmin = document.getElementById("esignAdmin").files[0];

        if (!esignAdmin) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Please select an e-signature image.",
                confirmButtonText: "OK",
            });
            return;
        }

        formData.append("esignAdmin", esignAdmin);

        $.ajax({
            url: "/store-esignature-admin",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                        confirmButtonText: "OK",
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                        confirmButtonText: "OK",
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "There was an error submitting the form. Please try again.",
                    confirmButtonText: "OK",
                });
            },
        });
    });

    // function fetchNotifications() {
    //     $.ajax({
    //         url: "/notifications",
    //         type: "GET",
    //         dataType: "json",
    //         success: function (data) {
    //             const notifContainer = $(".notif-container");

    //             if (data.notifications.length > 0) {
    //                 let notificationHTML = `
    //                     <div class="  rounded-lg overflow-hidden bg-white dark:bg-[#161616]  border border-gray-300 dark:border-[#404040] shadow-lg">
    //                         <div class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto">
    //                         <div class="flex justify-between">
    //                             <h3 class="text-lg font-bold mb-2 text-red-900 dark:text-[#CCAA2C] text-left">Notifications</h3>
    //                             <div class="flex justify-center items-center">
    //                         <div class="text-sm font-medium mb-2 text-red-900 dark:text-[#CCAA2C]">Mark all as Read</div>
    //                         </div>
    //                         </div>
    //                             <div class="space-y-2">`;

    //                 data.notifications.forEach((notification) => {
    //                     let formAction = "";
    //                     let inputFields = "";

    //                     if (role == 1) {
    //                         if (notification.type === "grade_request") {
    //                             formAction = "/mark-as-read";
    //                             inputFields = `
    //                                 <input type="hidden" name="classRecordIDRequest" value="${notification.classRecordID}">
    //                                 <input type="hidden" name="notifID" value="${notification.id}">
    //                             `;
    //                         } else if (notification.type === "notif_verified") {
    //                             formAction = "/notif/markasread-file";
    //                             inputFields = `
    //                                 <input type="hidden" name="notifIDVerified" value="${notification.id}">
    //                             `;
    //                         } else if (notification.type === "notice_faculty") {
    //                             formAction = "/store-class-record-id-notice";
    //                             inputFields = `
    //                                 <input type="hidden" name="notifIDNotice" value="${notification.id}">
    //                             `;
    //                         }
    //                     } else if (role == 2) {
    //                         if (notification.type === "submit_grades") {
    //                             formAction = "/store-file-id-notif";
    //                             inputFields = `
    //                                 <input type="hidden" name="notifIDAdmin" value="${notification.id}">
    //                             `;
    //                         }
    //                     } else if (role == 3) {
    //                         if (notification.type === "publish_score") {
    //                             formAction =
    //                                 "/store-stud-class-record-id-notif";
    //                             inputFields = `
    //                                 <input type="hidden" name="classRecordIDStudentNotif" value="${notification.classRecordID}">
    //                                 <input type="hidden" name="notifIDStudent" value="${notification.id}">
    //                             `;
    //                         }
    //                     }

    //                     notificationHTML += `
    //                         <form action="${formAction}" method="POST">
    //                             <input type="hidden" name="_token" value="${$(
    //                                 'meta[name="csrf-token"]'
    //                             ).attr("content")}">
    //                             ${inputFields}
    //                             <button type="submit" class="p-3 border ${
    //                                 !notification.read_at
    //                                     ? "border-red-900 font-bold dark:border-[#CCAA2C]"
    //                                     : "border-gray-200 dark:border-[#404040]"
    //                             } rounded-md  dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] text-black text-left">
    //                             <div class="flex flex-col">
    //                                 <p >
    //                                     ${notification.message}
    //                                 </p>
    //                                 <small class="text-gray-500">${
    //                                     notification.created_at
    //                                 }</small>
    //                                     </div>
    //                             </button>
    //                         </form>
    //                     `;
    //                 });

    //                 notificationHTML += `</div></div></div>`;
    //                 notifContainer.html(notificationHTML).removeClass("hidden");
    //             } else {
    //                 notifContainer
    //                     .html(
    //                         `
    //                     <div class=" border border-gray-300 dark:border-[#404040] rounded-lg overflow-hidden bg-white dark:bg-[#161616] ">
    //                         <div class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto flex justify-center items-center">
    //                             <p class="text-gray-600">No notifications</p>
    //                         </div>
    //                     </div>
    //                 `
    //                     )
    //                     .removeClass("hidden");
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("Error fetching notifications:", error);
    //         },
    //     });
    // }

    function fetchNotifications() {
        $.ajax({
            url: "/notifications",
            type: "GET",
            dataType: "json",
            success: function (data) {
                const notifContainer = $(".notif-container");

                if (data.notifications.length > 0) {
                    let notificationHTML = `
                        <div class="rounded-lg overflow-hidden bg-white dark:bg-[#161616] border border-gray-300 dark:border-[#404040] shadow-lg">
                            <div class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto">
                                <div class="flex justify-between">
                                    <h3 class="text-lg font-bold mb-2 text-red-900 dark:text-[#CCAA2C] text-left">Notifications</h3>
                                    <div class="flex justify-center items-center">
                                        <div class="text-sm font-medium mb-2 text-red-900 dark:text-[#CCAA2C] cursor-pointer mark-all-as-read">
                                            Mark all as Read
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">`;

                    data.notifications.forEach((notification) => {
                        let formAction = "";
                        let inputFields = "";
                        let buttonType = "submit"; // Default to submit for other types
                        let formMethod = "POST"; // Default method is POST

                        if (role == 1) {
                            if (notification.type === "grade_request") {
                                formAction = "/mark-as-read";
                                inputFields = `
                                                <input type="hidden" name="classRecordIDRequest" value="${notification.classRecordID}">
                                                <input type="hidden" name="notifID" value="${notification.id}">
                                            `;
                            } else if (notification.type === "notif_verified") {
                                formAction = "/notif/markasread-file";
                                inputFields = `
                                                <input type="hidden" name="notifIDVerified" value="${notification.id}">
                                            `;
                            } else if (notification.type === "notice_faculty") {
                                formAction = "/store-class-record-id-notice";
                                inputFields = `
                                                <input type="hidden" name="notifIDNotice" value="${notification.id}">
                                            `;
                            }
                        } else if (role == 2) {
                            if (notification.type === "submit_grades") {
                                formAction = "/store-file-id-notif";
                                inputFields = `
                                                <input type="hidden" name="notifIDAdmin" value="${notification.id}">
                                            `;
                            } else if (notification.type === "faculty_loads") {
                                // Handle "faculty_loads" separately with no action
                                formAction = "#"; // No action for this notification
                                inputFields = `
                                                <input type="hidden" name="notifIDFacultyLoad" value="${notification.id}">
                                            `;
                                buttonType = "button"; // Prevent form submission, just display the notification
                            }
                        } else if (role == 3) {
                            if (notification.type === "publish_score") {
                                formAction =
                                    "/store-stud-class-record-id-notif";
                                inputFields = `
                                                <input type="hidden" name="classRecordIDStudentNotif" value="${notification.classRecordID}">
                                                <input type="hidden" name="notifIDStudent" value="${notification.id}">
                                            `;
                            }
                        }

                        // If formAction is set, create a form, otherwise just display a button
                        if (formAction) {
                            notificationHTML += `
                                            <form action="${formAction}" method="${formMethod}">
                                                <input type="hidden" name="_token" value="${$(
                                                    'meta[name="csrf-token"]'
                                                ).attr("content")}">
                                                ${inputFields}
                                                <button type="${buttonType}" class="p-3 border ${
                                !notification.read_at
                                    ? "border-red-900 font-bold dark:border-[#CCAA2C]"
                                    : "border-gray-200 dark:border-[#404040]"
                            } rounded-md dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] text-black text-left">
                                                    <div class="flex flex-col"> 
                                                        <p>${
                                                            notification.message
                                                        }</p>
                                                        <small class="text-gray-500">${
                                                            notification.created_at
                                                        }</small>
                                                    </div>
                                                </button>
                                            </form>
                                        `;
                        } else {
                            notificationHTML += `
                                            <div class="p-3 border ${
                                                !notification.read_at
                                                    ? "border-red-900 font-bold dark:border-[#CCAA2C]"
                                                    : "border-gray-200 dark:border-[#404040]"
                                            } rounded-md dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] text-black text-left">
                                                <div class="flex flex-col"> 
                                                    <p>${
                                                        notification.message
                                                    }</p>
                                                    <small class="text-gray-500">${
                                                        notification.created_at
                                                    }</small>
                                                </div>
                                            </div>
                                        `;
                        }
                    });

                    // notificationHTML += `
                    //     <form action="${formAction}" method="POST">
                    //         <input type="hidden" name="_token" value="${$(
                    //             'meta[name="csrf-token"]'
                    //         ).attr("content")}">
                    //         ${inputFields}
                    //         <button type="submit" class="p-3 border ${
                    //             !notification.read_at
                    //                 ? "border-red-900 font-bold dark:border-[#CCAA2C]"
                    //                 : "border-gray-200 dark:border-[#404040]"
                    //         } rounded-md dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] text-black text-left">
                    //             <div class="flex flex-col">
                    //                 <p>${notification.message}</p>
                    //                 <small class="text-gray-500">${
                    //                     notification.created_at
                    //                 }</small>
                    //             </div>
                    //         </button>
                    //     </form>
                    // `;

                    notificationHTML += `</div></div></div>`;
                    notifContainer.html(notificationHTML).removeClass("hidden");

                    $(".mark-all-as-read").on("click", function () {
                        markAllNotificationsAsRead();
                    });
                } else {
                    notifContainer
                        .html(
                            `
                        <div class="border border-gray-300 dark:border-[#404040] rounded-lg overflow-hidden bg-white dark:bg-[#161616]">
                            <div class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto flex justify-center items-center">
                                <p class="text-gray-600">No notifications</p>
                            </div>
                        </div>
                    `
                        )
                        .removeClass("hidden");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching notifications:", error);
            },
        });
    }

    function updateUnreadCount() {
        $.ajax({
            url: "/notifications",
            type: "GET",
            dataType: "json",
            success: function (data) {
                const unreadCount = data.unreadCount;
                const badgeContainer = $("#notif-badge");

                if (unreadCount > 0) {
                    badgeContainer
                        .html(
                            `
                        <span
                            class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex justify-center items-center">
                            ${unreadCount}
                        </span>
                    `
                        )
                        .show();
                } else {
                    badgeContainer.empty().hide();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching unread count:", error);
            },
        });
    }

    $(document).ready(function () {
        updateUnreadCount();
    });

    function markAllNotificationsAsRead() {
        $.ajax({
            url: "/mark-all-as-read",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    fetchNotifications();
                    updateUnreadCount();
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error marking notifications as read:", error);
            },
        });
    }
});
