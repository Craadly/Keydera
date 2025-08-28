<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url = load_class('Config')->config['base_url'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Page not found - Keydera</title>
	<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/material.css" />
	<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/custom.css" />
</head>
<body>
<<<<<<< HEAD
	<div class="main-content main-content-expanded">  
		<section class="section">
			<div class="page-header"><h1 class="page-title"><?php echo $heading; ?></h1></div>
			<div class="settings-card">
				<div class="form-section">
					<p class="p-b-sm"><?php echo $message; ?></p>
				</div>
			</div>
=======
	<div class="container main_body">  
		<section class="section level-items m-t-xxl p-t-xxl">
			<h1 class="title p-b-sm">
				<?php echo $heading; ?>
			</h1>
			<p class="p-b-sm"><?php echo $message; ?></p>
>>>>>>> origin/main
		</section>
	</div>
</body>
</html>
