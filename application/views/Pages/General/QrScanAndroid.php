<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

        <!-- developer css  -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>application/assets/css/Employee/EmployeeAndroidScanQr.css">
    </head>
    <body style=" font-family: Raleway; background-color: #edf5e1;">
        <div class="main-panel" >
            
            <div class="holder">
                <div class="cameraContainer">
                    <video id="preview" class="camera" width="100%"></video>
                </div>
                
                <div class="cameraContainer innerbox"></div>
                
            </div>
            
            <!-- <input type="text" name="text" id="text" readonyy="" placeholder="scan qrcode" class="form-control"> -->
        </div>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script>
           let scanner = new Instascan.Scanner({ video: document.getElementById('preview'),mirror: false});
           Instascan.Camera.getCameras().then(function(cameras){
               if(cameras.length > 0 ){
				var selectedCam = cameras[0];
				$.each(cameras, (i, c) => {
					if (c.name.indexOf('back') != -1) {
						selectedCam = c;
						return false;
					}
				});

				scanner.start(selectedCam);
               } else{
                   alert('No cameras found');
               }

           }).catch(function(e) {
               console.error(e);
           });

           scanner.addListener('scan',function(c){
                var secretId = '<?php  echo $this->session->userdata('secretIdEmployee') ?>';
                var url = '<?php  echo base_url('EmployeeScan/') ?>' + c;
                if(c != secretId){
                    alert('This is not your QR code');
                }
                else{
                    location.href = url;
                    alert('Redirecting');
                }
                
           });

        </script>
    </body>
</html>