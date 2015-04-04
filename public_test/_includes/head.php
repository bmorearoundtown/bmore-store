<!DOCTYPE html>

<html>

<head>

	<title><?= $GLOBALS['config']->getPageTitle() ?></title>
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no">
	
	<link rel="shortcut icon" href="<?= $GLOBALS['config']->getParam( 'favIcon' ) ?>" />
	
	<?php if( $isProduction || $isTest ){ ?>
	
		<link rel="stylesheet" href="/_assets/_css/compressed.css" />
	
	<?php } else { ?>
	
		<link rel="stylesheet" href="/_assets/_css/bootstrap.min.css" />
		<link rel="stylesheet" href="/_assets/_css/font-awesome.css" />
		<link rel="stylesheet" href="/_assets/_css/styles.css" />
		<link rel="stylesheet" href="/_assets/_css/owl.carousel.css" /> 
	
	<?php } ?>
	
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:300%7COswald:400%7CRoboto" />
	
	<!-- defined on a per module basis -->
	<?php foreach ($GLOBALS['config']->getAdditionalCSS() as $strCSS) { ?>
	
		<link rel="stylesheet" href="<?= $strCSS ?>" />
		
	<?php } ?>

	<!-- defined on a per module basis -->
	<?php foreach ($GLOBALS['config']->getAdditionalJS() as $strJs) { ?>
		
		<script src="<?= $strJs ?>"></script>
	
	<?php } ?>
	 
</head>

<body>
	
	<?php require_once( "systemMessenger.php" ); ?>
		