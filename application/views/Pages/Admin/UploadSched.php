<div class="uploadsched">
    <div class="banner">
        <p class="greeting">Upload Schedule</p>
        <p class="clock"id="ct7"><?php echo date('m/d/Y - g:i:s A') ?></p>
    </div>
     <div class="section1">
        <p class="header m-0">Import Schedule</p>
        <form method="post" id="import_csv_schedule" enctype="multipart/form-data">
            <div class="form-label-group importform">
                <label for="csv_file" class="labelDesign mr-3">Import Csv: </label>
                <input type="file" name="csv_file" id="csv_file" required accept=".csv" />
            </div>
            <div class="buttons">
                <button id="uploadButton_schedule" type="submit" class="btn btn-primary" >Upload</button>
                <a download href="<?php echo base_url("application/assets/csv/csvformatschedule.csv"); ?>" class="btn btn-primary ml-5"> Sample CSV</a>
            </div>
        </form>
    </div> 
    <div class="section2">
        <p class="header m-0">Active schedules</p>
        <div class="scheduleTable m-0">
            <table id="scheduleTable" class="responsive display nowrap cell-border hover" width="100%">
                <thead>
                    <tr>
                        <th class="headertable">ID</th>
                        <th class="headertable">First Name</th>
                        <th class="headertable">Last Name</th>
                        <th class="headertable">Schedule</th>
                        <th class="headertable">Day Off</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>