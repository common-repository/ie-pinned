<?php
/*
	Plugin Name: IE-Pinned
	Plugin URI: http://sam-benne.co.uk/_downloads/iepin
	Description: This allows you to have your blog posts pinned in IE.
	Version: 0.3.6 beta
	Author: Sam Bennett
	Author URI: http://www.sam-benne.co.uk
*/
?>
<?php
/*  Copyright 2011  Sam Bennett  (email : sam@newbz.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

	global $ie_pinned_table;
	global $ie_pinned_db_version;
	global $wpdb;
	$ie_pinned_table = $wpdb->prefix . 'ie_pinned';
	$ie_pinned_db_version = '0.3.6';
	
	register_activation_hook( __FILE__,  'ie_pinned_install' );
	
	add_action('wp_head', 'ie_pinned_add');
	add_action('admin_menu', 'ie_pinned_admin_menu');
	//Add the menu option
	function ie_pinned_admin_menu() {
		add_options_page('IE Pinned', 'IE Pinned', 9, basename(__FILE__), 'ie_pinned_settings');
	}
	//The install bit
	function ie_pinned_install() {
		global $wpdb;
		global $ie_pinned_table;
		global $ie_pinned_db_version;
					
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
			
		add_option("ie_pinned_db_version", $ie_pinned_db_version);
		add_option("ie_pinned_post_show", "true");
		add_option("ie_pinned_post_amount", "5");
		add_option("ie_pinned_post_title", "Latest Posts");
		add_option("ie_pinned_show_cat", "false");
		add_option("ie_pinned_show_menu", "true");
		add_option("ie_pinned_show_login", "false");		
		add_option("ie_pinned_name", get_bloginfo());
		add_option("ie_pinned_tooltip", get_bloginfo());
		add_option("ie_pinned_window", "1024x768");
		add_option("ie_pinned_navbutton", "#000000");
		add_option("ie_pinned_start", "./");
		add_option("ie_pinned_cats", "");

	}
	//The actual bit that will add the script
	function ie_pinned_add() {
		echo "<!-- IE PINNED SCRIPTS -->\n";
		?>
		<script type="text/javascript">
			jQuery.noConflict();
			jQuery(document).ready(function(){
				jQuery('head').pinned();
			});
		</script>
		<?php
		
		echo "<!-- IE PINNED META TAGS -->\n";
		if(get_option('ie_pinned_name')) {
			echo '<meta name="application-name" content="'.get_option('ie_pinned_name').'" />';
			echo "\n";
		}
		if(get_option('ie_pinned_tooltip')) {
			echo '<meta name="msapplication-tooltip" content="'.get_option('ie_pinned_tooltip').'" />';
			echo "\n";
		}
		if(get_option('ie_pinned_window')) {
			$data = explode("x", get_option('ie_pinned_window'));
			echo '<meta name="msapplication-window" content="width='.$data[0].';height='.$data[1].'" />';
			echo "\n";
		}
		if(get_option('ie_pinned_navbutton')) {
			echo '<meta name="msapplication-navbutton-color" content="'.get_option('ie_pinned_navbutton').'" />';
			echo "\n";
		}
		if(get_option('ie_pinned_start')) {
			echo '<meta name="msapplication-starturl" content="'.get_option('ie_pinned_start').'" />';
			echo "\n";
		}
		if(get_option('ie_pinned_show_menu') == "true") {
			echo '<meta name="msapplication-task-separator" content="sep1" />';
				//Loop through menu options here
			echo '<meta content="name=Home;action-uri=./;icon-uri=' . WP_PLUGIN_URL . '/ie-pinned/img/home.ico" name="msapplication-task" />';
			echo "\n";
		}
		if(get_option('ie_pinned_show_login') == "true") {
			echo '<meta name="msapplication-task-separator" content="sep3" />';echo "\n";
			echo '<meta content="name=Login;action-uri=./wp-login.php;icon-uri=' . WP_PLUGIN_URL . '/ie-pinned/img/home.ico" name="msapplication-task" />';
			echo "\n";
		}
		if(get_option('ie_pinned_show_cat') == "true") {
			echo '<meta name="msapplication-task-separator" content="sep2" />';
				//Loop through categories here
				$aCats = explode("|", get_option('ie_pinned_cats'));
				//Want
				$arrCats1 = explode(",", $aCats[0]);
				$num1 = count($arrCats1);
					for($i = 0; $i < $num1; $i++) {
						echo "\n";
						$cat = get_categories();
						foreach($cat as $c) {
							if($c->name == $arrCats1[$i]) {
								echo '<meta content="name='.$c->name.';action-uri='.get_category_link($c).';icon-uri=' . WP_PLUGIN_URL . '/ie-pinned/img/home.ico" name="msapplication-task" />';
							}
						}
					}
			echo "\n";
		}
		echo "<!-- END OF IE PINNED -->\n";
	}
	//Admin Functions
	function settings_update() {
		update_option('ie_pinned_post_title', $_POST['disp_title']);
		update_option('ie_pinned_post_amount', $_POST['post_amount']);
		
		update_option('ie_pinned_name', $_POST['name']);
		update_option('ie_pinned_tooltip', $_POST['tooltip']);
		update_option('ie_pinned_window', $_POST['width'] . "x" . $_POST['height']);
		update_option('ie_pinned_navbutton', $_POST['nav']);
		update_option('ie_pinned_start', $_POST['start']);
		
	
		if ($_POST['show_posts']=='on') { $opt = 'true'; } else { $opt = 'false'; }
			update_option('ie_pinned_post_show', $opt);
			
		if ($_POST['show_cat']=='on') { $opt = 'true'; } else { $opt = 'false'; }
			update_option('ie_pinned_show_cat', $opt);

		if ($_POST['show_menu']=='on') { $opt = 'true'; } else { $opt = 'false'; }
			update_option('ie_pinned_show_menu', $opt);

		if ($_POST['show_login']=='on') { $opt = 'true'; } else { $opt = 'false'; }
			update_option('ie_pinned_show_login', $opt);


	}
	function ie_pinned_settings() {
		if ( $_POST['update_ie_pinned_settings'] == 'true' ) { settings_update(); }
		//$op[] = get_option('ie_pinned');
		$op[title] = get_option('ie_pinned_post_title');
		$op[amount] = get_option('ie_pinned_post_amount');
		$op[post] = get_option('ie_pinned_post_show');
		$op[menu] = get_option('ie_pinned_show_menu');
		$op[cat] = get_option('ie_pinned_show_cat');
		$op[cats] = get_option( "ie_pinned_cats");
		$op[login] = get_option('ie_pinned_show_login');
		
		$op[name] = get_option( "ie_pinned_name");
		$op[tooltip] = get_option( "ie_pinned_tooltip");
		$op[window] = explode("x", get_option( "ie_pinned_window"));
		$op[navbutton] = get_option( "ie_pinned_navbutton");
		$op[start] = get_option( "ie_pinned_start");
?>
	<div class="wrap">
		<div id="icon-tools" class="icon32"><br /></div>
		<h2>IE Pinned Settings</h2>

		<form method="post" action="" id="ie-pinned-options">
			<div id="accord">
				<h3><a href="#section1">Basic Settings</a></h3>
				<div>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Application Name</th>
							<td>
								<input class="regular-text" type="text" name="name" id="name" size="64" value="<?php echo $op[name]; ?>"/>
								<div class="description">This is the name of your application, typically this is the same as the title. Use {site_name} to use your default blog name.</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Tooltip</th>
							<td>
								<input class="regular-text" type="text" name="tooltip" id="tooltip" size="32" value="<?php echo $op[tooltip]; ?>"/>
								<div class="description">When hovering over the thumbnail in the taskbar this will show up.</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Window Default Size</th>
							<td>
								Width (px) <input type="text" name="width" id="width" size="32" value="<?php echo $op[window][0]; ?>"/> - Height (px) <input type="text" name="height" id="height" size="32" value="<?php echo $op[window][1]; ?>"/>
								<div class="description">You can choose the size of the window.</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Navigation Button Color</th>
							<td>
								<input class="regular-text" type="text" name="nav" id="nav" size="32" value="<?php echo $op[navbutton]; ?>"/>
								<div class="description">Change the colour of the navigation.</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Start Location</th>
							<td>
								<input type="text" name="start" id="start" size="32" value="<?php echo $op[start]; ?>"/>
								<div class="description">Change the starting location.</div>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
				<h3><a href="#section2">Post Settings</a></h3>
				<div>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Display Title</th>
							<td>
								<input class="regular-text" type="text" name="disp_title" id="disp_titie" size="32" value="<?php echo $op[title]; ?>"/>
								<div class="description">This is the title that will show up above the items. Currently only this one will work as you can only have one custom group meaning all the other links will appear in the same group.</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Show Post</th>
							<td>
								<label><input type="checkbox" name="show_posts" id="show_posts" <?php if($op[post] == "true") { echo 'checked="checked"'; } ?> /> Show Posts</label>
							</td>
						</tr>
						<tr>
							<th scope="row">Display How Many</th>
							<td>
								<input type="text" name="post_amount" id="post_amount" size="32" value="<?php echo $op[amount]; ?>"/>
								<div class="description">You can choose how many you would like to be displayed.</div>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="update_ie_pinned_settings" value="true" />
				</div>
				<h3><a href="#section3">Menu Settings</a></h3>
				<div>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Show Menu</th>
							<td>
								<label><input type="checkbox" name="show_menu" id="show_menu" <?php if($op[menu] == "true") { echo 'checked="checked"'; } ?> /> Show Menu</label>
								<div class="description">This link will appear in the task section. The task section is limited to 5 items.</div>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
				<h3><a href="#section5">Login Settings</a></h3>
				<div>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Show Login</th>
							<td>
								<label><input type="checkbox" name="show_login" id="show_login"  <?php if($op[login] == "true") { echo 'checked="checked"'; } ?> />Show Login</label>
								<div class="description">This link will appear in the task section. The task section is limited to 5 items.</div>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
				<h3><a href="#section4">Category Settings</a></h3>
				<div>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Show Categories</th>
							<td>
								<label><input type="checkbox" name="show_cat" id="show_cat" <?php if($op[cat] == "true") { echo 'checked="checked"'; } ?> /> Show Categories</label>
								<div class="description">This link will appear in the task section. The task section is limited to 5 items.</div>
							</td>
						</tr>
						<tr>
							<th scope="row">Order Categories</th>
							<td>
								<?php
									if($op[cats] != "") {
										$aCats = explode("|", $op[cats]);
										//Want
										$arrCats1 = explode(",", $aCats[0]);
										$num1 = count($arrCats1);
										echo '<div class="description">Ones you want!</div><ul class="sortable use" style="min-height: 10px;">';
											for($i = 0; $i < $num1; $i++) {
												echo '<li class="ui-state-default">'.$arrCats1[$i] . "";
												echo '</li>';
											}
										echo '</ul>';
										//Dont
										$arrCats2 = explode(",", $aCats[1]);
										$num2 = count($arrCats2);											
										echo '<div class="description">Ones you don\'t!</div><ul class="sortable dontUse" style="min-height: 10px;">';
											for($i = 0; $i < $num2; $i++) {
												echo '<li class="ui-state-default">'.$arrCats2[$i].'</li>';
											}
										echo '</ul>';
									} else {
										$args = array('type' => 'post', 'orderby' => 'name');
										$cat = get_categories($args);
										$num = count($cat);
										echo '<div class="description">Ones you want!</div><ul class="sortable use" style="min-height: 10px;">';
										for($i = 0; $i < 5; $i++) {
											echo '<li class="ui-state-default">'.$cat[$i]->name.'</li>';
										}
										echo '</ul>';
										echo '<div class="description">Ones you don\'t!</div><ul class="sortable dontUse">';
										for($i = 5; $i < $num; $i++) {
											echo '<li class="ui-state-default">'.$cat[$i]->name.'</li>';
										}
										echo '</ul>';
									}
									echo 'Custom icon? <a onclick="return false;" title="Upload icon" class="thickbox add_icon" href="media-upload.php?type=image&TB_iframe=true&width=640&height=105">Upload Icon</a>';
								?>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="update1"></div>
				<p><input type="button" name="earch" id="update_list" value="Update List" class="button button-primary" /></p>
				</div>
				<h3><a href="#section3">News/ Updates</a></h3>
				<div>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">28/03/2011</th>
							<td>
								<div class="description">Because of the limit to the tasks being at 5 you have to choose what you want in there. I am currently working on a callback function so that you can add more to the top half.<br /><br />Also the icons are all the same but you can change the dynamic ones to your own by cahnging the one in the plugin folder. I am adding that to the basic settings so you can use your own more easily. I am looking into the thumbnails aswell. I will be making it so you can customise the icons you use inthe task section as well.<br /><br />The task section is done Menu, Login then Categories. I am thinking about making it so that you can choose what you want and combine them all together and allow you to create your own. So that you can do other links like social media etc.</div>
								<div class="description">If you do want to contact me then either contact me on Twitter sam_benn or my email sambenne[at]hotmail[dot]co[dot]uk (<-- this is done because I get lots of spam)</div>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
			</div>
			<p><input type="submit" name="search"  value="Update Options" class="button button-primary" /></p>
		</form>
	</div>
	<?php
	}
	
	//Get jQuery to work with the plugin
	function my_init_method() {
	    if (!is_admin()) {
	        wp_deregister_script( 'jquery' );
	        wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js');
	        wp_enqueue_script('ie-pinned', WP_PLUGIN_URL . '/ie-pinned/js/jquery.pinned.js?r=' . rand(1000, 9000) . '.js', array('jquery'), '1.0');
	    } else {
		    wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_register_script('jquery-ui-accordion', 'http://jqueryui.com/ui/jquery.ui.accordion.js');
	    	wp_enqueue_script('ieAdminScript', WP_PLUGIN_URL . '/ie-pinned/js/ieAdmin.js?r=' . rand(1000, 9000) . '.js', array('jquery', 'jquery-ui-widget', 'jquery-ui-accordion', 'jquery-ui-button', 'jquery-color', 'jquery-ui-mouse', 'jquery-ui-sortable'), '1.8.9');
	    	global $wp_scripts;
			//var_dump( $wp_scripts );
	    }
	}
	add_action('admin_print_styles', 'jquery_style');
	function jquery_style() {
		wp_enqueue_style('thickbox');
        $myStyleUrl = WP_PLUGIN_URL . '/ie-pinned/styles/jquery-ui-1.8.11.custom.css';
        $myStyleFile = WP_PLUGIN_DIR . '/ie-pinned/styles/jquery-ui-1.8.11.custom.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('jqueryStyle', $myStyleUrl);
            wp_enqueue_style('jqueryStyle');
        }
    }
	
	add_action('init', 'my_init_method');
?>