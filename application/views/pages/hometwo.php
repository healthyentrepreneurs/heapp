<!-- Step 3 -->
<!DOCTYPE html>
<!-- Template Name: Rapido - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.0 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD -->
<?php echo $this->load->view('parts/header', '', TRUE); ?>
<!-- end: HEAD -->
<!-- start: BODY -->

<body>
    <!-- start: SLIDING BAR (SB) -->
    <!-- end: SLIDING BAR -->
    <div class="main-wrapper">
        <!-- start: TOPBAR -->
        <header class="topbar navbar navbar-inverse navbar-fixed-top inner">
            <!-- start: TOPBAR CONTAINER -->
            <div class="container">
                <div class="navbar-header">
                    <a class="sb-toggle-left hidden-md hidden-lg" href="#main-navbar">
                        <i class="fa fa-bars"></i>
                    </a>
                    <!-- start: LOGO -->
                    <a class="navbar-brand" href="<?= base_url() ?>">
                        <img src="<?= base_url() ?>assets/favicon/favicon-32x32.png" alt="Logo " height="30" />
                    </a>
                    <!-- end: LOGO -->
                </div>
                <div class="topbar-tools">
                    <!-- start: TOP NAVIGATION MENU -->
                    <ul class="nav navbar-right">
                        <!-- start: USER DROPDOWN -->
                        <li class="dropdown current-user">
                            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                                <img src="<?= $this->session->userdata('logged_in_lodda')['profileimageurlsmall'] ?>" class="img-circle" alt=""> <span class="username hidden-xs"><?= $this->session->userdata('logged_in_lodda')['firstname'] ?> <?= $this->session->userdata('logged_in_lodda')['lastname'] ?></span> <i class="fa fa-caret-down "></i>
                            </a>
                            <ul class="dropdown-menu dropdown-dark">
                                <li>
                                    <a href="<?= base_url('auth/logout') ?>">
                                        Log Out
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end: USER DROPDOWN -->
                        <li class="right-menu-toggle">
                            <a href="#" class="sb-toggle-right">
                                <i class="fa fa-globe toggle-icon"></i> <i class="fa fa-caret-right"></i> <span class="notifications-count badge badge-default hide"> 3</span>
                            </a>
                        </li>
                    </ul>
                    <!-- end: TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- end: TOPBAR CONTAINER -->
        </header>
        <!-- end: TOPBAR -->
        <!-- start: PAGESLIDE LEFT -->
        <a class="closedbar inner hidden-sm hidden-xs" href="#">
        </a>
        <?php echo $this->load->view($sidenav, '', TRUE); ?>
        <!-- end: PAGESLIDE LEFT -->
        <!-- start: PAGESLIDE RIGHT -->
        <!-- end: PAGESLIDE RIGHT -->
        <!-- start: MAIN CONTAINER -->
        <div class="main-container inner">
            <!-- start: PAGE -->
            <div class="main-content">
                <!-- start: PANEL CONFIGURATION MODAL FORM -->
                <!-- /.modal -->
                <!-- end: SPANEL CONFIGURATION MODAL FORM -->
                <div class="container">
                    <!-- start: PAGE HEADER -->
                    <!-- start: TOOLBAR -->
                    <div class="toolbar row">
                        <div class="col-sm-6 hidden-xs">
                            <div class="page-header">
                                <h1>
                                    <?php
                                    if (empty($survey_name)) {
                                        echo "Dashboard";
                                    } else {
                                        echo $survey_name;
                                    }
                                    ?>
                                    <small>
                                        <?php
                                        if (empty($user_profile)) {
                                            echo "Dashboard";
                                        } else {
                                            echo $user_profile['username'] . " " . $user_profile['firstname']. " " . $user_profile['lastname'];
                                            // print_array($user_profile);
                                        }
                                        ?>
                                    </small>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <!-- end: TOOLBAR -->
                    <!-- end: PAGE HEADER -->
                    <!-- start: BREADCRUMB -->
                    <div class="row">
                        <div class="col-md-12">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="#">
                                        <?php
                                        echo "Dashboard";
                                        ?>
                                    </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <!-- end: BREADCRUMB -->
                    <!-- start: PAGE CONTENT -->
                    <?php echo $this->load->view($content_admin, '', TRUE); ?>
                    <!-- end: PAGE CONTENT-->
                </div>
            </div>
            <!-- Njovu's Problem -->
            <!-- end: PAGE -->
        </div>
        <!-- end: MAIN CONTAINER -->
        <!-- start: FOOTER -->
        <?php echo $this->load->view('parts/footeradmin', '', TRUE); ?>
        <!-- end: FOOTER -->
        <!-- start: SUBVIEW SAMPLE CONTENTS -->
        <!-- *** NEW NOTE *** -->
        <!-- *** READ NOTE *** -->
    </div>
    <!-- start: MAIN JAVASCRIPTS -->
    <!--[if lt IE 9]>
		<script src="assets/plugins/respond.min.js"></script>
		<script src="assets/plugins/excanvas.min.js"></script>
		<script type="text/javascript" src="assets/plugins/jQuery/jquery-1.11.1.min.js"></script>
		<![endif]-->
    <!--[if gte IE 9]><!-->
    <?php echo $this->load->view('parts/jsone', '', TRUE); ?>
    <!-- end: CORE JAVASCRIPTS  -->
    <script>
        jQuery(document).ready(function() {
            Main.init();
            // Index.init();
        });
    </script>
</body>
<!-- end: BODY -->

</html>