<div class="attendancelist">
    <div class="banner">
        <p class="greeting">Time Sheet</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section1">
        <!-- <p class="header m-0">Attendance List</p> -->
        <div class="filterform ">
            <form id="attendanceFilter">

                <div class="form-label-group mt-5">
                    <label for="empIdFilter" class="labelDesign spacing">Employee ID</label>
                    <input name="empIdFilter" type="text" id="empIdFilter" class="inputDesign form-control" placeholder="E.g EMP-xxxxxxxx" value="">
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
                        <th class="headertable">Attendance ID</th>
                        <th class="headertable">First Name</th>
                        <th class="headertable">Last Name</th>
                        <th class="headertable">Time</th>
                        <th class="headertable">Late/EarlyIn</th>
                        <th class="headertable">UT/OT</th>
                        <th class="headertable">Hours Worked</th>
                        <th class="headertable">Date Timein</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>