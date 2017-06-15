<?php

	if(!$_POST['venueId']) {
		echo 'You must select a venue';
		die;
	}
	
	function curl_post($url, array $post = NULL, array $options = array()) 
	{ 
		$defaults = array( 
			CURLOPT_POST => 1, 
			CURLOPT_HEADER => 0, 
			CURLOPT_URL => $url, 
			CURLOPT_FRESH_CONNECT => 1, 
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_FORBID_REUSE => 1, 
			CURLOPT_TIMEOUT => 4, 
			CURLOPT_POSTFIELDS => http_build_query($post) 
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
	$url = 'https://api.foursquare.com/v2/checkins/add?oauth_token=' . $_POST['access_token'];

	$data = curl_post($url, $_POST);
	$data = json_decode($data);
	
	//set the cookies for the display list
	$vid = $data->response->checkin->venue->id;
	$vname = $data->response->checkin->venue->name;
	
	$venues = unserialize($_COOKIE['checkins']);
	if(!isset($venues)) {
		$venues = array();
	}
	
	if(isset($venues[$vid])) {
		$venues[$vid]['count']++;
	} else {
		$venues[$vid]['count'] = 1;
		$venues[$vid]['name'] = $vname;
	}
	
	setcookie('checkins', serialize($venues), time()+(3600*365), '/');	
?>
<html>
<body>
	<?php 
		foreach($data->notifications as $note) {
			if($note->type == "message" || $note->type == "mayorship" || $note->type == "leaderboard") {
				echo $note->item->message . '<br /><br />';
			}
			
			if($note->type == "score") {
				foreach($note->item->scores as $scoreMsg) {
					echo $scoreMsg->message . ': +' . $scoreMsg->points . '<br /><br />';
				}
				echo 'Total: ' . $note->item->total;
			}			
		}
	?>
</body>
</html>
