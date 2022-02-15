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

			/* Allowed Email Domains */
			$kl_customize->textarea( $wp_customize, 'klbs_settings', '[klbs][allowed_email_domains]', 'Allowed Email Domains', '' );

		}

	}

}

KLBS_ADMIN::getInstance();
