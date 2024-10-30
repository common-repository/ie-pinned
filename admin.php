<?php
	define('WP_USE_THEMES', false);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $loc = str_replace("wp-content\\plugins\\ie-pinned", "", dirname(__FILE__));
    } else {
        $loc = str_replace("wp-content/plugins/ie-pinned", "", dirname(__FILE__));
    }
    require($loc . 'wp-load.php');	
	$job = ($_POST['job'] ? $_POST['job'] : $_GET['job']);
	switch($job) {
		case "list":
			if(update_option('ie_pinned_cats', $_POST['arr'])) {
				echo "List updated!";
			} else {
				echo "List unable to update";
			}
		break;
	}
?>