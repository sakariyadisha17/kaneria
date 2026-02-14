<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<title><?php echo $__env->yieldContent('title'); ?> | Panel</title>
		<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
		<link rel="apple-touch-icon" href="<?php echo e(url('/app-assets/images/ico/apple-icon-120.png')); ?>">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo e(url('/app-assets/images/ico/favicon.ico')); ?>">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/vendors/css/vendors.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/bootstrap.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/bootstrap-extended.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/colors.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/components.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/core/menu/menu-types/vertical-menu-modern.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/core/colors/palette-gradient.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/fonts/simple-line-icons/style.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/pages/card-statistics.min.css')); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo e(url('/app-assets/css/pages/vertical-timeline.min.css')); ?>">
		<link rel="icon" type="image/x-icon" href="<?php echo e(url('/app-assets/images/logo/stack-logo.png')); ?>">

		
		<style type="text/css">
			.dataTables_wrapper .col-sm-12
			{
				overflow-x: auto; 
			}
			.custom-select-sm{
				font-size: 15px;
			}
			.text-right{
				margin-bottom: 1.5rem !important;
			}
		</style>
		
		<?php echo $__env->yieldContent('additionalcss'); ?>
		<?php echo $__env->yieldContent('pagecss'); ?>
	</head>
	<!-- <body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar pace-running pace-running menu-expanded" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns"> -->
	<body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar pace-running pace-running menu-expanded" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns"><?php /**PATH D:\wamp64\www\old_project\kaneria\resources\views/layouts/header.blade.php ENDPATH**/ ?>