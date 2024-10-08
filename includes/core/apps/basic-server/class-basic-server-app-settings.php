<?php
/**
 * Basic Server App Settings
 *
 * @package wpcd
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BASIC_SERVER_APP_SETTINGS
 */
class BASIC_SERVER_APP_SETTINGS extends WPCD_APP_SETTINGS {

	/**
	 * Holds a reference to this class
	 *
	 * @var $instance instance.
	 */
	private static $instance;

	/**
	 * Static function that can initialize the class
	 * and return an instance of itself.
	 *
	 * @TODO: This just seems to duplicate the constructor
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * BASIC_SERVER_APP_SETTINGS constructor.
	 */
	public function __construct() {

		// setup WordPress and settings hooks.
		$this->hooks();

	}

	/**
	 * Hook into WordPress and other plugins as needed.
	 */
	private function hooks() {

		add_filter( 'wpcd_settings_tabs', array( &$this, 'settings_tabs' ) );  // add a new tab to the settings page.

		add_filter( 'wpcd_settings_metaboxes', array( &$this, 'settings_metaboxes' ) );  // add new metaboxes to our new tab on the settings pages.

	}

	/**
	 * Add a new tab to the settings page
	 *
	 * Filter hook: wpcd_settings_tabs
	 *
	 * @param array $tabs Array of tabs on the settings page.
	 *
	 * @return array $tabs New array of tabs on the settings page
	 */
	public function settings_tabs( $tabs ) {
		$new_tab = array( 'app-basic-server' => __( 'APP: Basic Server - Settings', 'wpcd' ) );
		$tabs    = $tabs + $new_tab;
		return $tabs;
	}


	/**
	 * Add a new metaboxes to the settings page
	 *
	 * See the Metabox.IO website for documentation on
	 * the structure of the metabox settings array.
	 * https://docs.metabox.io/extensions/mb-settings-page/
	 *
	 * Filter hook: wpcd_settings_metaboxes
	 *
	 * @param array $metaboxes Array of metaboxes on the settings page.
	 *
	 * @return array $metaboxes New array of metaboxes on the settings page
	 */
	public function settings_metaboxes( $metaboxes ) {

		$metaboxes[] = array(
			'id'             => 'basic-server',
			'title'          => __( 'All Server Settings', 'wpcd' ),
			'settings_pages' => 'wpcd_settings',
			'tab'            => 'app-basic-server',  // this is the top level tab on the setttings screen, not to be confused with the tabs inside a metabox as we're defining below.
			// List of tabs in the metabox, in one of the following formats.
			// 1) key => label.
			// 2) key => array( 'label' => Tab label, 'icon' => Tab icon ).
			'tabs'           => $this->metabox_tabs(),
			'tab_style'      => 'left',
			'tab_wrapper'    => true,
			'fields'         => $this->all_fields(),

		);

		return $metaboxes;
	}

	/**
	 * Return a list of tabs that will go inside the metabox.
	 */
	public function metabox_tabs() {
		$tabs = array(
			'basic-server-general'    => array(
				'label' => 'General',
				'icon'  => 'dashicons-text',
			),
			'basic-server-scripts'    => array(
				'label' => 'Scripts',
				'icon'  => 'dashicons-format-aside',
			),
			'basic-server-promotions' => array(
				'label' => 'Promotions',
				'icon'  => 'dashicons-testimonial',
			),
		);

		return $tabs;
	}

	/**
	 * Return an array that combines all fields that will go on all tabs.
	 */
	public function all_fields() {
		$general_fields = $this->general_fields();
		$promo_fields   = $this->promotional_fields();
		$script_fields  = $this->scripts_fields();
		$all_fields     = array_merge( $general_fields, $promo_fields, $script_fields );
		return $all_fields;
	}

	/**
	 * Return array portion of field settings for use in the script fields tab.
	 */
	public function scripts_fields() {

		$fields = array(
			array(
				'id'   => 'basic_server_script_version',
				'type' => 'text',
				'name' => __( 'Version of scripts', 'wpcd' ),
				'desc' => __( 'Version of scripts to run.  Default is V1.  Updates to plugins that contain new scripts will NOT usually change this value so if you want to use new scripts on plugin updates, you should change this version number.', 'wpcd' ),
				'tab'  => 'basic-server-scripts',
			),
			array(
				'id'   => 'basic_server_commands_after_server_install',
				'type' => 'textbox',
				'name' => __( 'After provisioning commands', 'wpcd' ),
				'desc' => __( '<b>NOT active yet</b> Run these commands after the server has been provisioned.', 'wpcd' ),
				'tab'  => 'basic-server-scripts',
			),
		);

		return $fields;

	}

