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
    <div id="slidingbar-area">
        <div id="slidingbar">
            <div class="row">
                <!-- start: SLIDING BAR FIRST COLUMN -->
                <div class="col-md-4 col-sm-4">
                    <h2>My Options</h2>
                    <div class="row">
                        <div class="col-xs-6 col-lg-3">
                            <button class="btn btn-icon btn-block space10">
                                <i class="fa fa-folder-open-o"></i>
                                Projects <span class="badge badge-info partition-red"> 4 </span>
                            </button>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <button class="btn btn-icon btn-block space10">
                                <i class="fa fa-envelope-o"></i>
                                Messages <span class="badge badge-info partition-red"> 23 </span>
                            </button>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <button class="btn btn-icon btn-block space10">
                                <i class="fa fa-calendar-o"></i>
                                Calendar <span class="badge badge-info partition-blue"> 5 </span>
                            </button>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <button class="btn btn-icon btn-block space10">
                                <i class="fa fa-bell-o"></i>
                                Notifications <span class="badge badge-info partition-red"> 9 </span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end: SLIDING BAR FIRST COLUMN -->
                <!-- start: SLIDING BAR SECOND COLUMN -->
                <div class="col-md-4 col-sm-4">
                    <h2>My Recent Works</h2>
                    <div class="blog-photo-stream margin-bottom-30">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#"><img alt="" src="assets/images/image01_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image02_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image03_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image04_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image05_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image06_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image07_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image08_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image09_th.jpg"></a>
                            </li>
                            <li>
                                <a href="#"><img alt="" src="assets/images/image10_th.jpg"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end: SLIDING BAR SECOND COLUMN -->
                <!-- start: SLIDING BAR THIRD COLUMN -->
                <div class="col-md-4 col-sm-4">
                    <h2>My Info</h2>
                    <address class="margin-bottom-40">
                        Peter Clark
                        <br>
                        12345 Street Name, City Name, United States
                        <br>
                        P: (641)-734-4763
                        <br>
                        Email:
                        <a href="#">
                            peter.clark@example.com
                        </a>
                    </address>
                    <a class="btn btn-transparent-white" href="#">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                </div>
                <!-- end: SLIDING BAR THIRD COLUMN -->
            </div>
            <div class="row">
                <!-- start: SLIDING BAR TOGGLE BUTTON -->
                <div class="col-md-12 text-center">
                    <a href="#" class="sb_toggle"><i class="fa fa-chevron-up"></i></a>
                </div>
                <!-- end: SLIDING BAR TOGGLE BUTTON -->
            </div>
        </div>
    </div>
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
                    <a class="navbar-brand" href="index.html">
                        <img src="assets/images/logo.png" alt="Rapido" />
                    </a>
                    <!-- end: LOGO -->
                </div>
                <div class="topbar-tools">
                    <!-- start: TOP NAVIGATION MENU -->
                    <ul class="nav navbar-right">
                        <!-- start: USER DROPDOWN -->
                        <li class="dropdown current-user">
                            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                                <img src="assets/images/avatar-1-small.jpg" class="img-circle" alt=""> <span class="username hidden-xs">Peter Clark</span> <i class="fa fa-caret-down "></i>
                            </a>
                            <ul class="dropdown-menu dropdown-dark">
                                <li>
                                    <a href="pages_user_profile.html">
                                        My Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_calendar.html">
                                        My Calendar
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_messages.html">
                                        My Messages (3)
                                    </a>
                                </li>
                                <li>
                                    <a href="login_lock_screen.html">
                                        Lock Screen
                                    </a>
                                </li>
                                <li>
                                    <a href="login_login.html">
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
        <nav id="pageslide-left" class="pageslide inner">
            <div class="navbar-content">
                <!-- start: SIDEBAR -->
                <div class="main-navigation left-wrapper transition-left">
                    <div class="navigation-toggler hidden-sm hidden-xs">
                        <a href="#main-navbar" class="sb-toggle-left">
                        </a>
                    </div>
                    <div class="user-profile border-top padding-horizontal-10 block">
                        <div class="inline-block">
                            <img src="assets/images/avatar-1.jpg" alt="">
                        </div>
                        <div class="inline-block">
                            <h5 class="no-margin"> Welcome </h5>
                            <h4 class="no-margin"> Peter Clark </h4>
                            <a class="btn user-options sb_toggle">
                                <i class="fa fa-cog"></i>
                            </a>
                        </div>
                    </div>
                    <!-- start: MAIN NAVIGATION MENU -->
                    <ul class="main-navigation-menu">
                        <li class="active open">
                            <a href="index.html"><i class="fa fa-home"></i> <span class="title"> Dashboard </span><span class="label label-default pull-right ">LABEL</span> </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-desktop"></i> <span class="title"> Layouts </span><i class="icon-arrow"></i> </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        Horizontal Menu <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="layouts_horizontal_menu.html">
                                                Horizontal Menu
                                            </a>
                                        </li>
                                        <li>
                                            <a href="layouts_horizontal_menu_fixed.html">
                                                Horizontal Menu Fixed
                                            </a>
                                        </li>
                                        <li>
                                            <a href="layouts_horizontal_sidebar_menu.html">
                                                Horizontal &amp; Sidebar Menu
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="layouts_sidebar_closed.html">
                                        <span class="title"> Sidebar Closed </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="layouts_sidebar_not_fixed.html">
                                        <span class="title"> Sidebar Not Fixed </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="layouts_boxed_layout.html">
                                        <span class="title"> Boxed Layout </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="layouts_footer_fixed.html">
                                        <span class="title"> Footer Fixed </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="layouts_single_page.html">
                                        <span class="title"> Single-Page Interface </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-cogs"></i> <span class="title"> UI Lab </span><i class="icon-arrow"></i> </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="ui_elements.html">
                                        <span class="title"> Elements </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_buttons.html">
                                        <span class="title"> Buttons </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_icons.html">
                                        <span class="title"> Icons </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_animations.html">
                                        <span class="title"> CSS3 Animation </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_subview.html">
                                        <span class="title"> Subview </span> <span class="label partition-blue pull-right ">HOT</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_modals.html">
                                        <span class="title"> Extended Modals </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_tabs_accordions.html">
                                        <span class="title"> Tabs &amp; Accordions </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_panels.html">
                                        <span class="title"> Panels </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_notifications.html">
                                        <span class="title"> Notifications </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_sliders.html">
                                        <span class="title"> Sliders </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_treeview.html">
                                        <span class="title"> Treeview </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_nestable.html">
                                        <span class="title"> Nestable List </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="ui_typography.html">
                                        <span class="title"> Typography </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-th-large"></i> <span class="title"> Tables </span><i class="icon-arrow"></i> </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="table_basic.html">
                                        <span class="title">Basic Tables</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="table_responsive.html">
                                        <span class="title">Responsive Tables</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="table_data.html">
                                        <span class="title">Advanced Data Tables</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="table_export.html">
                                        <span class="title">Table Export</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i> <span class="title"> Forms </span><i class="icon-arrow"></i> </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="form_elements.html">
                                        <span class="title">Form Elements</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_wizard.html">
                                        <span class="title">Form Wizard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_validation.html">
                                        <span class="title">Form Validation</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_inline.html">
                                        <span class="title">Inline Editor</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_x_editable.html">
                                        <span class="title">Form X-editable</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_image_cropping.html">
                                        <span class="title">Image Cropping</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_multiple_upload.html">
                                        <span class="title">Multiple File Upload</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="form_dropzone.html">
                                        <span class="title">Dropzone File Upload</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-user"></i> <span class="title">Login</span><i class="icon-arrow"></i> </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="login_login.html">
                                        <span class="title"> Login Form </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="login_login.html?box=register">
                                        <span class="title"> Registration Form </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="login_login.html?box=forgot">
                                        <span class="title"> Forgot Password Form </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="login_lock_screen.html">
                                        <span class="title">Lock Screen</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-code"></i> <span class="title">Pages</span><i class="icon-arrow"></i> </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="pages_user_profile.html">
                                        <span class="title">User Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_invoice.html">
                                        <span class="title">Invoice</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_gallery.html">
                                        <span class="title">Gallery</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_timeline.html">
                                        <span class="title">Timeline</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_calendar.html">
                                        <span class="title">Calendar</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_messages.html">
                                        <span class="title">Messages</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="pages_blank_page.html">
                                        <span class="title">Blank Page</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="fa fa-cubes"></i> <span class="title">Utility</span><i class="icon-arrow"></i> </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="utility_faq.html">
                                        <span class="title">Faq</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_search_result.html">
                                        <span class="title">Search Results </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_404_example1.html">
                                        <span class="title">Error 404 Example 1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_404_example2.html">
                                        <span class="title">Error 404 Example 2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_404_example3.html">
                                        <span class="title">Error 404 Example 3</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_500_example1.html">
                                        <span class="title">Error 500 Example 1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_500_example2.html">
                                        <span class="title">Error 500 Example 2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_pricing_table.html">
                                        <span class="title">Pricing Table</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="utility_coming_soon.html">
                                        <span class="title">Cooming Soon</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" class="active">
                                <i class="fa fa-folder"></i> <span class="title"> 3 Level Menu </span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        Item 1 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Item 1 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        Item 3
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-folder-open"></i> <span class="title"> 4 Level Menu </span><i class="icon-arrow"></i> <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        Item 1 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="javascript:;">
                                                Sample Link 1 <i class="icon-arrow"></i>
                                            </a>
                                            <ul class="sub-menu">
                                                <li>
                                                    <a href="#"><i class="fa fa-times"></i> Sample Link 1</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-pencil"></i> Sample Link 1</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-edit"></i> Sample Link 1</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Item 2 <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        Item 3
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="maps.html"><i class="fa fa-map-marker"></i> <span class="title">Maps</span> </a>
                        </li>
                        <li>
                            <a href="charts.html"><i class="fa fa-bar-chart-o"></i> <span class="title">Charts</span> </a>
                        </li>
                    </ul>
                    <!-- end: MAIN NAVIGATION MENU -->
                </div>
                <!-- end: SIDEBAR -->
            </div>
            <div class="slide-tools">
                <div class="col-xs-6 text-left no-padding">
                    <a class="btn btn-sm status" href="#">
                        Status <i class="fa fa-dot-circle-o text-green"></i> <span>Online</span>
                    </a>
                </div>
                <div class="col-xs-6 text-right no-padding">
                    <a class="btn btn-sm log-out text-right" href="login_login.html">
                        <i class="fa fa-power-off"></i> Log Out
                    </a>
                </div>
            </div>
        </nav>
        <!-- end: PAGESLIDE LEFT -->
        <!-- start: PAGESLIDE RIGHT -->
        <div id="pageslide-right" class="pageslide slide-fixed inner">
            <div class="right-wrapper">
                <div class="notifications">
                    <div class="pageslide-title">
                        You have 11 notifications
                    </div>
                    <ul class="pageslide-list">
                        <li>
                            <a href="javascript:void(0)">
                                <span class="label label-primary"><i class="fa fa-user"></i></span> <span class="message"> New user registration</span> <span class="time"> 1 min</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span class="label label-success"><i class="fa fa-comment"></i></span> <span class="message"> New comment</span> <span class="time"> 7 min</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span class="label label-success"><i class="fa fa-comment"></i></span> <span class="message"> New comment</span> <span class="time"> 8 min</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span class="label label-success"><i class="fa fa-comment"></i></span> <span class="message"> New comment</span> <span class="time"> 16 min</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span class="label label-primary"><i class="fa fa-user"></i></span> <span class="message"> New user registration</span> <span class="time"> 36 min</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <span class="label label-warning"><i class="fa fa-shopping-cart"></i></span> <span class="message"> 2 items sold</span> <span class="time"> 1 hour</span>
                            </a>
                        </li>
                        <li class="warning">
                            <a href="javascript:void(0)">
                                <span class="label label-danger"><i class="fa fa-user"></i></span> <span class="message"> User deleted account</span> <span class="time"> 2 hour</span>
                            </a>
                        </li>
                    </ul>
                    <div class="view-all">
                        <a href="javascript:void(0)">
                            See all notifications <i class="fa fa-arrow-circle-o-right"></i>
                        </a>
                    </div>
                </div>
                <div class="hidden-xs" id="style_selector">
                    <div id="style_selector_container">
                        <div class="pageslide-title">
                            Style Selector
                        </div>
                        <div class="box-title">
                            Choose Your Layout Style
                        </div>
                        <div class="input-box">
                            <div class="input">
                                <select name="layout" class="form-control">
                                    <option value="default">Wide</option>
                                    <option value="boxed">Boxed</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-title">
                            Choose Your Header Style
                        </div>
                        <div class="input-box">
                            <div class="input">
                                <select name="header" class="form-control">
                                    <option value="fixed">Fixed</option>
                                    <option value="default">Default</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-title">
                            Choose Your Sidebar Style
                        </div>
                        <div class="input-box">
                            <div class="input">
                                <select name="sidebar" class="form-control">
                                    <option value="fixed">Fixed</option>
                                    <option value="default">Default</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-title">
                            Choose Your Footer Style
                        </div>
                        <div class="input-box">
                            <div class="input">
                                <select name="footer" class="form-control">
                                    <option value="default">Default</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-title">
                            10 Predefined Color Schemes
                        </div>
                        <div class="images icons-color">
                            <a href="#" id="default"><img src="assets/images/color-1.png" alt="" class="active"></a>
                            <a href="#" id="style2"><img src="assets/images/color-2.png" alt=""></a>
                            <a href="#" id="style3"><img src="assets/images/color-3.png" alt=""></a>
                            <a href="#" id="style4"><img src="assets/images/color-4.png" alt=""></a>
                            <a href="#" id="style5"><img src="assets/images/color-5.png" alt=""></a>
                            <a href="#" id="style6"><img src="assets/images/color-6.png" alt=""></a>
                            <a href="#" id="style7"><img src="assets/images/color-7.png" alt=""></a>
                            <a href="#" id="style8"><img src="assets/images/color-8.png" alt=""></a>
                            <a href="#" id="style9"><img src="assets/images/color-9.png" alt=""></a>
                            <a href="#" id="style10"><img src="assets/images/color-10.png" alt=""></a>
                        </div>
                        <div class="box-title">
                            Backgrounds for Boxed Version
                        </div>
                        <div class="images boxed-patterns">
                            <a href="#" id="bg_style_1"><img src="assets/images/bg.png" alt=""></a>
                            <a href="#" id="bg_style_2"><img src="assets/images/bg_2.png" alt=""></a>
                            <a href="#" id="bg_style_3"><img src="assets/images/bg_3.png" alt=""></a>
                            <a href="#" id="bg_style_4"><img src="assets/images/bg_4.png" alt=""></a>
                            <a href="#" id="bg_style_5"><img src="assets/images/bg_5.png" alt=""></a>
                        </div>
                        <div class="style-options">
                            <a href="#" class="clear_style">
                                Clear Styles
                            </a>
                            <a href="#" class="save_style">
                                Save Styles
                            </a>
                        </div>
                    </div>
                    <div class="style-toggle open"></div>
                </div>
            </div>
        </div>
        <!-- end: PAGESLIDE RIGHT -->
        <!-- start: MAIN CONTAINER -->
        <div class="main-container inner">
            <!-- start: PAGE -->
            <div class="main-content">
                <!-- start: PANEL CONFIGURATION MODAL FORM -->
                <div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4 class="modal-title">Panel Configuration</h4>
                            </div>
                            <div class="modal-body">
                                Here will be a configuration form
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" class="btn btn-primary">
                                    Save changes
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- end: SPANEL CONFIGURATION MODAL FORM -->
                <div class="container">
                    <!-- start: PAGE HEADER -->
                    <!-- start: TOOLBAR -->
                    <div class="toolbar row">
                        <div class="col-sm-6 hidden-xs">
                            <div class="page-header">
                                <h1>Dashboard <small>overview &amp; stats </small></h1>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <a href="#" class="back-subviews">
                                <i class="fa fa-chevron-left"></i> BACK
                            </a>
                            <a href="#" class="close-subviews">
                                <i class="fa fa-times"></i> CLOSE
                            </a>
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
                                        Dashboard
                                    </a>
                                </li>
                                <li class="active">
                                    Dashboard
                                </li>
                            </ol>
                        </div>
                    </div>
                    <!-- end: BREADCRUMB -->
                    <!-- start: PAGE CONTENT -->

                    <!-- end: PAGE CONTENT-->
                </div>

            </div>
            <!-- end: PAGE -->
        </div>
        <!-- Njovu's Problem -->
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="panel">
                    <div class="col-md-12">
                        <div class="progress progress-xs transparent-black no-radius space5">
                            <div aria-valuetransitiongoal="88" class="progress-bar progress-bar-success partition-white animate-progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: MAIN CONTAINER -->
        <!-- start: FOOTER -->
        <footer class="inner">
            <div class="footer-inner">
                <div class="pull-left">
                    2014 &copy; Rapido by cliptheme.
                </div>
                <div class="pull-right">
                    <span class="go-top"><i class="fa fa-chevron-up"></i></span>
                </div>
            </div>
        </footer>
        <!-- end: FOOTER -->
        <!-- start: SUBVIEW SAMPLE CONTENTS -->
        <!-- *** NEW NOTE *** -->
        <!-- *** READ NOTE *** -->
        <!-- *** SHOW CALENDAR *** -->
        <!-- *** NEW EVENT *** -->
        <!-- *** READ EVENT *** -->
        <!-- *** NEW CONTRIBUTOR *** -->
        <!-- *** SHOW CONTRIBUTORS *** -->
        <!-- end: SUBVIEW SAMPLE CONTENTS -->
    </div>
    <!-- start: MAIN JAVASCRIPTS -->
    <!--[if lt IE 9]>
		<script src="assets/plugins/respond.min.js"></script>
		<script src="assets/plugins/excanvas.min.js"></script>
		<script type="text/javascript" src="assets/plugins/jQuery/jquery-1.11.1.min.js"></script>
		<![endif]-->
    <!--[if gte IE 9]><!-->
    <?php echo $this->load->view('parts/jsone', '', TRUE); ?>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            // SVExamples.init();
            // Index.init();
        });
    </script>
</body>
<!-- end: BODY -->

</html>