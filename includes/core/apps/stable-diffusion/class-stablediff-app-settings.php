<?php
/**
 * Stable Diffusion App Settings
 *
 * @package wpcd
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class STABLEDIFF_APP_SETTINGS
 */
class STABLEDIFF_APP_SETTINGS extends WPCD_APP_SETTINGS {

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
	 * STABLEDIFF_APP_SETTINGS constructor.
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
	 * @return array $tab New array of tabs on the settings page
	 */
	public function settings_tabs( $tabs ) {
		$new_tab = array( 'app-stablediff' => __( 'APP: Stable Diffusion - Settings', 'wpcd' ) );
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
			'id'             => 'stablediff',
			'title'          => __( 'All Stable Diffusion Settings', 'wpcd' ),
			'settings_pages' => 'wpcd_settings',
			'tab'            => 'app-stablediff',  // this is the top level tab on the setttings screen, not to be confused with the tabs inside a metabox as we're defining below.
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
			'stablediff-general'     => array(
				'label' => 'General',
				'icon'  => 'dashicons-text',
			),
			'stablediff-woocommerce' => array(
				'label' => 'WooCommerce',
				'icon'  => 'dashicons-store',
			),
			'stablediff-s3'          => array(
				'label' => 'AWS S3',
				'icon'  => 'dashicons-images-alt2',
			),
			'stablediff-frontend'    => array(
				'label' => 'Front End',
				'icon'  => 'dashicons-cover-image',
			),
			'stablediff-scripts'     => array(
				'label' => 'Scripts',
				'icon'  => 'dashicons-format-aside',
			),
			'stablediff-promotions'  => array(
				'label' => 'Promotions',
				'icon'  => 'dashicons-testimonial',
			),
			'stablediff-help'        => array(
				'label' => 'Help',
				'icon'  => 'dashicons-editor-help',
			),
		);

