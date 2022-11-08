<div class="adminemployee">
    <div class="banner">
        <p class="greeting">Employees</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>

    <div class="section1">
        <p class="header m-0">Employee Form</p>
        <div class="employeeform m-0 mb-5">

             <!-- RESULT NOTIFICATION  -->
             <?php if($this->session->flashdata('successEditEmployee')){ ?>
                <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="alert alert-success" > 
                    <?php  echo $this->session->flashdata('successEditEmployee'); $this->session->unset_userdata ( 'successEditEmployee' );?>
                </div>
            <?php } ?>  

            <?php if ($this->session->flashdata('failEditEmployee')){ ?>
                <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="alert alert-danger" > 
                    <?php  echo $this->session->flashdata('failEditEmployee'); $this->session->unset_userdata ( 'failEditEmployee' );?>
                </div>
            <?php } ?>
            <?php echo form_open_multipart('Admin/Employees/saveEdit') ?>
                
                <div class="form-label-group d-none">
                    <label for="employeeId" class="labelDesign spacing">Employee Id</label>
                    <input name="employeeId" type="hidden" id="employeeId" class="inputDesign form-control" placeholder="E.g Juan" value="<?php echo $employeeData->empId?>">
                </div>

                <div class="form-label-group">
                    <label for="employeeFirstName" class="labelDesign spacing">First Name</label>
                    <input name="employeeFirstName" type="text" id="employeeFirstName" class="inputDesign form-control" placeholder="E.g Juan" value="<?php echo $employeeData->fname?>">
                </div>
            
            
                <div class="form-label-group mt-3">
                    <label for="employeeLastName" class="labelDesign spacing">Last Name</label>
                    <input name="employeeLastName" type="text" id="employeeLastName" class="inputDesign form-control" placeholder="E.g Dela Cruz" value="<?php echo $employeeData->lname?>">
                </div>

                <div class="form-label-group mt-3">
                    <label for="employeeBranch" class="labelDesign spacing">Branch</label>
                    <input name="employeeBranch" type="text" id="employeeBranch" class="inputDesign form-control" placeholder="E.g Branch - City" value="<?php echo $employeeData->location?>">
                </div>

                <div class="form-label-group mt-3">
                    <label for="timein" class="labelDesign spacing">Time in</label>
                    <input name="timein" type="time" id="timein" class="inputDesign form-control" placeholder="Time in" value="<?php echo $employeeData->timein?>">
                </div>

                <div class="form-label-group mt-3">
                    <label for="timeout" class="labelDesign spacing">Time out</label>
                    <input name="timeout" type="time" id="timeout" class="inputDesign form-control" placeholder="Time out" value="<?php echo $employeeData->timeout?>">
                </div>

                <div class="form-group mt-3">                      
                    <div class="form-group"> 
                        <label>Day Off</label>
                            <?php
                                $dayList = array(
                                    ''=>'Select Day Off',
                                    'Sunday'=>'Sunday',
                                    'Monday'=>'Monday',
                                    'Tuesday'=>'Tuesday',
                                    'Wednesday'=>'Wednesday',
                                    'Thursday'=>'Thursday',
                                    'Friday'=>'Friday',
                                    'Saturday'=>'Saturday'
                                );
                                echo form_dropdown('dayoff', $dayList, $employeeData->dayoff, 'class="form-control" id="dayoff" '); 
                            ?>
                    </div>
                </div>

                <div class="form-label-group mt-3">
                    <label for="userPassword" class="labelDesign">Password</label>
                    <input name="userPassword" type="password" id="userPassword" class="inputDesign form-control" placeholder="E.g ********" >
                    <small id="passwordHelp" class="form-text text-muted">Password must contain at least 1 special character, uppercase letter, lowercase letter, and must be at least 8 characters long.</small>
                </div>

                <div class="form-label-group mt-3">
                    <label for="userRePassword" class="labelDesign">Confirm Password</label>
                    <input name="userRePassword" type="password" id="userRePassword" class="inputDesign form-control" placeholder="E.g ********" >
                </div>

                <button type="submit" class="btn my-3" >Submit</button>
            <?php echo form_close() ?>
        </div>
    </div>
</div>