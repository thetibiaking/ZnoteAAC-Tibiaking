<div class="col-lg-4">
    <div class="blog_right_sidebar">
		<?php
			if (user_logged_in() === true) {
				include 'layout/widgets/loggedin.php';
			} else {
				include 'layout/widgets/login.php';
			}
			if (user_logged_in() && is_admin($user_data)) include 'layout/widgets/Wadmin.php';
			if ($config['otservers_eu_voting']['enabled']) include 'layout/widgets/vote.php';
			include 'layout/widgets/charactersearch.php';
			include 'layout/widgets/topplayers.php';
			include 'layout/widgets/highscore.php';
		?>
	</div>
</div>