

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
		<script src="<?php echo base_url('application/assets/js/easypiechart.js') ?>"></script>
		<!-- circular progress bar  -->
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var chart = window.chart = new EasyPieChart(document.querySelector('span'), {
					easing: 'easeOutElastic',
					size: 150,
					delay: 3000,
					barColor: '#C96567',
					trackColor: '#1A1A1D',
					scaleColor: false,
					lineWidth: 10,
					trackWidth: 10,
					// lineCap: 'butt',
					onStep: function(from, to, percent) {
						this.el.children[0].innerHTML = Math.round(percent);
					}
				});

				document.querySelector('.js_update').addEventListener('click', function(e) {
					chart.update(Math.random()*200-100);
				});

			});
		</script>
		<!-- employee attendance table script  -->
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
					
					// "lengthMenu": [[10, 25, 100, 1000, 3000, -1], [10, 25, 100, 1000, 3000]],
					"processing":true,
					"language": {
						processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
					},
					"lengthChange": false,
					"info": false,   
					// "bLengthChange": true,
					// "serverSide":true,
					"responsive": true,
					// "bPaginate": true,
					// "sPaginationType": "full_numbers",
					"ajax": {
						"url": "<?php echo base_url('Employee/EmployeeDashboard/generateTable')?>",
						"type": "POST",
						// "data": {txtSearch:''}
					},
					columns: [
						{
							data: 'date',
							className: 'data'
						},
						{
							data: 'timebefore',
							className: 'data'
						},
						{
							data: 'timeafter',
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
					"searching": false,
				});
			}
		</script>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const chart1 = Highcharts.chart('profileDashboard', {
					chart: {
						type: 'column',
						scrollablePlotArea: {
							minWidth: 400,
							scrollPositionX: 1
						}
					},

					title: {
						text: 'Monthly Tardiness Measure'
					},

					xAxis: {
						categories: ['Late', 'On Time'],
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
						name: 'Tardiness Measure',
						// data: [1,1]
						data: [<?php echo (isset($lateCount) ? $lateCount : 0)?>, 
						<?php echo (isset($OnTimeCount) ? $OnTimeCount : 0)?>]
					}]

				});
			});
		</script>
    </body>
	
</html>