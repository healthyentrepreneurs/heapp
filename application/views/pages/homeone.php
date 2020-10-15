<!DOCTYPE html>
<!-- Template Name: Rapido - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.0 Author: ClipTheme -->
<!--[if !IE]><!-->
<html lang="en">

<?php echo $this->load->view('parts/header', '', TRUE); ?>
<!-- end: HEAD -->
<!-- start: BODY -->

<body class="sidebar-close horizontal-menu-fixed">
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
					<a class="navbar-brand" href="#">
						<img src="<?= base_url() ?>assets/images/logo.png" alt="Logo " height="40" />
					</a>
					<!-- end: LOGO -->
				</div>
			</div>
			<!-- end: TOPBAR CONTAINER -->
		</header>
		<!-- end: TOPBAR -->
		<!-- start: HORIZONTAL MENU -->
		<?php echo $this->load->view('parts/nav_home', '', TRUE); ?>
		<!-- start: PAGESLIDE RIGHT -->
		<!-- end: PAGESLIDE RIGHT -->
		<!-- start: MAIN CONTAINER -->
		<div class="main-container inner">
			<!-- start: PAGE -->
			<div class="main-content">
				<!-- end: SPANEL CONFIGURATION MODAL FORM -->
				<?php echo $this->load->view($content, '', TRUE); ?>
				<div class="subviews">
					<div class="subviews-container"></div>
				</div>
			</div>
			<!-- end: PAGE -->
		</div>
		<!-- end: MAIN CONTAINER -->
		<!-- start: FOOTER -->
		<?php echo $this->load->view('parts/footer', '', TRUE); ?>
		<!-- end: FOOTER -->
		<!-- start: SUBVIEW SAMPLE CONTENTS -->
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
</body>
<!-- end: BODY -->

</html>