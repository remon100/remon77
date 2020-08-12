<?php

/*--------------------------------------------------------------------------
	
	Script Name : Responsive BBS
	Author      : FIRSTSTEP - Motohiro Tani
	Author URL  : https://www.1-firststep.com
	Create Date : 2014/03/07
	Version     : 4.0
	Last Update : 2019/10/21
	
--------------------------------------------------------------------------*/


session_start();
error_reporting( E_ALL );




mb_language( 'ja' );
mb_internal_encoding( 'UTF-8' );




if ( isset( $_POST['response-number'] ) && $_POST['response-number'] !== '' ) {
	
	require_once( dirname( __FILE__ ) .'/php/class.responsive-bbs-write.php' );
	$responsive_bbs_write = new Responsive_Bbs_Write();
	
	$responsive_bbs_write->javascript_action_check();
	$responsive_bbs_write->referer_check();
	$responsive_bbs_write->token_check();
	$responsive_bbs_write->post_check();
	$responsive_bbs_write->url_check();
	$responsive_bbs_write->block_ip();
	$responsive_bbs_write->block_word();
	$responsive_bbs_write->bbs_write();
	exit;
	
}




if ( isset( $_POST['edit-number'] ) && $_POST['edit-number'] !== '' ) {
	
	require_once( dirname( __FILE__ ) .'/php/class.responsive-bbs-write.php' );
	$responsive_bbs_write = new Responsive_Bbs_Write();
	
	$responsive_bbs_write->javascript_action_check();
	$responsive_bbs_write->referer_check();
	$responsive_bbs_write->session_check();
	$responsive_bbs_write->token_check();
	$responsive_bbs_write->post_check();
	$responsive_bbs_write->bbs_edit();
	exit;
	
}




if ( isset( $_POST['delete-number'] ) && $_POST['delete-number'] !== '' ) {
	
	require_once( dirname( __FILE__ ) .'/php/class.responsive-bbs-write.php' );
	$responsive_bbs_write = new Responsive_Bbs_Write();
	
	$responsive_bbs_write->javascript_action_check();
	$responsive_bbs_write->referer_check();
	$responsive_bbs_write->session_check();
	$responsive_bbs_write->token_check();
	$responsive_bbs_write->bbs_delete();
	exit;
	
}




require_once( dirname( __FILE__ ) .'/php/class.responsive-bbs-display.php' );
$responsive_bbs_display = new Responsive_Bbs_Display();




if ( isset( $_GET['page'] ) ) {
	$responsive_bbs_display->get_page();
}




if ( isset( $_GET['search'] ) ) {
	$responsive_bbs_display->get_search();
}




$responsive_bbs_display->html_header();
$responsive_bbs_display->header();

if ( isset( $_GET['search'] ) && file_exists( dirname( __FILE__ ) .'/addon/search/search-post-count.php' ) ) {
	$responsive_bbs_display->search_post_count();
} else {
	$responsive_bbs_display->all_post_count();
}

$responsive_bbs_display->bbs_form();
$responsive_bbs_display->edit_form();
$responsive_bbs_display->search_form();
$responsive_bbs_display->pagination_link();

if ( isset( $_GET['search'] ) && file_exists( dirname( __FILE__ ) .'/addon/search/search-display.php' ) ) {
	$responsive_bbs_display->search_display();
} else if ( file_exists( dirname( __FILE__ ) .'/addon/pagination/' ) ) {
	$responsive_bbs_display->bbs_display_pagination();
} else {
	$responsive_bbs_display->bbs_display();
}

$responsive_bbs_display->pagination_link();
$responsive_bbs_display->footer();








?>