		return $tabs;
	}

	/**
	 * Return an array that combines all fields that will go on all tabs.
	 */
	public function all_fields() {
		$general_fields     = $this->general_fields();
		$woocommerce_fields = $this->woocommerce_fields();
		$promo_fields       = $this->promotional_fields();
		$script_fields      = $this->scripts_fields();
		$s3_fields          = $this->s3_fields();
		$help_fields        = $this->help_fields();
		$frontend_fields    = $this->frontend_fields();
		$all_fields         = array_merge( $general_fields, $woocommerce_fields, $promo_fields, $script_fields, $s3_fields, $frontend_fields, $help_fields );
		return $all_fields;
	}

	/**
	 * Return array portion of field settings for use in the script fields tab.
	 */
	public function scripts_fields() {

		$fields = array(
			array(
				'id'   => 'stablediff_scripts_header',
				'type' => 'heading',
				'name' => __( 'Scripts', 'wpcd' ),
				'tab'  => 'stablediff-scripts',
			),
			array(
				'id'   => 'stablediff_script_version',
				'type' => 'text',
				'name' => __( 'Version of scripts', 'wpcd' ),
				'desc' => __( 'Version of scripts to run.  Default is V1.  Updates to plugins that contain new scripts will NOT usually change this value so if you want to use new scripts on plugin updates, you should change this version number.', 'wpcd' ),
				'tab'  => 'stablediff-scripts',
			),
			array(
				'id'   => 'stablediff_commands_after_server_install',
				'type' => 'textbox',
				'name' => __( 'After provisioning commands', 'wpcd' ),
				'desc' => __( '<b>NOT active yet</b> Run these commands after the server has been provisioned.', 'wpcd' ),
				'tab'  => 'stablediff-scripts',
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
				'id'   => 'stablediff_promo_header',
				'type' => 'heading',
				'name' => __( 'Promotional Fields', 'wpcd' ),
				'tab'  => 'stablediff-promotions',
			),
			array(
				'id'      => 'stablediff_promo_item01_url',
				'type'    => 'text',
				'name'    => __( 'URL To First Product Being Promoted', 'wpcd' ),
				'std'     => 'https://yourdomain.com/store/product1',
				'tooltip' => __( 'You can add a link to the top of all subscriptions in the users Account screen.  This link can be to your store page or to a specific item.  Do NOT use a link that automatically adds a product to the cart.', 'wpcd' ),
				'tab'     => 'stablediff-promotions',
			),
			array(
				'id'      => 'stablediff_promo_item01_text',
				'type'    => 'textarea',
				'name'    => __( 'What is the text that the user should see for the promotional link?', 'wpcd' ),
				'tooltip' => __( 'Example: Add a new server', 'wpcd' ),
				'tab'     => 'stablediff-promotions',
			),
			array(
				'id'      => 'stablediff_promo_item01_button_option',
				'type'    => 'checkbox',
				'name'    => __( 'Make the above promo a button?', 'wpcd' ),
				'tooltip' => __( 'You can make the promo a button or just a standard text link. A button is more obvious but might annoy your users so be careful with this choice.', 'wpcd' ),
				'tab'     => 'stablediff-promotions',
			),
			array(
				'id'      => 'stablediff_promo_item02_url',
				'type'    => 'text',
				'name'    => __( 'URL To Second Product Being Promoted', 'wpcd' ),
				'std'     => 'https://yourdomain.com/store/product2',
				'tooltip' => __( 'You can add a link to the top of the server instances page when there are no instances on the page.  This link can be to your store page or to a specific item.  Do NOT use a link that automatically adds a product to the cart.', 'wpcd' ),
				'tab'     => 'stablediff-promotions',
			),
			array(
				'id'      => 'stablediff_promo_item02_text',
				'type'    => 'textarea',
				'name'    => __( 'What is the text that the user should see for the promotional link?', 'wpcd' ),
				'tooltip' => __( 'Example: Add a new server', 'wpcd' ),
				'tab'     => 'stablediff-promotions',
			),
			array(
				'id'      => 'stablediff_promo_item02_button_option',
				'type'    => 'checkbox',
				'name'    => __( 'Make the above promo a button?', 'wpcd' ),
				'tooltip' => __( 'You can make the promo a button or just a standard text link. A button is more obvious but might annoy your users so be careful with this choice.', 'wpcd' ),
				'tab'     => 'stablediff-promotions',
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
				'id'   => 'stablediff_general_header',
				'type' => 'heading',
				'name' => __( 'General Settings', 'wpcd' ),
				'tab'  => 'stablediff-general',
			),
			array(
				'id'      => 'stablediff_general_help_url',
				'type'    => 'text',
				'name'    => __( 'URL To Help Pages', 'wpcd' ),
				'default' => 'https://domain.com/help',
				'tooltip' => __( 'Certain error messages will be more helpful to your users if it includes a link to additional help resources. Add that link here.', 'wpcd' ),
				'tab'     => 'stablediff-general',
			),
			array(
				'id'      => 'stablediff_general_allowed_providers',
				'type'    => 'text',
				'name'    => __( 'Allowed Providers For Front-end', 'wpcd' ),
				'default' => 'awsec2',
				'tooltip' => __( 'Enter a command separated list of provider slugs that users will be restricted to.', 'wpcd' ),
				'tab'     => 'stablediff-general',
			),
			array(
				'id'      => 'stablediff_general_servers_page_url',
				'type'    => 'text',
				'name'    => __( 'URL To Server Page', 'wpcd' ),
				'default' => 'https://domain.com/stable-diffusion-servers',
				'tooltip' => __( 'Full URL to the page that contains the stable diffusion shortcodes.  This will be used in emails to the user.', 'wpcd' ),
				'tab'     => 'stablediff-general',
			),
			array(
				'id'      => 'stablediff_general_notes',
				'type'    => 'textarea',
				'name'    => __( 'Settings Notes', 'wpcd' ),
				'tooltip' => __( 'Use this to store any private notes related to Stable Diffusion configurations. This is a notational field only.', 'wpcd' ),
				'rows'    => '10',
				'tab'     => 'stablediff-general',
			),
		);

		return $fields;
	}

	/**
	 * Return array portion of field settings for use in the woocommerce fields tab.
	 */
	public function woocommerce_fields() {

		$fields = array(
			array(
				'id'   => 'stablediff_wc_header',
				'type' => 'heading',
				'name' => __( 'WooCommerce Settings', 'wpcd' ),
				'tab'  => 'stablediff-woocommerce',
			),
			array(
				'id'      => 'stablediff_general_wc_thank_you_text_before',
				'type'    => 'textarea',
				'name'    => __( 'WooCommerce Thank You Page Text', 'wpcd' ),
				'tooltip' => __( 'You will likely need to give the user some instructions on how to proceed after checking out. This will go at the top of the thank-you page after checkout - it will not completely replace any existing text though!', 'wpcd' ),
				'rows'    => '10',
				'tab'     => 'stablediff-woocommerce',
			),
			array(
				'id'      => 'stablediff_general_wc_show_acct_link_ty_page',
				'type'    => 'checkbox',
				'name'    => __( 'Show a link to the Stable Diffusion account screen on the thank you page?', 'wpcd' ),
				'tooltip' => __( 'You can offer the user an option to go straight to their account page after checkout.  Turn this on and fill out the two boxes below to enable this. IMPORTANT: For this to work, you do need the token ##STABLEDIFFACCOUNTPAGE## in the thank you text above.', 'wpcd' ),
				'tab'     => 'stablediff-woocommerce',
			),
			array(
				'id'      => 'stablediff_general_wc_ty_acct_link_url',
				'type'    => 'text',
				'name'    => __( 'URL to Your Stable Diffusion Account Page', 'wpcd' ),
				'default' => 'https://domain.com/account/stablediff/',
				'tooltip' => __( 'You can offer the user an option to go straight to their account page after checkout. This link is the account page link you will send them to.', 'wpcd' ),
				'tab'     => 'stablediff-woocommerce',
			),
			array(
				'id'      => 'stablediff_general_wc_ty_acct_link_text',
				'type'    => 'text',
				'name'    => __( 'Text that the user should see for the account link on the thank you page', 'wpcd' ),
				'tooltip' => __( 'Example: Go to your account page now', 'wpcd' ),
				'size'    => '90',
				'tab'     => 'stablediff-woocommerce',
			),
		);
		return $fields;
	}

	/**
	 * Array of fields used to store s3 settings.
	 */
	public function s3_fields() {

		$fields = array(
			array(
				'id'   => 'stablediff_app_s3_heading_01',
				'type' => 'heading',
				'name' => __( 'AWS S3 Credentials', 'wpcd' ),
				'desc' => __( 'Images will be uploaded to the AWS S3 bucket specified below.  Respect security best practice - please make sure your credentials are restricted to the specied bucket.', 'wpcd' ),
				'tab'  => 'stablediff-s3',
			),
			array(
				'id'         => 'stablediff_app_aws_access_key',
				'type'       => 'text',
				'name'       => __( 'AWS Access Key ID', 'wpcd' ),
				'tooltip'    => __( 'AWS Access Key ID', 'wpcd' ),
				'tab'        => 'stablediff-s3',
				'std'        => wpcd_get_option( 'stablediff_app_aws_access_key' ),
				'size'       => 60,
				'attributes' => array(
					'spellcheck' => 'false',
				),
			),
			array(
				'id'         => 'stablediff_app_aws_secret_key',
				'type'       => 'text',
				'name'       => __( 'AWS Secret Key', 'wpcd' ),
				'tooltip'    => __( 'AWS Secret Key', 'wpcd' ),
				'tab'        => 'stablediff-s3',
				'size'       => 60,
				'attributes' => array(
					'spellcheck' => 'false',
				),
			),
			array(
				'id'   => 'stablediff_app_aws_default_region',
				'type' => 'text',
				'name' => __( 'Default Region', 'wpcd' ),
				'tab'  => 'stablediff-s3',
				'desc' => sprintf( __( '<a href="%s" target="_blank" >Valid Regions</a>', 'wpcd' ), 'https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/using-regions-availability-zones.html#concepts-available-regions' ),
				'std'  => wpcd_get_option( 'stablediff_app_aws_default_region' ),
				'size' => 60,
			),
			array(
				'id'         => 'stablediff_app_aws_bucket',
				'type'       => 'text',
				'name'       => __( 'AWS Bucket Name', 'wpcd' ),
				'tooltip'    => __( 'AWS Bucket Name', 'wpcd' ),
				'tab'        => 'stablediff-s3',
				'std'        => wpcd_get_option( 'stablediff_app_aws_bucket' ),
				'size'       => 60,
				'attributes' => array(
					'spellcheck' => 'false',
				),
			),

		);

		return $fields;

	}

	/**
	 * Return array portion of field settings for use in the frontend fields tab.
	 */
	public function frontend_fields() {

		$fields = array(
			array(
				'id'   => 'stablediff_frontend_header',
				'type' => 'heading',
				'name' => __( 'Frontend Controls', 'wpcd' ),
				'tab'  => 'stablediff-frontend',
			),
			array(
				'id'      => 'stablediff_frontend_load_auto_refresh',
				'type'    => 'checkbox',
				'name'    => __( 'AutoRefresh Page When There Are Pending Requests?', 'wpcd' ),
				'tooltip' => __( 'Automatically refresh the page at regular intervals when there are image requests in the queue.', 'wpcd' ),
				'tab'     => 'stablediff-frontend',
			),
			array(
				'id'   => 'stablediff_frontend_auto_refresh_interval',
				'type' => 'number',
				'name' => __( 'Auto-refresh Interval In Seconds', 'wpcd' ),
				'tab'  => 'stablediff-frontend',
				'std'  => 180,
				'size' => 5,
			),
		);

		return $fields;

	}


	/**
	 * Return array portion of field settings for use in the help tab.
	 */
	public function help_fields() {

		$instructions  = __( 'The Stable Diffusion app is not really fully designed for 3rd party use.', 'wpcd' );
		$instructions .= '<br />';
		$instructions .= __( 'This is because it requires our AWS EC2 provider AND a specially configured EC2 image.', 'wpcd' );
		$instructions .= '<br />';
		$instructions .= __( 'If you really really want to use this app, then contact our support team so that we can create the EC2 image in your AWS account.', 'wpcd' );
		$instructions .= '<br />';
		$instructions .= __( 'And, you will also need our All-Access license since that contains the AWS EC2 server provider..', 'wpcd' );

		$fields = array(
			array(
				'id'   => 'stablediff_help_header',
				'type' => 'heading',
				'name' => __( 'Important Instructions', 'wpcd' ),
				'desc' => $instructions,
				'tab'  => 'stablediff-help',
			),
		);

			return $fields;
	}

}
