$(document).ready(function () {
    $('table[id^="datatable-"]').each(function () {
        $(this).DataTable({
            // responsive: true,
            paging: false,
            searching: false, 
            info: false, 
            lengthChange: false,
            order: [], 
            columnDefs: [
                {
                    targets: "_all", 
                    orderable: false, 
                },
            ],
            language: {
                zeroRecords: "No records found",
            },
        });
    });
});
