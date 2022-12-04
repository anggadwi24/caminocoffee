
<?php 
    if(isset($title)){
        $title = $title;
    }else{
        $title = 'PENJADWALAN AGENDA KEGIATAN - KECAMATAN KUTA SELATAN';
    }
   
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Camino Coffee Featured Products include our popular Folly Beach and Edisto Island coffee, an 8oz medium-dark roasted assorted pack, and a 10oz coffee mug.">
		<meta name="keywords" content="Camino Coffee Featured Products include our popular Folly Beach and Edisto Island coffee, an 8oz medium-dark roasted assorted pack, and a 10oz coffee mug">
        <meta name="author" content="Camino Coffee">
        <meta name="robots" content="noindex, nofollow">
        <title><?= $title ?></title>
        <link rel="shortcut icon" href="<?= base_url() ?>assets/img/favicon.ico">
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/line-awesome.min.css">
		
        <?php 
            if(isset($style)){
                if(count($style) > 0){
                    for($a=0;$a<count($style);$a++){
                        echo '<link rel="stylesheet" href="'.base_url($style[$a]).'">';
                    }
                }
            }
        ?>
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sweet-alert2/sweetalert2.css">
        <script src="<?= base_url() ?>assets/js/jquery-3.2.1.min.js"></script>
        <script src="<?= base_url() ?>assets/js/popper.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    </head>

    

    <body >
        <div class="main-wrapper">
            <?php $this->load->view('layouts/navbar') ?>
            <?php $this->load->view('layouts/sidebar') ?>

			<div class="page-wrapper">
			
				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Header -->
					
                    <?= $contents?>
                </div>
            </div>
        </div>
     
       
        <script src="<?= base_url() ?>assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <?php 
        if($this->session->flashdata('error')){
        
        ?>
        <script>
         
            swal(
                {
                    title: 'Ooppss..',
                    text: '<?= $this->session->flashdata('error') ?>',
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
      
		<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/morris/morris.min.js"></script>
	
        <?php 
            if(isset($script)){
                if(count($script) > 0){
                    for($a=0;$a<count($script);$a++){
                        echo '<script  src="'.base_url($script[$a]).'"></script>';
                    }
                }
            }
        ?>
         <?php 
            if(isset($ajax)){
                if(count($ajax) > 0){
                    for($a=0;$a<count($ajax);$a++){
                        echo '<script  src="'.base_url($ajax[$a]).'" type="module"></script>';
                    }
                }
            }
        ?>
		<script src="<?= base_url() ?>assets/js/app.js"></script>
    </body>
</html>