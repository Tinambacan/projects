import './bootstrap';

import Swal from 'sweetalert2';

window.Swal = Swal;

import $ from 'jquery';

window.jQuery = window.$ = $

import DataTable from 'datatables.net-dt';
// // import 'datatables.net-responsive';
import 'datatables.net-dt/css/jquery.dataTables.min.css';
import 'datatables.net/js/jquery.dataTables.min.js';

// // import 'resources/js/jquery.dataTable.min.js'


// let table = new DataTable('#myTable', {
//     // config options...
//     responsive: true
// });