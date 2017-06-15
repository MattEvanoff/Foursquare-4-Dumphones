<?php
	ini_set('display_errors','On'); 
	error_reporting(-1);

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
	$url = 'https://api.foursquare.com/v2/users/leaderboard?oauth_token=' . $_GET['access_token'] . '&v=20120126';
var_dump($url);
	$data = curl_post($url);
	$data = json_decode($data);
?>
<html>
<body>
	<?php var_dump($data);
		//foreach($data->response->leaderboard->items as $item) {
		//	echo $item->user->firstName . ' ' . $item->user->lastName . 'Score: ' . $item->scores->recent . ' 7 day max: ' . $item->scores->max . '<br /><br />';
		//}
	?>
</body>
</html>
