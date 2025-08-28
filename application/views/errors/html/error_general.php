<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url = load_class('Config')->config['base_url'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Error - Keydera</title>
	<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/material.css" />
	<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/custom.css" />
</head>
<body>
	<div class="main-content main-content-expanded">  
		<section class="section">
			<div class="page-header"><h1 class="page-title"><?php echo $heading; ?></h1></div>
			<div class="settings-card">
				<div class="form-section">
					<p class="p-b-sm"><?php echo $message; ?></p>
					<br>
					<p class="p-b-sm"><b>Stuck on this page? kindly contact support.</b></p>
				</div>
			</div>
		</section>
	</div>
</body>
</html>
