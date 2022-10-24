<div class="employeeprofile">
    <div class="banner">
        <center>
            <p class="greeting">Profile</p>
        </center>
    </div>

    <div class="profile">
        <center>
            <div><img src="<?php echo base_url(); ?>application/assets/images/logo1.png" alt=""></div>
            <p class="name py-4"><?php echo $empData->fname.' '.$empData->lname; ?></p>
            <p class="empId">ID: <?php echo $empData->empId?></p>
            <p class="schedule">Schedule: <?php echo $empData->timein.'-'.$empData->timeout; ?></p>
            <p class="dayoff">Dayoff: <?php echo $empData->dayoff;?></p>
        </center>
    </div>

    <div class="banner2">
        <center>
            <p class="greeting">Edit Profile</p>
        </center>
    </div>

    <div class="editprofile mb-5">
        
            <?php echo form_open_multipart('Employee/EmployeeProfile/saveEdit') ?>

                <div class="spacing1" style="">
                <!-- RESULT NOTIFICATION  -->
                <?php if($this->session->flashdata('successEditEmployee')){ ?>
                    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="alert alert-success" > 
                        <?php  echo $this->session->flashdata('successEditEmployee'); $this->session->unset_userdata ( 'successEditEmployee' );?>
                    </div>
                <?php } ?>  
                <?php if ($this->session->flashdata('errorEditEmployee')){ ?>
                    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="alert alert-danger" > 
                        <?php  echo $this->session->flashdata('errorEditEmployee'); $this->session->unset_userdata ( 'errorEditEmployee' );?>
                    </div>
                <?php } ?>

                <div class="form-label-group d-none">
                    <input name="empIdEdit" type="hidden" id="empIdEdit" class="form-control" placeholder="Employee Id" value="<?php echo $this->session->userdata('employeeId')?>">
                    <label for="empIdEdit" class="">Employee Id</label>
                </div>

                <div class="form-label-group">
                    <input name="fnameEdit" type="text" id="fnameEdit" class="form-control" placeholder="First Name" value="<?php echo $this->session->userdata('firstNameEmployee')?>">
                    <label for="fnameEdit" class="">First Name</label>
                </div>

                <div class="form-label-group">
                    <input name="lnameEdit" type="text" id="lnameEdit" class="form-control" placeholder="Last Name" value="<?php echo $this->session->userdata('lastNameEmployee')?>">
                    <label for="lnameEdit" class="">Last Name</label>
                </div>

                <div class="form-label-group">
                    <input name="passwordEdit" type="password" id="passwordEdit" class="form-control" placeholder="Password">
                    <label for="passwordEdit" class="">Password</label>
                    <small id="passwordHelp" class="form-text text-muted">Password must contain at least 1 special character, uppercase letter, lowercase letter, and must be at least 8 characters long.</small>
                </div>

                <div class="form-label-group">
                    <input name="confPasswordEdit" type="password" id="confPasswordEdit" class="form-control" placeholder="Confirm Password">
                    <label for="confPasswordEdit" class="">Confirm Password</label>
                </div>

                <center class="spacing2">
                    <button type="submi" class="btn" >Submit</button>
                </center>
                </div>

            <?php echo form_close() ?>
        
    </div>

</div>