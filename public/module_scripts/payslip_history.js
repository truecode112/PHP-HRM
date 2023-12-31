// $(document).ready(function() {
//    var xin_table = $('#xin_table').dataTable({
//         "bDestroy": true,
//         "order": [[0, 'asc']],
// 		"ajax": {
//             url : main_url+"payroll/payslip_history_list",
//             type : 'GET'
//         },
// 		"language": {
//             "lengthMenu": dt_lengthMenu,
//             "zeroRecords": dt_zeroRecords,
//             "info": dt_info,
//             "infoEmpty": dt_infoEmpty,
//             "infoFiltered": dt_infoFiltered,
// 			"search": dt_search,
// 			"paginate": {
// 				"first": dt_first,
// 				"previous": dt_previous,
// 				"next": dt_next,
// 				"last": dt_last
// 			},
//         },
// 		"fnDrawCallback": function(settings){
// 		$('[data-toggle="tooltip"]').tooltip();          
// 		}
//     });
// });

$(document).ready(function() {
    var xin_table = $('#xin_table').dataTable({
         "bDestroy": true,
         "order": [[0, 'asc']],
         "ajax": {
             url : main_url+"payroll/payslip_history_list",
             type : 'GET'
         },
         "language": {
             "lengthMenu": dt_lengthMenu,
             "zeroRecords": dt_zeroRecords,
             "info": dt_info,
             "infoEmpty": dt_infoEmpty,
             "infoFiltered": dt_infoFiltered,
             "search": dt_search,
             "paginate": {
                 "first": dt_first,
                 "previous": dt_previous,
                 "next": dt_next,
                 "last": dt_last
             },
         },
         "fnDrawCallback": function(settings){
         $('[data-toggle="tooltip"]').tooltip();          
         }
     });
 
     // Sort the first column in reverse order
     xin_table.api().column(0).order('desc').draw();
 }); 
 