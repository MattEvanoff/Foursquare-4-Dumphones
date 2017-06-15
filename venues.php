<?php

	function curl_get($url, array $get = NULL, array $options = array()) 
	{    
		$defaults = array( 
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
			CURLOPT_HEADER => 0, 
			CURLOPT_RETURNTRANSFER => TRUE, 
			CURLOPT_TIMEOUT => 4 
		); 
				
		
		$ch = curl_init(); 
		curl_setopt_array($ch, ($options + $defaults)); 
		if( ! $result = curl_exec($ch)) 
		{ 
			trigger_error(curl_error($ch)); 
		} 
		curl_close($ch); 
		return $result; 
	} 
	
	$url = 'https://api.foursquare.com/v2/venues/search?ll='.$_GET['ll'].'&query='.urlencode($_GET['search']).'&oauth_token=' . $_GET['access_token'] . '&v=20120126';

	$data = curl_get($url);
	$data = json_decode($data);	
?>
<html>
<body>
	<?php 
		foreach($data->response->venues as $venue) {
			echo ' <a href="checkin.php?access_token='.$_GET['access_token'].'&venueId='.$venue->id.'">'.$venue->name.'</a> <br />';
			echo $venue->location->address . '<br /><br />';
		}
		
		if(count($data->response->venues) == 0) {
			echo 'No results found.';
		}
	?>
</body>
</html>
