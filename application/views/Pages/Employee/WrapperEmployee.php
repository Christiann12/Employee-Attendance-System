


<nav class="navbar navbar-expand navbar-light">
    <!-- <a class="navbar-brand" href="#">
        <img src="<?php echo base_url(); ?>application/assets/images/logo1.png" width="85" height="50" alt="">
    </a> -->
    <div style="width: 100%;">
        
            <ul class="navbar-nav">
                <li class="nav-item <?php echo ((strtolower($this->uri->segment(1)) == 'employeedashboard') ? "active" : null) ?>">
                    <a class="nav-link" href="<?php echo base_url('EmployeeDashboard')?>"><center><i class="fa fa-home" aria-hidden="true"></i></center></a>
                </li>
                <li class="nav-item <?php echo ((strtolower($this->uri->segment(1)) == 'employeeprofile') ? "active" : null) ?>">
                    <a class="nav-link" href="<?php echo base_url('EmployeeProfile')?>"><center><i class="fa fa-user" aria-hidden="true"></i></center></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('EmployeeLogin/signout')?>"><center><i class="fa fa-sign-out" aria-hidden="true"></i></center></a>
                </li>
            </ul>
        
    </div>
</nav>
    <!-- /#sidebar-wrapper -->

<!-- Admin Navbar -->
<div id="page-content-wrapper">
    <!-- Page content -->
    <?php include $page.".php";?> 
    <!-- end of page content -->
</div>
<!-- /#page-content-wrapper -->


<!-- /#wrapper -->