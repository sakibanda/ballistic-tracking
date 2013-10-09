<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
		<title>Ballistic Tracking - <?php echo $title; ?></title>
		<link rel="stylesheet" type="text/css" href="/theme/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="/theme/css/font-awesome-ie7.min.css" />
		<link rel="stylesheet" type="text/css" href="/theme/css/jquery-ui/smoothness/jquery-ui-1.10.3.custom.css" />
		<link rel="stylesheet" type="text/css" href="/theme/css/grid.css" />
		<link rel="stylesheet" type="text/css" href="/theme/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/theme/css/datatable.css" />
        <link rel="stylesheet" type="text/css" href="/theme/css/TableTools.css" />
        <script src="/theme/js/vendor/jquery-1.10.2.min.js"></script>
        <script src="/theme/js/vendor/jquery.validate.min.js"></script>
        <script src="/theme/js/vendor/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="/theme/js/vendor/jquery.dataTables.min.js"></script>
        <script src="/theme/js/vendor/TableTools.min.js"></script>
        <script src="/theme/js/vendor/ZeroClipboard.js"></script>
        <script src="/theme/js/jquery.tipsy.js"></script>
        <script src="/theme/js/app.js"></script>
        <script src="/theme/js/global.js"></script>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="header_content">
					<h1 id="logo">Ballistic Tracking</h1>
					
					<div id="welcome_header">
						Welcome, <?php echo BTAuth::user()->user_name; ?>
					</div>
					
					<ul id="header_navmenu">
						<li><a href="/profile"><span class="icon icon-wrench"></span> Profile</a></li>
						<li><a href="/admin"><span class="icon icon-user"></span> Admin</a></li>
						<li class="logout"><a href="/logout">Logout</a></li>
					</ul>
				</div>
			</div>
			
			<div id="main_navmenu_wrap">
				<div id="main_navmenu">
					<?php $navmenu->render(); ?>
			
					<div class="clear"></div>
				</div>
			</div>
			
			<div id="body">
				<div id="content" class="container_12">