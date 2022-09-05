<div class="employeedashboard">
    <div class="banner">
        <p class="greeting">Good day, <strong><?php echo $this->session->userdata('firstNameEmployee').' '.$this->session->userdata('lastNameEmployee'); ?></strong></p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="timesheet">
        <div class="containerPanel">
            <p class="title">Time Sheet</p>
            <center>
                <p class="hours">8 hours</p>
                <span class="chart" data-percent="16"></span>
                
            </center>
            <center>
                <a href="#" class="btn btn-primary mt-3" >Scan QR code to time-in</a>
            </center>
        </div>
    </div>
</div>