<?php
/*
	Plugin Name: Twitter Shortcode
	Plugin URI: http://wordpress.org/extend/plugins/twitter-shortcode/
	Description: Shortcode for the official Twitter Widgets.
	Version: 0.9
	License: Creative Commons attribution non-commercial share alike
	Author: Andrea Brandi
	Author URI: http://www.andreabrandi.com
*/

/* Current version of the plugin */
define('TW_SHORTCODE_CURRENT_VERSION', '0.9' );

/**
 * Return the current version of the plugin
 */
function tw_shortcode_get_version() {
	return TW_SHORTCODE_CURRENT_VERSION;
}

/**
 * Return previously installed version of the plugin
 */
function tw_shortcode_installed_version() {
	$options = get_option( 'tw_shortcode_options' );
	return $options['VERSION'];
}

/**
 * Upgrade database to the new version and do some manteinance if necessary
 */
function tw_shortcode_activation() {
	$installed_version = tw_shortcode_installed_version();

	if ( $installed_version == tw_shortcode_get_version() ) {
		// do nothing
	} else {
		// some maintenance if needed
		// nothing already installed, initialize options
		tw_shortcode_init();
		}
	// Update version number - not necessary see tw_shortcode_init()
	// update_option( 'tw_shortcode_version', tw_shortcode_get_version() );
}
add_action('activate_tw-shortcode/tw-shortcode.php', 'tw_shortcode_activation');

/**
 * Initialize plugin and install to database
 */
function tw_shortcode_init() {
	$default_options = array (
		'VERSION' => '0.9',
		'type' => 'profile',
		'search' => '#wordpress',
		'rpp' => '10',
		'interval' => '3000',
		'title' => 'Twitter Live',
		'subject' => 'Today for #wordpress',
		'width' => 'auto',
		'height' => '300',
		'setuser' => 'starise',
		'shell' => array( 'background' => '#8EC1DA', 'color' => '#FFFFFF' ),
		'tweets' => array( 'background' => '#FFFFFF', 'color' => '#444444', 'links' => '#1985B5' ),
		'features' => array( 'scroll' => 'true', 'loop' => 'false', 'live' => 'true', 'behavior' => 'default' ),
	);
	//add_option( 'tw_shortcode_options', $default_options );
	update_option( 'tw_shortcode_options', $default_options );
}

/**
 * Receives array of options and updates them in database
 */
function tw_shortcode_update_options( $options ) {
	update_option( 'tw_shortcode_options', $options );
}

/**
 * Set default options and add them to the database
 * @deprecated OLD FUNCTION v0.8
 */
function tw_shortcode_add_options() {
	$tw_type = 'search';
	$tw_search = 'musica';
	$tw_rpp = '4'; /* tweet to show */
	$tw_interval = 30000;
	$tw_title = 'Il navigatore delle stelle';
	$tw_subject = 'Starsailor';
	$tw_width = 'auto';
	$tw_height = '300';
	$tw_shell = array ( '#8EC1DA', '#FFFFFF' );
	$tw_tweets = array ( '#FFFFFF', '#444444', '#1985B5' );
	$tw_features = array ( 'true', 'false', 'true', 'default');
	$tw_setuser = 'starise';
	
	update_option('tw_shortcode_type', $tw_type );
	update_option('tw_shortcode_search', $tw_search );
	update_option('tw_shortcode_rpp', $tw_rpp );
	update_option('tw_shortcode_interval', $tw_interval );
	update_option('tw_shortcode_title', $tw_title );
	update_option('tw_shortcode_subject', $tw_subject );
	update_option('tw_shortcode_width', $tw_width );
	update_option('tw_shortcode_height', $tw_height );
	update_option('tw_shortcode_shell', $tw_shell );
	update_option('tw_shortcode_tweets', $tw_tweets );
	update_option('tw_shortcode_features', $tw_features );
	update_option('tw_shortcode_setuser', $tw_setuser );
}

/**
 * Add option menu to administration panel
 */
function tw_shortcode_admin() {
	if (function_exists('add_options_page')) {
		add_options_page('TW Shortcode Options', 'TW Shortcode', 7, __FILE__, 'tw_shortcode_options');
	}
}
add_action('admin_menu', 'tw_shortcode_admin');

/**
 * Create admin panel options page
 */
