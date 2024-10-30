<?php
	//Uninstall 
	function ie_pinned_uninstall() {
		remove_option("ie_pinned_db_version");
		remove_option("ie_pinned_post_show");
		remove_option("ie_pinned_post_amount");
		remove_option("ie_pinned_post_title");
		remove_option("ie_pinned_show_cat");
		remove_option("ie_pinned_show_menu");
		remove_option("ie_pinned_show_login");		
		remove_option("ie_pinned_name");
		remove_option("ie_pinned_tooltip");
		remove_option("ie_pinned_window");
		remove_option("ie_pinned_navbutton");
		remove_option("ie_pinned_start");
	}
	ie_pinned_uninstall();
?>