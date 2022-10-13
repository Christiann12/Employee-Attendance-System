<div class="employeedashboard">
    <div class="banner">
        <p class="greeting">Good day, <strong><?php echo $this->session->userdata('firstNameEmployee').' '.$this->session->userdata('lastNameEmployee'); ?></strong></p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="timesheet">
        <div class="containerPanel">
            <p class="title">Time Sheet</p>
            <center>
               
                <?php if($empData->timeout != 'timeout' || $empData->timein != 'timein' || $empData->dayoff != 'dayoff'  ){ ?>
                    <p class="hours"><?php  echo $circularData['message'] ?></p>
                <?php } else{ ?>
                    <p class="hours">No Current Schedule</p>
                <?php } ?>
                <span class="chart" data-percent="<?php echo ($empData->timeout != 'timeout' || $empData->timein != 'timein' || $empData->dayoff != 'dayoff'  ) ? $circularData['percent'] : 0;?>"></span>
                
            </center>
            <center>
                <a href="<?php echo base_url('General/ScanQrAndroid')?>" class="btn mt-3 <?= ($buttonStatus) ?  null : "disabled" ?>" >Scan QR code to time-in</a>
            </center>
        </div>
    </div>

    <div class="attendancelist">
        <p class="title">Time Sheet</p>
        <div class="employeeTable">
            <table id="employeeTable" class="responsive display nowrap cell-border hover" width="100%">
                <thead>
                    <tr>
                        <th class="headertable">Time in</th>
                        <th class="headertable">Time out</th>
                        <th class="headertable">Date</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
    <div class="dashboard mb-5">
        <div id="profileDashboard"></div>
    </div>
</div>