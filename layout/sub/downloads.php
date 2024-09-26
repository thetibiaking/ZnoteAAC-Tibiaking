<article class="blog_item">
	<div class="blog_details">
  <h1 class="display-4">Downloads</h1>
  <p class="lead">In order to play, you need an compatible IP changer and a Tibia client.</p>
  <hr class="my-4">
  <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Component</th>
      <th scope="col">Link</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Otland IP changer</td>
      <td><a class="btn btn-primary" href="https://static0.otland.net/ipchanger.exe" role="button"><i class="fas fa-cloud-download-alt"></i> Download</a></td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Windows Tibia Client [<?php echo ($config['client'] / 100); ?>]</td>
      <td><a class="btn btn-primary" href="<?php echo $config['client_download']; ?>" role="button"><i class="fab fa-windows"></i> Download</a></td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Linux Tibia Client [<?php echo ($config['client'] / 100); ?>]</td>
      <td><a class="btn btn-primary" href="<?php echo $config['client_download_linux']; ?>" role="button"><i class="fab fa-linux"></i> Download</a></td>
    </tr>
  </tbody>
</table>

<hr>

<h3>How to connect and play:</h3>
<ul class="list-group">
  <li class="list-group-item"><a href="<?php echo $config['client_download']; ?>">Download</a> and install the tibia client if you havent already.</li>
  <li class="list-group-item"><a href="https://static0.otland.net/ipchanger.exe">Download</a> and run the IP changer.</li>
  <li class="list-group-item">In the IP changer, write this in the IP field: <?php echo $_SERVER['SERVER_NAME']; ?></li>
  <li class="list-group-item">In the IP changer, click on <strong>Settings</strong> and then <strong>Add new Tibia client.</strong></li>
  <li class="list-group-item">In the IP changer, in the Version field, write your desired version.</li>
  <li class="list-group-item">In the IP changer, click on <strong>Browse</strong>, navigate to your desired Tibia version folder, select Tibia.exe and click <strong>Add</strong>. Then click <strong>Close</strong></li>
  <li class="list-group-item">In the IP changer, click on <strong>Browse</strong>, navigate to your desired Tibia version folder, select Tibia.exe and click <strong>Add</strong>. Then click <strong>Close</strong></li>
  <li class="list-group-item">Now you can successfully login on the tibia client and play clicking on <strong>Apply</strong> every time you want.<br></li>
  <li class="list-group-item">If you do not have an account to login with, you need to register an account <a href="register.php">HERE</a>.</li>
</ul>
</div>
</article>