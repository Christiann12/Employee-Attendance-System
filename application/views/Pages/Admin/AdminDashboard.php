<div class="admindashboard">
    <div class="banner">
        <p class="greeting">Good day, <strong><?php echo $this->session->userdata('firstName').' '.$this->session->userdata('lastName'); ?></strong></p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section1">
        <p class="header">For this month</p>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="col-12 dashboardcard" style="background-color: #46BC7E;">
                    <center>
                        <i class="fa fa-users icon" aria-hidden="true"></i>
                        <p class="cardheader">25</p>
                        <p class="carddesc">No. of Employees Active</p>
                    </center>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="col-12 dashboardcard" style="background-color: #EA677E;">
                    <center>
                        <i class="fa fa-user-times icon" aria-hidden="true"></i>
                        <p class="cardheader">55</p>
                        <p class="carddesc">No. of employees absent</p>
                    </center>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 ">
                <div class="col-12 dashboardcard" style="background-color: #42BAB4;">
                    <center>
                        <i class="fa fa-file icon" aria-hidden="true"></i>
                        <p class="cardheader">2</p>
                        <p class="carddesc">No. of employees files for leave</p>
                    </center>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <div class="col-12 dashboardcard" style="background-color: #FDA86F;">
                    <center>
                        <i class="fa fa-clock-o icon" aria-hidden="true"></i>
                        <p class="cardheader">1</p>
                        <p class="carddesc">No. of employees who were late</p>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="section2">
        <p class="header">Dashboard</p>
        <div class="graphcontainer">
            <center>
                <p>Graphs Goes Here!</p>
            </center>
        </div>
    </div>
</div>