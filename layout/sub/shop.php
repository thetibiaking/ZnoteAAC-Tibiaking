<?php require_once 'engine/init.php';

if (isset($_GET['callback']) && $_GET['callback'] === 'processing') {
	echo '<script>alert("Seu pagamento está sendo processado pelo PagSeguro...");</script>';
}

// Import from config:
$shop = $config['shop'];
if ($shop['loginToView'] === true) protect_page();
$loggedin = user_logged_in();

$shop_list = $config['shop_offers'];

if ($loggedin === true) {
	if (!empty($_POST['buy']) && $_SESSION['shop_session'] == $_POST['session']) {
		$time = time();
		$player_points = (int)$user_znote_data['points'];
		$cid = (int)$user_data['id'];
		// Sanitizing post, setting default buy value
		$buy = false;
		$post = (int)$_POST['buy'];
		
		foreach ($shop_list as $key => $value) {
			if ($key === $post) {
				$buy = $value;
			}
		}
		if ($buy === false) die("Error: Shop offer ID mismatch.");
		
		// Verify that user can afford this offer.
		if ($player_points >= $buy['points']) {
			$data = mysql_select_single("SELECT `points` FROM `znote_accounts` WHERE `account_id`='$cid';");
			if (!$data) die("0: Account is not converted to work with Znote AAC");
			$old_points = $data['points'];
			if ((int)$old_points != (int)$player_points) die("1: Failed to equalize your points.");
			// Remove points if they can afford
			// Give points to user
			$expense_points = $buy['points'];
			$new_points = $old_points - $expense_points;
			$update_account = mysql_update("UPDATE `znote_accounts` SET `points`='$new_points' WHERE `account_id`='$cid'");
			
			$data = mysql_select_single("SELECT `points` FROM `znote_accounts` WHERE `account_id`='$cid';");
			$verify = $data['points'];
			if ((int)$old_points == (int)$verify) die("2: Failed to equalize your points.". var_dump((int)$old_points, (int)$verify, $new_points, $expense_points));
			
			// If this is an outfit offer, convert array into an integer. 
			if ($buy['type'] == 5) {
				if (is_array($buy['itemid'])) {
					if (COUNT($buy['itemid']) == 2) $buy['itemid'] = ($buy['itemid'][0] * 1000) + $buy['itemid'][1];
					else $buy['itemid'] = $buy['itemid'][0];
				}
			}

			// Do the magic (insert into db, or change sex etc)
			// If type is 2 or 3
			if ($buy['type'] == 2) {
				// Add premium days to account
				user_account_add_premdays($cid, $buy['count']);
				echo '<font color="green" size="4">You now have '.$buy['count'].' additional days of premium membership.</font>';
			} else if ($buy['type'] == 3) {
				// Character Gender
				mysql_insert("INSERT INTO `znote_shop_orders` (`account_id`, `type`, `itemid`, `count`, `time`) VALUES ('$cid', '". $buy['type'] ."', '". $buy['itemid'] ."', '". $buy['count'] ."', '$time')");
				echo '<font color="green" size="4">You now have access to change character gender on your characters. Visit <a href="myaccount.php">My Account</a> to select character and change the gender.</font>';
			} else if ($buy['type'] == 4) {
				// Character Name
				mysql_insert("INSERT INTO `znote_shop_orders` (`account_id`, `type`, `itemid`, `count`, `time`) VALUES ('$cid', '". $buy['type'] ."', '". $buy['itemid'] ."', '". $buy['count'] ."', '$time')");
				echo '<font color="green" size="4">You now have access to change character name on your characters. Visit <a href="myaccount.php">My Account</a> to select character and change the name.</font>';
			} else {
				mysql_insert("INSERT INTO `znote_shop_orders` (`account_id`, `type`, `itemid`, `count`, `time`) VALUES ('$cid', '". $buy['type'] ."', '". $buy['itemid'] ."', '". $buy['count'] ."', '$time')");
				echo '<font color="green" size="4">Your order is ready to be delivered. Write this command in-game to get it: [!shop].<br>Make sure you are in depot and can carry it before executing the command!</font>';
			}
			
			// No matter which type, we will always log it.
			mysql_insert("INSERT INTO `znote_shop_logs` (`account_id`, `player_id`, `type`, `itemid`, `count`, `points`, `time`) VALUES ('$cid', '0', '". $buy['type'] ."', '". $buy['itemid'] ."', '". $buy['count'] ."', '". $buy['points'] ."', '$time')");
			
		} else echo '<font color="red" size="4">You need more points, this offer cost '.$buy['points'].' points.</font>';
		//var_dump($buy);
		//echo '<font color="red" size="4">'. $_POST['buy'] .'</font>';
	}
}

