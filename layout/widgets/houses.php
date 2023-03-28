<!-- House Widget -->
<aside class="single_sidebar_widget search_widget">
	<h4 class="widget_title">Search Town List</h4>
		<form action="houses.php" method="<?php if ($config['ServerEngine'] !== 'TFS_10') echo "post"; else echo "get" ;?>">
			<p>Select town:</p>
			<div class="form-group">
				<select class="default-select" name="<?php if ($config['ServerEngine'] !== 'TFS_10') echo "selected"; else echo "id" ;?>">
					<?php foreach ($config['towns'] as $id => $name) echo '<option value="'. $id .'">'. $name .'</option>';	?>
				</select>
				<?php
					/* Form file */
					if ($config['ServerEngine'] !== 'TFS_10') Token::create();
				?>
			</div> 
			<br>
			<button class="button rounded-0 primary-bg text-white w-100 btn_1" type="submit">Search</button>
		</form>
</aside>