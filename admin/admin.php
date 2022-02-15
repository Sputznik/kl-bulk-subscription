<?php

$inc_files = array(
  'class-klbs-admin.php',
  'class-klbs-woocommerce-coupon.php'
);

foreach( $inc_files as $inc_file ){
  require_once( $inc_file );
}