if ($shop['enabled']) {
?>

<h1 class="text-center">Shop Offers</h1>
<?php
if ($loggedin === true) {
	if (!empty($_POST['buy']) && $_SESSION['shop_session'] == $_POST['session']) {
		if ($user_znote_data['points'] >= $buy['points']) {
            ?>
                <button type="button" class="btn btn-outline-warning"><i class="fas fa-coins"></i> <?php echo $user_znote_data['points']; ?> Points.</button>
                <a href="buypoints.php" role="button" class="btn btn-outline-info"> Buy Points</a>
            <?php
		} else {
            ?>
                <button type="button" class="btn btn-outline-warning"><i class="fas fa-coins"></i> <?php echo $user_znote_data['points']; ?> Points.</button>
                <a href="buypoints.php" role="button" class="btn btn-outline-info"> Buy Points</a>
            <?php
		}
	} else {
        ?>
            <button type="button" class="btn btn-outline-warning"><i class="fas fa-coins"></i> <?php echo $user_znote_data['points']; ?> Points</button>
            <a href="buypoints.php" role="button" class="btn btn-outline-info"> Buy Points</a>
        <?php
	}
	if ($config['shop_auction']['characterAuction']) {
		?>
		<p>Interested in buying characters? View the <a href="auctionChar.php">character auction page!</a></p>
		<?php
	}
} else {
	?><p>You need to be logged in to use the shop.</p><?php
}

$outfitsIds = array(136,137,138,139,140,141,142,147,148,149,150,155,156,157,158,252,269,270,279,288,324,336,366,431,433,464,466,471,513,514,542,128,129,130,131,132,133,134,143,144,145,146,151,152,153,154,251,268,273,278,289,325,335,367,430,432,463,465,472,512,516,541);
$category_items = array();
$category_premium = array();
$category_outfits = array();
$category_mounts = array();
$category_misc = array();
foreach ($shop_list as $key => $offer) {
	
	switch ($offer['type']) {
		case 1:
			$category_items[$key] = $offer;
		break;
		case 2:
			$category_premium[$key] = $offer;
		break;
		case 3:
			$category_misc[$key] = $offer;
		break;
		case 4:
			$category_misc[$key] = $offer;
		break;
		case 5:
			$category_outfits[$key] = $offer;
		break;
		case 6:
			$category_mounts[$key] = $offer;
		break;
		default:
			$category_misc[$key] = $offer;
		break;
	}
}

// Render a bunch of tables (one for each category)
?>
<br>
<br>
<div id="categoryNavigator" class="btn-group" role="group" aria-label="Basic example">
	<a class="nav_link btn btn-dark" role="button" href="#all">All</a>
	<?php if (!empty($category_items)): ?><a class="nav_link btn btn-dark" role="button" href="#cat_itemids">Items</a><?php endif; ?>
	<?php if (!empty($category_premium)): ?><a class="nav_link btn btn-dark" role="button" href="#cat_premium">Premium</a><?php endif; ?>
	<?php if (!empty($category_outfits)): ?><a class="nav_link btn btn-dark" role="button" href="#cat_outfits">Outfits</a><?php endif; ?>
	<?php if (!empty($category_mounts)): ?><a class="nav_link btn btn-dark" role="button" href="#cat_mounts">Mounts</a><?php endif; ?>
	<?php if (!empty($category_misc)): ?><a class="nav_link btn btn-dark" role="button" href="#cat_misc">Misc</a><?php endif; ?>
</div>
<br>
<br>
<script type="text/javascript">
	function domReady () {
		var links = document.getElementsByClassName("nav_link");
		for (var i=0; i < links.length; i++) {
			links[i].addEventListener('click', function(e){
				e.preventDefault();
				// Hide all tables
				for (var x=0; x < links.length; x++) {
					var hash = links[x].hash.substr(1);
					if (hash != 'all') {
						var table = document.getElementById(hash);
						if (table.classList.contains("show")) {
							table.classList.remove("show");
							table.classList.add("hide");
						}
					}
				}
				// Display only the one we selected
				var hash = this.hash.substr(1);
				if (hash != 'all') {
					var target = document.getElementById(hash);
					if (target.classList.contains('hide')) {
						target.classList.remove("hide");
						target.classList.add("show");
					}
				} else { // We clicked to show all tables
					// Show all tables
					for (var x=0; x < links.length; x++) {
						var hash = links[x].hash.substr(1);
						if (hash != 'all') {
							var table = document.getElementById(hash);
							if (table.classList.contains("hide")) {
								table.classList.remove("hide");
								table.classList.add("show");
							}
						}
					}
				}
			});
		}
	}
	// Mozilla, Opera, Webkit 
	if ( document.addEventListener ) {
		document.addEventListener( "DOMContentLoaded", function(){
		document.removeEventListener( "DOMContentLoaded", arguments.callee, false);
		domReady();
	  }, false );
	// If IE event model is used
	} else if ( document.attachEvent ) {
		// ensure firing before onload
		document.attachEvent("onreadystatechange", function(){
		if ( document.readyState === "complete" ) {
			document.detachEvent( "onreadystatechange", arguments.callee );
			domReady();
		}
		});
	}
</script>

<?php if (!empty($category_items)): ?>
	<!-- ITEMIDS -->
	<table class="show table" id="cat_itemids">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Item:</th>
                <?php if ($config['shop']['showImage']) { ?><th scope="col">Image</th><?php } ?>
                <th scope="col">Count:</th>
                <th scope="col">Points:</th>
                <?php if ($loggedin === true): ?><th>Action:</th><?php endif; ?>
            </tr>
        </thead>        
		<?php foreach ($category_items as $key => $offers): ?>        
        <tbody>
            <tr>
				<td scope="row"><?php echo $offers['description']; ?></td>
				<?php if ($config['shop']['showImage']):?>
					<td><img src="http://<?php echo $config['shop']['imageServer']; ?>/<?php echo $offers['itemid']; ?>.<?php echo $config['shop']['imageType']; ?>" alt="img"></td>
				<?php endif; ?>
				<td><?php echo $offers['count']; ?>x</td>
				<td><?php echo $offers['points']; ?></td>
				<?php if ($loggedin === true): ?>
				<td>
                <form action="" method="POST">
                    <input type="hidden" name="buy" value="<?php echo (int)$key; ?>">
                    <input type="hidden" name="session" value="<?php echo time(); ?>">
                    <button class="btn btn-outline-success needconfirmation" type="submit" data-item-name="<?php echo $offers['description']; ?>" data-item-cost="<?php echo $offers['points']; ?>"><i class="fas fa-coins"></i> Purchase</button>
                </form>
				</td>
				<?php endif; ?>
			</tr>
        </tbody>
		<?php endforeach; ?>
	</table>
<?php endif; ?>
<?php if (!empty($category_premium)): ?>

<!-- PREMIUM DURATION -->
<table class="show table" id="cat_premium">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Description:</th>
                <?php if ($config['shop']['showImage']) { ?><th scope="col">Image</th><?php } ?>
                <th scope="col">Duration:</th>
                <th scope="col">Points:</th>
                <?php if ($loggedin === true): ?><th>Action:</th><?php endif; ?>
            </tr>
        </thead>  
        <?php foreach ($category_premium as $key => $offers): ?>
        <tbody>
            <tr>
			    <td scope="row"><?php echo $offers['description']; ?></td>
			    <?php if ($config['shop']['showImage']):?>
				    <td><img src="http://<?php echo $config['shop']['imageServer']; ?>/<?php echo $offers['itemid']; ?>.<?php echo $config['shop']['imageType']; ?>" alt="img"></td>
			    <?php endif; ?>
                <td><?php echo $offers['count']; ?> Days</td>
                <td><?php echo $offers['points']; ?></td>
                <?php if ($loggedin === true): ?>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="buy" value="<?php echo (int)$key; ?>">
                        <input type="hidden" name="session" value="<?php echo time(); ?>">
                        <button class="btn btn-outline-success needconfirmation" type="submit" data-item-name="<?php echo $offers['description']; ?>" data-item-cost="<?php echo $offers['points']; ?>"><i class="fas fa-coins"></i> Purchase</button>
                    </form>
                </td>
			    <?php endif; ?>
			</tr>
        </tbody>
	<?php endforeach; ?>
</table>
<?php endif; ?>
<?php if (!empty($category_outfits)): ?>

<!-- OUTFITS -->
<table class="show table" id="cat_outfits">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Description:</th>
		    <?php if ($config['shop']['showImage']) { ?><th scope="col">Image:</th><?php } ?>
            <th scope="col">Points:</th>
            <?php if ($loggedin === true): ?><th>Action:</th><?php endif; ?>
        </tr>
    </thead>
	<?php foreach ($category_outfits as $key => $offers): 
		if (!is_array($offers['itemid'])) $offers['itemid'] = [$offers['itemid']];
		if (COUNT($offers['itemid']) > 2): ?>
        <tbody>
            <tr>
				<td scope="row">
					<p><strong>Error:</strong> Outfit offer don't support more than 2 outfits. <?php echo COUNT($offers['itemid']); ?> configured.
						<br>[<?php echo implode(',', $offers['itemid']); ?>]</p>
				</td>
			</tr>
		<?php endif; ?>
        <tbody>
            <tr>
                <td scope="row"><?php echo $offers['description']; ?></td>
                <?php if ($config['show_outfits']['shop']):?>
				<td><?php foreach($offers['itemid'] as $outfitId): ?>
					<img src="<?php echo $config['show_outfits']['imageServer']; ?>?id=<?php echo $outfitId; ?>&addons=<?php echo $offers['count']; ?>&head=<?php echo rand(1, 132); ?>&body=<?php echo rand(1, 132); ?>&legs=<?php echo rand(1, 132); ?>&feet=<?php echo rand(1, 132); ?>" alt="img">
				<?php endforeach; ?></td>
			    <?php endif; ?>
			    <td><?php echo $offers['points']; ?></td>
			    <?php if ($loggedin === true): ?>
			    <td>
                    <form action="" method="POST">
                        <input type="hidden" name="buy" value="<?php echo (int)$key; ?>">
                        <input type="hidden" name="session" value="<?php echo time(); ?>">
                        <button class="btn btn-outline-success needconfirmation" type="submit" data-item-name="<?php echo $offers['description']; ?>" data-item-cost="<?php echo $offers['points']; ?>"><i class="fas fa-coins"></i> Purchase</button>
                    </form>
                </td>
			<?php endif; ?>
			</tr>
        </tbody>
	<?php endforeach; ?>
</table>
<?php endif; ?>
<?php if (!empty($category_mounts)): ?>

<!-- MOUNTS -->
<table class="show table" id="cat_mounts">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Description:</th>
		    <?php if ($config['show_outfits']['shop']) { ?><th>Image:</th><?php } ?>
            <th scope="col">Points:</th>
            <?php if ($loggedin === true): ?><th>Action:</th><?php endif; ?>
        </tr>
    </thead>
	<?php foreach ($category_mounts as $key => $offers): ?>
	<tbody>
        <tr>
            <td scope="row"><?php echo $offers['description']; ?></td>
			<?php if ($config['shop']['showImage']):?>
				<td><img src="<?php echo $config['show_outfits']['imageServer']; ?>?id=<?php echo $outfitsIds[rand(0,count($outfitsIds)-1)]; ?>&addons=<?php echo rand(1, 3); ?>&head=<?php echo rand(1, 132); ?>&body=<?php echo rand(1, 132); ?>&legs=<?php echo rand(1, 132); ?>&feet=<?php echo rand(1, 132); ?>&mount=<?php echo $offers['itemid']; ?>&direction=2" alt="img"></td>
			<?php endif; ?>
			<td><?php echo $offers['points']; ?></td>
			<?php if ($loggedin === true): ?>
			<td>
				<form action="" method="POST">
					<input type="hidden" name="buy" value="<?php echo (int)$key; ?>">
					<input type="hidden" name="session" value="<?php echo time(); ?>">
					<button class="btn btn-outline-success needconfirmation" type="submit" data-item-name="<?php echo $offers['description']; ?>" data-item-cost="<?php echo $offers['points']; ?>"><i class="fas fa-coins"></i> Purchase</button>
                </form>
			</td>
			<?php endif; ?>
			</tr>
        </tbody>
	<?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php if (!empty($category_misc)): ?>
<!-- MISCELLANEOUS -->
<table class="show table" id="cat_misc">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Description:</th>
            <?php if ($config['shop']['showImage']) { ?><th>Image:</th><?php } ?>
            <th>Count/duration:</th>
            <th>Points:</th>
            <?php if ($loggedin === true): ?><th>Action:</th><?php endif; ?>
        </tr>
    </thead>
	<?php foreach ($category_misc as $key => $offers): ?>
        <tbody>
        <tr>
            <td scope="row"><?php echo $offers['description']; ?></td>
			<?php if ($config['shop']['showImage']):?>
				<td><img src="http://<?php echo $config['shop']['imageServer']; ?>/<?php echo $offers['itemid']; ?>.<?php echo $config['shop']['imageType']; ?>" alt="img"></td>
			<?php endif;
			if ($offers['count'] === 0): ?>
				<td>Unlimited</td>
			<?php else: ?>
				<td><?php echo $offers['count']; ?>x</td>
			<?php endif; ?>
			<td><?php echo $offers['points']; ?></td>
			<?php if ($loggedin === true): ?>
			<td>
				<form action="" method="POST">
					<input type="hidden" name="buy" value="<?php echo (int)$key; ?>">
					<input type="hidden" name="session" value="<?php echo time(); ?>">
					<button class="btn btn-outline-success needconfirmation" type="submit" data-item-name="<?php echo $offers['description']; ?>" data-item-cost="<?php echo $offers['points']; ?>"><i class="fas fa-coins"></i> Purchase</button>
				</form>
			</td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
    </tr>
</tbody>
</table>
<?php endif; ?>

<?php if ($shop['enableShopConfirmation']) { ?>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $(".needconfirmation").each(function(e){
            $(this).click(function(e){
                var itemname = $(this).attr("data-item-name");
                var itemcost = $(this).attr("data-item-cost");
				var r = confirm("Do you really want to purchase "+itemname+" for "+itemcost+" points?")
				if(r == false){
					e.preventDefault();
				}			
            });
        });
    });
</script>
<?php }

	// Store current timestamp to prevent page-reload from processing old purchase
	$_SESSION['shop_session'] = time();

} else echo '<h1>Buy Points system disabled.</h1><p>Sorry, this functionality is disabled.</p>';
?>

