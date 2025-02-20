$(document).ready(function () {
    const auditTableSuperAdmin = document.querySelector(
        "#auditTableSuperAdmin"
    );

    if (auditTableSuperAdmin) {
        const dataTable = $(auditTableSuperAdmin).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-super-act-logs",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), 
                },
            },
            columns: [
                {
                    data: "action",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                { data: "description" },
                {
                    data: "action_time",
                    title: "Timestamp",
                    render: function (data, type, row) {
                        if (data) {
                            const date = new Date(data);
                            const options = {
                                year: "numeric",
                                month: "2-digit",
                                day: "2-digit",
                                hour: "numeric",
                                minute: "2-digit",
                                second: "2-digit",
                                hour12: true,
                            };
                            return date.toLocaleString("en-US", options);
                        }
                        return "";
                    },
                    className: "text-center",
                },
            ],
            // responsive: true,
            scrollX: true,
            pagingType: "simple",
            paging: true,
            order: [],
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            columnDefs: [
                {
                    targets: [0, 1],
                    orderable: false,
                },
            ],
        });
    }

    const auditTableAdmin = document.querySelector("#auditTableAdmin");

    if (auditTableAdmin) {
        const dataTable = $(auditTableAdmin).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/get-admin-act-logs",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), 
                },
            },
            columns: [
                {
                    data: "action",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                { data: "description", title: "Description" },
                {
                    data: "action_time",
                    title: "Timestamp",
                    render: function (data, type, row) {
                        if (data) {
                            const date = new Date(data);
                            const options = {
                                year: "numeric",
                                month: "2-digit",
                                day: "2-digit",
                                hour: "numeric",
                                minute: "2-digit",
                                second: "2-digit",
                                hour12: true,
                            };
                            return date.toLocaleString("en-US", options);
                        }
                        return "";
                    },
                    className: "text-center",
                },
            ],
            // responsive: true,
            scrollX: true,
            pagingType: "simple",
            paging: true,
            order: [],
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            columnDefs: [
                {
                    targets: [0, 1],
                    orderable: false,
                },
            ],
        });
    }

    const auditTableFaculty = document.querySelector("#auditTableFaculty");

    if (auditTableFaculty) {
        const dataTable = $(auditTableFaculty).DataTable({
            // processing: true,
            // ajax: {
            //     url: "/get-faculty-act-logs",
            //     type: "GET",
            //     dataType: "json",
            //     dataSrc: "",
            // },

            processing: true,
            serverSide: true,
            // ajax: {
            //     url: "/get-faculty-act-logs",
            // },
            // type: "GET",
            // dataType: "json",
            ajax: {
                url: "/get-faculty-act-logs",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), 
                },
            },
            columns: [
                {
                    data: "action",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                { data: "description", title: "Description" },
                {
                    data: "action_time",
                    title: "Timestamp",
                    render: function (data, type, row) {
                        if (data) {
                            const date = new Date(data);
                            const options = {
                                year: "numeric",
                                month: "2-digit",
                                day: "2-digit",
                                hour: "numeric",
                                minute: "2-digit",
                                second: "2-digit",
                                hour12: true,
                            };
                            return date.toLocaleString("en-US", options);
                        }
                        return "";
                    },
                    className: "text-center",
                },
            ],
            // responsive: true,
            scrollX: true,
            pagingType: "simple",
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            order: [[]],
            columnDefs: [
                {
                    targets: [0, 1],
                    orderable: false,
                },
            ],
        });
    }

    const auditTableStudent = document.querySelector("#auditTableStudent");

    if (auditTableStudent) {
        const dataTable = $(auditTableStudent).DataTable({
            processing: true,
            serverSide: true,
            // ajax: {
            //     url: "/get-student-act-logs",
            // },
            // type: "GET",
            // dataType: "json",
            ajax: {
                url: "/get-student-act-logs",
                type: "GET",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), 
                },
            },
            columns: [
                {
                    data: "action",
                    render: function (data, type, row) {
                        return `<div class=" font-bold">${data}</div>`;
                    },
                },
                { data: "description", title: "Description" },
                {
                    data: "action_time",
                    title: "Timestamp",
                    render: function (data, type, row) {
                        if (data) {
                            const date = new Date(data);
                            const options = {
                                year: "numeric",
                                month: "2-digit",
                                day: "2-digit",
                                hour: "numeric",
                                minute: "2-digit",
                                second: "2-digit",
                                hour12: true,
                            };
                            return date.toLocaleString("en-US", options);
                        }
                        return "";
                    },
                    className: "text-center",
                },
            ],
            scrollX: true,
            pagingType: "simple",
            paging: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            order: [[]],
            columnDefs: [
                {
                    targets: [0, 1],
                    orderable: false,
                },
            ],
        });
    }
});
