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
	
	
	$url = 'https://api.foursquare.com/v2/checkins/recent?oauth_token=' . $_GET['access_token'] . '&v=20120126';

	$data = curl_get($url);
	$data = json_decode($data);
?>
<html>
<body>
	<?php 
		foreach($data->response->recent as $checkin) {
			echo '<a href="viewCheckin.php?access_token='.$_GET['access_token'].'&checkinId='.$checkin->id.'">';
			echo '<img src="'.$checkin->user->photo.'" height="50" width="50" />';
			echo $checkin->user->firstName . ' ' . $checkin->user->lastName . '<br />';
			echo '</a>';
			echo '<a href="checkin.php?access_token='.$_GET['access_token'].'&venueId='.$checkin->venue->id.'">'.$checkin->venue->name.'</a> <br />';
			if($checkin->shout) {
				echo '"'.$checkin->shout.'" <br />';
			}
			if($checkin->isMayor) {
				echo '<i>Mayor</i><br />';
			}
			
			$time = date("F j, Y g:i a", $checkin->createdAt);
			echo $time . '<br />';			
						
			echo 'Comments: ' . $checkin->comments->count . '<br />';
			
			echo '<br />';
		}
	?>
</body>
</html>
