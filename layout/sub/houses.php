<form action="houses.php" method="post">
	<h2>Select town:</h2>
	<br>
	<div class="form-group">
		<select class="form-control" name="selected">
		<?php
		foreach ($config['towns'] as $id => $name) echo '<option class="form-control" value="'. $id .'">'. $name .'</option>';
		?>
		</select> 
		<?php
			/* Form file */
			Token::create();
		?>
	</div>
	<br>
	<button class="button rounded-0 primary-bg text-white btn_1" type="submit"><i class="fas fa-search"></i> Search</button>
</form>