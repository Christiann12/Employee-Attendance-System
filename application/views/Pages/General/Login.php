<!DOCTYPE html>
<html>
	<head>
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
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/assets/css/Login.css"/>
   
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <title>EAS System</title>

    </head>
    <body class="loginBody">
        
        <div class="primaryContainer">
            <div class="loginPanel">
               <div class="">
               <center>
                    <img class="spacing1" src="<?php echo base_url(); ?>application/assets/images/logo1.png" alt="Logo">
                    <p class="title spacing1">Welcome to Paxforce</p>
                    <p class="subtitle spacing1">Login</p>
                </center>
                <?php echo form_open_multipart('Admin/UserManagement/checkUser') ?>

                    <div class="m-auto spacing1" style="width: 50%;">
                        <!-- RESULT NOTIFICATION  -->
                        <?php if($this->session->flashdata('successLogin')){ ?>
                            <div class="alert alert-success" > 
                                <?php  echo $this->session->flashdata('successLogin'); $this->session->unset_userdata ( 'successLogin' );?>
                            </div>
                        <?php } ?>  
                        <?php if ($this->session->flashdata('errorLogin')){ ?>
                            <div class="alert alert-danger" > 
                                <?php  echo $this->session->flashdata('errorLogin'); $this->session->unset_userdata ( 'errorLogin' );?>
                            </div>
                        <?php } ?>
                        <div class="form-label-group">
                            <input name="emailLogin" type="email" id="email" class="form-control" placeholder="Email/Username">
                            <label for="emailLogin" class="">Email/Username</label>
                        </div>

                        <div class="form-label-group">
                            <input name="passwordLogin" type="password" id="passwordLogin" class="form-control" placeholder="Password">
                            <label for="passwordLogin" class="">Password</label>
                        </div>

                        <div class="row">
                            <div class="col-6 d-flex align-items-start">
                                <label for="rememberme" class="mr-3">Stay Signed In?</label>
                                <input name="rememberme" type="checkbox" id="rememberme" class="mt-1">
                            </div>
                            <div class="col-6">
                                <center>
                                    <p style="text-align: right;" class="forgot-password d-none">Forgot Password?</p>
                                </center>
                            </div>
                        </div>

                        <center class="spacing2 mt-3">
                            <button type="submit" id="ct7" class="btn" <?= !empty($status) ? 'disabled' : null ?> ><?= !empty($timeremaining) ? '' : 'Login' ?></button>
                        </center>
                    </div>

                <?php echo form_close() ?>
               </div>
                
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>		
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
        <script>
            
            // Set the date we're counting down to
              var countDownDate = new Date("<?=date("F j, Y H:i:s",strtotime($timeremaining."+1 sec"))?>").getTime();
              countDownDate.toLocaleString('en-SG', {
                    timeZone: 'Asia/Singapore',
                    // hour12: false
                })
              // Update the count down every 1 second
              var x = setInterval(function() {
                
                // Get today's date and time
                var now = new Date().getTime();
                now.toLocaleString('en-SG', {
                    timeZone: 'Asia/Singapore',
                    // hour12: false
                })
                // Find the distance between now and the count down date
                var distance = countDownDate - now;
    
                // Time calculations for days, hours, minutes and seconds
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
                // Display the result in the element with id="demo"
                document.getElementById("ct7").innerHTML = hours + "h "
                + minutes + "m " + seconds + "s ";
    
                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    location.reload();
                }

              }, 1000);
        </script>
    </body>
</html>