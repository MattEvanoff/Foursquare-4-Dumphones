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
	
	
	$url = 'https://api.foursquare.com/v2/venues/'.$_GET['venueId'].'?oauth_token=' . $_GET['access_token'] . '&v=20120126';

	$data = curl_get($url);
	$data = json_decode($data);

?>
<html>
<body>
	<b>Name:</b> <?php echo $data->response->venue->name; ?>
	<br />
	
	<?php if($data->response->venue->location && $data->response->venue->location->address): ?>
		<b>Address:</b> <?php echo $data->response->venue->location->address; ?>
	<?php endif; ?>
	<br />
	
	<?php if($data->response->venue->mayor && $data->response->venue->mayor->user && $data->response->venue->mayor->user->firstName && $data->response->venue->mayor->user->lastName): ?>
		<b>Mayor:</b> <?php echo $data->response->venue->mayor->user->firstName .' '. $data->response->venue->mayor->user->lastName; ?>
	<?php endif; ?>	
	<br />

	<?php if($data->response->venue->hereNow->groups): ?>
		<?php foreach($data->response->venue->hereNow->groups as $group): ?>
			<?php echo '<b>' . $group->name . '</b>: ' . $group->count . '<br />'; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<br />	
	
	<form name="checkinForm" action="processCheckin.php" method="post">
		<input type="hidden" name="access_token" value="<?php echo $_GET['access_token']; ?>" />
		<input type="hidden" name="venueId" value="<?php echo $_GET['venueId']; ?>" />
		<input type="checkbox" name="broadcast" value="facebook" checked="checked" />Facebook?
		<br />
		Comment: <input type="text" name="shout" />
		<br />
		<input type="submit" value="Check in!" />				
	</form>	
	<br />
</body>
</html>