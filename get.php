<?php
	define('WP_USE_THEMES', false);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $loc = str_replace("wp-content\\plugins\\ie-pinned", "", dirname(__FILE__));
    } else {
        $loc = str_replace("wp-content/plugins/ie-pinned", "", dirname(__FILE__));
    }
    require($loc . 'wp-load.php');	
	
	//echo $loc;
	
	if($_GET['job'] == "settings") {
		$posts = get_option('ie_pinned_post_show');
		$posts_title = get_option('ie_pinned_post_title');
		$menu = get_option('ie_pinned_show_menu');
		$cat = get_option('ie_pinned_show_cat');
		$login = get_option('ie_pinned_show_login');
		
		$return = array("post" => $posts, "post_title" => $posts_title, "menu" => $menu, "cat" => $cat, "login" => $login);
		echo json_encode($return);
	
	} else if($_GET['job'] == "posts") {
		if(get_option('ie_pinned_post_show') == true) {
			$amount = ($_GET['a'] == "" ? get_option('ie_pinned_post_amount') : $_GET['a']);
			$posts = get_posts('numberposts='.$amount.'&order=DESC&orderby=post_date');
			$arrData = array();
			$count = 0;
			foreach ($posts as $post) : start_wp();
				$title = the_title('','',false);
				$url = get_permalink($post->ID);
				$arrData[$count] = array("title" => $title, "url" => $url, "icon" => WP_PLUGIN_URL . "/ie-pinned/favicon.ico");
				$count ++;
			endforeach;
			echo json_encode($arrData);
		}
	} else if($_GET['job'] == "menu") {
	
	} else if($_GET['job'] == "cat") {
	
	} else if($_GET['job'] == "login") {
		if(get_option('ie_pinned_show_login') == true) {
				wp_login_url(); echo "," . WP_PLUGIN_URL . "/ie-pinned/favicon.ico|";
		}

	}
?>