<!-- Loggedin Widget -->
<aside class="single_sidebar_widget search_widget">
	<h4 class="widget_title">Welcome, <?php if ($config['ServerEngine'] !== 'OTHIRE') echo $user_data['name']; else echo $user_data['id'];?>.</h4>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-6">
			<ul class="unordered-list">
				<li>
				<a href='myaccount.php'>My Account</a>
				</li>
				<li>
				<a href='createcharacter.php'>Create Character</a>
				</li>
				<li>
				<a href='changepassword.php'>Change Password</a>
				</li>
			</ul>
			</div>
			<div class="col-lg-6">
			<ul class="unordered-list">
				<li>
				<a href='settings.php'>Settings</a>
				</li>
				<li>
				<a href='logout.php'>Logout</a>
				</li>
			</ul>
			</div>
		</div>
	</div>
</aside>