<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />

    <title><?php echo $title; ?></title>

    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/apps/img/favicon.ico" />

    <!-- CSS -->
    <!-- BEGIN APP -->
    <link href="<?php echo base_url(); ?>assets/apps/css/style.css" rel="stylesheet" type="text/css" />
    <!-- END APP -->
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <?php if(in_array(strtolower($this->uri->segment(1)),array('form_kehadiran_karyawan','form_kehadiran_siswa'))) { ?>
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
    <?php } ?>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php echo base_url(); ?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->

    <!-- BEGIN SWEET ALERT -->
    <link href="<?php echo base_url(); ?>assets/sweetalert/css/sweetalert.css" rel="stylesheet">
    <!-- END SWEET ALERT -->

    <?php if(in_array(strtolower($this->uri->segment(1)),array('','login','form_kehadiran_karyawan','form_kehadiran_siswa'))) { ?>
      <link href="<?php echo base_url(); ?>assets/pages/css/login-5.min.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo base_url(); ?>assets/global/plugins/socicon/socicon.css" rel="stylesheet" type="text/css" />
    <?php } ?>

    <!-- JS -->
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
    <?php if(in_array(strtolower($this->uri->segment(1)),array('form_kehadiran_karyawan','form_kehadiran_siswa'))) { ?>
        <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
    <?php } ?>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?php echo base_url(); ?>assets/global/js/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <?php if(in_array(strtolower($this->uri->segment(1)),array('form_kehadiran_karyawan','form_kehadiran_siswa'))) { ?>
        <script src="<?php echo base_url(); ?>assets/pages/js/components-bootstrap-select.min.js" type="text/javascript"></script>
    <?php } ?>
    <!-- END PAGE LEVEL SCRIPTS -->
    
    <!-- BEGIN SWEET ALERT -->
    <script src="<?php echo base_url(); ?>assets/sweetalert/js/sweetalert.min.js"></script>
    <!-- END SWEET ALERT -->
  </head>
  
  <?php echo $content; ?>

    <script type="text/javascript">
        $(document).ready(function() {
          <?php
            // "error", "success", "info", "warning"
            if($this->session->flashdata('error')!=''){
              $msg = $this->session->flashdata('error');
          ?>
            swal({
              title: "<?php echo $msg['title']; ?>",
              text: "<?php echo $msg['text']; ?>",
              imageUrl: "<?php echo base_url(); ?>assets/apps/img/error.png",
              html: true,
              confirmButtonColor:"#F14635"
            },
            function(){
              <?php echo (!empty($msg['redirect'])) ? "window.location.href = '".base_url().$msg['redirect']."'" : ''; ?>
            });
          <?php
            }
            if($this->session->flashdata('success')!=''){
              $msg = $this->session->flashdata('success');
          ?>
            swal({
              title: "<?php echo $msg['title']; ?>",
              text: "<?php echo $msg['text']; ?>",
              imageUrl: "<?php echo base_url(); ?>assets/apps/img/success.png",
              html: true,
              confirmButtonColor:"#2ECB71"
            },
            function(){
              <?php echo (!empty($msg['redirect'])) ? "window.location.href = '".base_url().$msg['redirect']."'" : ''; ?>
            });  
          <?php
            }
            if($this->session->flashdata('info')!=''){
              $msg = $this->session->flashdata('info');
          ?>
            swal({
              title: "<?php echo $msg['title']; ?>",
              text: "<?php echo $msg['text']; ?>",
              imageUrl: "<?php echo base_url(); ?>assets/apps/img/info.png",
              html: true,
              confirmButtonColor:"#FDB813"
            },
            function(){
              <?php echo (!empty($msg['redirect'])) ? "window.location.href = '".base_url().$msg['redirect']."'" : ''; ?>
            });  
          <?php
            }
            if($this->session->flashdata('warning')!=''){
              $msg = $this->session->flashdata('warning');
          ?>
            swal({
              title: "<?php echo $msg['title']; ?>",
              text: "<?php echo $msg['text']; ?>",
              imageUrl: "<?php echo base_url(); ?>assets/apps/img/semut-warning.png",
              html: true,
              confirmButtonColor:"#2ECB71"
            },
            function(){
              <?php echo (!empty($msg['redirect'])) ? "window.location.href = '".base_url().$msg['redirect']."'" : ''; ?>
            });  
          <?php
            }
          ?>
        });
    </script>

    <?php if(in_array(strtolower($this->uri->segment(1)),array('form_kehadiran_karyawan','form_kehadiran_siswa'))) { ?>
    <!-- BEGIN CLOCK -->
        <script src="<?php echo base_url(); ?>assets/clock/js/snap.svg-min.js"></script>
        <script src="<?php echo base_url(); ?>assets/clock/js/clock.js"></script>
    <!-- END CLOCK -->
    <?php } ?>
  </body>
</html>