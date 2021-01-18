<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://squareone.software
 * @since      1.0.0
 *
 * @package    Hetas_Certificate_Purchasing
 * @subpackage Hetas_Certificate_Purchasing/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hetas_Certificate_Purchasing
 * @subpackage Hetas_Certificate_Purchasing/public
 * @author     Elliott Richmond <elliott@squareonemd.co.uk>
 */
class Hetas_Certificate_Purchasing_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hetas_Certificate_Purchasing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hetas_Certificate_Purchasing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hetas-certificate-purchasing-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hetas_Certificate_Purchasing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hetas_Certificate_Purchasing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hetas-certificate-purchasing-public.js', array( 'jquery' ), $this->version, false );

		if(is_page('hetas-copy-certificate-notification-purchase')) {
			//wp_enqueue_script( 'sagepay', 'https://pi-live.sagepay.com/api/v1/js/sagepay.js', array( '' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-sagepay', 'https://pi-test.sagepay.com/api/v1/js/sagepay.js', array(), '3', false );
		}
	}

	/**
	 * Include plugin templates
	 *
	 * @return void
	 */
	public function hetas_copy_certificate_purchase_templates($template) {

		$config = ccp_config();
		$pages = $config['pages'];

		foreach($pages as $page) {

			if ( is_page( $page['slug'] ) ) {

				$new_template = plugin_dir_path( __FILE__ ) . 'partials/'.$page['slug'].'.php';
				if ( !empty( $new_template ) ) {
					return $new_template;
				}
			}
	
		}

		return $template;
	}
	
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function hetas_ccp_form_process() {

		if (isset($_POST) && !empty($_POST)) {

			if ( ! isset( $_POST['ccp_front_end_post'] ) || ! wp_verify_nonce( $_POST['ccp_front_end_post'], 'ccp_front_end_post_action' ) ) {

				wp_die('Sorry, security did not verify.');

			} else {


				if($_POST['action'] == 'sbpc') {

					$response = $this->ccp_get_notification_results($_POST['van_postcode'], 'van_postcode');
					return $response;
	
				}
				if($_POST['action'] == 'sbhi') {

					$response = $this->ccp_get_notification_results($_POST['van_name'], 'van_name');
					return $response;

				}
				if($_POST['action'] == 'sbir') {

					$response = $this->ccp_get_notification_results($_POST['van_installersuppliedreference'], 'van_installersuppliedreference');
					return $response;

				}

				

			}
		}

	}

	/**
	 * ccp results 
	 *
	 * @param string $postcode
	 * @return $response
	 */
	public function ccp_get_notification_results($value, $type) {
		$call = new Dynamics_crm('crm','1.1.0');
		$response = $call->get_notifications_by_postcode($value, $type);
		return $response->value;
	}

	public function hetas_get_ccp_notification_details($notification_id) {
		$call = new Dynamics_crm('crm','1.1.0');
		$notification = $call->get_notification_by_van_notification_id_for_ccp($notification_id);
		$notification_items = $call->get_notification_items_by_van_notification_id_for_ccp($notification_id);
		$array = array();
		$array['notification'] = $notification->value[0]; 
		$array['notification_items'] = $notification_items->value; 
		return $array;
	}

	public function ccp_get_product_by_id($id) {

		$call = new Dynamics_crm('crm','1.1.0');
		$response = $call->get_product($id);
		return $response;

	}

	public function generate_ccp_card_identifier($postdata, $test = null) {

		$merchantsessionkey = $postdata['merchantsessionkey'];

		$postArray = array(
			'cardDetails' => array(
				'cardholderName' => $postdata['cardholderName'],
				'cardNumber' => str_replace(' ', '', $postdata['cardNumber']),
				'expiryDate' => $postdata['expiryDate'],
				'securityCode' => $postdata['securityCode'],
			)
		);

		$postBody = json_encode($postArray);

		if($test) {
			$sage_card_identifier_url = 'https://pi-test.sagepay.com/api/v1/card-identifiers/';
		} else {
			$sage_card_identifier_url = 'https://pi-live.sagepay.com/api/v1/card-identifiers/';
		}


		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $sage_card_identifier_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postBody,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Authorization: Bearer $merchantsessionkey",
				"Cookie: AWSALB=M+S1UbmWSYOt1i6WGicMtQ4jwXdLaURAtkANazLkXoPfEEgezCfKoJZEVY71IPKdHsVkKilga0Zb20bZWWXaaRqIRfGU1T6037797mi0zeRbxmpW+I3l7QTZCgG3; AWSALBCORS=M+S1UbmWSYOt1i6WGicMtQ4jwXdLaURAtkANazLkXoPfEEgezCfKoJZEVY71IPKdHsVkKilga0Zb20bZWWXaaRqIRfGU1T6037797mi0zeRbxmpW+I3l7QTZCgG3"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response);
			error_log(json_encode($response));
			wp_mail(array('elliott@squareonemd.co.uk','info@hetas.co.uk'), 'COC Error: generate_ccp_card_identifier', json_encode($response));
		}

		return $response;


	}

	/**
	 * Process ccp paypal transaction
	 *
	 * @param array $postdata
	 * @return array $response
	 */
	public function process_ccp_paypal_transaction($postdata) {

		$firstname = $postdata['firstname'];
		$lastname = $postdata['lastname'];
		$address_1 = $postdata['billingaddress1'];
		$address_2 = $postdata['billingaddress2'];
		$city = $postdata['billingaddresscity'];
		$postcode = $postdata['billingaddresspostcode'];
		$state = $postdata['billingaddressstate'];
		$email = $postdata['emailaddress'];
		$mobile = $postdata['mobilephone'];
		$merchkey = $postdata['merchantsessionkey'];
		$creditunits = $postdata['creditunits'];
		$spamount = (int)$postdata['spamount'];

		$response_data = $this->successful_ccp_payment($postdata, $response);

		return $response_data;
		
	}
	
	/**
	 * Process ccp sagepay transaction
	 *
	 * @param array $postdata
	 * @param boolean $test
	 * @return array $response
	 */
	public function process_ccp_sagepay_transaction($postdata, $test = null) {

		$card_identifier = $this->generate_ccp_card_identifier($postdata, $test);
		
		$firstname = $postdata['firstname'];
		$lastname = $postdata['lastname'];
		$address_1 = $postdata['billingaddress1'];
		$address_2 = $postdata['billingaddress2'];
		$city = $postdata['billingaddresscity'];
		$postcode = $postdata['billingaddresspostcode'];
		$state = $postdata['billingaddressstate'];
		$email = $postdata['emailaddress'];
		$mobile = $postdata['mobilephone'];
		$merchkey = $postdata['merchantsessionkey'];
		$cardid = $card_identifier->cardIdentifier;
		$creditunits = $postdata['creditunits'];
		$vendorstxt = uniqid();
		$spamount = (int)$postdata['spamount'];

		$postArray = array(
			'paymentMethod' => array(
				'card' => array(
					'merchantSessionKey' => $merchkey,
					'cardIdentifier' => $cardid	
				)
			),
			'transactionType' => 'Payment',
			'vendorTxCode' => $vendorstxt,
			'amount' => $spamount,
			'currency' => 'GBP',
			'customerFirstName' => $firstname,
			'customerLastName' => $lastname,
			'billingAddress' => array(
				'address1' => $address_1,
				'address2' => $address_2,
				'city' => $city,
				'postalCode' => $postcode,
				'country' => 'GB',
				'state' => null
			),
			'entryMethod' => 'Ecommerce',
			'apply3DSecure' => 'Force',
			'applyAvsCvcCheck' => 'Force',
			'description' => 'Copy Business Certificate Via Website',
			'customerEmail' => $email,
			'customerPhone' => $mobile,
			'shippingDetails' => array(
				'recipientFirstName' => $firstname,
				'recipientLastName' => $lastname,
				'shippingAddress1' => $address_1,
				'shippingAddress2' => $address_2,
				'shippingCity' => $city,
				'shippingPostalCode' => $postcode,
				'shippingCountry' => 'GB',
				'shippingState' => null
			)
		);

		$postBody = json_encode($postArray);

		if($test) {
			$sage_transaction_url = 'https://pi-test.sagepay.com/api/v1/transactions/';
		} else {
			$sage_transaction_url = 'https://pi-live.sagepay.com/api/v1/transactions/';
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $sage_transaction_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postBody,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Authorization: Basic eGVpVGpTMWtieWoycnNWTFRDeW9uY3JVNE8yY3prSGttMnpoeTJxeHh6UVJSNjJyOGs6TkF3cWZ2eEc1NzkyM2VoZ0xwdUU2aGk2QVdUWnRtRU1kczBub3RVS2I4U2xiUWZpVnd4b0xqMDRLYUVjNVI0bHg=",
				"Cookie: AWSALB=YOkigtC+dDselolZRxSYpWn0RPxq4ldibLbroBX0DojW2hgXuukuDT7oLRJV+0WNuO9tcPXINP8MUDIoN+r89wZEFhZ+UcBEIMk8P4U5i0fRK+GOyzFg6/VUhH4h; AWSALBCORS=YOkigtC+dDselolZRxSYpWn0RPxq4ldibLbroBX0DojW2hgXuukuDT7oLRJV+0WNuO9tcPXINP8MUDIoN+r89wZEFhZ+UcBEIMk8P4U5i0fRK+GOyzFg6/VUhH4h"
			),
		));
		  
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response);
		}

		if ($response->statusCode == '0000') {
			$response_data = $this->successful_ccp_payment($postdata, $response);
		} else {
			error_log(json_encode($response_data));
			wp_mail(array('elliott@squareonemd.co.uk','info@hetas.co.uk'), 'COC Error: process_ccp_sagepay_transaction', json_encode($response));
		}

		return $response_data;
		
	}

	public function successful_ccp_payment($postdata, $response) {
		$emailaddress = $postdata["emailaddress"];

		$call = new Dynamics_crm('crm','1.1.0');
		$contact = $call->get_contact_by_email($emailaddress);
		if($contact->value) { // if contact then update with this data
			$this->check_update_contact($contact, $postdata);
		} else { // if contact null then add new contact
			$contact = $this->create_new_contact($postdata);
		}
		$invoice = $this->create_ccp_invoice($contact, $response, $postdata); // create invoice
 		$invoice_items = $this->create_ccp_invoice_items($invoice, $contact, $response, $postdata); // create invoice items
		$payment = $this->create_ccp_payment($invoice, $contact, $response, $postdata);
		// update notification by id with 
		// set van_onlinecoc to 1
		// populate van_emailcoc with the email address entered on the web form.
		$this->update_ccp_notification_with_users_emailaddress($postdata["emailaddress"],$postdata["notification_id"]);


		$successful_data = array();
		$successful_data['invoice'] = $invoice;
		$successful_data['contact'] = $contact;
		$successful_data['response'] = $response;
		$successful_data['postdata'] = $postdata;
		
		return $successful_data;

	}

	/**
	 * Update the notification record with the purchasers email address
	 *
	 * @param string $email
	 * @param string $notification_id
	 * @return void
	 */
	public function update_ccp_notification_with_users_emailaddress($email, $van_name_id) {
		$call = new Dynamics_crm('crm','1.1.0');
		$access_token = $call->get_access_token();

		$curl = curl_init();

		$request = CRM_RESOURCE . '/api/data/v8.2/van_notifications?$filter=van_name%20eq%20%27'.$van_name_id.'%27';

		curl_setopt_array($curl, array(
			CURLOPT_URL => $request,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"OData-Version: 4.0",
				"Authorization: $access_token",
				"Cache-Control: no-cache",
				"Cookie: ReqClientId=218ea227-0f2b-4c6d-84cc-ad7924b1c3e1"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  	error_log('cURL Error #:' . $err);
		} else {
			$response = json_decode($response);
		}

		// set the notification id to update
		$notification_id = $response->value[0]->van_notificationid;

		$patchfeilds = array(
			'van_onlinecoc' => true,
			'van_emailcoc' => $email
		);
		$patchfeilds = json_encode($patchfeilds);
		
		// initiate a new curl request to update the notification
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => CRM_RESOURCE . "/api/data/v8.2/van_notifications($notification_id)",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'PATCH',
			CURLOPT_POSTFIELDS => $patchfeilds,
			CURLOPT_HTTPHEADER => array(
			  "Accept: application/json",
			  "OData-Version: 4.0",
			  "Authorization: $access_token",
			  "Cache-Control: no-cache",
			  "Content-Type: application/json",
			  "Cookie: ReqClientId=218ea227-0f2b-4c6d-84cc-ad7924b1c3e1"
			),
		));

		$response = curl_exec($curl);

		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  	error_log('cURL Error #:' . $err);
		} else {
			error_log('Notification updated');
		}


	}

	/**
	 * Upon invoice created then associate invoice with a CRM payment
	 * 
	 * @param mixed $name
	 * @return void
	 */
	public function create_ccp_payment($invoice, $contact, $response, $postdata) {
		if(null == $response) {
			$van_paymentmethod = '100000007';
		} else {
			$van_paymentmethod = '100000004';
		}

		$call = new Dynamics_crm('crm','1.1.0');

		$access_token = $call->get_access_token();

		// convert the pence to pounds and pence
		$price = $postdata['spamount'];
		
		$payment = array(
			'van_PayerContact@odata.bind' => 'contacts('.$contact->value[0]->contactid.')',
			'van_invoiceId@odata.bind' => 'invoices('.$invoice->invoiceid.')',
			'van_paymentcategory' => '100000000',
			'van_paymentmethod' => $van_paymentmethod,
			'van_paidon' => $invoice->createdon,
			'van_amount' => (int) number_format($price/100, 2)
		);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => CRM_RESOURCE . "/api/data/v8.2/van_payments",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($payment),
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"OData-Version: 4.0",
				"Authorization: $access_token",
				"Cache-Control: no-cache",
				"Content-Type: application/json",
				"Cookie: ReqClientId=218ea227-0f2b-4c6d-84cc-ad7924b1c3e1"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

	}

	public function create_ccp_invoice_items($invoice, $contact, $response, $postdata) {
		$call = new Dynamics_crm('crm','1.1.0');
		$access_token = $call->get_access_token();

		$object = array(
			'invoiceid@odata.bind' => 'invoices('.$invoice->invoiceid.')',
			'productid@odata.bind' => 'products(3d2f255a-abab-e911-80d3-00155d0515b7)',
			'uomid@odata.bind' => 'uoms(c286415d-9ecd-43b6-88bc-15cd9c04ee50)',
			'quantity' => 1,
			'priceperunit' => 15		
		);

		$object = json_encode($object);

		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => CRM_RESOURCE . "/api/data/v8.2/invoicedetails",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $object,
		CURLOPT_HTTPHEADER => array(
			"Accept: application/json",
			"OData-Version: 4.0",
			"Authorization: $access_token",
			"Cache-Control: no-cache",
			"Prefer: return=representation",
			"Content-Type: application/json",
			"Cookie: ReqClientId=218ea227-0f2b-4c6d-84cc-ad7924b1c3e1"
		),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response);
		}

		return $response;

	}

	public function create_ccp_invoice($contact, $response, $postdata) {

		$call = new Dynamics_crm('crm','1.1.0');
		$access_token = $call->get_access_token();

		$object = array(
			'transactioncurrencyid@odata.bind' => 'transactioncurrencies(12565274-81B2-E811-80D2-00155D050FFD)',
			'pricelevelid@odata.bind' => 'pricelevels(7C11153C-B03E-E911-80D3-00155D0515B7)',
			'customerid_contact@odata.bind' => 'contacts('.$contact->value[0]->contactid.')',
			'van_NotificationId@odata.bind' => 'van_notifications('.$postdata['notification_uid'].')',
			'van_invoicetype' => '100000000',
			'name' => 'Conf: ' . $postdata['notification_id'],
			'billto_line1' => $postdata['billingaddress1'],
			'billto_line2' => $postdata['billingaddress2'],
			'billto_city' => $postdata['billingaddresscity'],
			'billto_stateorprovince' => $postdata['billingaddressstate'],
			'billto_postalcode' => $postdata['billingaddresspostcode'],
		);

		$object = json_encode($object);

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => CRM_RESOURCE . "/api/data/v8.2/invoices",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => false,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $object,
		  CURLOPT_HTTPHEADER => array(
			"Accept: application/json",
			"OData-Version: 4.0",
			"Authorization: $access_token",
			"Cache-Control: no-cache",
			"Prefer: return=representation",
			"Content-Type: application/json",
			"Cookie: ReqClientId=218ea227-0f2b-4c6d-84cc-ad7924b1c3e1"
		  ),
		));
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response);
		}

		return $response;


	}

	public function check_update_contact($contact, $postdata) {

		$contactid = $contact->value[0]->contactid;
		$call = new Dynamics_crm('crm','1.1.0');
		$access_token = $call->get_access_token();
		$object = array(
			'firstname' => $postdata['firstname'],
			'lastname' => $postdata['lastname'],
			'address1_line1' => $postdata['billingaddress1'],
			'address1_line2' => $postdata['billingaddress2'],
			'address1_city' => $postdata['billingaddresscity'],
			'address1_postalcode' => $postdata['billingaddresspostcode'],
			'emailaddress1' => $postdata['emailaddress'],
			'telephone1' => $postdata['mobilephone'],
			'donotemail' => $postdata['donotemail'] ? true : false,
			'van_consumer' => true
		);
		$object = json_encode($object);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => CRM_RESOURCE . "/api/data/v8.2/contacts($contactid)",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "PATCH",
			CURLOPT_POSTFIELDS => $object,
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"OData-Version: 4.0",
				"Authorization: $access_token",
				"Cache-Control: no-cache",
				"Content-Type: application/json",
				"Cookie: ReqClientId=218ea227-0f2b-4c6d-84cc-ad7924b1c3e1"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			error_log("cURL Error #:" . $err);
		}

	}

	public function create_new_contact($postdata) {

		$call = new Dynamics_crm('crm','1.1.0');
		$access_token = $call->get_access_token();

		$object = array(
			'firstname' => $postdata['firstname'],
			'lastname' => $postdata['lastname'],
			'address1_line1' => $postdata['billingaddress1'],
			'address1_line2' => $postdata['billingaddress2'],
			'address1_city' => $postdata['billingaddresscity'],
			'address1_postalcode' => $postdata['billingaddresspostcode'],
			'emailaddress1' => $postdata['emailaddress'],
			'telephone1' => $postdata['mobilephone'],
			'donotemail' => $postdata['donotemail'] ? true : false,
			'van_consumer' => true
		);

		$object = json_encode($object);
		$err = curl_error($curl);
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => CRM_RESOURCE . "/api/data/v8.2/contacts",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $object,
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"OData-Version: 4.0",
				"Authorization: $access_token",
				"Cache-Control: no-cache",
				"Prefer: return=representation",
				"Content-Type: application/json",
				"Cookie: ReqClientId=218ea227-0f2b-4c6d-84cc-ad7924b1c3e1"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			$contact = json_decode($response);
		}

		return $contact;

	}
}
