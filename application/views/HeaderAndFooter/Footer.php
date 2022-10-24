

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>		
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
		<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
	
		
		<script src="https://code.highcharts.com/highcharts.js"></script>

		<script src="//unpkg.com/alpinejs" defer></script>

		<!-- developer js -->
		<script src="<?php echo base_url('application/assets/js/clock.js') ?>"></script>
		<!-- confirm delete -->
		<script>
			function ConfirmDelete(){
				if (confirm("Are you sure you want to delete?")){
					return true;
				}
				else {
					return false;
				}
			}  
		</script>
		<!-- usertable script  -->
		<script>
			$(document).ready( function () {
				
				$('#userTable').DataTable().destroy();
				// var VtxtSearch=$("#txtSearchChild").val();
				loaduserTable();
			});	
			// $("#childSearch").submit(function(event){
			// 	event.preventDefault();
			// 	$('#userTable').DataTable().destroy();
			// 	var VtxtSearch=$("#txtSearchChild").val();
			// 	loaduserTable(VtxtSearch);
			// });
			function loaduserTable(txtSearch=''){
				// alert('thiswork');
				var dataTable = $('#userTable').DataTable({
					
					"lengthMenu": [[10, 25, 100, 1000, 3000, -1], [10, 25, 100, 1000, 3000]],
					"processing":true,
					"language": {
						processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
					},
					// "serverSide":true,
					"responsive": true,
					// "bPaginate": true,
					// "sPaginationType": "full_numbers",
					"ajax": {
						"url": "<?php echo base_url('Admin/UserManagement/UserTableAjax')?>",
						"type": "POST",
						// "data": {txtSearch:''}
					},
					columns: [
						{
							data: 'id',
							className: 'data'
						},
						{
							data: 'fname',
							className: 'data'
						},
						{
							data: 'lname',
							className: 'data'
						},
						{
							data: 'email',
							className: 'data'
						},
						{
							data: 'userRole',
							className: 'data'
						},
						{
							data: null,
							orderable: false,
							className: 'data',
							render: function(data) {
								// console.log()
								var editLink = '<?php  echo base_url('EditUser/') ?>' + data.encId;
								var deleteLink = '<?php  echo base_url('DeleteUser/') ?>' + data.encId;
								return '<a class="btn btn-success rounded-1 " href="'+editLink+'">Edit</a><a class="btn btn-danger rounded-1 ml-1" onclick="return ConfirmDelete()" href="'+deleteLink+'">Delete</a>';
							}
						}
					],
					// "order":[],
					"searching": true,
				});
			}
		</script>
		<!-- employee table script  -->
		<script>
			$(document).ready( function () {
				
				$('#employeeTable').DataTable().destroy();
				// var VtxtSearch=$("#txtSearchChild").val();
				loademployeeTable();
			});	
			// $("#childSearch").submit(function(event){
			// 	event.preventDefault();
			// 	$('#employeeTable').DataTable().destroy();
			// 	var VtxtSearch=$("#txtSearchChild").val();
			// 	loademployeeTable(VtxtSearch);
			// });
			function loademployeeTable(txtSearch=''){
				// alert('thiswork');
				var dataTable = $('#employeeTable').DataTable({
					
					"lengthMenu": [[10, 25, 100, 1000, 3000, -1], [10, 25, 100, 1000, 3000]],
					"processing":true,
					"language": {
						processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
					},
					
					// "serverSide":true,
					// "responsive": true,
					// "bPaginate": true,
					// "sPaginationType": "full_numbers",
					"ajax": {
						"url": "<?php echo base_url('Admin/Employees/EmployeeTableAjax')?>",
						"type": "POST",
						// "data": {txtSearch:''}
					},
					columns: [
						{
							data: 'data8',
							className: 'data d-none',
						},
						{
							data: 'data1',
							className: 'data'
						},
						{
							data: 'data2',
							className: 'data'
						},
						{
							data: 'data3',
							className: 'data'
						},
						{
							data: 'data9',
							className: 'data'
						},
						{
							data: 'data4',
							className: 'data'
						},
						{
							data: 'data5',
							className: 'data'
						},
						{
							data: null,
							orderable: false,
							className: 'data',
							render: function(data) {
								// console.log()
								var editLink = '<?php  echo base_url('EditEmployee/') ?>' + data.data7;
								var deleteLink = '<?php  echo base_url('DeleteEmployee/') ?>' + data.data7;
								return '<a class="btn btn-success rounded-1 " href="'+editLink+'">Edit</a><a class="btn btn-danger rounded-1 ml-1" onclick="return ConfirmDelete()" href="'+deleteLink+'">Delete</a>';
							}
						},
						
					],
					"columnDefs":[{
						"targets":[1],
						"orderable":false,
						// className : "d-none"
					},],
					// "order":[],
					"searching": true,
					"dom": "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-sm-12't>><'row'<'col-sm-6'i><'col-sm-6'p>>",
					
					
					buttons: [
						// 'copyHtml5',
						{
							extend: 'copyHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'Copy',
							className: 'btn-size',
							exportOptions: {
								columns: [ 1,2,3,4,5]
							}
						},
						// 'excelHtml5',
						{
							extend: 'excelHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'Excel',
							className: 'btn-size',
							exportOptions: {
								columns: [ 1,2,3,4,5]
							}
						},
						// 'csvHtml5',
						{
							extend: 'csvHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'CSV',
							className: 'btn-size',
							exportOptions: {
								columns: [ 1,2,3,4,5]
							}
						},
						// 'pdfHtml5',
						{
							extend: 'pdfHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'PDF',
							className: 'btn-size',
							exportOptions: {
								columns: [ 1,2,3,4,5]
							}
						},
						// 'print',
						{
							extend: 'print',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'Print',
							className: 'btn-size',
							exportOptions: {
								columns: [ 1,2,3,4,5]
							}
						},
					]
					
				});
			}
		</script>
		<!-- generateqr script  -->
		<script>
			$(document).ready( function () {
				
				$('#generateqrTable').DataTable().destroy();
				// var VtxtSearch=$("#txtSearchChild").val();
				loadgenerateqrTable();
			});	
			// $("#childSearch").submit(function(event){
			// 	event.preventDefault();
			// 	$('#generateqrTable').DataTable().destroy();
			// 	var VtxtSearch=$("#txtSearchChild").val();
			// 	loadgenerateqrTable(VtxtSearch);
			// });
			function loadgenerateqrTable(txtSearch=''){
				// alert('thiswork');
				var dataTable = $('#generateqrTable').DataTable({
					
					"lengthMenu": [[10, 25, 100, 1000, 3000, -1], [10, 25, 100, 1000, 3000]],
					"processing":true,
					"language": {
						processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
					},
					// "serverSide":true,
					"responsive": true,
					// "bPaginate": true,
					// "sPaginationType": "full_numbers",
					"ajax": {
						"url": "<?php echo base_url('Admin/Employees/EmployeeTableAjax')?>",
						"type": "POST",
						// "data": {txtSearch:''}
					},
					columns: [
						{
							data: 'data1',
							className: 'data'
						},
						{
							data: 'data2',
							className: 'data'
						},
						{
							data: 'data3',
							className: 'data'
						},
						{
							data: null,
							orderable: false,
							className: 'data',
							render: function(data) {
								// console.log()
								var qrlink = 'https://chart.googleapis.com/chart?cht=qr&chs=250x250&chl=' + data.data6;
								return '<a download class="btn btn-primary rounded-1" href="'+qrlink+'">Open QR</a>';
							}
						}
					],
					// "order":[],
					"searching": true,
				});
			}
		</script>
		<!-- schedule table script  -->
		<script>
			$(document).ready( function () {
				
				$('#scheduleTable').DataTable().destroy();
				// var VtxtSearch=$("#txtSearchChild").val();
				loadscheduleTable();
			});	
			// $("#childSearch").submit(function(event){
			// 	event.preventDefault();
			// 	$('#scheduleTable').DataTable().destroy();
			// 	var VtxtSearch=$("#txtSearchChild").val();
			// 	loadscheduleTable(VtxtSearch);
			// });
			function loadscheduleTable(txtSearch=''){
				// alert('thiswork');
				var dataTable = $('#scheduleTable').DataTable({
					
					"lengthMenu": [[10, 25, 100, 1000, 3000, -1], [10, 25, 100, 1000, 3000]],
					"processing":true,
					"language": {
						processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
					},
					// "serverSide":true,
					"responsive": true,
					// "bPaginate": true,
					// "sPaginationType": "full_numbers",
					"ajax": {
						"url": "<?php echo base_url('Admin/UploadSched/generateTable')?>",
						"type": "POST",
						// "data": {txtSearch:''}
					},
					columns: [
						{
							data: 'data1',
							className: 'data'
						},
						{
							data: 'data2',
							className: 'data'
						},
						{
							data: 'data3',
							className: 'data'
						},
						{
							data: 'data4',
							className: 'data'
						},
						{
							data: 'data5',
							className: 'data'
						},
					],
					// "order":[],
					"searching": true,
				});
			}
		</script>
		<!-- attendance table script  -->
		<script>
			$(document).ready( function () {
				
				$('#attendancetable').DataTable().destroy();
				// var VtxtSearch=$("#txtSearchChild").val();
				loadattendancetable();
			});	
			$("#attendanceFilter").submit(function(event){
				event.preventDefault();
				var empId=$("#empIdFilter").val();
				var date=$("#dateFilter").val();
				$('#attendancetable').DataTable().destroy();
				loadattendancetable(empId,date);
			});
			function loadattendancetable(empId='',date=''){
				// alert('thiswork');
				var dataTable = $('#attendancetable').DataTable({
					
					"lengthMenu": [[10, 25, 100, 1000, 3000, -1], [10, 25, 100, 1000, 3000]],
					"processing":true,
					"language": {
						processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
					},
					// "serverSide":true,
					"responsive": true,
					// "bPaginate": true,
					// "sPaginationType": "full_numbers",
					"ajax": {
						"url": "<?php echo base_url('Admin/AttendanceList/generateTable')?>",
						"type": "POST",
						"data": {empId:empId,date:date}
					},
					columns: [
						{
							data: 'attendanceId',
							className: 'data'
						},
						{
							data: 'empId',
							className: 'data'
						},
						{
							data: 'fname',
							className: 'data'
						},
						{
							data: 'lname',
							className: 'data'
						},
						{
							data: 'time1',
							className: 'data'
						},
						{
							data: 'time2',
							className: 'data'
						},
						{
							data: 'Date_Time_In',
							className: 'data'
						},
						{
							data: 'Hours_Worked_Regular',
							className: 'data'
						},
						{
							data: 'Hours_Worked_OT',
							className: 'data'
						},
						{
							data: 'Break_Hour',
							className: 'data'
						},
						{
							data: 'Dayoff',
							className: 'data'
						},
						{
							data: 'Late',
							className: 'data'
						},
						{
							data: 'UT_OT',
							className: 'data'
						},
						{
							data: 'OverBreak',
							className: 'data'
						},
						
					],
					// "order":[],
					"searching": true,
					"dom": "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-sm-12't>><'row'<'col-sm-6'i><'col-sm-6'p>>",
					buttons: [
						// 'copyHtml5',
						{
							extend: 'copyHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'Copy',
							className: 'btn-size',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
							}
						},
						// 'excelHtml5',
						{
							extend: 'excelHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'Excel',
							className: 'btn-size',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
							}
						},
						// 'csvHtml5',
						{
							extend: 'csvHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'CSV',
							className: 'btn-size',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
							}
						},
						// 'pdfHtml5',
						{
							extend: 'pdfHtml5',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'PDF',
							className: 'btn-size',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
							}
						},
						// 'print',
						{
							extend: 'print',
							footer: true,
							// text: '<i class="fa fa-copy"></i>',
							text: 'Print',
							className: 'btn-size',
							exportOptions: {
								columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
							}
						},
					]
				});
			}
		</script>
		<!-- import csv script  -->
		<script type="text/javascript">
			$(document).ready(function(){
				$('#import_csv').on('submit', function(event){
					//alert('test');
					event.preventDefault();
					$.ajax({
						url:"<?php echo base_url('Admin/Employees/import'); ?>",
						method:"POST",
						data:new FormData(this),
						contentType:false,
						cache:false,
						processData:false,
						beforeSend:function(){
							$('#uploadButton').html('Importing...');
						},
						success:function(data)
						{
							$('#import_csv')[0].reset();
							$('#uploadButton').attr('disabled', false);
							$('#uploadButton').html('Import Done');
							setInterval(function(){
								window.location.href = "<?= base_url('AdminEmployees')?>";
							}, 2000);
						},
						error: function( jqXhr ) {
							
							if( jqXhr.status == 400 ) { //Validation error or other reason for Bad Request 400
								var json = JSON.parse( jqXhr.responseText );
								alert(json.message);
								$('#import_csv')[0].reset();
								// $('#uploadButton').attr('disabled', false);
								$('#uploadButton').html('Upload');
							}
						}
					})
				});
			});
		</script>
		<!-- import csv sched  -->
		<script type="text/javascript">
			$(document).ready(function(){
				$('#import_csv_schedule').on('submit', function(event){
					//alert('test');
					event.preventDefault();
					$.ajax({
						url:"<?php echo base_url('Admin/UploadSched/import'); ?>",
						method:"POST",
						data:new FormData(this),
						contentType:false,
						cache:false,
						processData:false,
						beforeSend:function(){
							$('#uploadButton_schedule').html('Importing...');
						},
						success:function(data)
						{
							$('#import_csv_schedule')[0].reset();
							$('#uploadButton_schedule').attr('disabled', false);
							$('#uploadButton_schedule').html('Import Done');
							setInterval(function(){
								window.location.href = "<?= base_url('UploadSched')?>";
							}, 2000);
						},
						error: function( jqXhr ) {
							
							if( jqXhr.status == 400 ) { //Validation error or other reason for Bad Request 400
								var json = JSON.parse( jqXhr.responseText );
								alert(json.message);
								$('#import_csv_schedule')[0].reset();
								// $('#uploadButton').attr('disabled', false);
								$('#uploadButton_schedule').html('Upload');
							}
						}
					})
				});
			});
		</script>
		<!-- import offline log  -->
		<script type="text/javascript">
			$(document).ready(function(){
				$('#import_csv_timesheet').on('submit', function(event){
					//alert('test');
					event.preventDefault();
					$.ajax({
						url:"<?php echo base_url('Admin/AttendanceList/import'); ?>",
						method:"POST",
						data:new FormData(this),
						contentType:false,
						cache:false,
						processData:false,
						beforeSend:function(){
							$('#uploadButtonTimeSheet').html('Importing...');
						},
						success:function(data)
						{
							$('#import_csv_timesheet')[0].reset();
							$('#uploadButtonTimeSheet').attr('disabled', false);
							$('#uploadButtonTimeSheet').html('Import Done');
							var x =  setInterval(function(){
								clearInterval(x);
								window.location.href = "<?= base_url('TimeSheet')?>";
							}, 2000);
						},
						error: function( jqXhr ) {
							
							if( jqXhr.status == 400 ) { //Validation error or other reason for Bad Request 400
								var json = JSON.parse( jqXhr.responseText );
								alert(json.message);
								$('#import_csv_timesheet')[0].reset();
								// $('#uploadButtonTimeSheet').attr('disabled', false);
								$('#uploadButtonTimeSheet').html('Upload');
							}
						}
					})
				});
			});
		</script>
		<!-- graph js  -->
		<script>
			
			document.addEventListener('DOMContentLoaded', function () {
				const chart1 = Highcharts.chart('MonthlyLate', {
					chart: {
						type: 'column',
						scrollablePlotArea: {
							minWidth: 400,
							scrollPositionX: 1
						}
					},

					title: {
						text: 'Monthly Late Count'
					},

					xAxis: {
						categories: ['January', 'February','March','April','May','June','July','August','September','October','November','December'],
						labels: {
							overflow: 'justify'
						}
					},
					yAxis: {
						type: 'logarithmic',

						title: {
							text: 'Late Count'
						}
					},

					series: [{
						name: 'Number of Late employees monthly',
						// data: [1,1,1,1,1,1,1,1,1,1,1,1]
						data: [<?php echo (isset($LateCountJanuary) ? $LateCountJanuary : 0)?>, 
						<?php echo (isset($LateCountFebruary) ? $LateCountFebruary : 0)?>, 
						<?php echo (isset($LateCountMarch) ? $LateCountMarch : 0)?>, 
						<?php echo (isset($LateCountApril) ? $LateCountApril : 0)?>, 
						<?php echo (isset($LateCountMay) ? $LateCountMay : 0)?>, 
						<?php echo (isset($LateCountJune) ? $LateCountJune : 0)?>, 
						<?php echo (isset($LateCountJuly) ? $LateCountJuly : 0)?>,
						<?php echo (isset($LateCountAugust) ? $LateCountAugust : 0)?>,
						<?php echo (isset($LateCountSeptember) ? $LateCountSeptember : 0)?>,
						<?php echo (isset($LateCountOctober) ? $LateCountOctober : 0)?>,
						<?php echo (isset($LateCountNovember) ? $LateCountNovember : 0)?>,
						<?php echo (isset($LateCountDecember) ? $LateCountDecember : 0)?>]
					}]

				});
			});
			document.addEventListener('DOMContentLoaded', function () {
				const chart1 = Highcharts.chart('MonthlyOnTime', {
					chart: {
						type: 'column',
						scrollablePlotArea: {
							minWidth: 400,
							scrollPositionX: 1
						}
					},

					title: {
						text: 'Monthly On Time Count'
					},

					xAxis: {
						categories: ['January', 'February','March','April','May','June','July','August','September','October','November','December'],
						labels: {
							overflow: 'justify'
						}
					},
					yAxis: {
						type: 'logarithmic',

						title: {
							text: 'Count'
						}
					},

					series: [{
						name: 'Number of On Time employees monthly',
						// data: [1,1,1,1,1,1,1,1,1,1,1,1]
						data: [<?php echo (isset($OnTimeCountJanuary) ? $OnTimeCountJanuary : 0)?>, 
						<?php echo (isset($OnTimeCountFebruary) ? $OnTimeCountFebruary : 0)?>, 
						<?php echo (isset($OnTimeCountMarch) ? $OnTimeCountMarch : 0)?>, 
						<?php echo (isset($OnTimeCountApril) ? $OnTimeCountApril : 0)?>, 
						<?php echo (isset($OnTimeCountMay) ? $OnTimeCountMay : 0)?>, 
						<?php echo (isset($OnTimeCountJune) ? $OnTimeCountJune : 0)?>, 
						<?php echo (isset($OnTimeCountJuly) ? $OnTimeCountJuly : 0)?>,
						<?php echo (isset($OnTimeCountAugust) ? $OnTimeCountAugust : 0)?>,
						<?php echo (isset($OnTimeCountSeptember) ? $OnTimeCountSeptember : 0)?>,
						<?php echo (isset($OnTimeCountOctober) ? $OnTimeCountOctober : 0)?>,
						<?php echo (isset($OnTimeCountNovember) ? $OnTimeCountNovember : 0)?>,
						<?php echo (isset($OnTimeCountDecember) ? $OnTimeCountDecember : 0)?>]
					}]

				});
			});
		</script>
		
    </body>
</html>