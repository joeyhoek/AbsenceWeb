<?php
	namespace Team10\Absence\View;
	use Team10\Absence\Model\User as User;
?>

<header class="cd-header">
	<a class="cd-primary-nav-trigger">
		<span class="cd-menu-icon"></span>
	</a> <!-- cd-primary-nav-trigger -->
 </header>

<nav>
	<ul class="cd-primary-nav">
		<div class="horizontalMenu">
			<li class="cd-label<?php if (!isset($_GET["url"])) { echo " menuActive"; } ?>"><a href="/"><i class=" faf fa fa-tachometer" aria-hidden="true"></i><figcaption>Dashboard</figcaption></a></li>
			<?php if ($userRole !== 1) { ?>
			<li class="cd-label<?php if (isset($_GET["url"]) && $_GET["url"] == "lesson") { echo " menuActive"; } ?>"><a href="/lesson"><i class="faf fa fa-users" aria-hidden="true"></i><figcaption>Lesson</figcaption></a></li>
			<?php } ?>
			<li class="cd-label<?php if (isset($_GET["url"]) && $_GET["url"] == "overview") { echo " menuActive"; } ?>"><a href="/overview"><i class="faf fa fa-bar-chart" aria-hidden="true"></i><figcaption>Overview</figcaption></a></li>
			<?php if ($userRole !== 4) { ?>
			<li class="cd-label<?php if (isset($_GET["url"]) && $_GET["url"] == "manage") { echo " menuActive"; } ?>"><a href="/manage"><i class="faf fa fa-pencil" aria-hidden="true"></i><figcaption>Manage</figcaption></a></li>
			<?php } ?>
			<div class="profile">
				<li class="cd-label<?php if (isset($_GET["url"]) && $_GET["url"] == "profile") { echo " menuActive"; } ?>"><a href="/profile"><i class="faf fa fa-user" aria-hidden="true"></i><figcaption><?php echo $user->getFirstname() . " " . $user->getLastname(); ?></figcaption></a></li>
				<a href="/?action=logout"><img class="imgs logout" src="view/images/logout.png"></a>
			</div>
		</div>
		<a href="/about"><i class="fa fa-question questionMark<?php if (isset($_GET["url"]) && $_GET["url"] == "about") { echo " menuActive"; } ?>"></i></a>
	</ul>
</nav>

<div class="header">

<div id="whiteSquare"></div>
<div id="whiteTriangle"></div>
	<div id="logo"><img class="logo logo2" src="view/images/logo.png"></div>
</div>

<main>