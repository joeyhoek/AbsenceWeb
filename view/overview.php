<style>
	html, body {
		width: 100%;
		margin: 0;
		padding: 0;
	}

	main {
		width: calc(100% - 40px);
		max-width: 1280px;
		padding: 20px;
		margin: -70px auto 0;
		display: block;
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

	#searchBar input {
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
	
	.list {
		list-style-type: square;
		text-align: left;
		-webkit-column-count: 3; /* Chrome, Safari, Opera */
		-moz-column-count: 3; /* Firefox */
		column-count: 3;
	}
	
	.list li i {
		font-style: italic !important;
		text-align: left;
	}
</style>
<main>
	<div id="searchBar">
		<div id="filter">
			<i class="fa fa-sliders" aria-hidden="true"></i>
		</div>
		<input id="searchBox" type="text" placeholder="Search..." />
		<div id="searchButton" onclick="search();">
			<i class="fa fa-search" aria-hidden="true"></i>
		</div>
	</div>

	<div id="results">

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
						
						if (typeof results.students != "undefined") {
							html = html + "<h1><strong>Students</strong></h1><ul class='list'>";
							for (var result in results.students) {
								html = html + "<li><i>(" + results.students[result].id + ")</i> " + results.students[result].firstname + " " + results.students[result].lastname + "</li>";
							}
							html = html + "</ul>";
						}
						
						if (typeof results.teachers != "undefined") {
							html = html + "<h1><strong>Teachers</strong></h1><ul class='list'>";
							for (var result in results.teachers) {
								html = html + "<li><i>(" + results.teachers[result].id + ")</i> " + results.teachers[result].firstname + " " + results.teachers[result].lastname + "</li>";
							}
							html = html + "</ul>";
						}
						
						if (typeof results.courses != "undefined") {
							html = html + "<h1><strong>Courses</strong></h1><ul class='list'>";
							for (var result in results.courses) {
								html = html + "<li><i>(" + results.courses[result].code + ")</i> " + results.courses[result].name + "</li>";
							}
							html = html + "</ul>";
						}
						
						if (typeof results.classes != "undefined") {
							html = html + "<h1><strong>Classes</strong></h1><ul class='list'>";
							for (var result in results.classes) {
								html = html + "<li>" + results.classes[result].code + "</li>";
							}
							html = html + "</ul>";
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
</main>