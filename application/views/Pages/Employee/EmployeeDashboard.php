<div class="employeedashboard">
    <div class="banner">
        <p class="greeting">Good day, <strong><?php echo $this->session->userdata('firstNameEmployee').' '.$this->session->userdata('lastNameEmployee'); ?></strong></p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="timesheet">
        <div class="containerPanel">
            <!-- RESULT NOTIFICATION  -->
            <?php if($this->session->flashdata('successEmpDashboard')){ ?>
                <div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="alert alert-success" > 
                    <?php  echo $this->session->flashdata('successEmpDashboard'); $this->session->unset_userdata ( 'successEmpDashboard' );?>
                </div>
            <?php } ?>  
            <?php if ($this->session->flashdata('errorEmpDashboard')){ ?>
                <div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="alert alert-danger" > 
                    <?php  echo $this->session->flashdata('errorEmpDashboard'); $this->session->unset_userdata ( 'errorEmpDashboard' );?>
                </div>
            <?php } ?>
            <p class="title">Attendance</p>
            <center>
               
                <?php if($empData->timeout != 'timeout' || $empData->timein != 'timein' || $empData->dayoff != 'dayoff'  ){ ?>
                    <p class="hours"><?php  echo $circularData['message'] ?></p>
                <?php } else{ ?>
                    <p class="hours">No Current Schedule</p>
                <?php } ?>
                <span class="chart" data-percent="<?php echo ($empData->timeout != 'timeout' || $empData->timein != 'timein' || $empData->dayoff != 'dayoff'  ) ? $circularData['percent'] : 0;?>"></span>
                
            </center>
            <center>
                <div style=" display: flex; flex-direction: column;">
                    <a href="<?php echo base_url('General/ScanQrAndroid')?>" class="btn mt-3 <?= ($buttonStatus) ?  null : "disabled" ?>" >Scan QR code to Timein/Timeout</a>
                    <a href="<?php echo base_url('Employee/EmployeeScan/break/'.$this->session->userdata('secretIdEmployee'))?>" class="btn mt-3 <?= ($buttonStatusBreak) ?  null : "disabled" ?>" >Break Timein/Timeout</a>
                </div>
            </center>
        </div>
    </div>

    <div class="attendancelist">
        <p class="title">Time Sheet</p>
        <div class="employeeTable">
            <table id="employeeTable" class="responsive display nowrap cell-border hover" width="100%">
                <thead>
                    <tr>
                        <th class="headertable">Date</th>
                        <th class="headertable mobile-p">Time Before Break</th>
                        <th class="headertable mobile-p">Time After Break</th>
                        <th class="headertable text-wrap mobile-p">Regular Hour</th>
                        <th class="headertable text-wrap mobile-p">OT Hour</th>
                        <th class="headertable text-wrap mobile-p">Break Hour</th>
                        <th class="headertable text-wrap mobile-p ">Dayoff</th>
                        <th class="headertable text-wrap mobile-p ">Late</th>
                        <th class="headertable text-wrap mobile-p ">UT_OT</th>
                        <th class="headertable text-wrap mobile-p ">OverBreak</th>
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