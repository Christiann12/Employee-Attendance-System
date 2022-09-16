

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

		<!-- developer js -->
		<script src="<?php echo base_url('application/assets/js/clock.js') ?>"></script>
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
							data: 'userId',
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
							data: null,
							orderable: false,
							className: 'data',
							render: function(data) {
								// console.log()
								var editLink = '<?php  echo base_url('EditUser/') ?>' + data.userId;
								var deleteLink = '<?php  echo base_url('DeleteUser/') ?>' + data.userId;
								return '<a class="btn btn-success rounded-1 " href="'+editLink+'">Edit</a><a class="btn btn-danger rounded-1 ml-1" href="'+deleteLink+'">Delete</a>';
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
								var editLink = '<?php  echo base_url('EditEmployee/') ?>' + data.data1;
								var deleteLink = '<?php  echo base_url('DeleteEmployee/') ?>' + data.data1;
								return '<a class="btn btn-success rounded-1 " href="'+editLink+'">Edit</a><a class="btn btn-danger rounded-1 ml-1" href="'+deleteLink+'">Delete</a>';
							}
						}
					],
					// "order":[],
					"searching": true,
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
								return '<a download class="btn btn-primary rounded-1" href="'+qrlink+'" >Open QR</a>';
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
						{
							data: 'data6',
							className: 'data'
						},
						{
							data: 'data7',
							className: 'data'
						},
					],
					// "order":[],
					"searching": true,
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
    </body>
</html>