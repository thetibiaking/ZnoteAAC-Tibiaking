<!-- Login / Register Widget -->
<aside class="single_sidebar_widget search_widget">
	<h5 class="widget_title">Login / Register</h5>
		<form action="login.php" method="post">
			<label for="username">Username:</label>
			<input id="username" class="form-control" type="text" name="username">
			<br>
			<label for="password">Password:</label>
			<input id="password" class="form-control" type="password" name="password">
			<?php if ($config['twoFactorAuthenticator'] == true) { ?>
				<label for="token">Token:</label>
				<input id="token" class="form-control" type="password" name="authcode">
			<?php } ?>
			<br>
			<button class="button rounded-0 primary-bg text-white w-100 btn_1" type="submit">Login</button>
			<?php
				if ($config['use_token'] == true) {
					/* Form file */
					Token::create();
				}
			?>
		</form>
		<hr>
		<button class="button rounded-0 primary-bg text-white w-100 btn_1" onclick="window.location.href='recovery.php'">Account Recovery</button>
		<button class="button rounded-0 primary-bg text-white w-100 btn_1" onclick="window.location.href='register.php'">New account</button>
</aside>
