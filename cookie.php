<?php

	$venues = unserialize($_COOKIE['checkins']);
	var_dump($venues);

	echo '<br /><br /><br /><br />';
	var_dump($_COOKIE);
	
	
	setcookie('checkins', serialize(array('234'=>array('sdf', 'qwe'))), time()+(3600*365), '/');	
?>