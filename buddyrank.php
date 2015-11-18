<?php
/*
Plugin Name: BuddyRank
Plugin URI: http://aheadzen.com/
Description: Buddypress activity main stream listing display by ranking and keyword filter/hide blocked activity as per keyword added from wp-admin settings. See <a href="options-general.php?page=azbpactivity">BuddyRank Settings</a> for more details.
Author: Aheadzen Team | <a href="options-general.php?page=azbpactivity">BuddyRank Settings</a>
Author URI: http://aheadzen.com/
Text Domain: aheadzen
Version: 1.0.7
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

include_once('admin_settings.php');

class BP_Loop_Filters {
 
    /**
     * Constructor
     */
    public function __construct() {
		$this->setup_hook();
    }
	
	/**
	 * Filters
	 */
	private function setup_hook() {
		//add_filter( 'bp_activity_get_where_conditions', array( $this, 'bp_activity_get_where_conditions' ),10);
		add_filter( 'bp_activity_paged_activities_sql', array( $this, 'bp_activity_paged_activities_sql' ),10);	
		
	}
	
	function bp_activity_paged_activities_sql($sql)
	{
		if(!$_GET['buddyrank']){
			if(!bp_is_activity_directory())return $sql;
		}
		global $table_prefix,$wp_query;
		$disable_filter = get_option('az_activity_disable_filter');
		$diable_rank = get_option('az_activity_diable_rank');
		if($disable_filter && $diable_rank)return $sql;
		
		preg_match('#( WHERE )(.*)( ORDER BY )#si',$sql,$whereCondi);
		$sqlWhereConditions = preg_replace('#a\.#si',"",$whereCondi[2]);
		$sql = str_replace($whereCondi[0],' ORDER BY ',$sql);		
		
		$filter_subsql = '';
		$select_subsql = '';
		if(!$disable_filter){
			$block_kw = nl2br(trim(get_option('az_activity_bl_kw')));
			$kw_like_sql = array();
			if($block_kw){
				$block_kw = explode('<br />',$block_kw);
				for($k=0;$k<count($block_kw);$k++){
					$kw_like_sql[] = ' content NOT LIKE "%'.trim($block_kw[$k]).'%"';
				}
				if($kw_like_sql){
					$filter_subsql = ' AND ('. implode(' AND ',$kw_like_sql).')';
				}
			}
		}
		$filter_subsql = apply_filters('buddyrank_sql_where_keyword_filter',$filter_subsql);
		if(!$diable_rank && class_exists('VoterPluginClass')){
			$follow_sql='';
			if(is_user_logged_in()){
				$userID = is_user_logged_in();
			}else if($_GET['currentUserId']){
				$userID = $_GET['currentUserId'];
			}
			
			$follower_weightage = get_option('azbr_follower_weightage');
			if(!$follower_weightage){$follower_weightage=100;}
			$newavatar_weightage = get_option('azbr_newavatar_weightage');
			if(!$newavatar_weightage){$newavatar_weightage=200;}
			$activityphoto_weightage = get_option('azbr_activityphoto_weightage');
			if(!$activityphoto_weightage){$activityphoto_weightage=200;}
			$votedcount_weightage = get_option('azbr_votedcount_weightage');
			if(!$votedcount_weightage){$votedcount_weightage=50;}
			$last24hr_weightage = get_option('azbr_last24hr_weightage');
			if(!$last24hr_weightage){$last24hr_weightage=100;}
			$contentlength_weightage = get_option('azbr_contentlength_weightage');
			if(!$contentlength_weightage){$contentlength_weightage=1;}
			
			if($userID && class_exists('BP_Follow')){
				$bpfollow = new BP_Follow();
				$following = $bpfollow->get_following($userID);
				if($following){
					$following_ids = implode(',',$following);
					$follow_sql = "((IFNULL(a.user_id in ($following_ids),0))*".$follower_weightage.")+";
				}
			}
			
			$photo_sql = "((IFNULL(a.type='new_avatar',0))*".$newavatar_weightage.") + ((IFNULL(a.type='activity_photo',0))*".$activityphoto_weightage.")+";
			$now_date = bp_core_current_time();
			$select_subsql = ",( $photo_sql  $follow_sql ((select count(v.id) from ".$table_prefix."ask_votes v where v.secondary_item_id=a.id and v.action='up' and v.type='activity' and v.component='buddypress')*".$votedcount_weightage.")+IF(TIMESTAMPDIFF(HOUR, '".$now_date."', a.date_recorded) >24, 0, (".$last24hr_weightage."/(0.01+TIMESTAMPDIFF(HOUR, a.date_recorded, '".$now_date."'))))+(length(a.content)-length(replace(a.content,' ',''))+".$contentlength_weightage.")) as score";
			$orderby_str = ' score DESC ';
			$start1 = 'SELECT ';
			$end1  = ' FROM ';
			$select_subsql = apply_filters('buddyrank_sql_select_filter',$select_subsql);
			$sql = preg_replace('#('.$start1.')(.*)('.$end1.')#si', "$1 $2 $select_subsql $3", $sql);
			$filter_subsql .= ' AND component!="votes" ';
		}else{
			$orderby_str = '$2';
		}
		
		$where = $filter_subsql;
		$start3 = 'ORDER BY';
		$end3  = 'LIMIT';		
		$where = apply_filters('buddyrank_sql_where_filter',$where);
		$orderby_str = apply_filters('buddyrank_sql_orderby_filter',$orderby_str);		
		$sql = preg_replace('#('.$start3.')(.*)('.$end3.')#si', "$1 $orderby_str $3", $sql);
		
		global $wpdb,$table_prefix;
		$activityTbl = $table_prefix."bp_activity";
		$sqlWhereConditions = $sqlWhereConditions.$where;
		$from_subsql = "(select * FROM ".$activityTbl." WHERE ".$sqlWhereConditions." ORDER BY date_recorded DESC limit 5000) as a";
		$fromActivityTblWhere = $activityTbl.' a';
		$sql = str_replace($fromActivityTblWhere,$from_subsql,$sql);
		
		$sql = apply_filters('buddyrank_sql_filter',$sql);
		return $sql;	
	}
	
	public function bp_activity_get_where_conditions( $where_conditions) {
		$where_conditions['buddyrank_filter']='a.content > ""';
		//echo '<pre>';print_r($where_conditions);echo '</pre>';
		return $where_conditions;
	}

}
 
// 1, 2, 3 go !
function bp_loop_filters() {
    return new BP_Loop_Filters();
}
 
add_action( 'bp_include', 'bp_loop_filters' );