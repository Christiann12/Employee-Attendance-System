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
                <div class="alert alert-success" > 
                    <?php  echo $this->session->flashdata('successEditEmployee'); $this->session->unset_userdata ( 'successEditEmployee' );?>
                </div>
            <?php } ?>  

            <?php if ($this->session->flashdata('failEditEmployee')){ ?>
                <div class="alert alert-danger" > 
                    <?php  echo $this->session->flashdata('failEditEmployee'); $this->session->unset_userdata ( 'failEditEmployee' );?>
                </div>
            <?php } ?>
            <?php echo form_open_multipart('Admin/Employees/saveEdit') ?>
                
                <div class="form-label-group d-none">
                    <label for="employeeId" class="labelDesign spacing">Employee Id</label>
                    <input name="employeeId" type="hidden" id="employeeId" class="inputDesign form-control" placeholder="Employee Id" value="<?php echo $employeeData->empId?>">
                </div>

                <div class="form-label-group">
                    <label for="employeeFirstName" class="labelDesign spacing">First Name</label>
                    <input name="employeeFirstName" type="text" id="employeeFirstName" class="inputDesign form-control" placeholder="First Name" value="<?php echo $employeeData->fname?>">
                </div>
            
            
                <div class="form-label-group mt-3">
                    <label for="employeeLastName" class="labelDesign spacing">Last Name</label>
                    <input name="employeeLastName" type="text" id="employeeLastName" class="inputDesign form-control" placeholder="Last Name" value="<?php echo $employeeData->lname?>">
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

                <button type="submit" class="btn btn-primary my-3" >Submit</button>
            <?php echo form_close() ?>
        </div>
    </div>
</div>