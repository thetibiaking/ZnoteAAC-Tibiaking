<!-- Top Widget -->
<aside class="single_sidebar_widget search_widget">
	<h4 class="widget_title">Top 5 players</h4>
	<?php
		$cache = new Cache('engine/cache/topPlayer');
		if ($cache->hasExpired()) {
			$players = mysql_select_multi('SELECT `name`, `level`, `experience` FROM `players` WHERE `group_id` < ' . $config['highscore']['ignoreGroupId'] . ' ORDER BY `experience` DESC LIMIT 5;');
			
			$cache->setContent($players);
			$cache->save();
		} else {
			$players = $cache->load();
		}

		if ($players) {
			$count = 1;
			foreach($players as $player) {
				echo "$count - <a href='characterprofile.php?name=". $player['name']. "'>". $player['name']. "</a> (". $player['level'] .").<br>";
				$count++;
			}
		}
	?>
</aside>