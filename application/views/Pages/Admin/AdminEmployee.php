<div class="adminemployee">
    <div class="banner">
        <p class="greeting">Employees</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
    <div class="section3">
        <p class="header m-0">Import Employees</p>
        
        <form method="post" id="import_csv" enctype="multipart/form-data">
            
            <div class="form-label-group importform">
                <label for="csv_file" class="labelDesign mr-3">Import Csv: </label>
                <input type="file" name="csv_file" id="csv_file" required accept=".csv" />
            </div>
            <div class="buttons">
                <button id="uploadButton" type="submit" class="btn" >Upload</button>
                <a download href="<?php echo base_url("application/assets/csv/csvformat.csv"); ?>" class="btn ml-5"> Sample CSV</a>
            </div>
        </form>

    </div>
    <div class="section1">
        <p class="header m-0">Employee Form</p>
        <div class="employeeform m-0">

             <!-- RESULT NOTIFICATION  -->
             <?php if($this->session->flashdata('successAddEmployee')){ ?>
                <div class="alert alert-success" > 
                    <?php  echo $this->session->flashdata('successAddEmployee'); $this->session->unset_userdata ( 'successAddEmployee' );?>
                </div>
            <?php } ?>  

            <?php if ($this->session->flashdata('failAddEmployee')){ ?>
                <div class="alert alert-danger" > 
                    <?php  echo $this->session->flashdata('failAddEmployee'); $this->session->unset_userdata ( 'failAddEmployee' );?>
                </div>
            <?php } ?>
            <?php echo form_open_multipart('Admin/Employees/addEmployee') ?>
                
                <div class="form-label-group">
                    <label for="employeeFirstName" class="labelDesign spacing">First Name</label>
                    <input name="employeeFirstName" type="text" id="employeeFirstName" class="inputDesign form-control" placeholder="First Name" value="">
                </div>
            
            
                <div class="form-label-group mt-3">
                    <label for="employeeLastName" class="labelDesign spacing">Last Name</label>
                    <input name="employeeLastName" type="text" id="employeeLastName" class="inputDesign form-control" placeholder="Last Name" value="">
                </div>

                <button type="submit" class="btn my-3" >Submit</button>
            <?php echo form_close() ?>
        </div>
    </div>
    <div class="section2">
        <p class="header m-0">Employee Table</p>
        <div class="employeetable m-0">
            <table id="employeeTable" class="responsive display nowrap cell-border hover" width="100%">
                <thead>
                    <tr>
                        <th class="headertable">ID</th>
                        <th class="headertable">First Name</th>
                        <th class="headertable">Last Name</th>
                        <th class="headertable">Schedule</th>
                        <th class="headertable">Day off</th>
                        <th class="headertable">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>