$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const isMobile = window.innerWidth < 768;

    const archivedClassRecordFaculty = document.querySelector(
        "#archivedClassRecordFaculty"
    );

    if (archivedClassRecordFaculty) {
        const dataTable = $(archivedClassRecordFaculty).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-faculty-archived",
            },
            type: "GET",
            dataType: "json",
            columns: [
                {
                    data: "courseName",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                {
                    data: "courseCode",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                {
                    data: "null",
                    render: function (data, type, row) {
                        return `<div class="font-bold">${row.programName} ${row.yearLevel} (${row.branch})</div>`;
                    },
                },

                {
                    data: null,
                    render: function (data, type, row) {
                        let semesterLabel = "Unknown Semester";
                        if (row.semester == 1) {
                            semesterLabel = "1st Semester";
                        } else if (row.semester == 2) {
                            semesterLabel = "2nd Semester";
                        } else if (row.semester == 3) {
                            semesterLabel = "Summer Semester";
                        }

                        return `<div class="font-bold">${semesterLabel} (${row.schoolYear})</div>`;
                    },
                },

                {
                    data: null,
                    render: function (data, type, row) {
                        let formHTML = `
                            <div class="relative group flex justify-center items-center">
                                <div class="flex justify-center items-center">
                                    <form action="/store-class-record-id" method="POST">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                        <input type="hidden" name="classRecordID" value="${row.classRecordID}">
                        <button type="submit" class="cursor-pointer">
                            <i class="fa-solid fa-book text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer text-lg"></i>
                        </button>
                    </form>
                                </div>
                                <div class="absolute bottom-full left-1/2 transform hidden group-hover:block -translate-x-1/2 z-40 mb-4">
                                    <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                        <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">View Info</span>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                    </div>
                                </div>
                            </div>`;
                        return formHTML;
                    },
                },
            ],
            scrollX: isMobile,
            pagingType: "simple",
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            order: [],
        });
    }

   const archivedClassRecordStudent = document.querySelector(
        "#archivedClassRecordStudent"
    );

    if (archivedClassRecordStudent) {
        const dataTable = $(archivedClassRecordStudent).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-student-archived",
            },
            type: "GET",
            dataType: "json",
            columns: [
                {
                    data: "courseName",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                {
                    data: "courseCode",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                {
                    data: "null",
                    render: function (data, type, row) {
                        return `<div class="font-bold">${row.programName} ${row.yearLevel}</div>`;
                    },
                },

                {
                    data: null,
                    render: function (data, type, row) {
                        let semesterLabel = "Unknown Semester";
                        if (row.semester == 1) {
                            semesterLabel = "1st Semester";
                        } else if (row.semester == 2) {
                            semesterLabel = "2nd Semester";
                        } else if (row.semester == 3) {
                            semesterLabel = "Summer Semester";
                        }

                        return `<div class="font-bold">${semesterLabel} (${row.schoolYear})</div>`;
                    },
                },

                {
                    data: null,
                    render: function (data, type, row) {
                        let formHTML = `
                            <div class="relative group flex justify-center items-center">
                                <div class="flex justify-center items-center">
                                    <form action="/store-stud-class-record-id" method="POST">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                        <input type="hidden" name="classRecordIDView" value="${row.classRecordID}">
                        <button type="submit" class="cursor-pointer">
                            <i class="fa-solid fa-book text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer text-lg"></i>
                        </button>
                    </form>
                                </div>
                                <div class="absolute bottom-full left-1/2 transform hidden group-hover:block -translate-x-1/2 z-40 mb-4">
                                    <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                        <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">View Info</span>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                    </div>
                                </div>
                            </div>`;
                        return formHTML;
                    },
                },
            ],
            scrollX: isMobile,
            pagingType: "simple",
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            order: [],
        });
    }
});
