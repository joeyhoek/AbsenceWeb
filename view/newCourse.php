<div class="container">


	<div class="container2 container3">
		<div id="column-left2">
			<div class="importCourse">Course imported from schedule:</div><br /><br />
			<div class="course">Nederlands</div>
			<img class="start" src="view/images/start.png">
			<div class="startCourse">Start Course</div>
		
		</div>

		<div id="column-right">
			<div id="container-right">
				<div id="containerRightNewCourse">
					Find or create a course:
					<div id="searchBar">
						<div id="filter">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</div>
						<input id="searchBox" type="text" placeholder="Search..." />
							<div id="searchButton" onclick="search();">
								<i class="fa fa-search" aria-hidden="true"></i>
							</div>
							<div id="results">

							</div>
					</div>
				
				</div>
			</div>

		</div>	
			<div id="helper2">
				<img class="lijn" src="view/images/lijn.png"></img>
			</div>
			
	</div>
</div>


<script>
	function search() {
		var search = document.getElementById("searchBox").value;
		if (search == "" || search == null) {
			document.getElementById("results").innerHTML = "";
		} else {
			var http = new XMLHttpRequest();
			var params = "search=" + search + "&type=courses";

			http.open("POST", "<?php echo PROTOCOL . DOMAIN . ROOT; ?>webClient", true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			http.onreadystatechange = function() {
				if(http.readyState == 4 && http.status == 200) {
					var results = JSON.parse(this.responseText);
					var html = "";
					var totalCount = 0;
					
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


	