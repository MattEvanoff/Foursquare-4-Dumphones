<html>
<body>

	<form name="searchForm" action="venues.php" method="get">
		<input type="hidden" name="access_token" value="<?php echo $_GET['access_token']; ?>" />
		Search: <input type="text" name="search" />	
		<input type="hidden" name="ll" value="35.600945,-82.554015" id="ll" />	
		<input type="submit" value="Search" />		
	</form>	
	<div id="locationMessage">This browser does not support geolocation, defaulting to Asheville, NC</div>

	
	<script type="text/javascript">
		var locationFoundBool = false;
		var positioningBool = false;
		
		function locationFound(Geoposition) {
			if(!locationFoundBool) {
				locationFoundBool = true;
				document.getElementById('locationMessage').innerHTML = Geoposition.coords.latitude + ',' + Geoposition.coords.longitude;				
				document.getElementById('ll').value = Geoposition.coords.latitude + ',' + Geoposition.coords.longitude;				
			}
		}		
		
		function locationError(Geoposition) {
			if(!locationFoundBool) {
				document.getElementById('locationMessage').innerHTML = 'Could not get location.';
			}
		}		
		

		if(navigator && navigator.geolocation) {
			if(navigator.geolocation.getCurrentPosition) {
				positioningBool = true;
				navigator.geolocation.watchPosition(locationFound, locationError, {
					enableHighAccuracy: true,
					maximumAge: 5000,
					timeout: 10000
				});
			}

			if(navigator.geolocation.getCurrentPosition) {
				positioningBool = true;
				navigator.geolocation.getCurrentPosition(locationFound, locationError, {
					enableHighAccuracy: true,
					maximumAge: 5000,
					timeout: 10000
				});
			}
			
			if(positioningBool) {
				document.getElementById('locationMessage').innerHTML = 'Trying to get your location...';
			}
		}

	</script>
	
</body>
</html>