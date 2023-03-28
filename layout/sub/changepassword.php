<?php require_once 'engine/init.php';
protect_page();

if (empty($_POST) === false) {
	/* Token used for cross site scripting security */
	if (!Token::isValid($_POST['token'])) {
		$errors[] = 'Token is invalid.';
	}
	
	$required_fields = array('current_password', 'new_password', 'new_password_again');
	
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'You need to fill in all fields.';
			break 1;
		}
	}
	
	$pass_data = user_data($session_user_id, 'password');
	//$pass_data['password'];
	// $_POST['']
	
	// .3 compatibility
	if ($config['ServerEngine'] == 'TFS_03' && $config['salt'] === true) {
		$salt = user_data($session_user_id, 'salt');
	}
	if (sha1($_POST['current_password']) === $pass_data['password'] || $config['ServerEngine'] == 'TFS_03' && $config['salt'] === true && sha1($salt['salt'].$_POST['current_password']) === $pass_data['password']) {
		if (trim($_POST['new_password']) !== trim($_POST['new_password_again'])) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your new passwords do not match.</div>';
		} else if (strlen($_POST['new_password']) < 6) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your new passwords must be at least 6 characters.</div>';
		} else if (strlen($_POST['new_password']) > 100) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your new passwords must be less than 100 characters.</div>';
		}
	} else {
		$errors[] = '<div class="alert alert-danger" role="alert">Your current password is incorrect.</div>';
	}
}
?>

<h1>Change Password:</h1>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	echo '<div class="alert alert-success" role="alert">Your password has been changed.</div><br>You will need to login again with the new password.';
	session_destroy();
	header("refresh:2;url=index.php");
	exit();
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		//Posted the form without errors
		if ($config['ServerEngine'] == 'TFS_02' || $config['ServerEngine'] == 'TFS_10' || $config['ServerEngine'] == 'OTHIRE') {
			user_change_password($session_user_id, $_POST['new_password']);
		} else if ($config['ServerEngine'] == 'TFS_03') {
			user_change_password03($session_user_id, $_POST['new_password']);
		}
		header('Location: changepassword.php?success');
	} else if (empty($errors) === false){
		echo '';
		echo output_errors($errors);
		echo '';
	}
	?>

	<form action="" method="post">
        <div class="form-group">
            <label for="current_password">Current Password:</label>
            <input class="form-control" type="password" name="current_password">
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input class="form-control" type="password" name="new_password">
        </div>
        <div class="form-group">
            <label for="new_password_again">Confirm New Password</label>
            <input class="form-control" type="password" name="new_password_again">
        </div>
    <?php
        /* Form file */
        Token::create();
    ?>
        <button type="submit" class="btn btn-outline-success"><i class="fas fa-key"></i> Change Password</button>
	</form>
<?php
}
?>