<?php
require_once 'engine/init.php';
logged_in_redirect();
require_once('config.countries.php');

if (empty($_POST) === false) {
	// $_POST['']
	$required_fields = array('username', 'password', 'password_again', 'email', 'selected');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = '<div class="alert alert-danger" role="alert">You need to fill in all fields.</div>';
			break 1;
		}
	}

	// check errors (= user exist, pass long enough
	if (empty($errors) === true) {
		/* Token used for cross site scripting security */
		if (!Token::isValid($_POST['token'])) {
			$errors[] = '<div class="alert alert-danger" role="alert">Token is invalid.</div>';
		}

		if ($config['use_captcha']) {
			if(!verifyGoogleReCaptcha($_POST['g-recaptcha-response'])) {
				$errors[] = '<div class="alert alert-danger" role="alert">Please confirm that you are not a robot.</div>';
			}
		}

		if (user_exist($_POST['username']) === true) {
			$errors[] = '<div class="alert alert-danger" role="alert">Sorry, that username already exist.</div>';
		}

		// Don't allow "default admin names in config.php" access to register.
		$isNoob = in_array(strtolower($_POST['username']), $config['page_admin_access']) ? true : false;
		if ($isNoob) {
			$errors[] = '<div class="alert alert-danger" role="alert">This account name is blocked for registration.</div>';
		}
		if ($config['ServerEngine'] !== 'OTHIRE' && $config['client'] >= 830) {
		    if (preg_match("/^[a-zA-Z0-9]+$/", $_POST['username']) == false) {
		        $errors[] = '<div class="alert alert-danger" role="alert">Your account name can only contain characters a-z, A-Z and 0-9.</div>';
		    }
		} else {
		    if (preg_match("/^[0-9]+$/", $_POST['username']) == false) {
		        $errors[] = '<div class="alert alert-danger" role="alert">Your account can only contain numbers 0-9.</div>';
		    }
		    if ((int)$_POST['username'] < 100000 || (int)$_POST['username'] > 999999999) {
		        $errors[] = '<div class="alert alert-danger" role="alert">Your account number must be a value between 6-8 numbers long.</div>';
		    }
		}
		// name restriction
		$resname = explode(" ", $_POST['username']);
		foreach($resname as $res) {
			if(in_array(strtolower($res), $config['invalidNameTags'])) {
				$errors[] = '<div class="alert alert-danger" role="alert">Your username contains a restricted word.</div>';
			}
			else if(strlen($res) == 1) {
				$errors[] = '<div class="alert alert-danger" role="alert">Too short words in your name.</div>';
			}
		}
		if (strlen($_POST['username']) > 32) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your account name must be less than 33 characters.</div>';
		}
		// end name restriction
		if (strlen($_POST['password']) < 6) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your password must be at least 6 characters.</div>';
		}
		if (strlen($_POST['password']) > 100) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your password must be less than 100 characters.</div>';
		}
		if ($_POST['password'] !== $_POST['password_again']) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your passwords do not match.</div>';
		}
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = '<div class="alert alert-danger" role="alert">A valid email address is required.</div>';
		}
		if (user_email_exist($_POST['email']) === true) {
			$errors[] = '<div class="alert alert-danger" role="alert">That email address is already in use.</div>';
		}
		if ($_POST['selected'] != 1) {
			$errors[] = '<div class="alert alert-danger" role="alert">You are only allowed to have an account if you accept the rules.</div>';
		}
		if (validate_ip(getIP()) === false && $config['validate_IP'] === true) {
			$errors[] = '<div class="alert alert-danger" role="alert">Failed to recognize your IP address. (Not a valid IPv4 address).</div>';
		}
	        if (strlen($_POST['flag']) < 1) {
                        $errors[] = '<div class="alert alert-danger" role="alert">Please choose country.</div>';
                }
	}
}

?>
<article class="blog_item">
    <div class="blog_details">
		<h1>Register Account</h1>
