<div class="attendancelist">
    <div class="banner">
        <p class="greeting">Time Sheet</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section3">
        <p class="header m-0">Upload Time Sheet</p>
        <small class="text-muted mt-0" style="margin-left: 30px;">Use this in case the system went down and there are offline attendance list.</small>
        
        <form class="mt-3" method="post" id="import_csv_timesheet" enctype="multipart/form-data">
            
            <div class="form-label-group importform">
                <label for="csv_file" class="labelDesign mr-3">Import Csv: </label>
                <input type="file" name="csv_file" id="csv_file" required accept=".csv" />
            </div>
            <div class="buttons">
                <button id="uploadButtonTimeSheet" type="submit" class="btn" >Upload</button>
                <a download href="<?php echo base_url("application/assets/csv/csvformattimesheet.csv"); ?>" class="btn ml-5"> Sample CSV</a>
            </div>
        </form>

    </div>
    <div class="section1">
        <!-- <p class="header m-0">Attendance List</p> -->
        <div class="filterform ">
            <form id="attendanceFilter">

                <div class="form-label-group mt-5">
                    <label for="empIdFilter" class="labelDesign spacing">Employee ID</label>
                    <input name="empIdFilter" type="text" id="empIdFilter" class="inputDesign form-control" placeholder="E.g EMP-xxxx-xxx" value="">
                </div>

                <div class="form-label-group mt-3">
                    <label for="dateFilter" class="labelDesign spacing">Date</label>
                    <input name="dateFilter" type="date" id="dateFilter" class="inputDesign form-control" placeholder="Date">
                </div>
                <!-- <div class="form-label-group mt-3">
                    <label style="margin: 0;">Baranggay</label>
                        <?php
                            $cmbBaranggayList = array(
                                '' => 'Select',
                                "Brgy. Novaliches Proper" => "Brgy. Novaliches Proper",
                                "Brgy. Bagbag" => "Brgy. Bagbag",
                                "Brgy. Capri" => "Brgy. Capri",
                                "Brgy. Greater Fairview" => "Brgy. Greater Fairview",
                                "Brgy. Greater Lagro??O" => "Brgy. Greater Lagro",
                                "Brgy. Gulod" => "Brgy. Gulod",
                                "Brgy. Kaligayahan._F._HOMES" => "Brgy. Kaligayahan",
                                "Brgy. North Fairview" => "Brgy. North Fairview",
                                "Brgy. Pasong Putik Proper" => "Brgy. Pasong Putik Proper",
                                "Brgy. San Agustin" => "Brgy. San Agustin",
                                "Brgy. San Bartolome" => "Brgy. San Bartolome",
                                "Brgy. Sta. Lucia" => "Brgy. Sta. Lucia",
                                "Brgy. Sta. Monica" => "Brgy. Sta. Monica",
                                "Brgy. Nagkakaisang Nayon" => "Brgy. Nagkakaisang Nayon",
                            ); 
                            echo form_dropdown('cmbBaranggay', $cmbBaranggayList, $cmbBaranggay, 'class="form-control" id="cmbBaranggay"');
                        ?>
                </div> -->
                <button type="submit"class="btn mt-3">Filter</button>
            </form>
        </div>
        <div class="attendancetable m-0">
            <table id="attendancetable" class="responsive display nowrap cell-border hover" width="100%">
                <thead>
                    <tr>
                        <!-- <th class="headertable text-wrap ">Attendance ID</th> -->
                        <th class="headertable text-wrap ">EMP ID</th>
                        <th class="headertable text-wrap ">First Name</th>
                        <th class="headertable text-wrap ">Last Name</th>
                        <th class="headertable text-wrap ">Time Before Break</th>
                        <th class="headertable text-wrap ">Time After Break</th>
                        <th class="headertable text-wrap ">Date Time in</th>
                        <th class="headertable text-wrap ">Regular Hour</th>
                        <th class="headertable text-wrap ">OT Hour</th>
                        <th class="headertable text-wrap ">Break Hour</th>
                        <th class="headertable text-wrap ">Dayoff</th>
                        <th class="headertable text-wrap ">Late</th>
                        <th class="headertable text-wrap ">UT_OT</th>
                        <th class="headertable text-wrap ">OverBreak</th>
                        <th class="headertable text-wrap mobile-p">Time In Picture</th>
                        <th class="headertable text-wrap mobile-p">Time Out Picture</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>