function tw_shortcode_options() {
	// If options are updated
	if (isset($_POST['info_update'])) {
		// and don't need a reset
		if ( $_POST['tw_shortcode_reset'] == 'false' ) {
			// assing new settings and update database
			$options['type'] = $_POST['tw_shortcode_type'];
			$options['search'] = $_POST['tw_shortcode_search'];
			$options['setuser'] = $_POST['tw_shortcode_setuser'];
			$options['interval'] = $_POST['tw_shortcode_interval'];
			$options['search'] = $_POST['tw_shortcode_search'];
			$options['rpp'] = $_POST['tw_shortcode_rpp'];
			$options['title'] = $_POST['tw_shortcode_title'];
			$options['subject'] = $_POST['tw_shortcode_subject'];
			$options['width'] = $_POST['tw_shortcode_width'];
			$options['height'] = $_POST['tw_shortcode_height'];
			$options['shell']['background'] = $_POST['shell_options_bg'];
			$options['shell']['color'] = $_POST['shell_options_color'];
			$options['tweets']['background'] = $_POST['tweets_options_bg'];
			$options['tweets']['color'] = $_POST['tweets_options_color'];
			$options['tweets']['links'] = $_POST['tweets_options_links'];
			$options['features']['scroll'] = $_POST['features_options_scroll'];
			$options['features']['loop'] = $_POST['features_options_loop'];
			$options['features']['live'] = $_POST['features_options_live'];
			$options['features']['behavior'] = $_POST['features_options_behavior'];
			tw_shortcode_update_options( $options );
		} else {
		// reset all options to default values
		tw_shortcode_init();
		}
?>
    
    <div id="message" class="updated">
    	<p>Twitter Shortcode Options updated!</p>
    </div>
	
<?php } //endif ?>

<?php $options = get_option('tw_shortcode_options'); ?>

	<div class="wrap">
		<form method="post" action="" id="tw_shortcode-form">
			<h2>Twitter Shortcode Options</h2>
			
			<p>Configure the shortcode, than put [tw] in your content to show it.</p>
			<p>Available custom options: [tw type"{$}" search"{$}" title"{$}" subject"{$}" width"{$}" height"{$}"]</p>
			<p>{$} = your custom setting<p>
			
			<table class="form-table">
				<tr valign="top" id="tw_shortcode_type">
					<th scope="row">Widget Type</th>
					<td>
						<select name="tw_shortcode_type">
							<option value="search"<?php if($options['type'] == 'search'){ echo ' selected'; } ?>>Search</option>
							<option value="profile"<?php if($options['type'] == 'profile'){ echo ' selected'; } ?>>Profile</option>
							<option value="faves"<?php if($options['type'] == 'faves'){ echo ' selected'; } ?>>Faves</option>
						</select>
                        <span class="description">Choose type of widget</span>
					</td>
				</tr>
            	<tr valign="top">
                	<th scope="row">Set username</th>
                    <td><input type="text" name="tw_shortcode_setuser" value="<?php echo $options['setuser']; ?>" />
                    <span class="description">your username (used by &quot;Profile&quot; widget)</span>
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Interval</th>
                    <td><input type="number" name="tw_shortcode_interval" value="<?php echo $options['interval']; ?>" />
                    <span class="description">Time between tweets</span>
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Search key</th>
                    <td><input type="text" name="tw_shortcode_search" value="<?php echo $options['search']; ?>" />
                    <span class="description">(valid if &quot;Search&quot; is selected.)</span>
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Number of tweets</th>
                    <td><input type="number" name="tw_shortcode_rpp" value="<?php echo $options['rpp']; ?>" />
                    <span class="description">(not used for &quot;Search&quot;)</span>
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Title</th>
                    <td><input type="text" name="tw_shortcode_title" value="<?php echo $options['title']; ?>" />
                    <span class="description">(not used for &quot;Profile&quot;)</span>
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Subject</th>
                    <td><input type="text" name="tw_shortcode_subject" value="<?php echo $options['subject']; ?>" />
                    <span class="description">(not used for &quot;Profile&quot;)</span>
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Dimension</th>
                    <td><span class="description">Width</span>
                    <input type="text" name="tw_shortcode_width" value="<?php echo $options['width']; ?>" />
                    <span class="description">Height</span>
                    <input type="text" name="tw_shortcode_height" value="<?php echo $options['height']; ?>" />
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Shell options</th>
                    <td><span class="description">Background:</span>
                    <input type="text" name="shell_options_bg" value="<?php echo $options['shell']['background']; ?>" />
                    <span class="description">Color:</span>
                    <input type="text" name="shell_options_color" value="<?php echo $options['shell']['color']; ?>" />
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Tweet options</th>
                    <td><span class="description">Background:</span>
                    <input type="text" name="tweets_options_bg" value="<?php echo $options['tweets']['background']; ?>" />
                    <span class="description">Color:</span>
                    <input type="text" name="tweets_options_color" value="<?php echo $options['tweets']['color']; ?>" />
                    <span class="description">Links color:</span>
                    <input type="text" name="tweets_options_links" value="<?php echo $options['tweets']['links']; ?>" />
            		</td>
                 </tr>
            	<tr valign="top">
                	<th scope="row">Features Option</th>
                    <td><span class="description">Activate scroll:</span>
                    <input type="radio" name="features_options_scroll" value="true" <?php if($options['features']['scroll'] == 'true'){ echo 'checked'; } ?> /> Yes 
                    <input type="radio" name="features_options_scroll" value="false" <?php if($options['features']['scroll'] == 'false'){ echo ' checked'; } ?> /> No
                    <br /><span class="description">Activate loop:</span>
                    <input type="radio" name="features_options_loop" value="true" <?php if($options['features']['loop'] == 'true'){ echo 'checked'; } ?> /> Yes 
                    <input type="radio" name="features_options_loop" value="false" <?php if($options['features']['loop'] == 'false'){ echo ' checked'; } ?> /> No
                    <br /><span class="description">Activate live:</span>
                    <input type="radio" name="features_options_live" value="true" <?php if($options['features']['live'] == 'true'){ echo 'checked'; } ?> /> Yes 
                    <input type="radio" name="features_options_live" value="false" <?php if($options['features']['live'] == 'false'){ echo ' checked'; } ?> /> No
                    <br /><span class="description">Activate behavior:</span>
                    <input type="radio" name="features_options_behavior" value="all" <?php if($options['features']['behavior'] == 'all'){ echo 'checked'; } ?> /> All 
                    <input type="radio" name="features_options_behavior" value="default" <?php if($options['features']['behavior'] == 'default'){ echo ' checked'; } ?> /> Default
            		</td>
              </tr>
            	<tr valign="top">
                	<th scope="row">Reset to defaults</th>
                    <?php $tw_shortcode_reset = false; ?>
                    <td><span class="description">do you want to reset options?</span>
                    <input type="radio" name="tw_shortcode_reset" value="true"/> Yes 
                    <input type="radio" name="tw_shortcode_reset" value="false" checked /> No
            		</td>
              </tr>
			</table>                 
            <div class="submit">
				<input type="submit" name="info_update" value="Update Options &raquo;" />
			</div>
		</form>
	</div>
<?php
}

