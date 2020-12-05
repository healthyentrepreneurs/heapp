<div id="horizontal-menu" class="navbar navbar-inverse hidden-sm hidden-xs inner">
	<div class="container">
		<div class="navbar-collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="<?= base_url() ?>">
						Home
					</a>
				</li>
				<li>
					<a href="#">
						About Us
					</a>
				</li>
				<li>
					<a href="<?= base_url('welcome/landing/2') ?>">
						My Account
					</a>
				</li>
			</ul>
		</div>

		<!--/.nav-collapse -->
	</div>
</div>
<div class="visible-xs visible-sm hidden-md hidden-lg">
	<ul class="nav navbar-nav">
		<li>
			<a role="menuitem" tabindex="-1" href="<?= base_url() ?>">
				<i class="fa fa-home fa-fw"></i>&nbsp;Home
			</a>
		</li>
		<li>
			<a role="menuitem" tabindex="-1" href="">
				<i class="fa fa-info fa-fw"></i>&nbsp;About Us
			</a>
		</li>
		<li>
			<a role="menuitem" tabindex="-1" href="<?= base_url('welcome/landing/2') ?>">
				<i class="fa fa-user fa-fw"></i>&nbsp;My Account
			</a>
		</li>
	</ul>
</div>