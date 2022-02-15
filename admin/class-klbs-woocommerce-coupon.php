<?php

class KLBS_WOOCOMMERCE_COUPON extends KLBS_BASE{

  function __construct(){
    // ADD CUSTOM FIELD IN WOOCOMMERCE COUPON
    add_action( 'woocommerce_coupon_options', array( $this, 'addCouponEmailField' ), 10 );

    // SAVE CUSTOM FIELD VALUE
    add_action( 'woocommerce_coupon_options_save', array( $this, 'saveCouponEmailField' ), 10, 2 );

  }

  function addCouponEmailField() {
    woocommerce_wp_text_input( array(
      'id'           => 'user_email_id',
      'label'        => __( 'Email Address', 'woocommerce' ),
      'placeholder'  => '',
      'description'  => __( 'Assign an email address to a coupon', 'woocommerce' ),
      'desc_tip'     => true,
    ) );
  }

  function saveCouponEmailField( $post_id, $coupon ) {
    if( isset( $_POST['user_email_id'] ) ) {
      $coupon->update_meta_data( 'user_email_id', sanitize_text_field( $_POST['user_email_id'] ) );
      $coupon->save();
    }
  }

}

KLBS_WOOCOMMERCE_COUPON ::getInstance();
