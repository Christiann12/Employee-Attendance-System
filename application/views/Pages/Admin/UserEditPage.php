<div class="usermanagement">
    <div class="banner">
        <p class="greeting">Account Management</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section1">
        <p class="header m-0">Edit User Form</p>

        <div class="userform m-0 mb-5">

             <!-- RESULT NOTIFICATION  -->
            <?php if($this->session->flashdata('successEditUser')){ ?>
                <div class="alert alert-success" > 
                    <?php  echo $this->session->flashdata('successEditUser'); $this->session->unset_userdata ( 'successEditUser' );?>
                </div>
            <?php } ?>  
            <?php if ($this->session->flashdata('failEditUser')){ ?>
                <div class="alert alert-danger" > 
                    <?php  echo $this->session->flashdata('failEditUser'); $this->session->unset_userdata ( 'failEditUser' );?>
                </div>
            <?php } ?>

            <?php echo form_open_multipart('Admin/UserManagement/saveEdit') ?>
                
                <div class="form-label-group d-none">
                    <label for="userIdField" class="labelDesign spacing">User Id</label>
                    <input name="userIdField" type="hidden" id="userIdField" class="inputDesign form-control" placeholder="User Id" value="<?php echo $userData->userId?>">
                </div>

                <div class="form-label-group">
                    <label for="userFirstname" class="labelDesign spacing">First Name</label>
                    <input name="userFirstname" type="text" id="userFirstname" class="inputDesign form-control" placeholder="First Name" value="<?php echo $userData->fname?>">
                </div>
            
                <div class="form-label-group mt-3">
                    <label for="userLastname" class="labelDesign spacing">Last Name</label>
                    <input name="userLastname" type="text" id="userLastname" class="inputDesign form-control" placeholder="Last Name" value="<?php echo $userData->lname?>">
                </div>

                <div class="form-label-group mt-3">
                    <label for="userEmail" class="labelDesign">Email Address</label>
                    <input name="userEmail" type="email" id="userEmail" class="inputDesign form-control" placeholder="Email Address" aria-describedby="emailHelp" value="<?php echo $userData->email?>">
                </div>

                <div class="form-label-group mt-3">
                    <label for="userPassword" class="labelDesign">Password</label>
                    <input name="userPassword" type="password" id="userPassword" class="inputDesign form-control" placeholder="Password" >
                </div>

                <div class="form-label-group mt-3">
                    <label for="userRePassword" class="labelDesign">Confirm Password</label>
                    <input name="userRePassword" type="password" id="userRePassword" class="inputDesign form-control" placeholder="Confirm Password" >
                </div>

                <button type="submit" class="btn my-3" >Submit</button>
            <?php echo form_close() ?>
        </div>
    </div>
</div>