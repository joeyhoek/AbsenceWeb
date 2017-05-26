 <?php header("Content-Type: text/html; charset=utf-8"); ?>

<html>
 	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Absence | <?php echo $pageTitle; ?></title>
		<link rel="stylesheet" href="<?php echo PROTOCOL . DOMAIN . ROOT; ?>view/css/style.css?v=<?php echo (rand(1,999)); ?>">
		<link rel="stylesheet" href="<?php echo PROTOCOL . DOMAIN . ROOT; ?>view/css/shake.css?v=<?php echo (rand(1,999)); ?>">
		<link rel="stylesheet" href="<?php echo PROTOCOL . DOMAIN . ROOT; ?>view/css/reset.css?v=<?php echo (rand(1,999)); ?>">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="<?php echo PROTOCOL . DOMAIN . ROOT; ?>view/js/jquery-2.1.1.js?v=455434"></script>
		<script src="<?php echo PROTOCOL . DOMAIN . ROOT; ?>view/js/jquery.nicescroll.js?v=45"></script>
		<script>
			$(document).ready(function() {  
				$("main").niceScroll({
					cursorcolor: "#015679",
					horizrailenabled: false
				});
				
				$(".horizontalMenu").niceScroll({
					cursorcolor: "#015679",
					cursorborder: "0px solid #015679"
				});
			});
		</script>
		<meta name="theme-color" content="#464648">
	</head>
	
	<body>