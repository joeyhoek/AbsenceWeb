

	var close = document.getElementsByClassName("closebtn");
	var i;

	for (i = 0; i < close.length; i++) {
		close[i].onclick = function(){
			var div = this.parentElement;
			div.style.opacity = "0";
			setTimeout(function(){ div.style.display = "none"; }, 600);
		}
	}

var login = document.querySelector(".button").addEventListener("click", function() {
	document.querySelector(".alert").style.opacity = "1";
});