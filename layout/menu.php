<div class="container">
    <div class="row align-items-center">
        <div class="col-lg-12">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="index.php"> <img src="/layout/assets/img/logo.png" alt="logo"> </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu_icon"><i class="fas fa-bars"></i></span>
                </button>

                <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="serverinfo.php">Server Info</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="blog.html" id="navbarDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Community
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="market.php">Item Market</a>
                                <a class="dropdown-item" href="gallery.php">Gallery</a>
                                <a class="dropdown-item" href="support.php">Support</a>
                                <a class="dropdown-item" href="helpdesk.php">Helpdesk</a>
                                <a class="dropdown-item" href="killers.php">Killers</a>
                                <a class="dropdown-item" href="spells.php">Spells</a>
                                <?php if ($config['items'] == true) { ?>
                                <a class="dropdown-item" href="items.php">Items</a>
                                <?php } ?>
                                <a class="dropdown-item" href="forum.php">Forum</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="shop.php" id="navbarDropdown1"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Shop
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="buypoints.php">Buy Points</a>
                                <a class="dropdown-item" href="shop.php">Shop Offers</a>
                                <?php if ($config['shop_auction']['characterAuction']): ?>
                                <a class="dropdown-item" href="auctionChar.php">Character Auction</a>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="guilds.php" id="navbarDropdown1"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Guilds
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                                <a class="dropdown-item" href="guilds.php">Guild List</a>
                                <?php if ($config['guildwar_enabled'] === true) { ?>
                                    <a class="dropdown-item" href="guildwar.php">Guild Wars</a>
                                <?php } ?>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="changelog.php">Changelog</a>
                        </li>
                    </ul>
                </div>
                <a href="downloads.php" class="btn_1 d-none d-sm-block">Download Now</a>
            </nav>
        </div>
    </div>
</div>