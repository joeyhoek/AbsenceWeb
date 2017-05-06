window.onload = function () {

function shake() {
	var loginUsername = document.getElementsByClassName('username')[0];
	var loginPassword = document.getElementsByClassName('password')[0];
	var userIcon = document.getElementsByClassName('emailLogo')[0];
	var lockIcon = document.getElementsByClassName('passwordLogo')[0];
	loginUsername.classList.add("shake-horizontal");
	loginPassword.classList.add("shake-horizontal");
	loginUsername.classList.add("shake-constant");
	loginPassword.classList.add("shake-constant");
	userIcon.classList.add("shake-horizontal");
	lockIcon.classList.add("shake-horizontal");
	userIcon.classList.add("shake-constant");
	lockIcon.classList.add("shake-constant");
	window.setTimeout(function () {
		loginUsername.classList.remove("shake-horizontal");
		loginPassword.classList.remove("shake-horizontal");
		loginUsername.classList.remove("shake-constant");
		loginPassword.classList.remove("shake-constant");
		userIcon.classList.remove("shake-horizontal");
		lockIcon.classList.remove("shake-horizontal");
		userIcon.classList.remove("shake-constant");
		lockIcon.classList.remove("shake-constant");
	}, 400);
}

// Shake Animation Fixes
function shakeFix(delay) {
	//var loginUsername = document.getElementsByClassName('username')[0];
	//var loginPassword = document.getElementsByClassName('password')[0];
	var userIcon = document.getElementsByClassName('emailLogo')[0];
	var lockIcon = document.getElementsByClassName('passwordLogo')[0];

	setTimeout(function(){
		//loginUsername.classList.add("loaded");
		//loginPassword.classList.add("loaded");
		userIcon.classList.add("loaded");
		lockIcon.classList.add("loaded");
	}, delay);
}


	setTimeout(function(){
		document.getElementsByClassName('input')[0].classList.add("loaded");
		document.getElementsByClassName('input')[1].classList.add("loaded");
		shakeFix(0);
	}, 1500);
};