<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	if ($config['mailserver']['register']) {
		?>
		<h1>Email authentication required</h1>
		<p>We have sent you an email with an activation link to your submitted email address.</p>
		<p>If you can't find the email within 5 minutes, check your <strong>junk/trash inbox (spam filter)</strong> as it may be mislocated there.</p>
		<?php
	} else echo '<div class="alert alert-success" role="alert">Congratulations! Your account has been created. You may now login to create a character.</div>';
} elseif (isset($_GET['authenticate']) && empty($_GET['authenticate'])) {
	// Authenticate user, fetch user id and activation key
	$auid = (isset($_GET['u']) && (int)$_GET['u'] > 0) ? (int)$_GET['u'] : false;
	$akey = (isset($_GET['k']) && (int)$_GET['k'] > 0) ? (int)$_GET['k'] : false;
	// Find a match
	$user = mysql_select_single("SELECT `id`, `active`, `active_email` FROM `znote_accounts` WHERE `account_id`='$auid' AND `activekey`='$akey' LIMIT 1;");
	if ($user !== false) {
		$user = (int) $user['id'];
		$active = (int) $user['active'];
		$active_email = (int) $user['active_email'];
		// Enable the account to login
		if ($active == 0 || $active_email == 0) {
			mysql_update("UPDATE `znote_accounts` SET `active`='1', `active_email`='1' WHERE `id`= $user LIMIT 1;");
		}
		echo '<h1>Congratulations!</h1> <div class="alert alert-success" role="alert">Your account has been created. You may now login to create a character.</div>';
	} else {
		echo '<h1>Authentication failed</h1> <div class="alert alert-danger" role="alert">Either the activation link is wrong, or your account is already activated.</div>';
	}
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		if ($config['log_ip']) {
			znote_visitor_insert_detailed_data(1);
		}

		//Register
		if ($config['ServerEngine'] !== 'OTHIRE') {
			$register_data = array(
				'name'		=>	$_POST['username'],
				'password'	=>	$_POST['password'],
				'email'		=>	$_POST['email'],
				'created'	=>	time(),
				'ip'		=>	getIPLong(),
				'flag'		=> 	$_POST['flag']
			);
		} else {
			$register_data = array(
				'id'		=>	$_POST['username'],
				'password'	=>	$_POST['password'],
				'email'		=>	$_POST['email'],
				'created'	=>	time(),
				'ip'		=>	getIPLong(),
				'flag'		=> 	$_POST['flag']
			);			
		}	

		user_create_account($register_data, $config['mailserver']);
		if (!$config['mailserver']['debug']) header('Location: register.php?success');
		exit();
		//End register

	} else if (empty($errors) === false){
		echo '';
		echo output_errors($errors);
		echo '';
	}
?>
	<form action="" method="post">
        <label for="username">Account Name:</label>
        <br>
        <input class="form-control" type="text" name="username">
        <br>
        <label for="password">Password:</label>
        <br>
        <input class="form-control" type="password" name="password">
        <br>
        <label for="password_again">Confirm Password:</label>
        <br>
        <input class="form-control" type="password" name="password_again">
        <br>
        <label for="email">Email:</label>
        <br>
        <input class="form-control" type="text" name="email">
        <br>
        <label for="flag">Country:</label>
		<br>
		<div class="input-group-icon mt-10">
		<div class="icon"><i class="fa fa-globe" aria-hidden="true"></i></div>
		<div class="form-select" id="default-select_1">
			<select name="flag">
		    	<option class="form-control" value="">(Please choose)</option>
				<?php
				foreach(array('pl', 'se', 'br', 'us', 'gb', 'mx', 've') as $c)
					echo '<option value="' . $c . '">' . $config['countries'][$c] . '</option>';

					echo '<option value=""> Other</option>';
				?>
			</select>
		</div>
		</div>
        <?php
        if ($config['use_captcha']) {
            ?>
            <li>
                    <div class="g-recaptcha" data-sitekey="<?php echo $config['captcha_site_key']; ?>"></div>
            </li>
            <?php
        }
        ?>
        <hr>
        <h2>Server Rules</h2>
        <ul class="list-group">
            <li class="list-group-item">The golden rule: Have fun.</li>
            <li class="list-group-item">If you get pwn3d, don't hate the game.</li>
            <li class="list-group-item">No <a href='http://en.wikipedia.org/wiki/Cheating_in_video_games' target="_blank">cheating</a> allowed.</li>
            <li class="list-group-item">No <a href='http://en.wikipedia.org/wiki/Video_game_bot' target="_blank">botting</a> allowed.</li>
            <li class="list-group-item">If you get pwn3d, don't hate the game.</li>
            <li class="list-group-item">The staff can delete, ban, do whatever they want with your account and your submitted information. (Including exposing and logging your IP).</li>
            <li class="list-group-item">In the IP changer, click on <strong>Browse</strong>, navigate to your desired Tibia version folder, select Tibia.exe and click <strong>Add</strong>. Then click <strong>Close</strong></li>
            <li class="list-group-item">Now you can successfully login on the tibia client and play clicking on <strong>Apply</strong> every time you want.<br></li>
            <li class="list-group-item">If you do not have an account to login with, you need to register an account <a href="register.php">HERE</a>.</li>
        </ul>
        <hr>
        <h5>Do you agree to follow the server rules?</h5>
        <br>
        <div class="form-row">
            <div class="col">
                <select class="default-select" name="selected">
                    <option  value="0">(Please choose)</option>
                    <option  value="1">Yes.</option>
                    <option  value="2">No.</option>
                </select>
            </div>
            <?php
                /* Form file */
                Token::create();
            ?>
            <div class="col">
                <button class="btn btn-success" type="submit">Create Account</button>
            </div>
        </div>
	</form>
	</div>
</article>
<?php
}
?>
