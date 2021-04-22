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
                    <img src="<?= $this->session->userdata('logged_in_lodda')['profileimageurl'] ?>" alt="">
                </div>
                <div class="inline-block">
                    <h5 class="no-margin"> Welcome </h5>
                    <?php
                    // print_array($this->session->userdata('logged_in_lodda'));
                    ?>
                    <h4 class="no-margin"> <?= $this->session->userdata('logged_in_lodda')['firstname'] ?> <?= $this->session->userdata('logged_in_lodda')['lastname'] ?></h4>
                    <a class="btn user-options sb_toggle">
                        <i class="fa fa-cog"></i>
                    </a>
                </div>
            </div>
            <!-- start: MAIN NAVIGATION MENU -->
            <!-- <ul class="main-navigation-menu">
                <li class="active open">
                    <a href="<?= base_url() ?>"><i class="fa fa-home"></i> <span class="title"> DASHBOARD </span><span class="label label-default pull-right ">SUPER</span> </a>
                </li>
                <li>
                    <a href="<?= base_url('welcome/admin/1') ?>"><i class="fa fa-spinner fa-spin"></i> <span class="title"> ENHANCED ACTIVITIES</span> </a>
                </li>
                <li>
                    <a href="<?= base_url('welcome/admin/2') ?>"><i class="fa fa-circle-o-notch fa-spin"></i> <span class="title">SURVEYS</span> </a>
                </li>
                <li>
                    <a href="<?= base_url('welcome/admin/4') ?>"><i class="fa fa-circle-o-notch fa-spin"></i> <span class="title">COHORTS</span> </a>
                </li>
                <li class="active open">
                    <a href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i> <span class="title"> Forms </span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        <li class="active">
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
            </ul> -->
            <ul class="main-navigation-menu">
                <li class="active open">
                    <a href="<?= base_url() ?>"><i class="fa fa-home"></i> <span class="title"> DASHBOARD </span><span class="label label-default pull-right ">SUPER</span> </a>
                </li>
                <li>
                    <a href="<?= base_url('welcome/admin/1') ?>"><i class="fa fa-spinner fa-spin"></i> <span class="title"> ENHANCED ACTIVITIES</span> </a>
                </li>
                <li>
                    <a href="<?= base_url('welcome/admin/2') ?>"><i class="fa fa-circle-o-notch fa-spin"></i> <span class="title">SURVEYS</span> </a>
                </li>
                <li>
                    <a href="<?= base_url('welcome/admin/4') ?>"><i class="fa fa-circle-o-notch fa-spin"></i> <span class="title">COHORTS</span> </a>
                </li>
                <li class="active">
                    <a href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i> <span class="title"> Reports </span><i class="icon-arrow"></i> </a>
                    <ul class="sub-menu">
                        <li class="active">
                            <a href="<?= base_url('welcome/admin/8') ?>">
                                <i class="fa fa-list"></i>
                                <span class="title">Survey Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('welcome/admin/9') ?>">
                                <i class="fa fa-cloud-download"></i>
                                <span class="title">Download Zips</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('welcome/admin/10') ?>">
                                <i class="fa fa-book"></i>
                                <span class="title">Books Reports</span>
                            </a>
                        </li>
                    </ul>
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
            <a class="btn btn-sm log-out text-right" href="<?= base_url('auth/logout') ?>">
                <i class="fa fa-power-off"></i> Log Out
            </a>
        </div>
    </div>
</nav>