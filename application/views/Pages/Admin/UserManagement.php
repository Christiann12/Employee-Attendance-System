<div class="usermanagement">
    <div class="banner">
        <p class="greeting">User Management</p>
        <p class="clock" id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section1">
        <p class="header m-0">User Form</p>

        <div class="userform m-0">

            <!-- RESULT NOTIFICATION  -->
            <?php if($this->session->flashdata('successAddUser')){ ?>
            <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="alert alert-success">
                <?php  echo $this->session->flashdata('successAddUser'); $this->session->unset_userdata ( 'successAddUser' );?>
            </div>
            <?php } ?>
            <?php if ($this->session->flashdata('failAddUser')){ ?>
            <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="alert alert-danger">
                <?php  echo $this->session->flashdata('failAddUser'); $this->session->unset_userdata ( 'failAddUser' );?>
            </div>
            <?php } ?>

            <?php echo form_open_multipart('Admin/UserManagement/saveUser') ?>

            <div class="form-label-group">
                <label for="userFirstname" class="labelDesign spacing">First Name</label>
                <input name="userFirstname" type="text" id="userFirstname" class="inputDesign form-control"
                    placeholder="E.g Juan" value="">
            </div>


            <div class="form-label-group mt-3">
                <label for="userLastname" class="labelDesign spacing">Last Name</label>
                <input name="userLastname" type="text" id="userLastname" class="inputDesign form-control"
                    placeholder="E.g Dela Cruz" value="">
            </div>

            <div class="form-label-group mt-3">
                <label for="userEmail" class="labelDesign">Email Address</label>
                <input name="userEmail" type="email" id="userEmail" class="inputDesign form-control"
                    placeholder="E.g email@address.com" aria-describedby="emailHelp">
            </div>

            <div class="form-label-group mt-3">
                <label for="userRole">User Role</label>
                <?php
                        $userRoles = array(
                            '' => 'Choose a User Role',
                            "Admin" => "Admin", //all
                            "Operations" => "Operations", //support, ping
                            "Finance" => "Finance", //inventory product, iventory services
                            "HumanResource" => "HumanResource", //transactions
                        ); 
                        echo form_dropdown('userRole', $userRoles, '', 'class="form-control" id="userRole"');
                    ?>
            </div>

            <div class="form-label-group mt-3">
                <label for="userPassword" class="labelDesign">Password</label>
                <input name="userPassword" type="password" id="userPassword" class="inputDesign form-control"
                    placeholder="E.g ********">
                <small id="passwordHelp" class="form-text text-muted">Password must contain at least 1 special character, uppercase letter, lowercase letter, and must be at least 8 characters long.</small>
            </div>

            <div class="form-label-group mt-3">
                <label for="userRePassword" class="labelDesign">Confirm Password</label>
                <input name="userRePassword" type="password" id="userRePassword" class="inputDesign form-control"
                    placeholder="E.g ********">
            </div>

            <button type="submit" class="btn my-3">Submit</button>
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
                        <th class="headertable">User Role</th>
                        <th class="headertable">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>