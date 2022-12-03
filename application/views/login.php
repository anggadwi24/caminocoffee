
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Camino Coffee Featured Products include our popular Folly Beach and Edisto Island coffee, an 8oz medium-dark roasted assorted pack, and a 10oz coffee mug.">
		<meta name="keywords" content="Camino Coffee Featured Products include our popular Folly Beach and Edisto Island coffee, an 8oz medium-dark roasted assorted pack, and a 10oz coffee mug">
        <meta name="author" content="Camino Coffee">
        <meta name="robots" content="noindex, nofollow">
        <title>Login - <?= title()?></title>
		
		
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/img/favicon.ico">
		
	
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
	
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/font-awesome.min.css">
		
	
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sweet-alert2/sweetalert2.css">

      

    </head>

    <body class="account-page" >

    <div class="main-wrapper">
			<div class="account-content">
				
				<div class="container">
				
				
					<div class="account-logo">
						<a href="<?= base_url() ?>"><img src="<?= logo()?>" alt="<?= title()?>"></a>
					</div>
				
					
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Login</h3>
							<p class="account-subtitle">Access to our dashboard</p>
							
						
							<form action="<?= base_url('auth/do') ?>" method="POST">
								<div class="form-group">
									<label>Username</label>
									<input class="form-control" type="text" name="username" placeholder="Masukan username">
                                    <div class="invalid-feedback d-block"><?= form_error('username') ?></div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col">
											<label>Password</label>
										</div>
										<!-- <div class="col-auto">
											<a class="text-muted" href="forgot-password.html">
												Forgot password?
											</a>
										</div> -->
									</div>
									<input class="form-control" type="password" name="password" placeholder="Masukan password" >
                                    <div class="invalid-feedback d-block"><?= form_error('password') ?></div>
								</div>
								<div class="form-group text-center">
									<button class="btn btn-primary account-btn" type="submit">Login</button>
								</div>
							
							</form>
							
						</div>
					</div>
				</div>
			</div>
        </div>

       
      
        <script src="<?= base_url() ?>assets/js/jquery-3.2.1.min.js"></script>
        <script src="<?= base_url() ?>assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script>
            $(document).on('click','#toggle',function(){
                var pass = $('#password').attr('type');
                if(pass == 'password'){
                    $('#password').attr('type','text');
                    $('#toggle').html('<i class="fas fa-eye-slash"></i>');
                }else{
                    $('#password').attr('type','password');
                    $('#toggle').html('<i class="fas fa-eye"></i>');


                }
                
            })
        </script>
        <?php 
        if($this->session->flashdata('erorr')){
           
        ?>
         <script>
         var msg = <?= $this->session->flashdata('erorr') ?>;
        swal(
            {
                title: 'Ooppss..',
                text: msg,
                type: 'warning',
                
            }
        )
        </script>   
    <?php
        }
        ?>
    <?php
    if($this->session->flashdata('success')){ ?>
    <script>
        swal(
            {
                title: 'Success',
                html: '<?= $this->session->flashdata('success') ?>',
                type: 'success',
                
            }
        )
    </script>
    <?php }?>
    

       
		
		
        <script src="<?= base_url() ?>assets/js/popper.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
		
		
		<script src="<?= base_url() ?>assets/js/app.js"></script>

    </body>
</html>