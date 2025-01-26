<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme Neptune by Osetin for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

require_once (get_template_directory() . '/inc/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'neptune_by_osetin_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function neptune_by_osetin_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // Activating Helper Plugin
        array(
            'name'               => 'Osetin Helper', // The plugin name.
            'slug'               => 'osetin-helper', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/inc/plugins/osetin-helper.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '2.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        // Activating ACF
        array(
            'name'               => 'Advanced Custom Fields Pro', // The plugin name.
            'slug'               => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/inc/plugins/advanced-custom-fields-pro.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        // Activating Meal Planner
        array(
            'name'               => 'Osetin Meal Planner', // The plugin name.
            'slug'               => 'osetin-meal-planner', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/inc/plugins/osetin-meal-planner.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '1.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),


        // Activating UserPro
        array(
            'name'               => 'UserPro', // The plugin name.
            'slug'               => 'userpro', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/inc/plugins/userpro.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        // Activating Userpro Bookmarks
        array(
            'name'               => 'WordPress User Bookmarks plugin for UserPro', // The plugin name.
            'slug'               => 'userpro-bookmarks', // The plugin slug (typically the folder name).
            'source'             => get_template_directory() . '/inc/plugins/userpro-bookmarks.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),
        array(
            'name'               => 'Mailchimp for WordPress', // The plugin name.
            'slug'               => 'mailchimp-for-wp', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'               => 'Contact Form 7', // The plugin name.
            'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'               => 'Instagram Feed', // The plugin name.
            'slug'               => 'instagram-feed', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'               => 'Top 10 – Popular posts plugin for WordPress', // The plugin name.
            'slug'               => 'top-10', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'               => 'Widget CSS Classes', // The plugin name.
            'slug'               => 'widget-css-classes', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'               => 'bbPress', // The plugin name.
            'slug'               => 'bbpress', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'               => 'One Click Demo Import', // The plugin name.
            'slug'               => 'one-click-demo-import', // The plugin slug (typically the folder name).
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
    'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
    'default_path' => '',                      // Default absolute path to bundled plugins.
    'menu'         => 'tgmpa-install-plugins', // Menu slug.
    'parent_slug'  => 'themes.php',            // Parent menu slug.
    'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
    'is_automatic' => true,                   // Automatically activate plugins after installation or not.
    'message'      => '<div class="activate-plugins-message"><h3>Attention!</h3><p>Most of these plugins are optional, and only needed if you want to replicate a full live demo that you saw when you purchased our theme. The only required plugins are <strong>Osetin Helper</strong> and <strong>Advanced Custom Fields Pro</strong>. For example <strong>UserPro</strong> plugin is optional, and will give you user related features (e.g. registration, login). <strong>WooCommerce</strong> will give your ability to sell items. If you try to import demo data without installing all the plugins that we use on our demo - you will see <strong>"Failed import..."</strong> errors, if you know that you are not going to use those features (for example WooCommerce plugin), you can just ignore those messages.</p></div>',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );

}
