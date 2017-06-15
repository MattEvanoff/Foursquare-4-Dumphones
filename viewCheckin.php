<?php

	ini_set('error_reporting', E_ALL);
	error_reporting(-1);
	
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

	
	//if the user is commenting, submit the comment
	if(!empty($_POST)) {
		$commentUrl = 'https://api.foursquare.com/v2/checkins/'.$_POST['checkinId'].'/addcomment?oauth_token=' . $_GET['access_token'] . '&v=20120126';
		curl_post($commentUrl, $_POST);
	}
	
	
	$url = 'https://api.foursquare.com/v2/checkins/'.$_GET['checkinId'].'?oauth_token=' . $_GET['access_token'] . '&v=20120126';
	$data = curl_get($url);
	$data = json_decode($data);
	
?>
<html>
<body>
	<?php 
		echo $data->response->checkin->user->firstName . ' ' . $data->response->checkin->user->lastName . '<br />';
		echo $data->response->checkin->venue->name . '<br />';
		
		echo '<br /><b>Points:</b> <br />';
		foreach($data->response->checkin->score->scores as $score) {
			echo $score->message . ': +' . $score->points . '<br />';		
		}
		echo 'Total: ' . $data->response->checkin->score->total . '<br />';
		
		echo '<br /><b>Comments:</b> <br />';
		foreach($data->response->checkin->comments->items as $comment) {
			echo '<b>' . $comment->user->firstName . ' ' . $comment->user->lastName . '</b>: ' . $comment->text . '<br />';
		}
	?>
	
	<br /><br />
	<form name="commentForm" method="post">
		<input type="hidden" name="access_token" value="<?php echo $_GET['access_token']; ?>" />
		<input type="hidden" name="checkinId" value="<?php echo $_GET['checkinId']; ?>" />
		<input type="text" name="text" />
		<input type="submit" value="comment" />
	</form>
</body>
</html>