	/**
	 * Return array portion of field settings for use in the promotional fields tab.
	 */
	public function promotional_fields() {

		$fields = array(
			array(
				'id'      => 'basic_server_promo_item01_url',
				'type'    => 'text',
				'name'    => __( 'URL To First Product Being Promoted', 'wpcd' ),
				'std'     => get_site_url(),
				'tooltip' => __( 'You can add a link to the top of all subscriptions in the users Server Account screen.  This link can be to your store page or to a specific item.  Do NOT use a link that automatically adds a product to the cart.', 'wpcd' ),
				'tab'     => 'basic-server-promotions',
			),
			array(
				'id'      => 'basic_server_promo_item01_text',
				'type'    => 'textarea',
				'name'    => __( 'What is the text that the user should see for the promotional link?', 'wpcd' ),
				'tooltip' => __( 'Example: Add a new server', 'wpcd' ),
				'tab'     => 'basic-server-promotions',
			),
			array(
				'id'      => 'basic_server_promo_item01_button_option',
				'type'    => 'checkbox',
				'name'    => __( 'Make the above promo a button?', 'wpcd' ),
				'tooltip' => __( 'You can make the promo a button or just a standard text link. A button is more obvious but might annoy your users so be careful with this choice.', 'wpcd' ),
				'tab'     => 'basic-server-promotions',
			),
			array(
				'id'      => 'basic_server_promo_item02_url',
				'type'    => 'text',
				'name'    => __( 'URL To Second Product Being Promoted', 'wpcd' ),
				'std'     => get_site_url(),
				'tooltip' => __( 'You can add a link to the top of the server instances page when there are no instances on the page.  This link can be to your store page or to a specific item.  Do NOT use a link that automatically adds a product to the cart.', 'wpcd' ),
				'tab'     => 'basic-server-promotions',
			),
			array(
				'id'      => 'basic_server_promo_item02_text',
				'type'    => 'textarea',
				'name'    => __( 'What is the text that the user should see for the promotional link?', 'wpcd' ),
				'tooltip' => __( 'Example: Add a new server', 'wpcd' ),
				'tab'     => 'basic-server-promotions',
			),
			array(
				'id'      => 'basic_server_promo_item02_button_option',
				'type'    => 'checkbox',
				'name'    => __( 'Make the above promo a button?', 'wpcd' ),
				'tooltip' => __( 'You can make the promo a button or just a standard text link. A button is more obvious but might annoy your users so be careful with this choice.', 'wpcd' ),
				'tab'     => 'basic-server-promotions',
			),
		);

		return $fields;

	}

	/**
	 * Return array portion of field settings for use in the general fields tab.
	 */
	public function general_fields() {

		$fields = array(
			array(
				'id'      => 'basic_server_general_help_url',
				'type'    => 'text',
				'name'    => __( 'URL To Help Pages', 'wpcd' ),
				'default' => get_site_url() . '/help',
				'tooltip' => __( 'Certain error messages will be more helpful to your users if it includes a link to additional help resources. Add that link here.', 'wpcd' ),
				'tab'     => 'basic-server-general',
			),
			array(
				'id'      => 'basic_server_general_wc_thank_you_text_before',
				'type'    => 'textarea',
				'name'    => __( 'WooCommerce Thank You Page Text', 'wpcd' ),
				'tooltip' => __( 'You will likely need to give the user some instructions on how to proceed after checking out. This will go at the top of the thank-you page after checkout - it will not completely replace any existing text though!', 'wpcd' ),
				'rows'    => '10',
				'tab'     => 'basic-server-general',
			),
			array(
				'id'      => 'basic_server_general_wc_show_acct_link_ty_page',
				'type'    => 'checkbox',
				'name'    => __( 'Show a link to the Server account screen on the thank you page?', 'wpcd' ),
				'tooltip' => __( 'You can offer the user an option to go straight to their account page after checkout.  Turn this on and fill out the two boxes below to enable this. IMPORTANT: For this to work, you do need the token ##BASICSERVERACCOUNTPAGE## in the thank you text above.', 'wpcd' ),
				'tab'     => 'basic-server-general',
			),
			array(
				'id'      => 'basic_server_general_wc_ty_acct_link_url',
				'type'    => 'text',
				'name'    => __( 'URL to Server Account Page', 'wpcd' ),
				'std'     => get_site_url() . '/account',
				'tooltip' => __( 'You can offer the user an option to go straight to their account page after checkout. This link is the account page link you will send them to.', 'wpcd' ),
				'tab'     => 'basic-server-general',
			),
			array(
				'id'      => 'basic_server_general_wc_ty_acct_link_text',
				'type'    => 'text',
				'name'    => __( 'Text that the user should see for the account link on the thank you page', 'wpcd' ),
				'tooltip' => __( 'Example: Go to your account page now', 'wpcd' ),
				'size'    => '90',
				'tab'     => 'basic-server-general',
			),
			array(
				'id'      => 'basic_server_general_notes',
				'type'    => 'textarea',
				'name'    => __( 'Settings Notes', 'wpcd' ),
				'tooltip' => __( 'Use this to store any private notes related to server configurations. This is a notational field only.', 'wpcd' ),
				'rows'    => '10',
				'tab'     => 'basic-server-general',
			),
		);
		return $fields;
	}

}
