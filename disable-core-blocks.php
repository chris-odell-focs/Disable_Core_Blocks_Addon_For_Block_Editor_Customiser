<?php

/*
Plugin Name: Foxdell Folio BEC Disable Core Blocks
Plugin URI: 
Description: An addon for the Foxdell Folio Block Editor Customiser to disable core plugins.
Version: 1.0.0
Author: Foxdell Folio
Author URI: 
License: GPLv2 or later
Text Domain: fofobecdcb
*/

require_once( dirname(__FILE__).'/class-fofo-bec-disable-core-blocks.php' );

/**
 * Bootstrap the addon
 * 
 * @return  void
 * @since   1.0.0
 */
function fofo_bec_disable_core_plugin_bootstrap( $addon_registry ) {

    $addon_header = [
        'name' => 'Disable Core Blocks',
        'description' => 'Disable individual core blocks supplied with WordPress',
        'version' => '1.0.0'
    ];

    $addon_registry->add( $addon_header, dirname(__FILE__) );    

    $dcb = new FoFo_Bec_Disable_Core_Blocks();
    $dcb->attach();
}

add_action( 'fofo_bec_register_addon', 'fofo_bec_disable_core_plugin_bootstrap' );

/**
 * Get the list of disabled blocks as part of the
 * ajax action.
 * 
 * @return  void
 * @since   1.0.0
 */
function fofo_bec_dcb_disabled_blocks() {

    $dcb = new FoFo_Bec_Disable_Core_Blocks();
    $dcb->attach();

    echo json_encode( $dcb->list_disabled_blocks() );

    wp_die();
}

/**
 * Add the action for the 'fofo_bec_dcb_disabled_blocks'
 * api call.
 * 
 * @return  void
 * @since   1.0.0 
 */
add_action( "wp_ajax_fofo_bec_dcb_disabled_blocks", "fofo_bec_dcb_disabled_blocks" );

/**
    Thanks to https://shellcreeper.com/how-to-create-admin-notice-on-plugin-activation/
    for the code below.
*/

/**
 * Runs only when the plugin is activated.
 * @since 1.5.0
 */
function fofo_bec_dcb_disabled_blocks_activate() {

    set_transient( 'fofo_bec_dcb_disabled_blocks_activate', true, 5 );
}

register_activation_hook( __FILE__, 'fofo_bec_dcb_disabled_blocks_activate' );


/**
 * Admin Notice on Activation.
 * @since 0.1.0
 */
function fofo_bec_dcb_disabled_blocks_admin_notice(){

    /* Check transient, if available display notice */
    if( get_transient( 'fofo_bec_dcb_disabled_blocks_activate' ) ){
        ?>
        <div class="updated notice is-dismissible">
            <p>This Plugin is an extenstion to <a hre="https://en-gb.wordpress.org/plugins/foxdell-folio-block-editor-customiser/">The Foxdell Folio Block Editor Customiser</a></p>
            <p>If the BEC is not activated as a plugin then this plugin does nothing.</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'fofo_bec_dcb_disabled_blocks_activate' );
    }
}


/* Add admin notice */
add_action( 'admin_notices', 'fofo_bec_dcb_disabled_blocks_admin_notice' );