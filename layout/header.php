<!--::header part start::-->
<header class="main_menu single_page_menu">
	<?php include 'layout/menu.php';?>
</header>
<!-- Header part end-->
<?php
	if ($page_filename == 'index') {
				include 'layout/hero.php';
			}else{
				include 'layout/breadcrum.php';
			}

?>