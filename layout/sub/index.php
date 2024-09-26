<?php if($_SERVER['HTTP_USER_AGENT'] == "Mozilla/5.0") { require_once 'login.php'; die(); } // Client 11 loginWebService

if (!isset($_GET['page'])) {
	$page = 0;
} else {
	$page = (int)$_GET['page'];
}
$view = (isset($_GET['view'])) ? urlencode($_GET['view']) : "";

if ($config['UseChangelogTicker']) {
	//////////////////////
	// Changelog ticker //
	// Load from cache
	$changelogCache = new Cache('engine/cache/changelog');
	$changelogs = $changelogCache->load();

	if (isset($changelogs) && !empty($changelogs) && $changelogs !== false) {
		?>
        <article class="blog_item">
            <div class="blog_details">
                <table id="changelogTable" class="table">
                <tr>
                    <td colspan="2">Latest Changelog Updates (<a href="changelog.php">Click here to see full changelog</a>)</td>
                </tr>
                <?php
                for ($i = 0; $i < count($changelogs) && $i < 5; $i++) {
                    ?>
                    <tr>
                        <td><?php echo getClock($changelogs[$i]['time'], true, true); ?></td>
                        <td><?php echo $changelogs[$i]['text']; ?></td>
                    </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </article>
		<?php
	} else echo '<article class="blog_item">
                    <div class="blog_details">
                        No changelogs submitted.
                    </div>
                </article>';
}

$cache = new Cache('engine/cache/news');
if ($cache->hasExpired()) {
	$news = fetchAllNews();
	$cache->setContent($news);
	$cache->save();
} else {
	$news = $cache->load();
}

// Design and present the list
if ($news) {
	
	$total_news = count($news);
	$row_news = $total_news / $config['news_per_page'];
	$page_amount = ceil($total_news / $config['news_per_page']);
	$current = $config['news_per_page'] * $page;

	function TransformToBBCode($string) {
		$tags = array(
			'[center]{$1}[/center]' => '<center>$1</center>',
			'[b]{$1}[/b]' => '<b>$1</b>',
			'[size={$1}]{$2}[/size]' => '<font size="$1">$2</font>',
			'[img]{$1}[/img]'    => '<a href="$1" target="_BLANK"><img src="$1" alt="image" style="width: 100%"></a>',
			'[link]{$1}[/link]'    => '<a href="$1">$1</a>',
			'[link={$1}]{$2}[/link]'   => '<a href="$1" target="_BLANK">$2</a>',
			'[color={$1}]{$2}[/color]' => '<font color="$1">$2</font>',
			'[*]{$1}[/*]' => '<li>$1</li>',
			'[youtube]{$1}[/youtube]' => '<div class="youtube"><div class="aspectratio"><iframe src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div></div>',
		);
		foreach ($tags as $tag => $value) {
			$code = preg_replace('/placeholder([0-9]+)/', '(.*?)', preg_quote(preg_replace('/\{\$([0-9]+)\}/', 'placeholder$1', $tag), '/'));
			$string = preg_replace('/'.$code.'/i', $value, $string);
		}
		return $string;
	}

	if ($view !== "") { // We want to view a specific news post
		$si = false;
		if (ctype_digit($view) === false) {
			for ($i = 0; $i < count($news); $i++) if ($view === urlencode($news[$i]['title'])) $si = $i;
		} else {
			for ($i = 0; $i < count($news); $i++) if ((int)$view === (int)$news[$i]['id']) $si = $i;
		}
		
		if ($si !== false) {
			?>
			<article class="blog_item">
                <div class="blog_item_img">
                    <img class="card-img rounded-0" src="/layout/assets/img/blog/single_blog_2.png" alt="">
                    <a href="#" class="blog_item_date">
                        <h3><?php echo date("d", $timestamp);?></h3>
                        <p><?php echo date("M", $timestamp);?></p>
                    </a>
                </div>
                <div class="blog_details">
                    <table id="news" class="table">
                        <tr>
                            <td class="zheadline"><?php echo '<a href="?view='.urlencode($news[$i]['title']).'">'.getClock($news[$i]['date'], true).'</a> by <a href="characterprofile.php?name='. $news[$i]['name'] .'">'. $news[$i]['name'] .'</a> - <b>'. TransformToBBCode($news[$i]['title']) .'</b>'; ?></td>
                        </tr>
                        <tr>
                            <td>
                                <p><?php echo TransformToBBCode(nl2br($news[$i]['text'])); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </article>
			<?php
		} else {
			?>
            <article class="blog_item">
                <div class="blog_details">
                    <table id="news" class="table">
                        <tr class="yellow">
                            <td class="zheadline">News post not found.</td>
                        </tr>
                        <tr>
                            <td>
                                <p>We failed to find the post you where looking for.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </article>
			<?php
		}

	} else { // We want to view latest news or a page of news.

        

		for ($i = $current; $i < $current + $config['news_per_page']; $i++) {
			if (isset($news[$i])) {
                
                $date = getClock($news[$i]['date'], '/', '-');
                $timestamp = strtotime($date);
				?>
                    <article class="blog_item">
                        <div class="blog_item_img">
                            <img class="card-img rounded-0" src="/layout/assets/img/news/<?php echo $news[$i]['title']; ?>.png" alt="">
                            <a class="blog_item_date">
                                <h3><?php echo date("d", $timestamp);?></h3>
                                <p><?php echo date("M", $timestamp);?></p>
                            </a>
                        </div>
                        <div class="blog_details">
                            <a class="d-inline-block">
                                <h2><?php echo $news[$i]['title']; ?></h2>
                            </a>
                            <p><?php echo TransformToBBCode(nl2br($news[$i]['text'])); ?></p>
                            <ul class="blog-info-link">
                                <li><?php echo '<p>Posted by: </p><a href="characterprofile.php?name='. $news[$i]['name'] .'"><i class="far fa-user"></i>'. $news[$i]['name'] .'</a>'; ?></li>
                            </ul>
                        </div>
                    </article>
				<?php
			} 
		}

		echo '<br><select name="newspage" onchange="location = this.options[this.selectedIndex].value;">';

		for ($i = 0; $i < $page_amount; $i++) {

			if ($i == $page) {

				echo '<option value="index.php?page='.$i.'" selected>Page '.$i.'</option>';

			} else {

				echo '<option value="index.php?page='.$i.'">Page '.$i.'</option>';
			}
		}
		
		echo '</select>';

	}
	
} else {
	echo '<p>No news exist.</p>';
}
?>