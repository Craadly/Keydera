<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url = load_class('Config')->config['base_url'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Database Error - Keydera</title>
	<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/material.css" />
	<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/custom.css" />
</head>
<body>
	<div class="container main_body">  
		<section class="section level-items m-t-xxl p-t-xxl">
			<h1 class="title p-b-sm">
				<?php echo $heading; ?>
			</h1>
			<p class="p-b-sm"><?php echo $message; ?></p>
			<br>
			<p class="p-b-sm"><b>Stuck on this page? kindly contact support.</b></p>
		</section>
	</div>
</body>
</html>
