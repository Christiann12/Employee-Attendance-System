<div class="admindashboard">
    <div class="banner">
        <p class="greeting">Good day, <strong><?php echo $this->session->userdata('firstName').' '.$this->session->userdata('lastName').' - '.$this->session->userdata('userRole'); ?></strong></p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section1">
        <p class="header">Overview</p>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 ">
                <div class="col-12 dashboardcard" style="background-color: #42BAB4;">
                    <center>
                        <i class="fa fa-file icon" aria-hidden="true"></i>
                        <p class="cardheader"><?php echo !empty($totalNumEmp) ? $totalNumEmp : 0; ?></p>
                        <p class="carddesc">Total Number of employees</p>
                    </center>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="col-12 dashboardcard" style="background-color: #46BC7E;">
                    <center>
                        <i class="fa fa-users icon" aria-hidden="true"></i>
                        <p class="cardheader"><?php echo !empty($activeEmp) ? $activeEmp : 0; ?></p>
                        <p class="carddesc">No. of employees with schedule</p>
                    </center>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="col-12 dashboardcard" style="background-color: #EA677E;">
                    <center>
                        <i class="fa fa-user-plus icon" aria-hidden="true"></i>
                        <p class="cardheader"><?php echo !empty($noPresent) ? $noPresent : 0; ?></p>
                        <p class="carddesc">Present Employees Today</p>
                    </center>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="col-12 dashboardcard" style="background-color: #FDA86F;">
                    <center>
                        <i class="fa fa-clock-o icon" aria-hidden="true"></i>
                        <p class="cardheader"><?php echo  !empty($noLate) ? $noLate : 0; ?></p>
                        <p class="carddesc">Late Employees Today</p>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="section2">
        <p class="header">Dashboard</p>
        <div class="graphcontainer">
            <!-- <p>Graphs Goes Here!</p> -->
            <div class="row">
                <div class="col-6">
                
                    <div class="graph1" id="MonthlyLate">
                        <!-- <p>Graphs Goes Here!</p> -->
                    </div>
                </div>
                <div class="col-6">
                
                    <div class="graph1" id="MonthlyOnTime">
                        <!-- <p>Graphs Goes Here!</p> -->
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>