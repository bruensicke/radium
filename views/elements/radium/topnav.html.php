<nav class="navbar navbar-default navbar-static-top navbar-main" role="navigation">
	<div class="navbar-header">
		<a class="navbar-brand" href="<?= $this->url('/radium'); ?>"><i class="fa fa-html5"></i> Radium</a>
	</div>
	<ul class="nav navbar-nav navbar-right">
		<li class="visible-xs">
			<a href="#" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar">
				<span class="sr-only">Toggle navigation</span>
				<i class="fa fa-bars"></i>
			</a>
		</li>
<!-- 		<li class="dropdown notification">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<span class="label label-danger arrowed arrow-left-in pull-right">12</span>
				<i class="fa fa-bell"></i>
			</a>
			<ul class="dropdown-menu pull-right">
				<li>
					<a href="#">
						<i class="fa fa-inbox pull-left"></i>
						<span class="time">now</span>
						<p>Stet clita kasd gubergren, no sea takimata Lorem ipsum dolor sit amet.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-bell pull-left"></i>
						<span class="time">13 min. ago</span>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy et dolore.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-bell pull-left"></i>
						<span class="time">17 min. ago</span>
						<p>Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-inbox pull-left"></i>
						<span class="time">23 min. ago</span>
						<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco ut aliquid ex ea commodi consequat.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-inbox pull-left"></i>
						<span class="time">26 min. ago</span>
						<p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor et dolore magna aliqua.</p>
					</a>
				</li>
				<li class="open-section">
					<a href="#">View All Notifications</a>
				</li>
			</ul>
		</li> -->
<!-- 		<li class="dropdown notification">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<span class="label label-primary arrowed arrow-left-in pull-right">6</span>
				<i class="fa fa-inbox"></i>
			</a>
			<ul class="dropdown-menu pull-right">
				<li>
					<a href="#">
						<img src="img/users/alex.jpg" alt="alex" class="img-avatar pull-left" />
						<span class="time">now</span>
						<p>Stet clita kasd gubergren, no sea takimata Lorem ipsum dolor sit amet.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="img/users/fabbian.jpg" alt="fabbian" class="img-avatar pull-left" />
						<span class="time">13 min. ago</span>
						<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy et dolore.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="img/users/lex.jpg" alt="lex" class="img-avatar pull-left" />
						<span class="time">17 min. ago</span>
						<p>Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="img/users/lex.jpg" alt="lex" class="img-avatar pull-left" />
						<span class="time">23 min. ago</span>
						<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco ut aliquid ex ea commodi consequat.</p>
					</a>
				</li>
				<li>
					<a href="#">
						<img src="img/users/molly.jpg" alt="molly" class="img-avatar pull-left" />
						<span class="time">26 min. ago</span>
						<p>Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor et dolore magna aliqua.</p>
					</a>
				</li>
				<li class="open-section">
					<a href="#">View All Messages</a>
				</li>
			</ul>
		</li> -->
		<li class="dropdown">
			<a href="#" class="dropdown-toggle avatar pull-right" data-toggle="dropdown">
				<i class="fa fa-user fa-3x"></i>
<!-- 				<img src="img/users/mike.jpg" alt="mike" class="img-avatar" />
 -->				<span class="hidden-small">Mike Smith<b class="caret"></b></span>
			</a>
			<ul class="dropdown-menu pull-right">
				<li><a href="#"><i class="fa fa-gear"></i>Account Settings</a></li>
				<li><a href="profile.html"><i class="fa fa-user"></i>View Profile</a></li>
				<li class="divider"></li>
				<li><a href="login.html"><i class="fa fa-sign-out"></i>Logout</a></li>
			</ul>
		</li>
	</ul>
</nav>

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
