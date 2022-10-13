<div class="GenerateQr">
    <div class="banner">
        <p class="greeting">Generate QR</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section1">
        <p class="header m-0">Generate QR for All Employees</p>
        <div class="generateqrform">
            <?php echo form_open_multipart('Admin/GenerateQr/generateQr') ?>
                
                <!-- <div class="form-label-group">
                    <label for="userFirstname" class="labelDesign spacing">First Name</label>
                    <input name="userFirstname" type="text" id="userFirstname" class="inputDesign form-control" placeholder="First Name" value="">
                </div>
            
            
                <div class="form-label-group mt-3">
                    <label for="userLastname" class="labelDesign spacing">Last Name</label>
                    <input name="userLastname" type="text" id="userLastname" class="inputDesign form-control" placeholder="Last Name" value="">
                </div> -->

                <button type="submit" class="btn my-3" >Generate QR</button>
            <?php echo form_close() ?>
        </div>
    </div>
    <div class="section2">
        <p class="header m-0">Select below the employee you want to generate QR code for</p>
        <div class="generateqrtable m-0">
            <table id="generateqrTable" class="responsive display nowrap cell-border hover" width="100%">
                <thead>
                    <tr>
                        <th class="headertable">ID</th>
                        <th class="headertable">First Name</th>
                        <th class="headertable">Last Name</th>
                        <th class="headertable">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>