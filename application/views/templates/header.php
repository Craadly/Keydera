<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $title ?> - Keydera</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
<<<<<<< HEAD
	<meta name="theme-color" content="#6366f1">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/images/favicon-32x32.png" sizes="32x32"/>
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/images/favicon-16x16.png" sizes="16x16"/>
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/FontAwesome/css/all.min.css"/>
	
	<!-- Premium Design System -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/premium-design.css?v=<?php echo time() . rand(1000,9999); ?>" />

	<script>
	// Expose base URL, CSRF, and current route for external JS modules
	window.KD = {
	  baseUrl: '<?php echo base_url(); ?>',
	  csrf: { name: '<?php echo $this->security->get_csrf_token_name(); ?>', hash: '<?php echo $this->security->get_csrf_hash(); ?>' },
	  route: { cls: '<?php echo $this->router->fetch_class(); ?>', method: '<?php echo $this->router->fetch_method(); ?>' }
	};
	</script>
    
</head>
<body>
<div class="app-container">
	<?php $this->load->view('templates/sidebar'); ?>
	<?php $this->load->view('templates/topbar'); ?>
	<main class="app-main" id="mainContent" role="main">
=======
	<meta name="theme-color" content="<?php echo (strtolower(KEYDERA_THEME)=="classic")?"#4285f4":((strtolower(KEYDERA_THEME)=="flat")?"#34495e":"#3F51B5"); ?>">
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/images/favicon-32x32.png" sizes="32x32"/>
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/images/favicon-16x16.png" sizes="16x16"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo KEYDERA_THEME; ?>.css?v=lb152" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css?v=lb152" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/DataTables/css/datatables.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/DataTables/css/responsive.dataTables.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/dataTables.bulma.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/FontAwesome/css/all.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/JQueryDateTimePicker/jquery.datetimepicker.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/Select2/css/select2.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/Dropify/css/dropify.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/BulmaTagsInput/css/bulma-tagsinput.min.css"/>
</head>
<body>
>>>>>>> origin/main

