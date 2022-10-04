<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <div class="sidebar-heading mb-3">
            <center>
                <img src="<?php echo base_url(); ?>application/assets/images/logo1.png" alt="logo" class="">
            </center>
            <p class="text-center">
                <!-- AppEATite -->
            </p>
        </div>
        <div class="list-group list-group-flush">

            <a href="<?php echo base_url('AdminDashboard')?>"
                class=" <?php echo ((strtolower($this->uri->segment(1)) == 'admindashboard') ? "active" : null) ?> list-group-item list-group-item-action"
                data-toggle="tooltip" data-placement="right" title="Home">Home</a>

            <?php if (strtolower($this->session->userdata('userRole')) == "admin" || strtolower($this->session->userdata('userRole')) == "humanresource") {?>
            <a href="<?php echo base_url('AdminEmployees')?>"
                class=" <?php echo ((strtolower($this->uri->segment(1)) == 'adminemployees' || strtolower($this->uri->segment(1)) == 'editemployee' ) ? "active" : null) ?> list-group-item list-group-item-action"
                data-toggle="tooltip" data-placement="right" title="Add Child">Employees</a>
            <?php } if (strtolower($this->session->userdata('userRole')) == "admin" || strtolower($this->session->userdata('userRole')) == "finance") {?>
            <a href="<?php echo base_url('AttendanceList')?>"
                class=" <?php echo ((strtolower($this->uri->segment(1)) == 'attendancelist') ? "active" : null) ?> list-group-item list-group-item-action"
                data-toggle="tooltip" data-placement="right" title="Attendance List">Attendance List</a>
            <?php } if (strtolower($this->session->userdata('userRole')) == "admin" || strtolower($this->session->userdata('userRole')) == "operations" ) {?>
            <a href="<?php echo base_url('UploadSched')?>"
                class=" <?php echo ((strtolower($this->uri->segment(1)) == 'uploadsched') ? "active" : null) ?> list-group-item list-group-item-action"
                data-toggle="tooltip" data-placement="right" title="Upload schedule">Upload schedule</a>
            <?php } if (strtolower($this->session->userdata('userRole')) == "admin") {?>
            <a href="<?php echo base_url('UserManagement')?>"
                class=" <?php echo ((strtolower($this->uri->segment(1)) == 'usermanagement' || strtolower($this->uri->segment(1)) == 'edituser' ) ? "active" : null) ?> list-group-item list-group-item-action"
                data-toggle="tooltip" data-placement="right" title="User Management">User Management</a>
            <?php } if (strtolower($this->session->userdata('userRole')) == "admin" || strtolower($this->session->userdata('userRole')) == "humanresource") {?>
            <a href="<?php echo base_url('GenerateQr')?>"
                class=" <?php echo ((strtolower($this->uri->segment(1)) == 'generateqr') ? "active" : null) ?> list-group-item list-group-item-action"
                data-toggle="tooltip" data-placement="right" title="Generate Qr">Generate QR</a>
            <?php } ?>

            <a href="<?php echo base_url('Admin/UserManagement/signout')?>"
                class="list-group-item list-group-item-action" data-toggle="tooltip" data-placement="right"
                title="Log Out" style=" position: absolute; bottom: 0;">Sign Out</a>
        </div>


    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Admin Navbar -->
    <div id="page-content-wrapper">
        <!-- Page content -->
        <?php include $page.".php";?>
        <!-- end of page content -->
    </div>
    <!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->