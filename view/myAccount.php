<?php
	namespace Team10\Absence\View;
	use Team10\Absence\Model\User as User;
?>
<style>
	.fa {
		color: white;
		font-family: FontAwesome !important;
		font-size: 92px !important;
	}
	
	
	
</style>
<header class="cd-header">
	<a class="cd-primary-nav-trigger" href="#0">
		<span class="cd-menu-icon"></span>
	</a> <!-- cd-primary-nav-trigger -->
 </header>

<nav>
	<ul class="cd-primary-nav">
		<li class="cd-label"><a href="#"><i class="fa fa-tachometer" aria-hidden="true"></i><figcaption>Dashboard</figcaption></a></li>
		<li class="cd-label"><a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i><figcaption>Overview</figcaption></a></li>
		<li class="cd-label"><a href="#"><i class="fa fa-users" aria-hidden="true"></i><figcaption>Manage</figcaption></a></li>
		
		<div class="profile">
			<li class="cd-label"><a href="#"><i class="fa fa-user" aria-hidden="true"></i><figcaption><?php echo (new User($_SESSION["userId"]))->getFirstname() . " " . (new User($_SESSION["userId"]))->getLastname(); ?></figcaption></a></li>
			<a href="/?action=logout"><img class="imgs logout" src="view/images/logout.png"></a>
		</div>
		<a href="#"><img class="questionMark" src="view/images/questionMark.png"></a>
	</ul>

</nav>
