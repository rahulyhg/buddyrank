<?php
class Az_Bp_Activity_Filter {
	
	 /**
     * Constructor
     */
    public function __construct() {
		add_action('admin_menu',array($this,'aheadzen_admin_menu'));
    }
	
	/*************************************************
	Admin Settings For voter plugin menu function
	*************************************************/
	function aheadzen_admin_menu()
	{
		add_submenu_page('options-general.php', 'Buddy Rank - Buddypress Activity Settings', 'BuddyRank', 'manage_options', 'azbpactivity',array($this,'aheadzen_admin_settings_page'));
	}

	/*************************************************
	Admin Settings For voter plugin
	*************************************************/
	function aheadzen_admin_settings_page()
	{
		// Check that the user is allowed to update options  
		if (!current_user_can('manage_options'))
		{
			wp_die('You do not have sufficient permissions to access this page.');
		}
		global $bp,$post;	
		if($_POST)
		{
			update_option('az_activity_bl_kw',$_POST['az_activity_bl_kw']);
			update_option('az_activity_disable_filter',$_POST['az_activity_disable_filter']);
			update_option('az_activity_diable_rank',$_POST['az_activity_diable_rank']);
			
			update_option('azbr_follower_weightage',$_POST['azbr_follower_weightage']);
			update_option('azbr_newavatar_weightage',$_POST['azbr_newavatar_weightage']);
			update_option('azbr_activityphoto_weightage',$_POST['azbr_activityphoto_weightage']);
			update_option('azbr_votedcount_weightage',$_POST['azbr_votedcount_weightage']);
			update_option('azbr_last24hr_weightage',$_POST['azbr_last24hr_weightage']);
			update_option('azbr_contentlength_weightage',$_POST['azbr_contentlength_weightage']);
			
			echo '<script>window.location.href="'.admin_url().'options-general.php?page=azbpactivity&msg=success";</script>';
			exit;
		}
		$disable_filter = get_option('az_activity_disable_filter');
		$diable_rank = get_option('az_activity_diable_rank');
		?>
		<h2><?php _e('BuddyRank Settings','aheadzen');?></h2>
		<?php
		if($_GET['msg']=='success'){
		echo '<p class="success">'.__('Your settings updated successfully.','aheadzen').'</p>';
		}
		?>
		<style>.success{padding:10px; border:solid 1px green; width:70%; color:green;font-weight:bold;}</style>
		<form method="post" action="<?php echo admin_url();?>options-general.php?page=azbpactivity">
			<table class="form-table">
				
				
				<tr valign="top">
					<td>
					<label for="az_activity_bl_kw">
					<p><?php _e('Activity Content Filter Keywords','aheadzen');?></p>
					<p><textarea style="width:60%;height:150px;" name="az_activity_bl_kw"><?php echo get_option('az_activity_bl_kw');?></textarea></p>
					<small><?php _e('All activity with above keyword in the content will never display in the listing.','aheadzen');?></small>
					<br />
					<small><?php _e('The keywords should be entered in new lines by press "ENTER" key.','aheadzen');?></small>
					</label>
					
					</td>
				</tr>
				
				<tr valign="top">
					<td>
					<label for="az_activity_disable_filter">
					<p>
					<input type="checkbox" <?php if($disable_filter){echo 'checked';}?> name="az_activity_disable_filter" id="az_activity_disable_filter" >
					<?php _e('Disable activity filter ?','aheadzen');?></p>
					</label>					
					</td>
				</tr>
				
				<tr valign="top">
					<td>
					<label for="az_activity_diable_rank">
					<p>
					<input type="checkbox" <?php if($diable_rank){echo 'checked';}?> name="az_activity_diable_rank" id="az_activity_diable_rank" >
					<?php _e('Disable activity rank ?','aheadzen');?></p>
					</label>
					</td>
				</tr>
				
				<tr valign="top"><td style="margin-bottom:0;padding-bottom: 0;"><h3 style="margin-bottom:0;"><?php _e('BuddyRank Weightage','aheadzen');?></h3></td></tr>
				<tr valign="top">
					<td style="padding-bottom: 0;">
					<label for="azbr_last24hr_weightage">
					<p><?php _e('Last 24 hrs Activity (default is : 100)','aheadzen');?></p>
					<p><input type="text" name="azbr_last24hr_weightage" value="<?php echo get_option('azbr_last24hr_weightage');?>"></p>
					</label>					
					</td>
				</tr>
				<tr valign="top">
					<td style="padding-bottom: 0;">
					<label for="azbr_newavatar_weightage">
					<p><?php _e('New Avatar Activity (default is : 200)','aheadzen');?></p>
					<p><input type="text" name="azbr_newavatar_weightage" value="<?php echo get_option('azbr_newavatar_weightage');?>"></p>
					</label>					
					</td>
				</tr>
				<tr valign="top">
					<td style="padding-bottom: 0;">
					<label for="azbr_activityphoto_weightage">
					<p><?php _e('Activity Photo (default is : 200)','aheadzen');?></p>
					<p><input type="text" name="azbr_activityphoto_weightage" value="<?php echo get_option('azbr_activityphoto_weightage');?>"></p>
					</label>					
					</td>
				</tr>
				<tr valign="top">
					<td style="padding-bottom: 0;">
					<label for="azbr_follower_weightage">
					<p><?php _e('Followers Activity (default is : 100)','aheadzen');?></p>
					<p><input type="text" name="azbr_follower_weightage" value="<?php echo get_option('azbr_follower_weightage');?>"></p>
					</label>					
					</td>
				</tr>
				<tr valign="top">
					<td style="padding-bottom: 0;">
					<label for="azbr_votedcount_weightage">
					<p><?php _e('Voted Count (default is : 50)','aheadzen');?></p>
					<p><input type="text" name="azbr_votedcount_weightage" value="<?php echo get_option('azbr_votedcount_weightage');?>"></p>
					</label>					
					</td>
				</tr>
				<tr valign="top">
					<td style="padding-bottom: 0;">
					<label for="azbr_contentlength_weightage">
					<p><?php _e('Content Lenght (default is : 1)','aheadzen');?></p>
					<p><input type="text" name="azbr_contentlength_weightage" value="<?php echo get_option('azbr_contentlength_weightage');?>"></p>
					</label>					
					</td>
				</tr>
				
				<tr valign="top">
					<td>
						<input type="hidden" name="action" value="update" />
						<input type="submit" value="Save settings" class="button-primary"/>
					
					<br /><br /><br /><br />
					<p><b style="color:red;"><?php _e('Note','aheadzen');?> :: </b>
					<ul>
					<li><?php _e('To enable activity rank you should install <a href="https://wordpress.org/plugins/voter-plugin/" target="_blank">Voter Plugin</a>.','aheadzen');?></li>
					<li><?php _e('Without "Voter Plugin" only "Content Filter Keywords" will work.','aheadzen');?></li>
					<li><?php _e('Plugin included few wordpress filters hook for SQL customization ::','aheadzen');?>
						<br /><br />
						<ul>
							<li><?php _e('<b>buddyrank_sql_filter</b> ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; main sql filter','aheadzen');?></li>
							<li><?php _e('<b>buddyrank_sql_select_filter</b> ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; sql select options filter','aheadzen');?></li>
							<li><?php _e('<b>buddyrank_sql_where_filter</b> ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; sql where condition filter','aheadzen');?></li>
							<li><?php _e('<b>buddyrank_sql_orderby_filter</b> ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; sql order by filter','aheadzen');?></li>
							<li><?php _e('<b>buddyrank_sql_where_keyword_filter</b> ::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; sql where condition filter for content keywords bloking/filtering','aheadzen');?></li>
						</ul>
					</li>
					</ul>
					</p>
					</td>
				</tr>					
			</table>
		</form>
		<?php
		
	}
}

// 1, 2, 3 go !
function bp_activity_plugin_filters() {
    return new Az_Bp_Activity_Filter();
}
 
add_action( 'bp_include', 'bp_activity_plugin_filters' );