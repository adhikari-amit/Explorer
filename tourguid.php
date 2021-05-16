<html lang="en">
<head>
<meta charset="utf-8">
<title>Tour Guide</title>
<link rel="shortcut icon" href="/img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css"
	rel="stylesheet" id="bootstrap-css">
<style type="text/css">
.navbar {
	background-color: #03a9f4;
}

.alert {
	display: none;
}

#map {
	height: 100%;
	float: left;
	width: 70%;
	height: 100%;
}
</style>
<script src="https:////code.jquery.com/jquery-1.10.2.min.js"></script>
<script
	src="https:////maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</head>
<body style="">
	
	<div class="container content-div">
		<div class="row">
			<div class="row">
				<div id="map"></div>
			</div>
			<div class="row">
				<div id="directions-panel"></div>
			</div>
		</div>
	</div>

	<script>
		$(document).on("click", "#submit", function() {
			var params = {
				city : $("#city").val(),
				id : "1"
			};
			$.get("LocationServlet", $.param(params), function(responseText) {
				$('#start').find('option').remove();
				$('#waypoints').find('option').remove();
				$('#end').find('option').remove();
				var selectstart = $("#start");
				var selectwaypoints = $("#waypoints");
				var selectend = $("#end");
				$.each(responseText,function(index,json){
				  	selectstart.append($("<option></option>").attr("value", json.locationName).text(json.locationName).attr("data-long",json.longitude).attr("data-lat",json.latitude));
				  	selectwaypoints.append($("<option></option>").attr("value", json.locationName).text(json.locationName).attr("data-long",json.longitude).attr("data-lat",json.latitude));
				  	selectend.append($("<option></option>").attr("value", json.locationName).text(json.locationName).attr("data-long",json.longitude).attr("data-lat",json.latitude));
				}); 
			});
		});
		
		var liveLat, liveLong;
		function initMap() {
			var directionsService = new google.maps.DirectionsService;
			var directionsDisplay = new google.maps.DirectionsRenderer;
			var map = new google.maps.Map(document.getElementById('map'), {
				center : {
					lat : 17.3850,
					lng : 78.4867
				},
				zoom : 15,
				mapTypeId : google.maps.MapTypeId.ROADMAP
			});
			infoWindow = new google.maps.InfoWindow;
			// Try HTML5 geolocation.
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					var pos = {
						lat : position.coords.latitude,
						lng : position.coords.longitude
					};
					liveLat = position.coords.latitude;
					liveLong = position.coords.longitude;
					//infoWindow.setPosition(pos);
					//infoWindow.setContent('Location found.');
					infoWindow.open(map);
					console.log(pos);
					map.setCenter(pos);
				}, function() {
					handleLocationError(true, infoWindow, map.getCenter());
				});
			} else {
				// Browser doesn't support Geolocation
				handleLocationError(false, infoWindow, map.getCenter());
			}

			directionsDisplay.setMap(map);

			document.getElementById('loadmap').addEventListener(
					'click',
					function() {
						calculateAndDisplayRoute(directionsService,
								directionsDisplay);
					});
		}

		function handleLocationError(browserHasGeolocation, infoWindow, pos) {
			infoWindow.setPosition(pos);
			infoWindow
					.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.'
							: 'Error: Your browser doesn\'t support geolocation.');
			infoWindow.open(map);
		}

		function calculateAndDisplayRoute(directionsService, directionsDisplay) {
			var waypts = [];
			var checkboxArray = document.getElementById('waypoints');
			for (var i = 0; i < checkboxArray.length; i++) {
				if (checkboxArray.options[i].selected) {
					console.log($(checkboxArray[i]).attr("data-lat")+"---"+ $(checkboxArray[i]).attr("data-long"));
					waypts.push({
						location : new google.maps.LatLng($(checkboxArray[i]).attr("data-lat"), $(checkboxArray[i]).attr("data-long")),
						stopover : true
					});
				}
			}

			console.log($('option:selected', $("#start")).attr('data-lat'));
			directionsService.route({
				origin : new google.maps.LatLng($('option:selected', $("#start")).attr('data-lat'), $('option:selected', $("#start")).attr('data-long')),
				destination : new google.maps.LatLng($('option:selected', $("#end")).attr('data-lat'), $('option:selected', $("#end")).attr('data-long')),
				waypoints : waypts,
				optimizeWaypoints : true,
				travelMode : 'DRIVING'
			}, function(response, status) {
				if (status === 'OK') {
					directionsDisplay.setDirections(response);
					var route = response.routes[0];
					var summaryPanel = document
							.getElementById('directions-panel');
					summaryPanel.innerHTML = '';
					// For each route, display summary information.
					for (var i = 0; i < route.legs.length; i++) {
						var routeSegment = i + 1;
						summaryPanel.innerHTML += '<b>Route Segment: '
								+ routeSegment + '</b><br>';
						summaryPanel.innerHTML += route.legs[i].start_address
								+ ' to ';
						summaryPanel.innerHTML += route.legs[i].end_address
								+ '<br>';
						summaryPanel.innerHTML += route.legs[i].distance.text
								+ '<br><br>';
					}
				} else {
					window.alert('Directions request failed due to ' + status);
				}
			});
		}
	</script>
	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBAoKQLHah3tfC-Hq0ikM4np-LDwptN2Y&callback=initMap">
		
	</script>
</body>
</html>