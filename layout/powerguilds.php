<?php
// Most powerful guilds for TFS 0.3/4 and 1.0
////////////////////////
// Create a cache file to avoid high SQL load
$cache = new Cache('engine/cache/guilds');
if ($cache->hasExpired()) {
 // Fetch guild data
 
if ($config['TFSVersion'] == 'TFS_03') $guilds = mysql_select_multi('SELECT `g`.`id` AS `id`, `g`.`name` AS `name`, COUNT(`g`.`name`) as `frags` FROM `killers` k LEFT JOIN `player_killers` pk ON `k`.`id` = `pk`.`kill_id` LEFT JOIN `players` p ON `pk`.`player_id` = `p`.`id` LEFT JOIN `guild_ranks` gr ON `p`.`rank_id` = `gr`.`id` LEFT JOIN `guilds` g ON `gr`.`guild_id` = `g`.`id` WHERE `k`.`unjustified` = 1 AND `k`.`final_hit` = 1 GROUP BY `name` ORDER BY `frags` DESC, `name` ASC LIMIT 0, 3;');
elseif ($config['TFSVersion'] == 'TFS_10') $guilds = mysql_select_multi('SELECT `g`.`id` AS `id`, `g`.`name` AS `name`, COUNT(`g`.`name`) as `frags` FROM `players` p LEFT JOIN `player_deaths` pd ON `pd`.`killed_by` = `p`.`name` LEFT JOIN `guild_membership` gm ON `p`.`id` = `gm`.`player_id` LEFT JOIN `guilds` g ON `gm`.`guild_id` = `g`.`id` WHERE `pd`.`unjustified` = 1 GROUP BY `name` ORDER BY `frags` DESC, `name` ASC LIMIT 0, 3;');
 $cache->setContent($guilds);
 $cache->save();
} else {
 $guilds = $cache->load();
}
if (!empty($guilds) || !$guilds) {
 $divsize = 300;

?>

<!-- guilds part start-->
<div>
    <section class="pricing_part">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_tittle text-center">
                        <h3>Most powerful guilds</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php
                    $number = 1;
                    foreach ($guilds as $guild) {?> 
                    <div class="col-lg-3 col-sm-4">
                        <div class="single_pricing_part">
                        <img src="/layout/assets/img/medals/<?php echo $number; ?>.png" alt="" class="img-fluid">
                            <h4><?php echo $guild['name']; ?></h4>
                            <ul>
                                <li>Kills: <?php echo $guild['frags']; ?></li>
                            </ul>
                            <a href="guilds.php?name=<?php echo $guild['name']; ?>" class="btn_2">View Guild</a>
                        </div>
                    </div>
                        <?php
                    $number++;
                }?> 
            </div>
            <a href="#news" class="btn_1">Latest News <i class="fas fa-chevron-down"></i></a>
        </div>
    </section>
</div>
<!-- guilds part end-->
   
<?php } // End powerful guilds