<style>

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