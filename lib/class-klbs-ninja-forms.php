<?php

class KLBS_NINJA_FORMS extends KLBS_BASE{
  function __construct(){
    add_filter( 'ninja_forms_submit_data', array( $this, 'validateEmailField' ) );
  }

  // CUSTOM EMAIL VALIDATION
  function validateEmailField( $form_data ){

    global $kl_customize;

    $form_id = $form_data['id'];

  	$default_form_id = (int) $kl_customize->get_theme_option('klbs', 'nf_id', 0 );

    // CHECK FORM ID
    if( $form_id == $default_form_id ){

      $field_id = (int) $kl_customize->get_theme_option('klbs', 'nf_email_field_id', 0 );

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

    global $kl_customize;

  	$coupon_author_id = (int) $kl_customize->get_theme_option('klbs', 'coupon_author_id', 0 );

    $coupon_code = strtoupper( substr( md5( uniqid( $email_field ) ), 0, 8 ) );

    $new_coupon = array(
      'post_title'   => $coupon_code,
      'post_content' => '',
      'post_status'  => 'publish',
      'post_author'  => $coupon_author_id,
      'post_type'    => 'shop_coupon'
    );

    // INSERT THE COUPON
    $coupon_id = wp_insert_post( $new_coupon );

    // UPDATE COUPON META
    update_post_meta( $coupon_id, 'user_email_id', $email );

    // SEND EMAIL TO THE USER WITH COUPON CODE
    $this->sendEmail( $email, $coupon_code );

  }

  function sendEmail( $to_mail, $coupon_code ){
    ob_start();
    include("templates/coupon-email.php");
    $body = ob_get_clean();
    $site_name = get_bloginfo( 'name' );
    $subject = "Coupon Notification From " . $site_name;
    $header = array(
      'Content-Type: text/html; charset=UTF-8'
    );

    wp_mail( $to_mail, $subject, $body, $header );

  }

}

KLBS_NINJA_FORMS::getInstance();
