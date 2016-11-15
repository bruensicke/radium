<header id="navbar">

	<div id="navbar-container" class="boxed">

		<div class="navbar-header">
			<a href="<?= $this->url('/radium'); ?>" class="navbar-brand">
				<?= $this->html->image('/radium/img/logo.png', ['class' => 'brand-icon', 'alt' => 'radium']); ?>
				<div class="brand-title">
					<span class="brand-text">Radium</span>
				</div>
			</a>
		</div>


		<div class="navbar-content clearfix">

			<ul class="nav navbar-top-links pull-left">
				<li class="tgl-menu-btn">
					<a class="mainnav-toggle" href="#">
						<i class="ti-view-list icon-lg"></i>
					</a>
				</li>

                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="ti-bell icon-lg"></i>
                                <span class="badge badge-header badge-danger"></span>
                            </a>

                            <!--Notification dropdown menu-->
                            <div class="dropdown-menu dropdown-menu-md">
                                <div class="pad-all bord-btm">
                                    <p class="text-semibold text-main mar-no">You have 3 notifications.</p>
                                </div>
                                <div class="nano scrollable">
                                    <div class="nano-content">
                                        <ul class="head-list">

                                            <!-- Dropdown list-->
                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                        <p class="pull-left">Progressbar</p>
                                                        <p class="pull-right">70%</p>
                                                    </div>
                                                    <div class="progress progress-sm">
                                                        <div style="width: 70%;" class="progress-bar">
                                                            <span class="sr-only">70% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
									
									        <!-- Dropdown list-->
									        <li>
									            <a href="#" class="media">
									                <div class="media-left">
									                    <i class="ti-truck icon-lg"></i>
									                </div>
									                <div class="media-body">
									                    <div class="text-nowrap">With Icon</div>
									                    <small class="text-muted">15 minutes ago</small>
									                </div>
									            </a>
									        </li>
									
									        <!-- Dropdown list-->
									        <li>
									            <a href="#" class="media">
									                <div class="media-left">
									                    <i class="ti-plug icon-lg"></i>
									                </div>
									                <div class="media-body">
									                    <div class="text-nowrap">With Icon</div>
									                    <small class="text-muted">15 minutes ago</small>
									                </div>
									            </a>
									        </li>
									
									        <!-- Dropdown list-->
									        <li>
									            <a href="#" class="media">
									                <div class="media-left">
									                    
									<span class="icon-wrap icon-circle bg-primary">
									    <i class="ti-layout icon-lg"></i>
									</span>
									                </div>
									                <div class="media-body">
									                    <div class="text-nowrap">Circle Icon</div>
									                    <small class="text-muted">15 minutes ago</small>
									                </div>
									            </a>
									        </li>
									
									        <!-- Dropdown list-->
									        <li>
									            <a href="#" class="media">
									        <span class="badge badge-success pull-right">90%</span>
									                <div class="media-left">
									                    
									<span class="icon-wrap icon-circle bg-danger">
									    <i class="ti-crown icon-lg"></i>
									</span>
									                </div>
									                <div class="media-body">
									                    <div class="text-nowrap">Circle icon with badge</div>
									                    <small class="text-muted">50 minutes ago</small>
									                </div>
									            </a>
									        </li>
									
									        <!-- Dropdown list-->
									        <li>
									            <a href="#" class="media">
									                <div class="media-left">
									                    
									<span class="icon-wrap bg-info">
									    <i class="ti-camera icon-lg"></i>
									</span>
									                </div>
									                <div class="media-body">
									                    <div class="text-nowrap">Square Icon</div>
									                    <small class="text-muted">Last Update 8 hours ago</small>
									                </div>
									            </a>
									        </li>
									
									        <!-- Dropdown list-->
									        <li>
									            <a href="#" class="media">
									        <span class="label label-danger pull-right">New</span>
									                <div class="media-left">
									                    
									<span class="icon-wrap bg-purple">
									    <i class="ti-bolt icon-lg"></i>
									</span>
									                </div>
									                <div class="media-body">
									                    <div class="text-nowrap">Square icon with label</div>
									                    <small class="text-muted">Last Update 8 hours ago</small>
									                </div>
									            </a>
									        </li>
                                        </ul>
                                    </div>
                                </div>

                                <!--Dropdown footer-->
                                <div class="pad-all bord-top">
                                    <a href="#" class="btn-link text-dark box-block">
                                        <i class="ti-angle-right pull-right"></i>Show All Notifications
                                    </a>
                                </div>
                            </div>
                        </li>



			</ul>

		</div>

</header>

<!--
<nav class="navbar navbar-default navbar-static-top navbar-main" role="navigation">
	<div class="navbar-header">

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>

		<a href="<?=$this->url('/'); ?>" class="pull-left">
			<?=$this->html->image('logo.png', array('style' => 'padding-right: 10px')); ?>
		</a>
		<a href="<?=$this->url('/'); ?>" class="brand">
			<span>Home</span>
		</a>

	</div>

	<div class="collapse navbar-collapse navbar-ex1-collapse">

		<div class="navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><?php echo $this->html->link('Start', '/'); ?></li>
			</ul>
		</div>

	</div>
</nav>

-->
