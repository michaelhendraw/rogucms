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
        <!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css" /> -->
        <link href="<?php echo base_url(); ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <?php if(in_array(strtolower($this->uri->segment(1)),array('subject'))) { ?>
            <!-- <link href="<?php echo base_url(); ?>assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" /> -->
            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('subject'))) { ?>
            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('user'))) { ?>
            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('classes'))) { ?>
            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('user'))) { ?>
            <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <link href="<?php echo base_url(); ?>assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url(); ?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->

        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url(); ?>assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url(); ?>assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->

        <!-- BEGIN SERVER SIDE DATATABLE -->
        <link href="<?php echo base_url('assets/datatables/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
        <!-- END SERVER SIDE DATATABLE -->

        <!-- BEGIN SWEET ALERT -->
        <link href="<?php echo base_url(); ?>assets/sweetalert/css/sweetalert.css" rel="stylesheet">
        <!-- END SWEET ALERT -->

        <!-- BEGIN AUTOCOMPLETE -->
        <link href="<?php echo base_url(); ?>assets/autocomplete/css/jquery.autocomplete.css" rel="stylesheet">
        <!-- END AUTOCOMPLETE -->

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
        <script src="<?php echo base_url(); ?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('user'))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
            <script src="<?php echo base_url(); ?>assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('classes'))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/highcharts/js/highcharts.js" type="text/javascript"></script>
        <?php } ?>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url(); ?>assets/global/js/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <?php if(in_array(strtolower($this->uri->segment(1)),array('subject'))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('subject'))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('user'))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('classes'))) { ?>
            <script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <?php } ?>
        '<?php if(in_array(strtolower($this->uri->segment(1)),array('user'))) { ?>
            <script src="<?php echo base_url(); ?>assets/pages/js/components-bootstrap-select.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array(''))) { ?>
            <script src="<?php echo base_url(); ?>assets/pages/js/form-repeater.min.js" type="text/javascript"></script>
        <?php } ?>
        <?php if(in_array(strtolower($this->uri->segment(1)),array('classes'))) { ?>
            <script src="<?php echo base_url(); ?>assets/pages/scripts/charts-highcharts.min.js" type="text/javascript"></script>
        <?php } ?>
        <!-- END PAGE LEVEL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo base_url(); ?>assets/layouts/layout/js/layout.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/layouts/layout/js/demo.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/layouts/global/js/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        
        <!-- BEGIN SERVER SIDE DATATABLE -->
        <script src="<?php echo base_url('assets/datatables/datatables/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables/datatables/js/dataTables.bootstrap.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
        <!-- END SERVER SIDE DATATABLE -->
            
        <!-- BEGIN SWEET ALERT -->
        <script src="<?php echo base_url(); ?>assets/sweetalert/js/sweetalert.min.js"></script>
        <!-- END SWEET ALERT -->

        <!-- BEGIN AUTOCOMPLETE -->
        <script src="<?php echo base_url(); ?>assets/autocomplete/js/jquery.autocomplete.js"></script>
        <!-- END AUTOCOMPLETE -->
    </head>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed page-sidebar-closed">
        <div class="page-header navbar navbar-fixed-top">
            <div class="page-header-inner ">
                <div class="page-logo">
                    <a href="<?php echo base_url(); ?>">
                        <img src="<?php echo base_url(); ?>assets/layouts/layout/img/logo.png" alt="<?php echo APP; ?>" class="logo-default" /> 
                    </a>
                    <div class="menu-toggler sidebar-toggler">
                        <span></span>
                    </div>
                </div>
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    <span></span>
                </a>
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <!-- <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-default"> 7 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3>
                                        <span class="bold">12 pending</span> notifications</h3>
                                    <a href="page_user_profile_1.html">view all</a>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">just now</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-success">
                                                        <i class="fa fa-plus"></i>
                                                    </span> New user registered. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">3 mins</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> Server #12 overloaded. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">10 mins</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-warning">
                                                        <i class="fa fa-bell-o"></i>
                                                    </span> Server #2 not responding. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">14 hrs</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-info">
                                                        <i class="fa fa-bullhorn"></i>
                                                    </span> Application error. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">2 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> Database overloaded 68%. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">3 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> A user IP blocked. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">4 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-warning">
                                                        <i class="fa fa-bell-o"></i>
                                                    </span> Storage Server #4 not responding dfdfdfd. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">5 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-info">
                                                        <i class="fa fa-bullhorn"></i>
                                                    </span> System Error. </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="time">9 days</span>
                                                <span class="details">
                                                    <span class="label label-sm label-icon label-danger">
                                                        <i class="fa fa-bolt"></i>
                                                    </span> Storage server failed. </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li> -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <!-- <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-envelope-open"></i>
                                <span class="badge badge-default"> 4 </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="external">
                                    <h3>You have
                                        <span class="bold">7 New</span> Messages</h3>
                                    <a href="app_inbox.html">view all</a>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="<?php echo base_url(); ?>assets/layouts/layout3/img/avatar2.jpg" class="img-circle" alt=""> </span>
                                                <span class="subject">
                                                    <span class="from"> Lisa Wong </span>
                                                    <span class="time">Just Now </span>
                                                </span>
                                                <span class="message"> Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="<?php echo base_url(); ?>assets/layouts/layout3/img/avatar3.jpg" class="img-circle" alt=""> </span>
                                                <span class="subject">
                                                    <span class="from"> Richard Doe </span>
                                                    <span class="time">16 mins </span>
                                                </span>
                                                <span class="message"> Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="<?php echo base_url(); ?>assets/layouts/layout3/img/avatar1.jpg" class="img-circle" alt=""> </span>
                                                <span class="subject">
                                                    <span class="from"> Bob Nilson </span>
                                                    <span class="time">2 hrs </span>
                                                </span>
                                                <span class="message"> Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="<?php echo base_url(); ?>assets/layouts/layout3/img/avatar2.jpg" class="img-circle" alt=""> </span>
                                                <span class="subject">
                                                    <span class="from"> Lisa Wong </span>
                                                    <span class="time">40 mins </span>
                                                </span>
                                                <span class="message"> Vivamus sed auctor 40% nibh congue nibh... </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="photo">
                                                    <img src="<?php echo base_url(); ?>assets/layouts/layout3/img/avatar3.jpg" class="img-circle" alt=""> </span>
                                                <span class="subject">
                                                    <span class="from"> Richard Doe </span>
                                                    <span class="time">46 mins </span>
                                                </span>
                                                <span class="message"> Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li> -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <!-- <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="icon-calendar"></i>
                                <span class="badge badge-default"> 3 </span>
                            </a>
                            <ul class="dropdown-menu extended tasks">
                                <li class="external">
                                    <h3>You have
                                        <span class="bold">12 pending</span> tasks</h3>
                                    <a href="app_todo.html">view all</a>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">New release v1.2 </span>
                                                    <span class="percent">30%</span>
                                                </span>
                                                <span class="progress">
                                                    <span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">40% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">Application deployment</span>
                                                    <span class="percent">65%</span>
                                                </span>
                                                <span class="progress">
                                                    <span style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">65% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">Mobile app release</span>
                                                    <span class="percent">98%</span>
                                                </span>
                                                <span class="progress">
                                                    <span style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">98% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">Database migration</span>
                                                    <span class="percent">10%</span>
                                                </span>
                                                <span class="progress">
                                                    <span style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">10% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">Web server upgrade</span>
                                                    <span class="percent">58%</span>
                                                </span>
                                                <span class="progress">
                                                    <span style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">58% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">Mobile development</span>
                                                    <span class="percent">85%</span>
                                                </span>
                                                <span class="progress">
                                                    <span style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">85% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="task">
                                                    <span class="desc">New UI release</span>
                                                    <span class="percent">38%</span>
                                                </span>
                                                <span class="progress progress-striped">
                                                    <span style="width: 38%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">38% Complete</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li> -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="<?php echo base_url().'assets/apps/img//user_thumb.png'; ?>" />
                                <span class="username username-hide-on-mobile"> <?php echo $this->session->userdata('user_name'); ?></span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="<?php echo base_url().'user/profile'; ?>">
                                        <i class="icon-user"></i> Profil Saya
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="app_calendar.html">
                                        <i class="icon-calendar"></i> My Calendar
                                    </a>
                                </li>
                                <li>
                                    <a href="app_inbox.html">
                                        <i class="icon-envelope-open"></i> My Inbox
                                        <span class="badge badge-danger"> 3 </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="app_todo.html">
                                        <i class="icon-rocket"></i> My Tasks
                                        <span class="badge badge-success"> 7 </span>
                                    </a>
                                </li>
                                <li class="divider"> </li>
                                <li>
                                    <a href="page_user_lock_1.html">
                                        <i class="icon-lock"></i> Lock Screen
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo base_url(); ?>logout">
                                        <i class="icon-key"></i> Keluar
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clearfix"> </div>
        <div class="page-container">
            <div class="page-sidebar-wrapper">
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <!-- <ul class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px"> -->
                    <ul class="page-sidebar-menu page-header-fixed page-sidebar-menu-closed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        <li class="sidebar-toggler-wrapper hide">
                            <div class="sidebar-toggler">
                                <span></span>
                            </div>
                        </li>
                        <!-- START MENU -->
                        <li class="heading">
                            <h3 class="uppercase">Dashboard</h3>
                        </li>
                        <li class="nav-item <?php echo (strtolower($this->uri->segment(1))==strtolower('home')) ? 'active open' : ''; ?>">
                            <a href="<?php echo base_url(); ?>home" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Home</span>
                            </a>
                        </li>
                        <?php if($this->session->userdata('user_role') == 'admin'){ ?>
                        <li class="heading">
                            <h3 class="uppercase">Mengelola Pengguna</h3>
                        </li>
                        <li class="nav-item <?php echo (strtolower($this->uri->segment(1))==strtolower('user')) ? 'active open' : ''; ?>">
                            <a href="<?php echo base_url(); ?>user" class="nav-link nav-toggle">
                                <i class="fa fa-user-md"></i>
                                <span class="title">Pengguna</span>
                            </a>
                        </li>
                        <li class="nav-item <?php echo (strtolower($this->uri->segment(1))==strtolower('student')) ? 'active open' : ''; ?>">
                            <a href="<?php echo base_url(); ?>student" class="nav-link nav-toggle">
                                <i class="fa fa-users"></i>
                                <span class="title">Siswa</span>
                            </a>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase">Belajar Mengajar</h3>
                        </li>
                        <li class="nav-item <?php echo (strtolower($this->uri->segment(1))==strtolower('classes')) ? 'active open' : ''; ?>">
                            <a href="<?php echo base_url(); ?>classes" class="nav-link nav-toggle">
                                <i class="fa fa-graduation-cap"></i>
                                <span class="title">Kelas</span>
                            </a>
                        </li>
                        <?php }elseif($this->session->userdata('user_role') == 'guru'){?>
                        <li class="heading">
                            <h3 class="uppercase">Materi Belajar</h3>
                        </li>
                        <li class="nav-item <?php echo (strtolower($this->uri->segment(1))==strtolower('subject')) ? 'active open' : ''; ?>">
                            <a href="<?php echo base_url(); ?>subject" class="nav-link nav-toggle">
                                <i class="fa fa-book"></i>
                                <span class="title">Mapel</span>
                            </a>
                        </li>
                        <li class="heading">
                            <h3 class="uppercase">Belajar Mengajar</h3>
                        </li>
                        <li class="nav-item <?php echo (strtolower($this->uri->segment(1))==strtolower('classes')) ? 'active open' : ''; ?>">
                            <a href="<?php echo base_url(); ?>classes" class="nav-link nav-toggle">
                                <i class="fa fa-graduation-cap"></i>
                                <span class="title">Kelas</span>
                            </a>
                        </li>
                        <?php } ?>
                        <!-- END MENU -->
                    </ul>
                </div>
            </div>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <span><?php echo $bc_header; ?></span>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span><?php echo $bc_detail; ?></span>
                            </li>
                        </ul>
                    </div>
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
        <div class="page-footer">
            <div class="page-footer-inner"> <?php echo APP; ?> &copy; 2019 </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
  
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
                    // imageUrl: "<?php echo base_url(); ?>assets/apps/img/semut-warning.png",
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
    </body>
</html>