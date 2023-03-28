<?php require_once 'engine/init.php';
protect_page();

if (empty($_POST) === false) {
	// $_POST['']
	$required_fields = array('name', 'selected_town');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = '<div class="alert alert-danger" role="alert">You need to fill in all fields.</div>';
			break 1;
		}
	}
	
	// check errors (= user exist, pass long enough
	if (empty($errors) === true) {
		if (!Token::isValid($_POST['token'])) {
			$errors[] = '<div class="alert alert-danger" role="alert">Token is invalid.</div>';
		}
		$_POST['name'] = validate_name($_POST['name']);
		if ($_POST['name'] === false) {
			$errors[] = '<div class="alert alert-danger" role="alert">Your name can not contain more than 2 words.</div>';
		} else {
			if (user_character_exist($_POST['name']) !== false) {
				$errors[] = '<div class="alert alert-danger" role="alert">Sorry, that character name already exist.</div>';
			}
			if (!preg_match("/^[a-zA-Z ]+$/", $_POST['name'])) {
				$errors[] = '<div class="alert alert-danger" role="alert">Your name may only contain a-z, A-Z and spaces.</div>';
			}
			if (strlen($_POST['name']) < $config['minL'] || strlen($_POST['name']) > $config['maxL']) {
				$errors[] = '<div class="alert alert-danger" role="alert">Your character name must be between ' . $config['minL'] . ' - ' . $config['maxL'] . ' characters long.</div>';
			}
			// name restriction
			$resname = explode(" ", $_POST['name']);
			foreach($resname as $res) {
				if(in_array(strtolower($res), $config['invalidNameTags'])) {
					$errors[] = '<div class="alert alert-danger" role="alert">Your username contains a restricted word.</div>';
				}
				else if(strlen($res) == 1) {
					$errors[] = '<div class="alert alert-danger" role="alert">Too short words in your name.</div>';
				}
			}
			// Validate vocation id
			if (!in_array((int)$_POST['selected_vocation'], $config['available_vocations'])) {
				$errors[] = '<div class="alert alert-danger" role="alert">Permission Denied. Wrong vocation.</div>';
			}
			// Validate town id
			if (!in_array((int)$_POST['selected_town'], $config['available_towns'])) {
				$errors[] = '<div class="alert alert-danger" role="alert">Permission Denied. Wrong town.</div>';
			}
			// Validate gender id
			if (!in_array((int)$_POST['selected_gender'], array(0, 1))) {
				$errors[] = '<div class="alert alert-danger" role="alert">Permission Denied. Wrong gender.</div>';
			}
			if (vocation_id_to_name($_POST['selected_vocation']) === false) {
				$errors[] = '<div class="alert alert-danger" role="alert">Failed to recognize that vocation, does it exist?</div>';
			}
			if (town_id_to_name($_POST['selected_town']) === false) {
				$errors[] = '<div class="alert alert-danger" role="alert">Failed to recognize that town, does it exist?</div>';
			}
			if (gender_exist($_POST['selected_gender']) === false) {
				$errors[] = '<div class="alert alert-danger" role="alert">Failed to recognize that gender, does it exist?</div>';
			}
			// Char count
			$char_count = user_character_list_count($session_user_id);
			if ($char_count >= $config['max_characters']) {
				$errors[] = '<div class="alert alert-danger" role="alert">Your account is not allowed to have more than '. $config['max_characters'] .' characters.</div>';
			}
			if (validate_ip(getIP()) === false && $config['validate_IP'] === true) {
				$errors[] = '<div class="alert alert-danger" role="alert">Failed to recognize your IP address. (Not a valid IPv4 address).</div>';
			}
		}
	}
}
?>

<h1>Create Character</h1>
<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	echo '<div class="alert alert-success" role="alert">Congratulations! Your character has been created. See you in-game!</div>';
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		if ($config['log_ip']) {
			znote_visitor_insert_detailed_data(2);
		}
		//Register
		$character_data = array(
			'name'		=>	format_character_name($_POST['name']),
			'account_id'=>	$session_user_id,
			'vocation'	=>	$_POST['selected_vocation'],
			'town_id'	=>	$_POST['selected_town'],
			'sex'		=>	$_POST['selected_gender'],
			'lastip'	=>	getIPLong(),
			'created'	=>	time()
		);
		
		user_create_character($character_data);
		header('Location: createcharacter.php?success');
		exit();
		//End register
		
	} else if (empty($errors) === false){
		echo '<font color="red"><b>';
		echo output_errors($errors);
		echo '</b></font>';
	}
	?>
	<form action="" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input class="form-control" type="text" name="name">
        </div>
        <div class="form-row">
            <div class="col">
				<!-- Available vocations to select from when creating character -->
                <label class="mr-sm-2" for="selected_vocation">Vocation:</label>
				<select class="custom-select mr-sm-2" name="selected_vocation">
				    <?php foreach ($config['available_vocations'] as $id) { ?>
				    <option value="<?php echo $id; ?>"><?php echo vocation_id_to_name($id); ?></option>
				    <?php } ?>
				</select>
            </div>
            <div class="col">
				<!-- Available genders to select from when creating character -->
                <label class="mr-sm-2" for="selected_gender">Gender:</label>
				<select class="custom-select mr-sm-2" name="selected_gender">
				    <option value="1">Male(boy)</option>
				    <option value="0">Female(girl)</option>
				</select>
            </div>
			<?php
			    $available_towns = $config['available_towns'];
			    if (count($available_towns) > 1):
			?>
            <div class="col">
				<!-- Available towns to select from when creating character -->
                <label class="mr-sm-2" for="selected_town">Town:</label>
				<select class="custom-select mr-sm-2" name="selected_town">
                    <?php 
                    foreach ($available_towns as $tid): 
                        ?>
                        <option value="<?php echo $tid; ?>"><?php echo town_id_to_name($tid); ?></option>
                        <?php 
                    endforeach; 
                    ?>
				</select>
            </div>
        </div>
        <?php
            else:
        ?>
            <input type="hidden" name="selected_town" value="<?php echo end($available_towns); ?>">
        <?php 
            endif;

			/* Form file */
			Token::create();
		?> 
        <br>       
        <button type="submit" class="btn btn-outline-success"><i class="fas fa-user-plus"></i> Create Character</button>
	</form>
	<?php
}
?>