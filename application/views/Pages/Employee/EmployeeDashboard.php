<div class="employeedashboard">
    <div class="banner">
        <p class="greeting">Good day, <strong><?php echo $this->session->userdata('firstNameEmployee').' '.$this->session->userdata('lastNameEmployee'); ?></strong></p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="timesheet">
        <div class="containerPanel">
            <p class="title">Time Sheet</p>
            <center>
                <?php 
                    if($attendanceDetail->timeout != 'timeout'){
                        $timein = strtotime($attendanceDetail->timein);
                        $timeout = strtotime($attendanceDetail->timeout);
                        $diff  = ($timeout - $timein);

                        
                        if(strtotime($attendanceDetail->timein) > strtotime(date('H:i'))){
                            $time1 = strtotime($attendanceDetail->timein);
                            $time2 = strtotime($attendanceDetail->timeout);
                            $diff2 = ($time2 - $time1);
                        }
                        else{
                            $time1 = strtotime(date('H:i'));
                            $time2 = strtotime($attendanceDetail->timeout);
                            $diff2 = ($time2 - $time1);
                        }
                        $temp1 = ($diff/60)/60;
                        $temp2 = ($diff2/60)/60;
                        $newhour = $temp1 - $temp2;
                        $percent = round(($newhour / $temp1)*100,2);
                    }
                ?>
                <?php if($attendanceDetail->timeout != 'timeout'){ ?>
                    <p class="hours"><?php echo (round($temp2,2) < 0 ) ? 0 : round($temp2,2);?> hour left</p>

                <?php } else{ ?>
                    <p class="hours">No Current Schedule</p>
                <?php } ?>
                <span class="chart" data-percent="<?php echo ($attendanceDetail->timeout != 'timeout') ? $percent : 0;?>"></span>
                
            </center>
            <center>
                <a href="<?php echo base_url('General/ScanQrAndroid')?>" class="btn mt-3 <?php echo ($this->session->userdata('employeeTimein') == 'timein') ? "disabled"  : null ?>" >Scan QR code to time-in</a>
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
</div>