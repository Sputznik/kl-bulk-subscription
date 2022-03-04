<?php

class KLBS_ADMIN extends KLBS_BASE{

	function __construct(){

		// CUSTOMIZER OPTIONS
		add_action( 'customize_register', array( $this, 'klbsCustomizer' )	);

	}

	function klbsCustomizer( $wp_customize ){

		if( class_exists( 'KL_THEME_CUSTOMIZE' ) ){

			global $kl_customize;

			$kl_customize->section( $wp_customize, 'kl_theme_panel', 'klbs_settings', 'Bulk Subscriptions', 'Bulk Subscription Settings', 40 );

			/* Ninja form id */
	    $kl_customize->text( $wp_customize, 'klbs_settings', '[klbs][nf_id]', 'Ninja Form ID', '' );

			/* Ninja form email field id */
			$kl_customize->text( $wp_customize, 'klbs_settings', '[klbs][nf_email_field_id]', 'Ninja Form Email Field ID', '' );

			/* Woocommerce coupon author id */
			$kl_customize->text( $wp_customize, 'klbs_settings', '[klbs][coupon_author_id]', 'Coupon Author ID', '' );

			/* Products Ids that the coupon will be applied to. */
			$kl_customize->text( $wp_customize, 'klbs_settings', '[klbs][product_ids]', 'Product Ids', '' );

			/* Allowed Email Domains */
			$kl_customize->textarea( $wp_customize, 'klbs_settings', '[klbs][allowed_email_domains]', 'Allowed Email Domains', '' );


		}

	}

}

KLBS_ADMIN::getInstance();
