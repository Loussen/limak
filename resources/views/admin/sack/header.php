<?php
include("config.php");
include("func/functions.php");

function require_auth() {
    $AUTH_USER = ['user1', 'user2', 'fatura'];
    $AUTH_PASS = 'mvrtr_password';
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
        !$has_supplied_credentials ||
        !in_array($_SERVER['PHP_AUTH_USER'], $AUTH_USER) ||
        $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
    );
    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        exit;
    }else{
        session_start();
        $_SESSION['user'] = $_SERVER['PHP_AUTH_USER'];
    }
}
//require_auth();
$mission_in_money = getMoney($database,6);
?>
<html>
	<head>	
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">	
	<link rel="stylesheet" href="assets/css/font-awesome.css">	
	<link rel="stylesheet" href="assets/css/style.css">	
	<link rel="stylesheet" href="assets/css/animate.css">	
	<meta name='viewport' content='width=device-width, user-scalable=no' />	
	<meta name='format-detection' content='telephone=yes'>	
	<script src="assets/js/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
		<title>Mover TR</title>
	</head>
	<body>
		<?
		if ($_SESSION['user'] == "fatura" ) {


		
		} else {
	
		
		?>
		<div class="navbar navbar-inverse navbar-static-top shadow" id="navbar-header">
		<div class="container">
			<div class="navbar-header">
				<a href="index.php" class="navbar-brand">Mover TR</a>
				<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse navHeaderCollapse pull-right">
				<ul id="cats" class="nav menu navbar-nav navbar-left">
                    <li class="menu"><a href="faktura.php">Bəyənnamə</a></li>
                    <li class="menu"><a href="faturalar.php">FATURALAR</a></li>
					<li class="menu"><a href="cuvallar.php">ÇUVALLAR</a></li>
					<li class="menu"><a href="cuval_list.php">GÖNDERİLEN ÇUVALLAR</a></li>
					<li class="menu"><a href="print.php">PRINT</a></li>
					<li class="menu"><a href="addproducttypes.php">ÜRÜN TİPİ EKLE</a></li>
					<li class="menu"><a class="btn btn-success trbalance" href="#"> TR Balance : <span class="priceMission"><?=$mission_in_money['hesab_mebleg'].'</span> '.$mission_in_money['hesab_valyuta']?> </a></li>
				</ul>
			</div>
		</div>
		</div>
<?
}
?>