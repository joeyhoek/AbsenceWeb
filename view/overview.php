<style>
	html, body {
		width: 100%;
		margin: 0;
		padding: 0;
	}

	#searchBar {
		width: 100%;
		-webkit-box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.3);
		-moz-box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.3);
		box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.3);
		height: 54px;
	}

	#searchBar #filter {
		width: 54px;
		height: 54px;
		background-color: #464648;
		float: left;
		margin: 0;
		transition: all 0.3s;
	}

	#searchBar #filter:hover {
		background-color: #606060;
		cursor: pointer;
	}

	#searchBar #filter:focus, #searchBar #filter:active {
		background-color: #303030;
		cursor: pointer;
	}

	#searchBar #filter i {
		color: #ffffff;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		position: relative;
		font-size: 34px;
	}

	#searchBar #searchBox {
		outline: 0;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		width: calc(100% - 108px);
		max-width: calc(100% - 108px);
		padding: 16px 30px 16px 30px;
		margin: 0;
		float: left;
		font-size: 19px;
		color: #464648;
		border: none;
		background-color: #f9fcfd;
		z-index: 2;
		position: relative;
	}
	
	#searchBar #results {
		outline: 0;
		font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
		width: calc(100% - 108px);
		max-width: calc(100% - 108px);
		margin: 0 auto;
		font-size: 19px;
		color: #464648;
		border: none;
		background-color: #ffffff;
		display: block;
		margin-left: 54px;
		float: left;
		-webkit-box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.2);
		-moz-box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.2);
		box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.2);
	}

	#searchBar #searchButton {
		width: 54px;
		height: 54px;
		background-color: #015679;
		float: left;
		margin: 0;
		transition: all 0.3s;
	}

	#searchBar #searchButton:hover {
		background-color: #0ba2dd;
		cursor: pointer;
	}

	#searchBar #searchButton:active, #searchBar #searchButton:focus {
		background-color: #464648;
		cursor: pointer;
	}

	#searchBar #searchButton i {
		color: #ffffff;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		position: relative;
		font-size: 34px;
	}
	
	#searchBar .fa {
		font-family: FontAwesome !important;
		margin-left: -23px;
	}
	
	#searchBar #results .result {
		text-align: right;
		padding: 16px 30px 15px 30px;
		border: 1px solid #e1e1e1;
	}
	
	#searchBar #results .result:hover {
		background-color: #f5f6f7;
	}
	
	#searchBar #results .result:focus, #searchBar #results .result:active {
		background-color: #dadada;
	}
	
	#searchBar #results a .result i  {
		float: left;
		font-style: italic;
	}
	
	#searchBar #results a .result {
		color: #767676;
		font-size: 16px;
	}
	
	#searchBar #results a:visited .result {
		color: #767676;
	}
	
	#searchBar #results .result:not(.result-0) {
		border-top: none;
	}
</style>
<div id="searchBar">
	<div id="filter">
		<i class="fa fa-sliders" aria-hidden="true"></i>
	</div>
	<input id="searchBox" type="text" placeholder="Search..." />
	<div id="searchButton" onclick="search();">
		<i class="fa fa-search" aria-hidden="true"></i>
	</div>
	<div id="results">

	</div>
</div>

<script type="text/javascript">			
	function search() {
		var search = document.getElementById("searchBox").value;
		if (search == "" || search == null) {
			document.getElementById("results").innerHTML = "";
		} else {
			var http = new XMLHttpRequest();
			var params = "search=" + search;

			http.open("POST", "<?php echo PROTOCOL . DOMAIN . ROOT; ?>webClient", true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			http.onreadystatechange = function() {
				if(http.readyState == 4 && http.status == 200) {
					var results = JSON.parse(this.responseText);
					var html = "";
					var totalCount = 0;
					
					if (typeof results.students != "undefined") {
						var count = 0;
						for (var result in results.students) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=students&id=" + results.students[result].id + "'><div class='result result-" + totalCount + "'><i>(" + results.students[result].id + ")</i> " + results.students[result].firstname + " " + results.students[result].lastname + "</div></a>";
							totalCount++;
						}
					}

					if (typeof results.teachers != "undefined") {
						var count = 0;
						for (var result in results.teachers) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=teachers&id=" + results.teachers[result].id + "'><div class='result result-" + totalCount + "'><i>(" + results.teachers[result].id + ")</i> " + results.teachers[result].firstname + " " + results.teachers[result].lastname + "</div></a>";
							totalCount++;
						}
					}

					if (typeof results.courses != "undefined") {
						var count = 0;
						for (var result in results.courses) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=courses&id=" + results.courses[result].id + "'><div class='result result-" + totalCount + "'><i>(" + results.courses[result].code + ")</i> " + results.courses[result].name + "</div></a>";
							totalCount++;
						}
					}

					if (typeof results.classes != "undefined") {
						var count = 0;
						for (var result in results.classes) {
							if (count >= 3) {
								continue;
							}
							count++;
							html = html + "<a href='/overview?type=class&id=" + results.classes[result].id + "'><div class='result result-" + totalCount + "'>" + results.classes[result].code + "</div></a>";
							totalCount++;
						}
					}

					document.getElementById("results").innerHTML = html;
				} 
			};
			http.send(params);
		}
	}

	document.getElementById("searchBox").onkeyup = function () {
		search();
	};
</script>