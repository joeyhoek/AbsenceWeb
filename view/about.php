<style>
	i.fa {
		font-family: FontAwesome !important;
	}
	
	.aboutLeft {
		width: 80%;
		margin: 0;
		padding: 0;
		float: left;
	}
	
	.aboutRight {
		width: 20%;
		margin: 0;
		padding: 0;
		float: left;
	}
	/* als het scherm  groter is dan 779 px gebeurt het volgende*/
	
	@media only screen and (min-width: 779px) {
		.fa-windows,
		.fa-apple,
		.fa-android {
			font-size: 50px !important;
		}
		p.team {
			font-size: 20px !important;
		}
		p.downloadTitle {
			font-size: 20px !important;
		}
		p.par1,
		p.par2,
		p.par3,
		p.downloadLink,
		p.logoTekst {
			font-size: 16px !important;
		}
	}
	/* als het scherm kleiner is dan 779 px gebeurt het volgende */
	
	p.team {
		font-size: 16px;
	}
	
	
	p.logoTekst {
		font-size: 12px;
		color: #464648;
	}
	
	.fa-windows {
		font-size: 34px;
		color: #638CE6;
	}
	
	.fa-apple {
		font-size: 34px;
		color: grey;
	}
	
	.fa-android {
		font-size: 34px;
		color: green;
	}
	
	p.team {
		font-size: 16px;
		font-weight: bold;
		text-align: left;
		color: #464648;
	}
	
	p.par1,
	p.par2,
	p.par3 {
		text-align: left;
		color: #464648;
		font-size: 12px;
	}
	
	.downloadLink {
		margin-bottom: 10px;
		display: block;
		font-size: 12px;
	}
	
	.downloadTitle {
		font-size: 16px;
		font-weight: bold;
		color: #464648;
	}
	
	.downloadTitle,
	.team {
		margin-bottom: 20px;
	}
	
	.eulaLink {
		text-decoration: underline !important;
	}
</style>
<div class="aboutLeft">

	<p class="team">Team 10</p>

	<p class="par1">
		Hello there!<br><br>First of all, we would like to thank you for using Absence.
	</p><br>

	<p class="par2">
		This application was started as a project given by school, with the purpose of making present registration easier for the department ICT of Windesheim. The project has 12 teams in total, in which they work together and compete against each other as well! As for the winning team, their application will be made available to use. Isn’t that great? Well, since you are reading this, we must’ve won! Hurray! Here we would like to introduce ourselves:
	</p><br>

	<p class="par3">
		We proudly present you Team 10, consisting of the following members:<br><br>Ken Cheung - UX/UI Designer<br>Joey Hoek - Lead Developer<br>Trishul Manna - App Developer<br>Colin Small - Scrum Master<br> Racha Stapper - Product Owner<br>Jeffrey de Looper - Web Developer<br><br>By downloading or using this application, the End-User agrees to the <a href="#" class="eulaLink">EULA</a>.
	</p>
</div>

<div class="aboutRight">
	<p class="downloadTitle">Download</p><a href="#" class="downloadLink"><i class="fa fa-windows" aria-hidden="true"></i><p class="logoTekst">Windows</p></a>
	<a href="#" class="downloadLink"><i class="fa fa-apple" aria-hidden="true"></i><p class="logoTekst">OS X</p></a>
	<a href="#" class="downloadLink"><i class="fa fa-apple" aria-hidden="true"></i><p class="logoTekst">iOS</p></a>
	<a href="https://www.youtube.com/watch?v=cDLFrXMvOjs" class="downloadLink"><i class="fa fa-android" aria-hidden="true"></i><p class="logoTekst">Android</p></a>
</div>

<script>
</script>