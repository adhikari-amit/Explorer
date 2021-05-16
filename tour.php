<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title>Waypoints in directions</title>
<style>
#right-panel {
	font-family: 'Roboto', 'sans-serif';
	line-height: 30px;
	padding-left: 10px;
}

#right-panel select, #right-panel input {
	font-size: 15px;
}

#right-panel select {
	width: 100%;
}

#right-panel i {
	font-size: 12px;
}

html, body {
	height: 100%;
	margin: 0;
	padding: 0;
}

#map {
	height: 100%;
	float: left;
	width: 70%;
	height: 100%;
}

#right-panel {
	margin: 20px;
	border-width: 2px;
	width: 20%;
	height: 400px;
	float: left;
	text-align: left;
	padding-top: 0;
}

#directions-panel {
	margin-top: 10px;
	background-color: #FFEE77;
	padding: 10px;
	overflow: scroll;
	height: 174px;
}
</style>
</head>
<body>
	<div id="map"></div>
	<div id="right-panel">
		<div>
			<b>Start:</b> <select id="start">
				<option value="KOLKATA, IND">KOLKATA</option>
				<option value="DELHI, IND">DELHI</option>
				<option value="CHENNAI, IND">CHENNAI</option>
				<option value="MUMBAI, IND">MUMBAI</option>
			</select> <br> <b>Waypoints:</b> <br> <i>(Ctrl+Click or
				Cmd+Click for multiple selection)</i> <br> <select multiple
				id="waypoints">
				<option value="Digha, IN">Digha</option>
				<option value="Nadia, IND">Nadia</option>
				<option value="Kalighat, IND">Kalighat</option>
				<option value="Odisha, IND">Odisha</option>
			</select> <br> <b>End:</b> <select id="end">
				<option value="KOLKATA, IND">KOLKATA</option>
				<option value="DELHI, IND">DELHI</option>
				<option value="CHENNAI, IND">CHENNAI</option>
				<option value="MUMBAI, IND">MUMBAI</option>
			</select> <br> <input type="submit" id="submit">
		</div>
		<div id="directions-panel"></div>
	</div>
	<script>
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

			document.getElementById('submit').addEventListener(
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
					waypts.push({
						location : new google.maps.LatLng(12.9716, 77.5946),
						stopover : true
					});
				}
			}

			directionsService.route({
				origin : new google.maps.LatLng(liveLat, liveLong),
				destination : new google.maps.LatLng(liveLat, liveLong),
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