/* TW Shortcode main function
 * Generate shortcode output based on options passed or stored in database
 * Widget type: search, profile, faves | todo: check more options
 */
function tw_shortcode($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'type' => '',
		'search' => '',
		'title' => '',
		'subject' => '',
		'width' => '',
		'height' => '',
	), $atts));
	
	$options = get_option('tw_shortcode_options');

	if( !$type ){ $type = $options['type']; }
	if( !$rpp ){
		$rpp = $options['rpp'];
		if( !$rpp ){ $rpp = '4'; }
	}
	if( !$search ){
		$search = $options['search'];
		if( !$search ){ $search = ''; }
	}
	if( !$title ){
		$title = $options['title'];
		if( !$title ){ $title = ''; }
	}
	if( !$subject ){
		$subject = $options['subject'];
		if( !$subject ){ $subject = ''; }
	}
	if( !$width ){
		$width = $options['width'];
		if( !$width ){ $width = 'auto'; }
	}
	if( !$height ){
		$height = $options['height'];
		if( !$height ){ $height = '300'; }
	}
	$interval = $options['interval'];
	$setuser = $options['setuser'];
	
	/* Initiate output and attach the script based on options */
	$output = '';	
	$output .= "<script src=\"http://widgets.twimg.com/j/2/widget.js\"></script>
	<script>
	new TWTR.Widget({
	  version: 2,\n";
	$output .= "	  type: '".$type."',\n";
	if( $type == 'search' ){
		$output .= "	  search: '".$search."',\n";
	} else {
		$output .= "	  rpp: ".$rpp.",\n";
	}
	$output .= "	  interval: ".$interval.",\n";
	$output .= "	  width: '".$width."',\n";
	$output .= "	  height: ".$height.",\n";
	
	if( $type != 'profile' ){
		$output .= "	  title: '".$title."',\n";
		$output .= "	  subject: '".$subject."',\n";
	}

	$output .= "  	  theme: {
      	    shell: {\n";
	$output .= "	      background: '".$options['shell']['background']."',\n";
	$output .= "	      color: '".$options['shell']['color']."' \n";
	$output .= "      	    },
      	    tweets: {\n";
	$output .= "	      background: '".$options['tweets']['background']."',\n";
	$output .= "	      color: '".$options['tweets']['color']."',\n";
	$output .= "	      links: '".$options['tweets']['links']."' \n";
	$output .= "      	    }
      	  },
      	    features: {\n";
	$output .= "	      scrollbar: ".$options['features']['scroll'].",\n";
	$output .= "	      loop: ".$options['features']['loop'].",\n";
	$output .= "	      live: ".$options['features']['live'].",\n";
	$output .= "	      behavior: '".$options['features']['behavior']."' \n";
	$output .= "      	    }\n";
	if( $type != 'search' ){
		$output .= "	  }).render().setUser('".$setuser."').start();\n";
	} else {
		$output .= "	  }).render().start();\n";
	}
	
$output .= "	</script>\n";
	
	return $output;

}
add_shortcode('tw', 'tw_shortcode');

?>