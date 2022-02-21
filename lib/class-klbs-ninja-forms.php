<?php

class KLBS_NINJA_FORMS extends KLBS_BASE{
  function __construct(){
    add_filter( 'ninja_forms_submit_data', array( $this, 'validateEmailField' ) );
  }

  // CUSTOM EMAIL VALIDATION
  function validateEmailField( $form_data ){

    $form_id = $form_data['id'];

    // CHECK FORM ID
    if( $form_id == 3 ){

      $field_id = 12;
      $email_field = $form_data['fields'][$field_id]['value'];

      //  THROW ERROR IF THE EMAIL ADDRESS DOES NOT MATCH ANY OF THE ALLOWED EMAIL DOMAINS
      if( !$this->validateEmailDomain( $email_field ) ){
        $form_data['errors']['fields'][$field_id] = 'Email domain <strong>'.$this->getEmailDomain( $email_field ).'</strong> is not valid';
      } else{
        $this->generateCoupon( $email_field );
      }

    } //FORM CHECK

    // IF NO ERRORS RETURN THE $form_data
    return $form_data;

  }

  // CHECK SUBMITTED EMAIL ADDRESS AGAINST A LIST OF ALLOWED EMAIL DOMAINS FROM THEME OPTION
  // RETURN TRUE OR FALSE
  function validateEmailDomain( $email ){
    global $kl_customize;
    $option = $kl_customize->get_option();
    $option_name = 'allowed_email_domains';
    $allowed_email_domains = !empty( $option['klbs'][$option_name] ) ?  array_map('trim', explode(',', $option['klbs'][$option_name] ) ) : array();
    $email_domain = $this->getEmailDomain( $email );
    return ( in_array( $email_domain,  $allowed_email_domains ) );
  }

  // SPLIT ON @ AND RETURN LAST VALUE OF ARRAY (THE DOMAIN)
  function getEmailDomain( $email ){
    return  array_pop( explode( '@', $email ) );
  }

  // CREATE A NEW WOOCOMMERCE COUPON
  function generateCoupon( $email ){

    $coupon_code = strtoupper( substr( md5( uniqid( $email_field ) ), 0, 8 ) );

    $new_coupon = array(
      'post_title'   => $coupon_code,
      'post_content' => '',
      'post_status'  => 'publish',
      'post_author'  => 1,
      'post_type'    => 'shop_coupon'
    );

    // INSERT THE COUPON
    $coupon_id = wp_insert_post( $new_coupon );

    // UPDATE COUPON META
    update_post_meta( $coupon_id, 'user_email_id', $email );

  }

}

KLBS_NINJA_FORMS::getInstance();
