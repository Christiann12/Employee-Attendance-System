<!DOCTYPE html>
<html>
	<head>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('application/assets/images/favicon.ico'); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- end bootstrap css -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!-- developer css  -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/assets/css/Employee/TimeinInput.css"/>
   
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <title>EAS System</title>
    </head>
    <body class="loginBody">
        

        <div class="banner">
            <p class="greeting">
                Time Out Input Form
            </p>
            <div class="inputForm">
                <!-- RESULT NOTIFICATION  -->
                <?php if($this->session->flashdata('successEmpDashboard')){ ?>
                    <div  x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="alert alert-success" > 
                        <?php  echo $this->session->flashdata('successEmpDashboard'); $this->session->unset_userdata ( 'successEmpDashboard' );?>
                    </div>
                <?php } ?>  
                <?php if ($this->session->flashdata('errorEmpDashboard')){ ?>
                    <div  x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show" class="alert alert-danger" > 
                        <?php  echo $this->session->flashdata('errorEmpDashboard'); $this->session->unset_userdata ( 'errorEmpDashboard' );?>
                    </div>
                <?php } ?>
                <?php echo form_open_multipart('Employee/EmployeeScan/saveTimeout') ?>
                    <div class="form-label-group d-none">
                        <input name="EmpId" type="text" id="EmpId" class="form-control" placeholder="Email/Username" value="<?php echo $empData->secretId; ?>">
                        <label for="EmpId" class="">Secret Id</label>
                    </div>
                    <div class="form-label-gorup">
                        <label for="attachment" class="">Attach Picture</label>
                        <div class="">
                            <input type="file" name="attachment" id="attachment">
                        </div>
                    </div>
                    <button type="submit" class="btn">Submit</button>
                <?php echo form_close() ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>		
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </body>
</html>