<?php
/*
Plugin Name: KL Bulk Subscription
Plugin URI: https://sputznik.com/
Description: KL Bulk Subscription.
Version: 1.0.0
Author: Stephen Anil, Sputznik
Author URI: https://sputznik.com/
*/

if( ! defined( 'ABSPATH' ) ){ exit; }

$inc_files = array(
  'class-klbs-base.php',
  'admin/admin.php'
);

foreach( $inc_files as $inc_file ){
	require_once( $inc_file );
}
