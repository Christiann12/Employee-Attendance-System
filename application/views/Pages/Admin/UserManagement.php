<div class="usermanagement">
    <div class="banner">
        <p class="greeting">Account Management</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section1">
        <p class="header m-0">User Form</p>

        <div class="userform m-0">

             <!-- RESULT NOTIFICATION  -->
            <?php if($this->session->flashdata('successAddUser')){ ?>
                <div class="alert alert-success" > 
                    <?php  echo $this->session->flashdata('successAddUser'); $this->session->unset_userdata ( 'successAddUser' );?>
                </div>
            <?php } ?>  
            <?php if ($this->session->flashdata('failAddUser')){ ?>
                <div class="alert alert-danger" > 
                    <?php  echo $this->session->flashdata('failAddUser'); $this->session->unset_userdata ( 'failAddUser' );?>
                </div>
            <?php } ?>

            <?php echo form_open_multipart('Admin/UserManagement/saveUser') ?>
                
                <div class="form-label-group">
                    <label for="userFirstname" class="labelDesign spacing">First Name</label>
                    <input name="userFirstname" type="text" id="userFirstname" class="inputDesign form-control" placeholder="First Name" value="">
                </div>
            
            
                <div class="form-label-group mt-3">
                    <label for="userLastname" class="labelDesign spacing">Last Name</label>
                    <input name="userLastname" type="text" id="userLastname" class="inputDesign form-control" placeholder="Last Name" value="">
                </div>

                <div class="form-label-group mt-3">
                    <label for="userEmail" class="labelDesign">Email Address</label>
                    <input name="userEmail" type="email" id="userEmail" class="inputDesign form-control" placeholder="Email Address" aria-describedby="emailHelp">
                </div>

                <div class="form-label-group mt-3">
                    <label for="userPassword" class="labelDesign">Password</label>
                    <input name="userPassword" type="password" id="userPassword" class="inputDesign form-control" placeholder="Password">
                </div>

                <div class="form-label-group mt-3">
                    <label for="userRePassword" class="labelDesign">Confirm Password</label>
                    <input name="userRePassword" type="password" id="userRePassword" class="inputDesign form-control" placeholder="Confirm Password">
                </div>

                <button type="submit" class="btn my-3" >Submit</button>
            <?php echo form_close() ?>
        </div>
    </div>
    <div class="section2">
        <p class="header m-0">User Table</p>
        <div class="usertable m-0">
            <table id="userTable" class="responsive display nowrap cell-border hover" width="100%">
                <thead>
                    <tr>
                        <th class="headertable">ID</th>
                        <th class="headertable">First Name</th>
                        <th class="headertable">Last Name</th>
                        <th class="headertable">Email</th>
                        <th class="headertable">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>