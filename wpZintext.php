<?php
/*
Plugin Name: wpZintext
Plugin URI: http://www.knofun.com/wpzintext/
Description: Make money with eBay using your old blog posts! Implement <a href="http://www.zintext.com">ZinText</a> eBay contextual ads on your wordpress blog with no coding.
Version: 1.0
Author: Chris Hedgecock
Author URI: http://www.zintext.com
*/



function doZintextTest() {
	if (is_single()) {
	//test and see if we should be displaying zintext
	global $post;
	$DaysOld = get_option('wpztDaysOld');
	$postTime = strtotime($post->post_modified);
	$now = time();
	if (($postTime > 0) && (($now - $postTime > $DaysOld * 86400) || ($DaysOld == 0))) {
		add_action('wp_footer', 'doZintextCode');
	}
	}
}


function doZintextCode() {
	$CampaignID = get_option('wpztCampaignID');
	$CustomID = get_option('wpztCustomID');
	$Whitelist = get_option('wpztWhitelist');
	$Blacklist = get_option('wpztBlacklist');
	?>
<script language="javascript">
<?php if (!$CampaignID || $CampaignID == '') { $CampaignID = '5336141703'; } ?>
 var EB_campid='<?php echo $CampaignID; ?>';
<?php if ($CustomID && $CustomID != '') { ?>
 var EB_sid='<?php echo $CustomID; ?>';
<?php } ?>
<?php if ($Whitelist && $Whitelist != '') { ?>
 var EB_whitelist='<?php echo get_option('wpztWhitelist'); ?>';
<?php } ?>
<?php if ($Blacklist && $Blacklist != '') { ?>
 var EB_blacklist='<?php echo get_option('wpztBlacklist'); ?>';
<?php } ?>
</script>
<script src="http://www.zintext.com/showads.js" type="text/javascript"></script>

	<?php
}



function wpztAdmin() {
	// the admin code
	if ($_POST['zpwtHidden'] == 'Y') {
		//update data
		$CampaignID = $_POST['wpztCampaignID'];
		update_option('wpztCampaignID',$CampaignID);

		$CustomID = $_POST['wpztCustomID'];
		update_option('wpztCustomID',$CustomID);

		$Whitelist = $_POST['wpztWhitelist'];
		update_option('wpztWhitelist',$Whitelist);

		$Blacklist = $_POST['wpztBlacklist'];
		update_option('wpztBlacklist',$Blacklist);

		$DaysOld = $_POST['wpztDaysOld'];
		update_option('wpztDaysOld',$DaysOld);

		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		$CampaignID = get_option('wpztCampaignID');
		$CustomID = get_option('wpztCustomID');
		$Whitelist = get_option('wpztWhitelist');
		$Blacklist = get_option('wpztBlacklist');
		$DaysOld = get_option('wpztDaysOld');
	}
?>

<div class="wrap">
<h2><?php echo __('ZinText Settings', 'wpzt_trdom') ?></h2>
<form name="zpwtForm" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="zpwtHidden" value="Y">
<p><?php _e("EPN Campaign ID: "); ?><input type="text" name="wpztCampaignID" value="<?php echo $CampaignID; ?>" size="20"><?php _e(" * only required field" ); ?></p>
<p><?php _e("Custom ID: "); ?><input type="text" name="wpztCustomID" value="<?php echo $CustomID; ?>" size="20"><?php _e("(optional)" ); ?></p>
<p><?php _e("Whitelist: "); ?><input type="text" name="wpztWhitelist" value="<?php echo $Whitelist; ?>" size="20"><?php _e(" words you always want us to link, comma separated (optional)" ); ?></p>
<p><?php _e("Blacklist: "); ?><input type="text" name="wpztBlacklist" value="<?php echo $Blacklist; ?>" size="20"><?php _e(" words you never want us to link, comma separated (optional)" ); ?></p>
<p>Display on posts older than <input type="text" name="wpztDaysOld" value="<?php echo $DaysOld; ?>" size="5"> days (put 0 to display on all posts regardless of date)</p>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'wpzt_trdom' ) ?>">
</p>
</form>

<h2>README / FAQ</h2>
<p>Find answers to common questions here.</p>
<iframe id="faq_iframe" src="http://www.zintext.com/faq.php" width=800 height=400></iframe>

</div>

<?php
}


function wpztAdminActions() {
	//add the option to the settings menu
	add_options_page("wpZintext", "wpZintext", 9, "wpZintext", "wpztAdmin");
}

add_action('posts_selection', 'doZintextTest');

add_action('admin_menu', 'wpztAdminActions');

?>
