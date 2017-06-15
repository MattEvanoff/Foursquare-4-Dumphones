<?php

$url = 'https://foursquare.com/oauth2/access_token?client_id=CXQDVCLOTSNTYCZPURWHUIS3EFT2BXDFW1AXR5N1NY2FKYAG&client_secret=LUMMHXEUTJ4XSVNONSZULCIMTLWQGYDM5EQ0LTNMUQSZCIN3&grant_type=authorization_code&redirect_uri=http://www.mattevanoff.com/checkin/cb.php&code=' . $_GET['code'];

$handle = fopen($url, "r");

$data = fread($handle, 9999);

$access_token = json_decode($data);
$access_token = $access_token->access_token;

function venuesort($a, $b) {
	if($a['count'] == $b['count']) {
		return 0;
	}
	return ($a['count'] > $b['count']) ? -1 : 1;
}

$venues = $_COOKIE['checkins'];
if(!empty($venues)) {
	$venues = unserialize($_COOKIE['checkins']);
	uasort($venues, 'venuesort');
} else {
	$venues = false;
}

?>
<html>
<body>
	
	<form name="checkinForm" action="processCheckin.php" method="post">
		<input type="hidden" name="access_token" value="<?php echo $access_token; ?>" />
		<input type="checkbox" name="broadcast" value="facebook" checked="checked" />Facebook?
		<br />
		Comment: <input type="text" name="shout" />
		<br />
		
		<?php if($venues): ?>
			<?php $i = 0; ?>
			<?php foreach ($venues as $venueId => $venue): ?>			
				<?php if($i >= 5) { break; } ?>
				<input type="radio" name="venueId" value="<?php echo $venueId; ?>" /> <?php echo $venue['name']; ?>
				<br />
				<?php $i++; ?>
			<?php endforeach; ?>
		<?php else: ?>
			<input type="radio" name="venueId" value="4d72fe4f95bc8eec48946ef9" /> Avenue M
			<br />
			<input type="radio" name="venueId" value="4ceafdc30f196dcb320f55ae" /> Bywater
			<br />
			<input type="radio" name="venueId" value="4eec5aea0aaf1d45b09e4be4" /> Sam And Devin's
			<br />
			<input type="radio" name="venueId" value="4e962ce0775b4d5028fc77df" /> Matt's Pad
			<br />		
			<input type="radio" name="venueId" value="4bdc54772a3a0f473055b2b6" /> The Fresh Market
			<br />
			<input type="radio" name="venueId" value="4cdf4f963644a0931bdc519f" /> Papa John's
		<?php endif; ?>
		
		<br />		
		<input type="submit" value="Check in!" />				
	</form>	
	<a href="search.php?access_token=<?php echo $access_token; ?>">Search Venues</a>
	<br /><br />
	<a href="recentCheckins.php?access_token=<?php echo $access_token; ?>">Recent Checkins</a>
	<br /><br />
	<a href="leaderboard.php?access_token=<?php echo $access_token; ?>">View Leaderboard</a>
</body>
</html>